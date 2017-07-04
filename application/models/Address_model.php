<?php
    class Address_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function list($city_id) {

            $results = $this->db
                                ->select('*')
                                ->from('address')
                                ->where('city_id', $city_id)
                                ->join('store', 'store.address_id = address.address_id')
                                ->having('store.address_id = address.address_id')
                                ->join('inventory', 'inventory.store_id = store.store_id')
                                ->get()
                                ->result_array();

            if (!empty($results)) {

                $locations = array_values(group_by($results, 'store_id'));

                // Group inventory by film_id for each location
                $store_data = array_map(function ($loc_inventory) {
                    $movies      = group_by($loc_inventory, 'film_id');
                    $movie_count = count($movies);

                    $loc_inventory = [
                        'district'    => $loc_inventory[0]['district'],
                        'store_id'    => $loc_inventory[0]['store_id'],
                        'address'     => $loc_inventory[0]['address'],
                        'movies'      => $movies,
                        'movie_count' => $movie_count
                    ];

                    return $loc_inventory;
                }, $locations); 

                // sort:
                usort($store_data, function ($a, $b) {
                    return strcmp($a["district"], $b["district"]);
                });
            }
            
            return !empty($results)
                    ?   [
                            'status' => 200,
                            'message' => 'Success',
                            'data' => $store_data
                        ]
                    :   [
                            'status' => 203, // 204 breaks system/core/Common.php:575 for some reason.
                            'message' => "There aren't any stores in the selected city.",
                            'data' => []
                        ];
        }

        public function show($id) {
            return $this->db->get_where('store', ['store_id' => $id ])->result_array();
        }
    }
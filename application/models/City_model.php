<?php
    class City_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function list($id) {

            $cities = $this->db->get_where('city', ['country_id' => $id])->result_array();

            return $cities 
                    ?   [
                            'status' => 200,
                            'message' => 'Success',
                            'data' => $cities
                        ]
                    :   [
                            'status' => 203, // 204 breaks system/core/Common.php:575 for some reason.
                            'message' => 'Cities not found'
                        ];
        }

        public function show($id) {
            return $this->db->get_where('store', ['store_id' => $id ])->result_array();
        }
    }
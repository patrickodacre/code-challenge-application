<?php
    class Customer_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function list($store_id) {

            $results = $this->db
                                ->from('customer')
                                ->where('customer.store_id', $store_id)
                                ->join('rental', 'rental.customer_id = customer.customer_id', 'left')
                                ->join('inventory', 'inventory.inventory_id = rental.inventory_id', 'left')
                                ->join('film_text', 'film_text.film_id = inventory.film_id', 'left')
                                ->select('customer.customer_id, customer.first_name, customer.last_name, inventory.inventory_id, inventory.film_id, customer.create_date, customer.last_update, customer.active, rental.return_date, rental.rental_date, customer.email, customer.address_id, film_text.title, film_text.description')
                                ->get()
                                ->result_array();

            // rentals grouped by customer_id
            $customers = group_by($results, 'customer_id');

            $customer_list = array_map(function ($customerData) {

                $rentals = array_filter($customerData, function ($data) {
                    return !empty($data['film_id']);
                });

                // alpha sort
                usort($rentals, function ($a, $b) {
                    return strcmp($a["title"], $b["title"]);
                });
                
                $rental_count = count($rentals);

                $customer = [
                    'active'           => $customerData[0]['active'],
                    'create_date'      => $customerData[0]['create_date'],
                    'last_update'      => $customerData[0]['last_update'],
                    'rental_date'      => $customerData[0]['rental_date'],
                    'return_date'      => $customerData[0]['return_date'],
                    'address_id'       => $customerData[0]['address_id'],
                    'email'            => $customerData[0]['email'],
                    'customer_id'      => $customerData[0]['customer_id'],
                    'first_name'       => $customerData[0]['first_name'],
                    'last_name'        => $customerData[0]['last_name'],
                    'rentals'          => $rentals,
                    'rental_count'     => $rental_count,
                ];

                return $customer;
            }, $customers);

            $customer_list = array_values($customer_list);

            // sort - could not figure out how to do this with query builder without losing all my rental info
            usort($customer_list, function ($a, $b) {
                return strcmp($a["last_name"], $b["last_name"]);
            });

            return !empty($results)
                    ?   [
                            'status' => 200,
                            'message' => 'Success',
                            'data' => $customer_list
                        ]
                    :   [
                            'status' => 203, // 204 breaks system/core/Common.php:575 for some reason.
                            'message' => "There aren't any customers at this location.",
                            'data' => []
                        ];
        }

        public function create($data) {
            $data['address_id'] = 605; // just hardcode for now
            $this->db->insert('customer', $data);
            $response = $this->db->insert_id();

            return (!empty($response))
                        ?   [
                                'status' => 201,
                                'message' => 'Customer created.',
                                'data' => [
                                    'customer_id' => $response,
                                    'first_name' => $data['first_name'],
                                    'last_name' => $data['last_name']
                                ]
                            ]
                        :   [
                                'status' => 203,
                                'message' => "Failed to create customer.",
                                'data' => []
                            ];
        }

        public function delete($customer_id) {
            $this->db->delete('customer', array('customer_id' => $customer_id));
            $response = $this->db->affected_rows() > 0;

            return ($response)
                        ?   [
                                'status' => 200,
                                'message' => 'Customer deleted.',
                                'data' => [
                                    'customer_id' => $customer_id
                                ]
                            ]
                        :   [
                                'status' => 203,
                                'message' => "Failed to create customer.",
                                'data' => []
                            ];
        }

        public function update($data) {
            $this->db->set('first_name', $data['first_name']);
            $this->db->set('last_name', $data['last_name']);
            $this->db->set('active', $data['active']);
            $this->db->where('customer_id', $data['customer_id']);
            $this->db->update('customer');
            $response = $this->db->affected_rows() > 0;

            return ($response)
                        ?   [
                                'status' => 200,
                                'message' => 'Customer udpated.',
                                'data' => $data
                            ]
                        :   [
                                'status' => 203,
                                'message' => "Failed to update customer.",
                                'data' => [
                                    'customer_id' => $data['customer_id']
                                ]
                            ];
        }

        public function show($id) {
            return $this->db->get_where('customer', ['customer_id' => $id ])->result_array();
        }
    }
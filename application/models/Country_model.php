<?php
    class Country_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function list() {

            $countries = $this->db->get('country')->result_array();

            return $countries 
                    ?   [
                            'status' => 200,
                            'message' => 'Success',
                            'data' => $countries
                        ]
                    :   [
                            'status' => 203, // 204 breaks system/core/Common.php:575 for some reason.
                            'message' => 'Countries not found'
                        ];
        }

        public function show($id) {
            return $this->db->get_where('store', ['store_id' => $id ])->result_array();
        }
    }
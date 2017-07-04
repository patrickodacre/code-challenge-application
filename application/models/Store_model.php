<?php
    class Store_model extends CI_Model {
        public function __construct() {
            $this->load->database();
        }

        public function list() {

            $stores = $this->db->get('store')->result_array();

            return $stores 
                    ?   [
                            'status' => 200,
                            'message' => 'Success',
                            'data' => $stores
                        ]
                    :   [
                            'status' => 203, // 204 breaks system/core/Common.php:575 for some reason.
                            'message' => 'Stores not found'
                        ];
        }

        public function show($id) {
            return json_encode($this->db->get_where('store', ['store_id' => $id ])->row_array());
        }
    }
<?php
        
    class Customers extends CI_Controller {
        
        public $data = [];

        public function __construct() {
            parent::__construct();

            $config = $this->config->config;

            $this->data['links']     = $config['links'];
            $this->data['link_atts'] = $config['link_atts'];
        }

        public function list($store_id) {
            $response = $this->customer_model->list($store_id);
            json_output($response);
        }

        public function create() {
            $first_name = $this->input->post('first_name');
            $last_name  = $this->input->post('last_name');
            $store_id   = $this->input->post('store_id');

            $customer = [
                'first_name' => strtoupper($first_name), // sort breaks if case is different from other names in db
                'last_name' => strtoupper($last_name),
                'store_id' => $store_id
            ];

            $response = $this->customer_model->create($customer);
            json_output($response);
        }

        public function delete($customer_id) {
            $response = $this->customer_model->delete($customer_id);
            json_output($response);
        }
        public function update($customer_id) {

            $first_name = $this->input->input_stream('first_name');
            $last_name = $this->input->input_stream('last_name');
            $active = $this->input->input_stream('active');

            $data = [
                'customer_id' => $customer_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'active' => $active
            ];

            $response = $this->customer_model->update($data);
            json_output($response);
        }

        public function show($id) {

            // $this->data['title']     = 'Store Details';
            // $this->data['page_id']   = 'store_details';
            // $this->data['store'] = $this->store_model->show($id);

            // print_r($this->data['store']);

            // $this->loadViews('index');
        }

        public function view($store_id = 'home') {
            $this->data['title']     = 'Store Customers';
            $this->data['page_id']   = 'store_customers';
            $this->data['store_id']   = $store_id;

            $this->loadViews('customers');
        }

        private function loadViews($pageTemplate) {
            $this->load->view('partials/head');
            $this->load->view('partials/header', $this->data);
            $this->load->view('stores/' . $pageTemplate, $this->data);
            $this->load->view('partials/footer');
        }

    }
<?php

    class Stores extends CI_Controller {

        public $data = [];

        public function __construct() {
            parent::__construct();

            $config = $this->config->config;

            $this->data['links']     = $config['links'];
            $this->data['link_atts'] = $config['link_atts'];
        }

        public function index() {

            $this->data['title']     = 'Store Finder';
            $this->data['page_id']   = 'stores_index';

            $this->loadViews('index');
        }

        public function list() {

            $response = $this->store_model->list();
            json_output($response);
        }

        public function show($id) {

            $this->data['title']     = 'Store Details';
            $this->data['page_id']   = 'store_details';
            $this->data['store'] = $this->store_model->show($id);

            print_r($this->data['store']);

            $this->loadViews('index');
        }

        private function loadViews($pageTemplate) {
            $this->load->view('partials/head');
            $this->load->view('partials/header', $this->data);
            $this->load->view('stores/' . $pageTemplate, $this->data);
            $this->load->view('partials/footer');
        }
    }
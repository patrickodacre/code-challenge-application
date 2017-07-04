<?php
        
    class Countries extends CI_Controller {

        public function list() {
            $response = $this->country_model->list();
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
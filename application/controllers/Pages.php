<?php

    class Pages extends CI_Controller {

        public $data = [];

        public function __construct() {
            parent::__construct();

            $config = $this->config->config;

            $this->data['links']     = $config['links'];
            $this->data['link_atts'] = $config['link_atts'];
        }

        public function view($page = 'home') {
            if (!file_exists(APPPATH . 'views/pages/' . $page . '.php')) {
                show_404();
            }

            $config = $this->config->config;

            $this->data['title']     = ucfirst($page);
            $this->data['page_id']     = $page;

            $this->load->view('partials/head');
            $this->load->view('partials/header', $this->data);
            $this->load->view('pages/' . $page, $this->data);
            $this->load->view('partials/footer');
        }
    }
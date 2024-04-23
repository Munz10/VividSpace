<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        // Load other required models or libraries
    }

    public function result() {
        $query = $this->input->get('query');
        $data['results'] = $this->User_model->search_users($query);
        $this->load->view('search_result', $data);
    }
}

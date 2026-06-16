<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        // Load other required models or libraries
    }
    public function index() {
        // Optional: Load a search form view
        $this->load->view('search_form');
      }

    public function result() {
        $query = trim($this->input->get('query', TRUE));

        if (strlen($query) < 1) {
            redirect('profile/feed');
            return;
        }

        $data['results'] = $this->User_model->search_users($query);
        $data['query'] = $query;

        $this->load->view('search_result', $data);
    }

    public function dynamicResult() {
        $query = $this->input->get('query');
        $data['results'] = $this->User_model->search_users($query);
        header('Content-Type: application/json'); // Ensure proper JSON header
        echo json_encode($data);
    }
}

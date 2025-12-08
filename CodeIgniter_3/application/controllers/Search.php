<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller {
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
        $query = $this->input->get('query');

        // Validate search query if needed (e.g., minimum length)
        $this->form_validation->set_rules('query', 'Search Query', 'trim|required|min_length[3]');
        if ($this->form_validation->run() === FALSE) {
        // Display error message or redirect to search form
            return;
        }

        $data['results'] = $this->User_model->search_users($query);
        $data['query'] = $query; // Pass the search query to the view for display

        $this->load->view('search_result', $data);
    }

    public function dynamicResult() {
        $query = $this->input->get('query');
        $data['results'] = $this->User_model->search_users($query);
        header('Content-Type: application/json'); // Ensure proper JSON header
        echo json_encode($data);
    }
}

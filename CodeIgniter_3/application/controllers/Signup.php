<?php
defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH . '/libraries/REST_Controller.php';

class Signup extends \Restserver\Libraries\REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation' , 'session']);
        $this->load->model('User_model');
    }

    public function index() {
        $this->load->view('signup');
    }

    public function index_get() {
        $this->load->view('signup');
    }

    public function index_post() {
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Return validation errors
            $this->response(['error' => validation_errors()], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Prepare user data for insertion
            $userData = [
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name')
            ];

            // Insert user data
            $user_id = $this->User_model->insert_user($userData);

            if ($user_id) {
                // Return success response
                redirect('login');
            } else {
                // Return error response
                $this->response(['error' => 'Failed to create user'], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation' , 'session']);
        $this->load->model('User_model');
    }

    public function index() {
        $this->load->view('signup');
    }

    public function process() {
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('first_name', 'First Name', 'required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, load the signup view again with validation errors
            $this->load->view('signup');
        } else {

            // Validation passed, prepare user data for insertion
            $userData = array(
                'username' => $this->input->post('username'),
                'email' => $this->input->post('email'),
                'password_hash' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'first_name' => $this->input->post('first_name'), // Add this
                'last_name' => $this->input->post('last_name') // Add this
            );

            // Insert user data
            if ($user_id = $this->User_model->insert_user($userData)) {
                // Set session data
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('user_id', $user_id); // Store user ID or username
                $this->session->set_userdata('username', $userData['username']);
                // Redirect to the profile page
                redirect('profile');
            } else {
                // Load the signup view with an error message
                $data['error'] = 'There was a problem creating your account. Please try again.';
                $this->load->view('signup', $data);
            }
        }
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->model('User_model');
    }

    public function index() {
        // If the user is already logged in, redirect to the dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('profile');
        }
        $this->load->view('login');
    }

    public function process() {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            // If validation fails, load the login view again
            $this->load->view('login');
        } 
        else {
            // Validation passed, check user credentials
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            // The login method should now also return the user's data if login is successful
            $user_info = $this->User_model->login($username, $password);

            if ($user_info) {
                // Credentials are correct
                $this->session->set_userdata('logged_in', TRUE);
                $this->session->set_userdata('user_id', $user_info->id); // Store the user's ID
                $this->session->set_userdata('username', $user_info->username); // Store the username
                redirect('profile'); // Redirect to profile page, not dashboard
            } else {
                // Incorrect credentials, load login view with error message
                $data['error'] = 'Invalid username or password';
                $this->load->view('login', $data);
            }
        }
    }
}

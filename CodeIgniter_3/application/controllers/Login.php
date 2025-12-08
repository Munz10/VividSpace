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
        // If already logged in, redirect
        if ($this->session->userdata('logged_in')) {
            redirect('profile');
        }
        $this->load->view('login');
    }

    public function process() {
        // CSRF is automatically validated by CodeIgniter
        header('Content-Type: application/json');
        
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        if (empty($username) || empty($password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Username and password are required',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $user_info = $this->User_model->login($username, $password);

        if ($user_info) {
            // Set session
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->set_userdata('user_id', $user_info->id);
            $this->session->set_userdata('username', $user_info->username);
            
            echo json_encode([
                'status' => 'success',
                'message' => 'Login successful!',
                'redirect' => site_url('profile'),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid username or password',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }

    public function logout() {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('username');
        $this->session->sess_destroy();
        redirect('login');
    }
}

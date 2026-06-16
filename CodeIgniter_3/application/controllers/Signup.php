<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Signup extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library(['form_validation', 'session']);
        $this->load->model('User_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('profile/feed');
        }
        $this->load->view('signup');
    }

    public function create() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|regex_match[/^[a-zA-Z0-9_]+$/]|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|max_length[50]');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            echo json_encode([
                'success' => false,
                'error'   => strip_tags(validation_errors()),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $userData = [
            'username'    => $this->input->post('username'),
            'email'       => $this->input->post('email'),
            'password'    => $this->input->post('password'),
            'first_name'  => $this->input->post('first_name'),
            'last_name'   => $this->input->post('last_name'),
        ];

        $user_id = $this->User_model->insert_user($userData);

        if (!$user_id) {
            echo json_encode([
                'success' => false,
                'error'   => 'Username or email already in use',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        echo json_encode([
            'success'      => true,
            'redirect_url' => site_url('login'),
            'csrf_token'   => $this->security->get_csrf_hash()
        ]);
    }

    public function check_user() {
        header('Content-Type: application/json');
        $username = $this->input->post('username');
        echo json_encode([
            'exists'     => $this->User_model->checkUser($username) > 0,
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }
}

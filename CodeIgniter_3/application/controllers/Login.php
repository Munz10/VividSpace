<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

class Login extends \Restserver\Libraries\REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'form']);
        $this->load->library('session');
        $this->load->model('User_model');
        Header('Access-Control-Allow-Origin: *');
        Header('Access-Control-Allow-Headers: *');
        Header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
    }

    public function index() {
        // If the user is already logged in, redirect to the dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('profile');
        }
        $this->load->view('login');
    }

    public function index_options() {
        // Respond with CORS headers for OPTIONS request
        $this->output
             ->set_header('Access-Control-Allow-Origin: *')
             ->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS')
             ->set_header('Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding');
    }

    public function index_get() {
        // If the user is already logged in, redirect to the dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('profile');
        }
        $this->load->view('login');
    }

    public function index_post() {
        $username = $this->post('username');
        $password = $this->post('password');
    
        // Call your model method to authenticate user
        $result = $this->User_model->login($username, $password);

        if (!empty($result)) {
            // Credentials are correct
            $this->session->set_userdata('logged_in', TRUE);
            $this->session->set_userdata('username', $username);
            $this->response([
                'result' => 'success'
            ], \Restserver\Libraries\REST_Controller::HTTP_OK);
        } else {
            // Incorrect credentials
            $this->response([
                'result' => 'failed'
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}

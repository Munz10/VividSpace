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
        // Get the JSON request body
        $json_str = file_get_contents('php://input');
        $data = json_decode($json_str);
    
        // Extract values from the JSON data
        $username = isset($data->username) ? $data->username : null;
        $email = isset($data->email) ? $data->email : null;
        $password = isset($data->password) ? $data->password : null;
        $first_name = isset($data->first_name) ? $data->first_name : null;
        $last_name = isset($data->last_name) ? $data->last_name : null;
    
        // Set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('first_name', 'First Name');
        $this->form_validation->set_rules('last_name', 'Last Name');
    
        // Set POST data for validation
        $_POST['username'] = $username;
        $_POST['email'] = $email;
        $_POST['password'] = $password;
        $_POST['first_name'] = $first_name;
        $_POST['last_name'] = $last_name;
    
        if ($this->form_validation->run() == FALSE) {
            // Return validation errors
            $this->response(['error' => validation_errors()],  \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        } else {
            // Prepare user data for insertion
            $userData = [
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'first_name' => $first_name,
                'last_name' => $last_name
            ];
    
            // Insert user data
            $user_id = $this->User_model->insert_user($userData);
    
            if ($user_id) {
                // Return success response with the URL to redirect to
                $this->response(['success' => true, 'redirect_url' => site_url('login')]);
            } else {
                // Return error response
                $this->response(['error' => 'Failed to create user'], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
            
        }
    } 

    public function check_user(){
        $username = $this->post('username');
            $result = $this->User_model->checkUser($username);
            $this->response($result); 
    }
}

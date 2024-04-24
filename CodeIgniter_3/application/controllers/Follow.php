<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        // Ensure the user is logged in
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function do_follow() {
        $follower_id = $this->session->userdata('user_id');
        $following_id = $this->input->post('following_id');
        
        if ($follower_id && $following_id) {
            $result = $this->User_model->follow($follower_id, $following_id);
            $status = $result ? 'success' : 'error';
            $action = $result ? 'unfollow' : 'follow';
            echo json_encode(['status' => $status, 'action' => $action]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }

    public function do_unfollow() {
        $follower_id = $this->session->userdata('user_id');
        $following_id = $this->input->post('following_id');
        
        if ($follower_id && $following_id) {
            $result = $this->User_model->unfollow($follower_id, $following_id);
            $status = $result ? 'success' : 'error';
            $action = $result ? 'follow' : 'unfollow';
            echo json_encode(['status' => $status, 'action' => $action]);
        } else {
            echo json_encode(['status' => 'error']);
        }
    }
}

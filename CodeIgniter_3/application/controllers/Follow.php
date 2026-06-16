<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        $this->load->model('Notification_model');
        $this->load->library('session');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function do_follow() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('following_id', 'Following User ID', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() !== TRUE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p>', '</p>'),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $follower_id = $this->session->userdata('user_id');
        $following_id = (int) $this->input->post('following_id');

        if ($follower_id == $following_id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'You cannot follow yourself',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $result = $this->User_model->follow($follower_id, $following_id);

        if ($result) {
            log_message('info', "User {$follower_id} followed user {$following_id}");
            $this->Notification_model->create($following_id, $follower_id, 'follow');
        }

        echo json_encode([
            'status'  => $result ? 'success' : 'error',
            'action'  => $result ? 'unfollow' : 'follow',
            'followers_count' => $this->User_model->count_followers($following_id),
            'following_count' => $this->User_model->count_following($following_id),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    public function do_unfollow() {
        header('Content-Type: application/json');

        $this->form_validation->set_rules('following_id', 'Following User ID', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() !== TRUE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors('<p>', '</p>'),
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
            return;
        }

        $follower_id = $this->session->userdata('user_id');
        $following_id = (int) $this->input->post('following_id');

        $result = $this->User_model->unfollow($follower_id, $following_id);

        if ($result) {
            log_message('info', "User {$follower_id} unfollowed user {$following_id}");
        }

        echo json_encode([
            'status'  => $result ? 'success' : 'error',
            'action'  => $result ? 'follow' : 'unfollow',
            'followers_count' => $this->User_model->count_followers($following_id),
            'following_count' => $this->User_model->count_following($following_id),
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }
}

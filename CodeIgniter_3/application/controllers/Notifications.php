<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Notification_model');
        $this->load->library('session');
        $this->load->helper('url');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        $data['notifications'] = $this->Notification_model->list_for_user($user_id);
        $this->Notification_model->mark_all_read($user_id);
        $this->load->view('notifications', $data);
    }
}

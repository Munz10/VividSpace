<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->_inject_notif_count();
    }

    private function _inject_notif_count()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->load->vars(['notif_unread_count' => 0]);
            return;
        }
        $this->load->model('Notification_model');
        $count = $this->Notification_model->unread_count(
            $this->session->userdata('user_id')
        );
        $this->load->vars(['notif_unread_count' => $count]);
    }
}

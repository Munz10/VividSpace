<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LandingPage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('profile/feed');
        }

        $this->load->view('landing_page');
    }
}

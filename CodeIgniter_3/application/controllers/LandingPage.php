<?php
class LandingPage extends CI_Controller {

    public function index() {
        $this->load->helper('url');
        $this->load->view('landing_page');
    }
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model');
        $this->load->helper('url');
        // Ensure user is logged in
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            // Redirect to the login page
            redirect('login');
        }
    }

    public function detail($post_id) {
        // Fetch the post details from the database using the Post_model
        $data['post'] = $this->Post_model->get_post_by_id($post_id);

        // Check if the post exists
        if (!$data['post']) {
            // If the post does not exist, show a 404 error page
            show_404();
        } else {
            // Load the detailed post view and pass the post data to it
            $this->load->view('post_detail', $data);
        }
    }
    
    // ... Other methods related to posts ...
}

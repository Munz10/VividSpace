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
    
    // Method to toggle a like
    public function toggle_like() {
        $post_id = $this->input->post('post_id');
        $user_id = $this->session->userdata('user_id');
        $likes_count = $this->Post_model->toggle_like($post_id, $user_id);

        echo json_encode(['likes_count' => $likes_count]);
    }

    // Method to add a comment
    public function add_comment() {
        $post_id = $this->input->post('post_id');
        $user_id = $this->session->userdata('user_id');
        $content = $this->input->post('content');
        $comment_id = $this->Post_model->add_comment($post_id, $user_id, $content);

        if ($comment_id) {
            $comment = ['id' => $comment_id, 'content' => $content];
            echo json_encode(['comment' => $comment]);
        } else {
            echo json_encode(['error' => 'Unable to add comment']);
        }
    }

    public function get_comments() {
        $post_id = $this->input->post('post_id');
        // Get comments from the model
        $comments = $this->Post_model->get_comments_by_post_id($post_id);
    
        // Return comments as JSON
        echo json_encode(['comments' => $comments]);
    }
    
    // Method to check the like status of a post
    public function check_like_status() {
        // Assuming you have a model to handle post-related database operations
        $this->load->model('post_model');
        
        // Retrieve post ID from the AJAX request
        $post_id = $this->input->post('post_id');

        // Assuming you have a method in your model to check if the post has been liked by the current user
        $liked = $this->post_model->is_post_liked($post_id);

        // Send response back to the client
        echo json_encode(array('liked' => $liked));
    }
}

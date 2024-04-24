<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Post_model'); 
        $this->load->helper('url');
        $this->load->model('User_model');
        // Ensure user is logged in
        $this->load->library('session');
        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function index() {
        $user_id = $this->session->userdata('user_id');
        $data['profile'] = $this->User_model->get_user_by_id($user_id);
        $data['posts'] = $this->Post_model->get_posts_by_user_id($user_id);
        $data['followers_count'] = $this->User_model->count_followers($user_id);
        $data['following_count'] = $this->User_model->count_following($user_id);
        
        $this->load->view('profile', $data);
    }    

    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    public function feed() {
        $user_id = $this->session->userdata('user_id');
        // Get an array of user IDs that the current user is following
        $following_ids = $this->User_model->get_following_user_ids($user_id);
        
        // Get the posts from these users
        $data['posts'] = $this->Post_model->get_posts_by_user_ids($following_ids);
        
        // Load the feed view with the posts data
        $this->load->view('feed', $data);
    }    
    
    public function create_post() {
        // Load the create post view
        $this->load->view('create_post');
    }

    public function save_post() {
        // Check if there's a file being uploaded
        if (isset($_FILES['post_image']['name']) && $_FILES['post_image']['name'] != '') {
            // Configure upload.
            $config['upload_path'] = './uploads/'; // Ensure you have this directory.
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048'; // 2MB
    
            // Load upload library and initialize configuration.
            $this->load->library('upload', $config);
    
            if (!$this->upload->do_upload('post_image')) {
                // If the upload fails, display error to user.
                $error = array('error' => $this->upload->display_errors());
                echo 'hi';
                $this->load->view('create_post', $error);
            } else {
                // File is uploaded successfully. Now save post information to the database.
                $upload_data = $this->upload->data();
                $image_path = "/uploads/" . $upload_data['file_name'];
    
                // Assuming $this->session->userdata('user_id') returns the ID of the currently logged-in user.
                $post_data = array(
                    'user_id' => $this->session->userdata('user_id'),
                    'caption' => $this->input->post('caption'),
                    'image_path' => $image_path,
                    'hashtags' => $this->input->post('hashtags'),
                    // Include other post data as necessary.
                );
    
                $this->db->insert('posts', $post_data);
                // Redirect to the profile page where the post will be visible.
                redirect('profile');
            }
        } else {
            // Handle the case where no file is selected.
        }
    }
    
    public function view($username) {
        $data['user_profile'] = $this->User_model->get_user_by_username($username);
        
        // Assuming $data['user_profile'] contains the profile you're viewing
        $follower_id = $this->session->userdata('user_id'); // The current logged-in user
        $following_id = $data['user_profile']['id']; // The user being viewed
        
        // Check if the current user is following the viewed profile
        $data['is_following'] = $this->User_model->is_following($follower_id, $following_id);
        
        // Get posts for the user
        // $user_id = $data['user_profile']['id']; // You'll get this after you've set up $data['user_profile']

        $viewed_user_id = $data['user_profile']['id'];
        $data['followers_count'] = $this->User_model->count_followers($viewed_user_id);
        $data['following_count'] = $this->User_model->count_following($viewed_user_id);
        $data['posts'] = $this->Post_model->get_posts_by_user_id($viewed_user_id);

        // Check if the posts are returned; if not, set it as an empty array
        if (!$data['posts']) {
            $data['posts'] = []; // This ensures $posts is always an array
        }
    
        // Now load the view and pass the $data array
        $this->load->view('user_profile', $data);
    }
}

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
        $this->load->library('upload');
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
        $this->db->select('id, username, first_name, last_name, bio, email, profile_image'); // Include profile_image here
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

        // Fetch suggested users to follow
        $data['suggested_users'] = $this->User_model->get_suggested_users($user_id);
        
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
            $this->upload->initialize($config); 
    
            if (!$this->upload->do_upload('post_image')) {
                // If the upload fails, display error to user.
                $error = array('error' => $this->upload->display_errors());
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

        $data['first_name'] = $data['user_profile']['first_name'];
        $data['last_name'] = $data['user_profile']['last_name'];
        $data['bio'] = $data['user_profile']['bio'];
        
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

    public function update() {
        $user_id = $this->session->userdata('user_id');
        $userData = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'bio' => $this->input->post('bio'),
            'email' => $this->input->post('email'),
        );
    
        if (isset($_FILES['profile_image']['name']) && $_FILES['profile_image']['name'] != '') {
            // Set configuration for file upload
            $config['upload_path'] = './profile_pics/'; // New directory for profile pictures
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = '2048'; // 2MB

            $this->upload->initialize($config); 
        
            // Configuration and file upload handling logic goes here
            if (!$this->upload->do_upload('profile_image')) {
                // Handle error
                $error = $this->upload->display_errors();
                $this->session->set_flashdata('error', $error);
                redirect('profile/edit');
                return; // Stop execution if there is an error
            } 
            else {
                $upload_data = $this->upload->data();
                $userData['profile_image'] = "/profile_pics/" . $upload_data['file_name']; // Adjust the path accordingly
            }
        }
    
        $updateStatus = $this->User_model->update_user($user_id, $userData);
    
        // Check if the update was successful
        if ($updateStatus) {
            $this->session->set_flashdata('success', 'Profile updated successfully.');
        } else {
            $this->session->set_flashdata('error', 'Failed to update profile.');
        }

        // After updating, redirect the user to the profile page.
        redirect('profile');
    }

    public function edit() {
        $user_id = $this->session->userdata('user_id');
        $data['profile'] = $this->User_model->get_user_by_id($user_id);
        
        // Load the edit profile view
        $this->load->view('edit_profile', $data);
    }

    public function logout() {
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('user_id');
        // You can add more session data to unset if needed
        $this->session->sess_destroy(); // This destroys the session completely
        redirect('login'); // Redirect to the login page or your application's entry point
    }
    
    public function delete_post($post_id) {
        // Assuming $post_id is passed from the view or URL parameters
        $deleted = $this->Post_model->delete_post($post_id);
    
        if ($deleted) {
            // Post deleted successfully
            $response['success'] = true;
            redirect('profile');
        } else {
            // Post not found or not deleted
            $response['success'] = false;
        }
    
        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode($response);
    }  
           
}

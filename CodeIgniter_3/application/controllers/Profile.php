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

    public function index($page = 1) {
        $user_id = $this->session->userdata('user_id');
        $per_page = 12;
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $per_page;

        $data['profile'] = $this->User_model->get_user_by_id($user_id);
        $data['posts'] = $this->Post_model->get_posts_by_user_id($user_id, $per_page, $offset);
        $data['followers_count'] = $this->User_model->count_followers($user_id);
        $data['following_count'] = $this->User_model->count_following($user_id);
        $data['page'] = $page;
        $data['has_more'] = count($data['posts']) === $per_page;

        $this->load->view('profile', $data);
    }

    public function get_user_by_id($user_id) {
        $this->db->select('id, username, first_name, last_name, bio, email, profile_image'); // Include profile_image here
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array();
    }

    public function feed($page = 1) {
        $user_id = $this->session->userdata('user_id');
        $per_page = 12;
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $per_page;

        $following_ids = $this->User_model->get_following_user_ids($user_id);

        $data['posts'] = !empty($following_ids)
            ? $this->Post_model->get_posts_by_user_ids($following_ids, $per_page, $offset)
            : [];
        $data['suggested_users'] = $this->User_model->get_suggested_users($user_id);
        $data['page'] = $page;
        $data['has_more'] = count($data['posts']) === $per_page;

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
    
    public function view($username, $page = 1) {
        $profile = $this->User_model->get_user_by_username($username);
        if (!$profile) {
            show_404();
            return;
        }

        $per_page = 12;
        $page = max(1, (int) $page);
        $offset = ($page - 1) * $per_page;
        $viewed_user_id = (int) $profile['id'];
        $follower_id = (int) $this->session->userdata('user_id');

        $data['user_profile']    = $profile;
        $data['first_name']      = $profile['first_name'];
        $data['last_name']       = $profile['last_name'];
        $data['bio']             = $profile['bio'];
        $data['is_following']    = $this->User_model->is_following($follower_id, $viewed_user_id);
        $data['followers_count'] = $this->User_model->count_followers($viewed_user_id);
        $data['following_count'] = $this->User_model->count_following($viewed_user_id);
        $data['posts']           = $this->Post_model->get_posts_by_user_id($viewed_user_id, $per_page, $offset) ?: [];
        $data['page']            = $page;
        $data['has_more']        = count($data['posts']) === $per_page;

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
        $user_id = $this->session->userdata('user_id');
        $deleted = $this->Post_model->delete_post((int) $post_id, (int) $user_id);

        if (!$deleted) {
            show_error('You do not have permission to delete this post.', 403);
            return;
        }

        redirect('profile');
    }
           
}

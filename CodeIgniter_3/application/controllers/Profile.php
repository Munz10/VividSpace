<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

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
        if (empty($_FILES['post_image']['name'])) {
            $this->session->set_flashdata('error', 'Please choose an image to upload.');
            redirect('profile/create_post');
            return;
        }

        $upload = $this->_handle_image_upload('post_image', FCPATH . 'uploads/');
        if (isset($upload['error'])) {
            $this->session->set_flashdata('error', $upload['error']);
            redirect('profile/create_post');
            return;
        }

        $this->db->insert('posts', [
            'user_id'    => $this->session->userdata('user_id'),
            'caption'    => $this->input->post('caption'),
            'image_path' => 'uploads/' . $upload['file_name'],
            'hashtags'   => $this->input->post('hashtags'),
        ]);

        redirect('profile');
    }

    /**
     * Validates, randomises, and MIME-sniffs an uploaded image.
     * Returns ['file_name' => ...] on success or ['error' => ...] on failure.
     */
    private function _handle_image_upload($field, $upload_path) {
        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE,
        ];
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($field)) {
            return ['error' => trim(strip_tags($this->upload->display_errors()))];
        }

        $data = $this->upload->data();

        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $actual_mime = $finfo ? finfo_file($finfo, $data['full_path']) : null;
        if ($finfo) {
            finfo_close($finfo);
        }

        if (!in_array($actual_mime, $allowed_mimes, true)) {
            @unlink($data['full_path']);
            return ['error' => 'Uploaded file is not a valid image.'];
        }

        return ['file_name' => $data['file_name']];
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
    
        if (!empty($_FILES['profile_image']['name'])) {
            $upload = $this->_handle_image_upload('profile_image', FCPATH . 'profile_pics/');
            if (isset($upload['error'])) {
                $this->session->set_flashdata('error', $upload['error']);
                redirect('profile/edit');
                return;
            }
            $userData['profile_image'] = 'profile_pics/' . $upload['file_name'];
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

    public function reset_password() {
        $user_id = (int) $this->session->userdata('user_id');

        if ($this->input->method(TRUE) !== 'POST') {
            $this->load->view('reset_password');
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('old_password', 'Current password', 'required');
        $this->form_validation->set_rules('new_password', 'New password', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_new_password', 'Confirm new password', 'required|matches[new_password]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            redirect('profile/reset_password');
            return;
        }

        $old_password = (string) $this->input->post('old_password');
        $new_password = (string) $this->input->post('new_password');

        $user = $this->db->select('password_hash')
                         ->where('id', $user_id)
                         ->get('users')
                         ->row();

        if (!$user || !password_verify($old_password, $user->password_hash)) {
            $this->session->set_flashdata('error', 'Current password is incorrect.');
            redirect('profile/reset_password');
            return;
        }

        if (password_verify($new_password, $user->password_hash)) {
            $this->session->set_flashdata('error', 'New password must be different from the current one.');
            redirect('profile/reset_password');
            return;
        }

        if (!$this->User_model->update_password($user_id, $new_password)) {
            $this->session->set_flashdata('error', 'Could not update password. Please try again.');
            redirect('profile/reset_password');
            return;
        }

        $this->session->set_flashdata('success', 'Password updated successfully.');
        redirect('profile/edit');
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

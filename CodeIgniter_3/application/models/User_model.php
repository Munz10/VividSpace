<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_user($userData) {
        if (isset($userData['first_name'])) {
            $userData['first_name'] = sanitize_input($userData['first_name']);
        }
        if (isset($userData['last_name'])) {
            $userData['last_name'] = sanitize_input($userData['last_name']);
        }
        if (isset($userData['email'])) {
            $userData['email'] = filter_var($userData['email'], FILTER_SANITIZE_EMAIL);
        }

        if (isset($userData['password'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            unset($userData['password']);
        }

        $insert = $this->db->insert('users', $userData);
        if (!$insert) {
            log_message('error', 'Database insert failed: ' . $this->db->error()['message']);
            return false;
        }
        return $this->db->insert_id();
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() != 1) {
            return false;
        }

        $user_info = $query->row();
        if (!password_verify($password, $user_info->password_hash)) {
            return false;
        }

        if (password_needs_rehash($user_info->password_hash, PASSWORD_DEFAULT)) {
            $this->db->where('id', $user_info->id)
                     ->update('users', ['password_hash' => password_hash($password, PASSWORD_DEFAULT)]);
        }

        return $user_info;
    }

    public function checkUser($username)
    {
        $result = $this->db->get_where('users', array('username' => $username));
        return $result->num_rows();
    }

    public function email_exists($email)
    {
        return $this->db->get_where('users', array('email' => $email))->num_rows() > 0;
    }

    public function get_user_by_id($user_id) {
        $this->db->select('id, username, first_name, last_name, bio, email, profile_image'); 
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array(); // Return user data as an associative array
    }

    public function search_users($query) {
        $this->db->like('username', $query);
        $query = $this->db->get('users');
        return $query->result_array();
    }
    
    public function dynamic_user_search($username) {
        $this->db->like('username', $username, 'after');
        $this->db->limit(10);
        return $this->db->get('users')->result();
    }

    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row_array();
    }
    
    public function follow($follower_id, $following_id) {
        if (!$this->is_following($follower_id, $following_id)) {
            $data = array(
                'follower_id' => $follower_id,
                'following_id' => $following_id
            );
            return $this->db->insert('user_follows', $data);
        }
        return false;
    }

    public function unfollow($follower_id, $following_id) {
        $this->db->where('follower_id', $follower_id);
        $this->db->where('following_id', $following_id);
        return $this->db->delete('user_follows');
    }

    public function is_following($follower_id, $following_id) {
        $this->db->where('follower_id', $follower_id);
        $this->db->where('following_id', $following_id);
        $query = $this->db->get('user_follows');

        return $query->num_rows() > 0;
    }  

    public function count_followers($user_id) {
        $this->db->where('following_id', $user_id);
        $this->db->from('user_follows');
        return $this->db->count_all_results();
    }
    
    public function count_following($user_id) {
        $this->db->where('follower_id', $user_id);
        $this->db->from('user_follows');
        return $this->db->count_all_results();
    }

    public function get_following_user_ids($user_id) {
        $this->db->select('following_id');
        $this->db->from('user_follows');
        $this->db->where('follower_id', $user_id);
        $query = $this->db->get();
        return array_column($query->result_array(), 'following_id');
    } 

    public function get_suggested_users($user_id, $limit = 5) {
        $exclude_ids = $this->get_following_user_ids($user_id);
        $exclude_ids[] = (int) $user_id;

        $this->db->select('id, username, first_name, last_name, profile_image');
        $this->db->from('users');
        $this->db->where_not_in('id', $exclude_ids);
        $this->db->order_by('RAND()');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }
    
    public function update_user($user_id, $userData) {
        // Sanitize text fields to prevent XSS
        if (isset($userData['first_name'])) {
            $userData['first_name'] = sanitize_input($userData['first_name']);
        }
        if (isset($userData['last_name'])) {
            $userData['last_name'] = sanitize_input($userData['last_name']);
        }
        if (isset($userData['bio'])) {
            $userData['bio'] = sanitize_input($userData['bio']);
        }
        if (isset($userData['email'])) {
            $userData['email'] = filter_var($userData['email'], FILTER_SANITIZE_EMAIL);
        }
        
        $this->db->where('id', $user_id);
        return $this->db->update('users', $userData);
    }

    public function update_password($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update the user's password in the database
        $data = array(
            'password_hash' => $hashed_password
        );
        $this->db->where('id', $user_id);
        $this->db->update('users', $data);
        
        // Check if the update was successful
        return $this->db->affected_rows() > 0;
    }
}

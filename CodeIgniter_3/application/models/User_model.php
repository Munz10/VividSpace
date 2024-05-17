<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function insert_user($userData) {
        $insert = $this->db->insert('users', $userData);
        if (!$insert) {
            log_message('error', 'Database insert failed: ' . $this->db->error()['message']);
            return false;
        }
        return $this->db->insert_id(); // Return the ID of the inserted user
    }
    
    public function login($username, $password) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');

        if ($query->num_rows() == 1) {
            $user_info = $query->row();
            // Verify the password hash
            if (password_verify($password, $user_info->password_hash)) {
                return $user_info; // Return the user's data
            }
        }

        return false; // The user does not exist or password is wrong
    }

    public function checkUser($username)
    {
        $result = $this->db->get_where('users', array('Username' => $username));
        return $result->num_rows();
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
        $query = $this->db->query("SELECT * FROM users WHERE Username LIKE '".$username."%'");
        return $query->result();
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

    public function get_suggested_users($user_id) {
        // Get the user IDs of users who the current user is already following
        $following_ids = $this->get_following_user_ids($user_id);
        
         // Check if there are any users being followed
        if (empty($following_ids)) {
            // For new users who haven't followed anyone yet, suggest random users
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id !=', $user_id); // Exclude the current user
        $this->db->order_by('RAND()'); // Order randomly
        $this->db->limit(3); // Limit the number of suggested users

        $query = $this->db->get();
        return $query->result_array();
        } else {
            // If the user is already following someone, return an empty array
            return array();
        }
    }
    
    
    public function update_user($user_id, $userData) {
        $this->db->where('id', $user_id);
        return $this->db->update('users', $userData);
    }
}

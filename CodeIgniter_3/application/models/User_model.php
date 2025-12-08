<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    //Hashing During User Registration
    public function insert_user($userData) {
        // Sanitize text fields to prevent XSS
        // Note: Username is validated by form validation, not sanitized
        // Usernames should only contain alphanumeric and underscore characters
        
        if (isset($userData['first_name'])) {
            $userData['first_name'] = sanitize_input($userData['first_name']);
        }
        if (isset($userData['last_name'])) {
            $userData['last_name'] = sanitize_input($userData['last_name']);
        }
        if (isset($userData['email'])) {
            $userData['email'] = filter_var($userData['email'], FILTER_SANITIZE_EMAIL);
        }
        
        // Hash the password if it hasn't been hashed yet
        // Check if password is provided (not already hashed as password_hash)
        if (isset($userData['password']) && !isset($userData['password_hash'])) {
            $userData['password_hash'] = password_hash($userData['password'], PASSWORD_BCRYPT);
            unset($userData['password']); // Remove plain text password
        } elseif (isset($userData['password'])) {
            // If both exist, remove plain password and keep the hash
            unset($userData['password']);
        }
        // If only password_hash exists (already hashed by controller), use it as-is
      
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

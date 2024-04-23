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

    public function get_user_by_id($user_id) {
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');
        return $query->row_array(); // Return user data as an associative array
    }

    public function search_users($query) {
        $this->db->like('username', $query);
        $query = $this->db->get('users');
        return $query->result_array();
    } 

    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get('users');
        return $query->row_array();
    }

}

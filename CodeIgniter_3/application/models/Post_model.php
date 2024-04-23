<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Method to get posts by user ID
    public function get_posts_by_user_id($user_id) {
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('posts');
        return $query->result_array(); // Return the result as an array
    }

    public function get_post_by_id($post_id) {
        $this->db->where('id', $post_id);
        $query = $this->db->get('posts');

        if ($query->num_rows() === 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }
}

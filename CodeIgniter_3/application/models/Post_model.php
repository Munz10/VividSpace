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
        $this->db->select('
            posts.*,
            users.username,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comments_count,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as likes_count
        ');
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.user_id');
        $this->db->where('posts.id', $post_id);
        $query = $this->db->get();
    
        if ($query->num_rows() === 1) {
            return $query->row_array();
        } else {
            return false;
        }
    }

    // Add a like or remove it if it already exists
    public function toggle_like($post_id, $user_id) {
        $this->db->where(['post_id' => $post_id, 'user_id' => $user_id]);
        $exists = $this->db->get('likes');

        if ($exists->num_rows() > 0) {
            $this->db->delete('likes', ['post_id' => $post_id, 'user_id' => $user_id]);
        } else {
            $this->db->insert('likes', ['post_id' => $post_id, 'user_id' => $user_id]);
        }

        // Return the current count of likes for this post
        $this->db->where('post_id', $post_id);
        return $this->db->count_all_results('likes');
    }

    // Add a new comment to a post
    public function add_comment($post_id, $user_id, $content) {
        $this->db->insert('comments', [
            'post_id' => $post_id,
            'user_id' => $user_id,
            'content' => $content
        ]);
    
        // Get the inserted comment ID
        $comment_id = $this->db->insert_id();
    
        // Fetch the username associated with the user ID
        $this->db->select('username');
        $this->db->where('id', $user_id);
        $query = $this->db->get('users');

        // Check if the query was successful
        if ($query->num_rows() === 1) {
            // Fetch the username from the query result
            $username = $query->row()->username;
        } else {
            // Set a default username if user not found
            $username = 'Unknown';
        }    
        // Return an array containing the comment ID, content, and username
        return array(
            'id' => $comment_id,
            'content' => $content,
            'username' => $username
        );
    }
    
    public function get_comments_by_post_id($post_id) {
        $this->db->select('comments.*, users.username');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id');
        $this->db->where('post_id', $post_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_posts_by_user_ids($user_ids) {
        if (empty($user_ids)) {
            // If the array of user IDs is empty, return an empty array of posts
            return array();
        }
    
        $this->db->select('posts.*, users.username as author_username');
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.user_id');
        $this->db->where_in('posts.user_id', $user_ids);
        $query = $this->db->get();
        return $query->result_array();
    }
    
    
    public function delete_post($post_id) {
        // First, delete associated likes
        $this->db->where('post_id', $post_id);
        $this->db->delete('likes');

        // Delete associated comments first
        $this->db->where('post_id', $post_id);
        $this->db->delete('comments');
    
        // Then delete the post
        $this->db->where('id', $post_id);
        $this->db->delete('posts');
    
        // Check if any rows were affected
        if ($this->db->affected_rows() > 0) {
            // Post deleted successfully
            return true;
        } else {
            // Post not found or not deleted
            return false;
        }
    }

    // Method to check if the post has been liked by the current user
    public function is_post_liked($post_id) {
        // Assuming you have a table to store likes with columns like 'post_id' and 'user_id'
        $user_id = $this->session->userdata('user_id'); // Assuming you're using sessions for authentication
        
        // Query the database to check if the post has been liked by the current user
        $query = $this->db->get_where('likes', array('post_id' => $post_id, 'user_id' => $user_id));

        // Return true if the query result is not empty (post has been liked by the user), otherwise return false
        return $query->num_rows() > 0;
    }
}

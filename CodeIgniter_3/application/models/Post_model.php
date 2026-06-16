<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_posts_by_user_id($user_id, $limit = 12, $offset = 0) {
        $this->db->select('
            posts.*,
            users.username,
            (SELECT COUNT(*) FROM likes    WHERE likes.post_id    = posts.id) as likes_count,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comments_count
        ');
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.user_id');
        $this->db->where('posts.user_id', $user_id);
        $this->db->order_by('posts.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
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

    // Add a like or remove it if it already exists.
    // Returns ['is_liked' => bool, 'likes_count' => int].
    public function toggle_like($post_id, $user_id) {
        $this->db->where(['post_id' => $post_id, 'user_id' => $user_id]);
        $exists = $this->db->get('likes');

        if ($exists->num_rows() > 0) {
            $this->db->delete('likes', ['post_id' => $post_id, 'user_id' => $user_id]);
            $is_liked = false;
        } else {
            $this->db->insert('likes', ['post_id' => $post_id, 'user_id' => $user_id]);
            $is_liked = true;
        }

        $this->db->where('post_id', $post_id);
        return [
            'is_liked'    => $is_liked,
            'likes_count' => $this->db->count_all_results('likes'),
        ];
    }

    public function has_liked($post_id, $user_id) {
        if (!$user_id) {
            return false;
        }
        return $this->db->where(['post_id' => $post_id, 'user_id' => $user_id])
                        ->count_all_results('likes') > 0;
    }

    // Add a new comment to a post
    public function add_comment($post_id, $user_id, $content) {
        // Sanitize content - remove HTML tags but keep text
        $content = sanitize_input($content, false);
        
        $this->db->insert('comments', [
            'post_id' => $post_id,
            'user_id' => $user_id,
            'content' => $content
        ]);
        return $this->db->insert_id();
    }
    
    // In your Post_model
    public function get_comments_by_post_id($post_id) {
        $this->db->select('comments.*, users.username');
        $this->db->from('comments');
        $this->db->join('users', 'users.id = comments.user_id');
        $this->db->where('post_id', $post_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_posts_by_user_ids($user_ids, $limit = 12, $offset = 0) {
        if (empty($user_ids)) {
            return [];
        }
        $this->db->select('
            posts.*,
            users.username as author_username,
            (SELECT COUNT(*) FROM likes WHERE likes.post_id = posts.id) as likes_count,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comments_count
        ', FALSE);
        $this->db->from('posts');
        $this->db->join('users', 'users.id = posts.user_id');
        $this->db->where_in('posts.user_id', $user_ids);
        $this->db->order_by('posts.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
    
    public function delete_post($post_id, $user_id) {
        $owner = $this->db->select('user_id, image_path')
                          ->where('id', $post_id)
                          ->get('posts')
                          ->row_array();

        if (!$owner || (int) $owner['user_id'] !== (int) $user_id) {
            return false;
        }

        $this->db->where('post_id', $post_id)->delete('likes');
        $this->db->where('post_id', $post_id)->delete('comments');
        $this->db->where('id', $post_id)->delete('posts');

        if ($this->db->affected_rows() <= 0) {
            return false;
        }

        if (!empty($owner['image_path'])) {
            $file = FCPATH . ltrim($owner['image_path'], '/');
            if (is_file($file)) {
                @unlink($file);
            }
        }

        return true;
    }

    public function update_post($post_id, $user_id, $data) {
        $allowed = ['caption', 'hashtags'];
        $clean = array_intersect_key($data, array_flip($allowed));
        if (empty($clean)) {
            return false;
        }

        $this->db->where('id', $post_id);
        $this->db->where('user_id', $user_id);
        $this->db->update('posts', $clean);
        return $this->db->affected_rows() >= 0 && $this->is_owner($post_id, $user_id);
    }

    public function is_owner($post_id, $user_id) {
        $row = $this->db->select('user_id')
                        ->where('id', $post_id)
                        ->get('posts')
                        ->row();
        return $row && (int) $row->user_id === (int) $user_id;
    }
}

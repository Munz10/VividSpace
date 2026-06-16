<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bookmark_model extends CI_Model {

    public function toggle($user_id, $post_id)
    {
        $exists = $this->db->where(['user_id' => $user_id, 'post_id' => $post_id])
                           ->count_all_results('bookmarks') > 0;
        if ($exists) {
            $this->db->delete('bookmarks', ['user_id' => $user_id, 'post_id' => $post_id]);
            return false;
        }
        $this->db->insert('bookmarks', ['user_id' => $user_id, 'post_id' => $post_id]);
        return true;
    }

    public function list_for_user($user_id, $limit = 24, $offset = 0)
    {
        $this->db->select('
            posts.*,
            users.username as author_username,
            (SELECT COUNT(*) FROM likes    WHERE likes.post_id    = posts.id) as likes_count,
            (SELECT COUNT(*) FROM comments WHERE comments.post_id = posts.id) as comments_count
        ', FALSE);
        $this->db->from('bookmarks');
        $this->db->join('posts', 'posts.id = bookmarks.post_id');
        $this->db->join('users', 'users.id = posts.user_id');
        $this->db->where('bookmarks.user_id', (int) $user_id);
        $this->db->order_by('bookmarks.created_at', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }
}

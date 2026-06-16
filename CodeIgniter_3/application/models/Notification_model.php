<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_model extends CI_Model {

    public function create($user_id, $actor_id, $type, $entity_id = null)
    {
        if ((int) $user_id === (int) $actor_id) {
            return;
        }
        $this->db->insert('notifications', [
            'user_id'   => (int) $user_id,
            'actor_id'  => (int) $actor_id,
            'type'      => $type,
            'entity_id' => $entity_id !== null ? (int) $entity_id : null,
        ]);
    }

    public function list_for_user($user_id, $limit = 20)
    {
        return $this->db
            ->select('n.id, n.type, n.entity_id, n.is_read, n.created_at, u.username AS actor_username')
            ->from('notifications n')
            ->join('users u', 'u.id = n.actor_id')
            ->where('n.user_id', (int) $user_id)
            ->order_by('n.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    public function unread_count($user_id)
    {
        return (int) $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->count_all_results('notifications');
    }

    public function mark_all_read($user_id)
    {
        $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_read', 0)
            ->update('notifications', ['is_read' => 1]);
    }
}

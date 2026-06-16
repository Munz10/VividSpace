<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Post_model');
    $this->load->model('Notification_model');
    $this->load->helper('url');
    $this->load->library('session');

    $public = ['detail'];
    if (!$this->session->userdata('logged_in') && !in_array($this->router->method, $public, true)) {
      redirect('login');
    }
  }

  public function detail($post_id) {
    $data['post'] = $this->Post_model->get_post_by_id($post_id);
    if (!$data['post']) {
      show_404();
      return;
    }
    $user_id = $this->session->userdata('user_id');
    $data['is_liked'] = $this->Post_model->has_liked($post_id, $user_id);
    $data['comments'] = $this->Post_model->get_comments_by_post_id($post_id);
    $this->load->view('post_detail', $data);
  }

  // Method to toggle a like
  public function toggle_like() {
    // Set JSON header
    header('Content-Type: application/json');
    
    // Validate CSRF token (automatic with CodeIgniter when enabled)
    $post_id = $this->input->post('post_id');
    $user_id = $this->session->userdata('user_id');
    
    // Validate input
    if (!$post_id || !$user_id) {
      echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
      return;
    }
    
    $result = $this->Post_model->toggle_like($post_id, $user_id);

    if ($result['is_liked']) {
      $post = $this->Post_model->get_post_by_id($post_id);
      if ($post) {
        $this->Notification_model->create($post['user_id'], $user_id, 'like', $post_id);
      }
    }

    echo json_encode([
      'status'      => 'success',
      'is_liked'    => $result['is_liked'],
      'likes_count' => $result['likes_count'],
      'csrf_token'  => $this->security->get_csrf_hash()
    ]);
  }

  // Method to add a comment
  public function add_comment() {
    // Set JSON header
    header('Content-Type: application/json');
    
    // Validate CSRF token (automatic with CodeIgniter when enabled)
    $post_id = $this->input->post('post_id');
    $user_id = $this->session->userdata('user_id');
    $content = trim($this->input->post('content'));
    
    // Validate input
    if (!$post_id || !$user_id || empty($content)) {
      echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request or empty comment'
      ]);
      return;
    }
    
    $comment_id = $this->Post_model->add_comment($post_id, $user_id, $content);

    if ($comment_id) {
      $post = $this->Post_model->get_post_by_id($post_id);
      if ($post) {
        $this->Notification_model->create($post['user_id'], $user_id, 'comment', $post_id);
      }
      echo json_encode([
        'status' => 'success',
        'comment_id' => $comment_id,
        'csrf_token' => $this->security->get_csrf_hash()
      ]);
    } else {
      echo json_encode([
        'status' => 'error',
        'message' => 'Unable to add comment'
      ]);
    }
  }

  public function get_comments($post_id) {
    // Get comments from the model and format them in the view (same as before)
    $comments = $this->Post_model->get_comments_by_post_id($post_id);
    $data['comments'] = $comments;
    $this->load->view('partials/comments', $data);
  }

  public function edit($post_id) {
    $user_id = $this->session->userdata('user_id');
    $post = $this->Post_model->get_post_by_id($post_id);

    if (!$post || !$this->Post_model->is_owner($post_id, $user_id)) {
      show_error('You do not have permission to edit this post.', 403);
      return;
    }

    $this->load->view('post_edit', ['post' => $post]);
  }

  public function update() {
    $user_id = $this->session->userdata('user_id');
    $post_id = (int) $this->input->post('id');

    if (!$post_id || !$this->Post_model->is_owner($post_id, $user_id)) {
      show_error('You do not have permission to edit this post.', 403);
      return;
    }

    $data = [
      'caption'  => $this->input->post('caption'),
      'hashtags' => $this->input->post('hashtags'),
    ];

    if ($this->Post_model->update_post($post_id, $user_id, $data)) {
      redirect('post/detail/' . $post_id);
    } else {
      header('Content-Type: application/json');
      echo json_encode(['error' => 'Unable to update post']);
    }
  }
}
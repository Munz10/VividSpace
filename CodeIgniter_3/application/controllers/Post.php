<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model('Post_model');
    $this->load->helper('url');
    $this->load->library('session');
    
    // Ensure user is logged in for all methods (except detail)
    if (!$this->session->userdata('logged_in') && !method_exists($this, $_SERVER['REQUEST_METHOD'] . '_' . $this->router->method)) {
      redirect('login');
    }
  }

  public function detail($post_id) {
    // Fetch post details and check existence (same as before)
    $data['post'] = $this->Post_model->get_post_by_id($post_id);
    if (!$data['post']) {
      show_404();
    } else {
      $data['comments'] = $this->Post_model->get_comments_by_post_id($post_id);
      $this->load->view('post_detail', $data);
    }
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
    
    $likes_count = $this->Post_model->toggle_like($post_id, $user_id);

    echo json_encode([
      'status' => 'success',
      'likes_count' => $likes_count,
      'csrf_token' => $this->security->get_csrf_hash() // Return new token
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
      echo json_encode([
        'status' => 'success',
        'comment_id' => $comment_id,
        'csrf_token' => $this->security->get_csrf_hash() // Return new token
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

  // Method to edit a post (assuming edit permission check in Post_model)
  public function edit($post_id) {
    $post = $this->Post_model->get_post_by_id($post_id);
    if ($post && $this->Post_model->can_edit_post($post_id)) { // Check edit permission
      // Load edit form with post data
      $this->load->view('post_edit', ['post' => $post]);
    } else {
      show_404(); // Or handle unauthorized access differently
    }
  }

  // Method to update a post after edit form submission
  public function update() {
    $post_id = $this->input->post('id');
    $data = $this->input->post(); // Assuming validation is done in Post_model

    if ($this->Post_model->update_post($post_id, $data)) {
      redirect('post/detail/' . $post_id); // Redirect to updated post detail
    } else {
      echo json_encode(['error' => 'Unable to update post']); // Or display error message
    }
  }

  // ... Other methods related to posts ...
}


//Centralized Login Check: The __construct now checks if the user is logged in for all methods except detail. This ensures unauthorized access is prevented for actions requiring user authentication.
//Full Comment Data in Add Comment: In add_comment, you can optionally retrieve the full comment data (including user information) after successful insertion using `$comment = $this->Post_model->get
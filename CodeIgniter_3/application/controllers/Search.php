<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('User_model');
        $this->load->model('Post_model');
    }
    public function index() {
        // Optional: Load a search form view
        $this->load->view('search_form');
      }

    public function result() {
        $query = trim($this->input->get('query', TRUE));

        if (strlen($query) < 1) {
            redirect('profile/feed');
            return;
        }

        $current_user_id = (int) $this->session->userdata('user_id');
        $data['users']  = $this->User_model->search_users($query, $current_user_id);
        $data['posts']  = $this->Post_model->search_posts($query);
        $data['query']  = $query;

        $this->load->view('search_result', $data);
    }

    public function hashtag($tag) {
        $tag = trim($this->uri->segment(3));
        if (!$tag) { redirect('profile/feed'); return; }

        $data['tag']      = $tag;
        $data['posts']    = $this->Post_model->get_posts_by_hashtag($tag);
        $data['has_more'] = count($data['posts']) === 12;
        $this->load->view('hashtag_posts', $data);
    }

    public function dynamicResult() {
        $query = $this->input->get('query');
        $current_user_id = (int) $this->session->userdata('user_id');
        $data['results'] = $this->User_model->search_users($query, $current_user_id);
        header('Content-Type: application/json');
        echo json_encode($data);
    }
}

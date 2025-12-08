<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_xss extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(['url', 'xss']);
        $this->load->library('session');
    }

    /**
     * Display XSS test page
     */
    public function index() {
        $this->load->view('test_xss');
    }

    /**
     * Test endpoint - demonstrates proper XSS protection
     */
    public function test_safe_output() {
        header('Content-Type: application/json');
        
        $user_input = $this->input->post('user_input');
        
        // Sanitize for storage
        $sanitized = sanitize_input($user_input, false);
        
        // Escape for output
        $escaped = esc($user_input);
        
        echo json_encode([
            'status' => 'success',
            'original' => $user_input,
            'sanitized_for_storage' => $sanitized,
            'escaped_for_output' => $escaped,
            'message' => 'Input processed safely',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }

    /**
     * UNSAFE example - shows what happens without protection
     * DO NOT USE THIS IN PRODUCTION
     */
    public function test_unsafe_output() {
        header('Content-Type: application/json');
        
        $user_input = $this->input->post('user_input');
        
        // WARNING: This is UNSAFE - for demonstration only
        echo json_encode([
            'status' => 'warning',
            'unsafe_output' => $user_input, // NOT ESCAPED!
            'message' => 'This output is NOT safe!',
            'csrf_token' => $this->security->get_csrf_hash()
        ]);
    }
}


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_csrf extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        
        // For testing purposes, allow access without login
        // Remove this check in production
    }

    /**
     * Display CSRF test page
     */
    public function index() {
        $this->load->view('test_csrf');
    }

    /**
     * Test endpoint - with CSRF protection
     */
    public function test_protected_action() {
        // Set JSON header
        header('Content-Type: application/json');
        
        // This will automatically validate CSRF token
        $test_data = $this->input->post('test_data');
        
        if ($test_data) {
            echo json_encode([
                'status' => 'success',
                'message' => 'CSRF token validated successfully!',
                'data_received' => $test_data,
                'csrf_token' => $this->security->get_csrf_hash() // Return new token
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'No data received',
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
        }
    }

    /**
     * Test what happens without CSRF token
     */
    public function test_without_token() {
        echo json_encode([
            'status' => 'info',
            'message' => 'If you see this, CSRF validation passed (or is disabled)'
        ]);
    }
}


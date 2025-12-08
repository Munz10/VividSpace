<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Follow extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        // Ensure the user is logged in
        $this->load->library('session');
        $this->load->library('form_validation');

        if (!$this->session->userdata('logged_in')) {
            redirect('login');
        }
    }

    public function do_follow() {
        // Set JSON header
        header('Content-Type: application/json');
        
        // CSRF token validated automatically by CodeIgniter
        $this->form_validation->set_rules('following_id', 'Following User ID', 'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() === TRUE) {
            $follower_id = $this->session->userdata('user_id');
            $following_id = $this->input->post('following_id');
            
            // Prevent self-follow
            if ($follower_id == $following_id) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'You cannot follow yourself'
                ]);
                return;
            }
            
            $result = $this->User_model->follow($follower_id, $following_id);
            $status = $result ? 'success' : 'error';
            $action = $result ? 'unfollow' : 'follow';
            
            if ($result) {
              // Activity Logging (Replace with your logging mechanism)
              log_message('info', "User $follower_id followed user $following_id");
              
              // Follow Notifications (Replace with your notification library)
              // $this->send_notification($following_id, "User $follower_id started following you");
            }
            
            echo json_encode([
                'status' => $status,
                'action' => $action,
                'csrf_token' => $this->security->get_csrf_hash() // Return new token
            ]);
          } else {
            $errors = validation_errors('<p>', '</p>');
            echo json_encode([
                'status' => 'error',
                'message' => $errors,
                'csrf_token' => $this->security->get_csrf_hash()
            ]);
          }
        }


        public function do_unfollow() {
            // Set JSON header
            header('Content-Type: application/json');
            
            // CSRF token validated automatically by CodeIgniter
            // Rate Limiting (TODO: Implement rate limiting library)
            // $this->rate_limit->check($this->session->userdata('user_id'), 'unfollow');
        
            $this->form_validation->set_rules('following_id', 'Following User ID', 'trim|required|is_natural_no_zero');
        
            if ($this->form_validation->run() === TRUE) {
              $follower_id = $this->session->userdata('user_id');
              $following_id = $this->input->post('following_id');
              
              $result = $this->User_model->unfollow($follower_id, $following_id);
              $status = $result ? 'success' : 'error';
              $action = $result ? 'follow' : 'unfollow';
              
              if ($result) {
                // Activity Logging
                log_message('info', "User $follower_id unfollowed user $following_id");
              }
              
              echo json_encode([
                  'status' => $status,
                  'action' => $action,
                  'csrf_token' => $this->security->get_csrf_hash() // Return new token
              ]);
            } else {
              $errors = validation_errors('<p>', '</p>');
              echo json_encode([
                  'status' => 'error',
                  'message' => $errors,
                  'csrf_token' => $this->security->get_csrf_hash()
              ]);
            }
          }

        private function log_activity($message) {
            // ...  function is added as a placeholder to demonstrate where you would implement activity logging logic specific to your application
            private function log_activity($message) {
                $this->load->library('Log');
                $this->log->write('log', $message);
              }
              
        }
  
        private function send_notification($user_id, $message) {
            // ... function is added as a placeholder to demonstrate where you would implement notification logic using a library or custom solution.
            $this->load->library('email');
            $this->email->from('your_email@example.com', 'Your Application');
            $this->email->to($this->get_user_email_by_id($user_id)); // Replace with your logic to get user email
            $this->email->subject('Notification');
            $this->email->message($message);
            $this->email->send();
        }

        //need to configure email settings in config/email.php
}

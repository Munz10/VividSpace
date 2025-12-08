<?php
class LandingPage extends CI_Controller {

    public function index() {
        $this->load->helper('url');
        $this->load->model('Product_model');
        
        // Load featured products
        $featured_products = $this->Product_model->get_featured_products();
        
        // Check for logged-in user
        $logged_in = $this->session->userdata('logged_in');
      
        // Pass data to the view
        $this->load->view('landing_page', [
          'featured_products' => $featured_products,
          'logged_in' => $logged_in
        ]);
      }
    }      

    
//Load Data: You can modify the code to load data from a model before displaying it in the view. For example, you could load featured products, testimonials, or blog posts:

//Conditional Content: You can display different content based on certain conditions, like user login status:


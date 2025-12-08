<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * XSS Protection Helper
 * 
 * Provides consistent XSS protection throughout the application
 */

if (!function_exists('esc')) {
    /**
     * Escape output for HTML context
     * 
     * @param string $string The string to escape
     * @param string $encoding Character encoding (default: UTF-8)
     * @return string Escaped string safe for HTML output
     */
    function esc($string, $encoding = 'UTF-8') {
        if ($string === null || $string === '') {
            return '';
        }
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5, $encoding, false);
    }
}

if (!function_exists('esc_attr')) {
    /**
     * Escape output for HTML attributes
     * 
     * @param string $string The string to escape
     * @return string Escaped string safe for HTML attributes
     */
    function esc_attr($string) {
        return esc($string);
    }
}

if (!function_exists('esc_url')) {
    /**
     * Escape and validate URL
     * 
     * @param string $url The URL to escape
     * @return string Sanitized URL
     */
    function esc_url($url) {
        // Remove any dangerous characters
        $url = strip_tags($url);
        $url = str_replace(['javascript:', 'data:', 'vbscript:'], '', strtolower($url));
        return filter_var($url, FILTER_SANITIZE_URL);
    }
}

if (!function_exists('esc_js')) {
    /**
     * Escape output for JavaScript context
     * 
     * @param string $string The string to escape
     * @return string Escaped string safe for JavaScript
     */
    function esc_js($string) {
        // Escape backslashes and single quotes
        $string = str_replace('\\', '\\\\', $string);
        $string = str_replace("'", "\\'", $string);
        $string = str_replace('"', '\\"', $string);
        $string = str_replace("\n", '\\n', $string);
        $string = str_replace("\r", '\\r', $string);
        $string = str_replace('</', '<\/', $string); // Prevent closing script tags
        return $string;
    }
}

if (!function_exists('sanitize_input')) {
    /**
     * Sanitize user input (for storage, not display)
     * Removes dangerous HTML tags but keeps basic formatting
     * 
     * @param string $input The input to sanitize
     * @param bool $allow_basic_html Allow basic HTML tags (p, br, b, i, em, strong)
     * @return string Sanitized input
     */
    function sanitize_input($input, $allow_basic_html = false) {
        if ($input === null || $input === '') {
            return '';
        }
        
        // Get CodeIgniter instance
        $CI =& get_instance();
        
        if ($allow_basic_html) {
            // Allow only safe HTML tags
            $allowed_tags = '<p><br><b><i><em><strong><u>';
            $input = strip_tags($input, $allowed_tags);
        } else {
            // Remove all HTML tags
            $input = strip_tags($input);
        }
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
}

if (!function_exists('clean_filename')) {
    /**
     * Sanitize filename for safe file uploads
     * 
     * @param string $filename The filename to sanitize
     * @return string Safe filename
     */
    function clean_filename($filename) {
        // Remove any path information
        $filename = basename($filename);
        
        // Remove dangerous characters
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Prevent double extensions
        $filename = preg_replace('/\.+/', '.', $filename);
        
        return $filename;
    }
}


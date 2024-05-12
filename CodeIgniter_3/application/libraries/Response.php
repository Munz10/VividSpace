<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Response {

    public function send($data) {
        // Code to send response data
        echo json_encode($data);
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rssfeed extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Global_model'));
        $this->load->helper('xml');
    }

    /* To get Post Data */

    function index() {
        if ($_SERVER['SERVER_NAME'] == '192.168.1.38') {
            $url = 'http://192.168.1.38/xploration/stories';
            $imageUrl = 'http://192.168.1.38/xploration/api/uploads/mediauploads/images/thumbnails_300_250/';
        } else if ($_SERVER['SERVER_NAME'] == '199.38.182.140') {
            $url = 'http://199.38.182.140/~xplorationstatio/stories';
            $imageUrl = 'http://199.38.182.140/~xplorationstatio/api/uploads/mediauploads/images/thumbnails_300_250/';
        } else {
            $url = 'http://www.xplorationstation.com/stories';
            $imageUrl = 'http://www.xplorationstation.com/api/uploads/mediauploads/images/thumbnails_300_250/';
        }
        $data['urlen'] = $url;
        $data['imagUrl'] = $imageUrl;
        $data['encoding'] = 'utf-8';
        $data['feed_name'] = 'www.xplorationstation.com';
        $data['feed_url'] = 'http://www.xplorationstation.com';
        $data['page_description'] = 'Welcome to www.xplorationstation.com feed url page';
        $data['page_language'] = 'en-ca';
        $data['creator_email'] = 'xplorationstation@gmail.com';
        $query = $this->Global_model->getAllData('posts');
        $data['post_details'] = null;
        if ($query) {
            $data['post_details'] = $query;
        }
        header("Content-Type: application/rss+xml");
        $this->load->view('feed', $data);
    }

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function authIn($result) {
        $this->db->select('*');
        $this->db->where('email', $result['email']);
        $this->db->where('password', md5($result['password']));
        $this->db->where('status', 'A');
        $query = $this->db->get('admin_users');
        return $query->row_array();
    }

}

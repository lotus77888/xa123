<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function changePassword($result) {
        $this->db->select('*');
        $this->db->where('email', $result['email']);
        $this->db->where('password', md5($result['password']));
        $this->db->where('status', 'A');
        $query = $this->db->get('admin_users');
        return $query->row_array();
    }

    public function toGetUserData($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $query = $this->db->get('admin_users');
        return $query->row_array();
    }

    public function toGetSubAdminsData($subId) {
        $this->db->select('*');
        $this->db->where('id', $subId);
        $query = $this->db->get('admin_users');
        return $query->row_array();
    }

    public function chkUserExisitingPassword($id, $pwd) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $this->db->where('password', md5($pwd));
        $query = $this->db->get('admin_users');
//        echo $this->db->last_query();exit;
        return $query->row_array();
    }

    /* To Get Email */

    public function toGetEmailData($email, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('email', $email);
        } else {
            $this->db->where('email', $email);
            $this->db->where("status != 'I'");
        }
        $this->db->from('admin_users');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }
    
    /* To Get User Email */

    public function toGetUserEmailData($email, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('email', $email);
        } else {
            $this->db->where('email', $email);
            $this->db->where("status != 'I'");
        }
        $this->db->from('users');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }
    
    
    
    

}

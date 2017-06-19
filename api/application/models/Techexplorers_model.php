<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Techexplorers_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function togetAllTechexplorersDataByLimit($limit, $slug) {
        $this->db->select('te.*, c.category_name,s.show_slug');
        $this->db->from('tech_explorers te');
        $this->db->join('categories c', 'c.id = te.category_id');
        $this->db->join('shows s', 's.xplorer_id = te.id','LEFT');
        $this->db->where('c.slug', $slug);
//        $this->db->order_by('rand()');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->limit($limit, 0);
        $query = $this->db->get();
        return $query->result_array();
    }
    function toGetAllXplorersDataByLimitById($limit, $id) {
        $this->db->select('te.*, c.category_name,s.show_slug');
        $this->db->from('tech_explorers te');
        $this->db->join('categories c', 'c.id = te.category_id');
        $this->db->join('shows s', 's.xplorer_id = te.id','LEFT');
        $this->db->where('c.id', $id);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->group_by('te.id');
        $this->db->limit($limit, 0);
        $query = $this->db->get();
        return $query->result_array();
    }
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shows_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function toGetAllShowsData() {
        $this->db->select('s.*,x.*, s.id as show_id, s.status as show_status');
        $this->db->from('shows s');
        $this->db->join('tech_explorers x', 'x.id = s.xplorer_id');
        $this->db->order_by('s.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
//        echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    function toGetShowData($id) {
        $this->db->select('*,id as show_id');
        $this->db->where('id', $id);
        $this->db->from('shows');
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    function toGetShowSeasonsData($id) {
        $this->db->select('id as show_season_id, season_name, shows_id, show_xplorer_id');
        $this->db->where('shows_id', $id);
        $this->db->from('shows_seasons');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function toGetShowWatchLinksData($slug) {
        $this->db->select('sw.id as show_watchlink_id, sw.link_logo, sw.shows_id, sw.watch_link');
        $this->db->where('s.show_slug', $slug);
        $this->db->from('shows s');
        $this->db->join('shows_watchlinks sw', 'sw.shows_id = s.id');
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }

    function toGetShowWatchLinksDataAdmin($id) {
        $this->db->select('sw.id as show_watchlink_id, sw.link_logo, sw.shows_id, sw.watch_link');
        $this->db->where('s.id', $id);
        $this->db->from('shows s');
        $this->db->join('shows_watchlinks sw', 'sw.shows_id = s.id');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    function toGetXplorerDataByUsingShowId($slug) {
        $this->db->select('x.*');
        $this->db->from('shows s');
        $this->db->where('s.show_slug', $slug);
        $this->db->join('tech_explorers x', 'x.id = s.xplorer_id');
        $query = $this->db->get();
        $result = $query->row_array();
//        echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    function toGetShowsDataByUsingShowId($slug) {
        $this->db->select('s.*');
        $this->db->from('shows s');
        $this->db->where('s.show_slug', $slug);
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

    function togetSeasonsBasedOnShowId($slug) {
        $this->db->select('ss.*');
        $this->db->from('shows s');
        $this->db->where('s.show_slug', $slug);
        $this->db->join('shows_seasons ss', 'ss.shows_id = s.id');
//        $this->db->from('shows_seasons');
        $this->db->order_by('ss.season_name', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    function toGetSeasonsDataByUsingShowId($slug, $limit, $offset, $seasonId) {
        $this->db->select('e.id, e.seasons_id, ss.season_name, e.feature_image,e.thumbnail_image, e.episode_title,e.episode_description,e.episode_slug');
        $this->db->from('shows s');
        $this->db->join('episodes e', 'e.shows_id= s.id', 'LEFT');
        $this->db->join('shows_seasons ss', 'ss.id= e.seasons_id', 'LEFT');
        $this->db->where('e.seasons_id', $seasonId);
        $this->db->where('s.show_slug', $slug);
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        if (isset($limit) && !empty($limit)) {
            $result = $query->result_array();
        } else {
            $result = $query->num_rows();
        }

        return $result;
    }

    /* To Check Slug in Show */

    public function toCheckShowSlugData($slug, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('show_slug', $slug);
        } else {
            $this->db->where('show_slug', $slug);
            $this->db->where("status != 'I'");
        }
        $this->db->from('shows');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* To Check  Name of the Show */

    public function toCheckNameOfShow($showName, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('show_name', $showName);
        } else {
            $this->db->where('show_name', $showName);
            $this->db->where("status != 'I'");
        }
        $this->db->from('shows');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }
    

}

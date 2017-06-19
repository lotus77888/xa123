<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Episode_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /* to get Episode Data */

    function toGetEpisodeData() {
        $this->db->select('e.*, s.show_name, ss.season_name');
        $this->db->from('episodes e');
        $this->db->join('shows s', 's.id = e.shows_id', 'LEFT');
        $this->db->join('shows_seasons ss', 'ss.id = e.seasons_id', 'LEFT');
        $this->db->order_by('e.id', 'DESC');
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }

    /* to get Edit Episode Data */

    function toeditEpisodeData($id) {
        $this->db->select('*,id as episode_id');
        $this->db->from('episodes');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /* to get EpisodeLinks */

    function toGetEpisodeLinks($id) {
        $this->db->select('el.*,sw.link_logo');
        $this->db->from('episode_links el');
        $this->db->where('el.episode_id', $id);
        $this->db->join('shows_watchlinks sw', 'sw.id = el.show_watchlinks_id');
        $query = $this->db->get();
        return $query->result_array();
    }

    /* To Check Slug in Show */

    function toCheckEpisodeSlugData($slug, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('episode_slug', $slug);
        } else {
            $this->db->where('episode_slug', $slug);
            $this->db->where("status != 'I'");
        }
        $this->db->from('episodes');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* to Get Trending Episode Data by episode views */

    function toGetTrendingEpiosdeData($tbname) {
        $this->db->select('*');
        $this->db->from($tbname);
        $this->db->order_by('episode_views', 'DESC');
        $this->db->limit('3', 0);
        $query = $this->db->get();

        return $query->result_array();
    }

    /* to Get episode Data */

    public function episodeDetailView($id) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('episodes e');
        $this->db->where('e.id', $id);
        $wr = 'status = "A" AND publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $query = $this->db->get();
        return $query->row_array();
    }

    /* to Get Next Episode Details */

    public function toGetNextEpisodeDetails($id, $season_id) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('episodes e');
//        $nxtId = 'e.id'.'>'.$id;
        $this->db->where('e.id !=', $id);
        $this->db->where('e.seasons_id', $season_id);
        $wr = 'status = "A" AND publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->order_by('e.id', 'DESC');
        $query = $this->db->get();
        return $query->row_array();
    }

    /* to Get Related Episode Details */

    public function toGetRelatedEpisodeDetails($seasonId, $id, $epiosdes_id) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('episodes e');
        $this->db->where('e.seasons_id', $seasonId);
        $this->db->where_not_in('e.id', $epiosdes_id);
        $this->db->where('e.id !=', $id);
        $this->db->where('e.id !=', $id + 1);
        $wr = 'status = "A" AND publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->limit(3);
        $query = $this->db->get();
        return $query->result_array();
    }

    /* to Get seasonsId */

    public function togetseasonId($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where($tbWheref, $tbWhere);
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    /* to Techxplorer Data using Season Id */

    function toGetXplorerDataBySeasonId($id) {
//        echo $id;exit;
        $this->db->select('x.*');
        $this->db->from('shows_seasons ss');
        $this->db->where('ss.id', $id);
        $this->db->join('tech_explorers x', 'x.id = ss.show_xplorer_id');
        $query = $this->db->get();
        $result = $query->row_array();
//        echo "<pre>";print_r($this->db->last_query());exit;
        return $result;
    }

    function toUpdateEpisodeViewBySlug($tbname, $slug) {
        $this->db->set('episode_views', 'episode_views+1', FALSE);
        $this->db->where('episode_slug', $slug);
        $this->db->update($tbname);
        return true;
    }

}

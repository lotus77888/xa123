<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    function toGetPostData() {
        $this->db->select('p.*,pc.*,c.category_name,c.id as category_id,p.id as post_id');
        $this->db->from('posts p');
        $this->db->join('post_categories pc', 'pc.post_id = p.id');
        $this->db->join('categories c', 'c.id = pc.category_id');
        $this->db->order_by('p.id', 'DESC');
        $this->db->group_by('p.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    function toGetPostDataByCategory($id) {
        $this->db->select('p.post_title,p.id as post_id,p.id as featured_post_id,c.category_name,c.id as category_id');
        $this->db->from('posts p');
        $this->db->join('post_categories pc', 'pc.post_id = p.id');
        $this->db->join('categories c', 'c.id = pc.category_id');
        $this->db->where('c.id', $id);
        $this->db->order_by('p.id', 'DESC');
        $this->db->group_by('p.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    function toEditPostData($id) {
        $this->db->select('*,id as post_id');
        $this->db->from('posts');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    function toGetPostCategoriesById($id) {
        $this->db->select('category_id as id');
        $this->db->from('post_categories');
        $this->db->where('post_id', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    function toGetPostPublishDataByCategory($category, $post_id, $lmt, $ofSet) {
        $dtn = date("Y-m-d");
        $this->db->select(array('p.id', 'p.post_title', 'p.feature_image', 'p.social_sharing_link', 'p.thumbnail_image', 'p.post_slug'));
        $this->db->from('posts p');
        $this->db->join('post_categories pi', 'pi.post_id = p.id');
        $this->db->join('categories c', 'c.id = pi.category_id');
        $wr = 'p.status = "A" AND p.publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (p.publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR p.publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->where('c.slug', $category);
        if ($post_id != 0) {
            $this->db->where('p.id!=', $post_id);
        }
        $this->db->limit($lmt, $ofSet);
        $this->db->order_by('p.created_date', 'DESC');
        $query = $this->db->get();
//         echo $this->db->last_query();exit;
        if (!empty($lmt) && isset($lmt)) {

            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    function toGetPostPublishDataByCategoryById($category, $post_id, $lmt, $ofSet) {
        $dtn = date("Y-m-d");
        $this->db->select(array('p.id', 'p.post_title', 'p.feature_image', 'p.social_sharing_link', 'p.thumbnail_image', 'p.post_slug'));
        $this->db->from('posts p');
        $this->db->join('post_categories pi', 'pi.post_id = p.id');
        $this->db->join('categories c', 'c.id = pi.category_id');
        $wr = 'p.status = "A" AND p.publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (p.publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR p.publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->where('c.id', $category);
        if ($post_id != 0) {
            $this->db->where('p.id!=', $post_id);
        }
        $this->db->limit($lmt, $ofSet);
        $this->db->order_by('p.created_date', 'DESC');
        $query = $this->db->get();
//         echo $this->db->last_query();exit;
        if (!empty($lmt) && isset($lmt)) {

            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }
    
    function toGetTrendingPostData($tbname) {
        $this->db->select('post_title,id as post_id,post_slug');
        $this->db->from($tbname);
        $this->db->order_by('post_views', 'DESC');
        $this->db->limit('3', 0);
        $query = $this->db->get();

        return $query->result_array();
    }

    function toGetTrendingPostDataByCategory($slug) {
        $this->db->select('p.post_title,p.id as post_id,p.post_slug');
        $this->db->from('categories c');
        $this->db->join('post_categories pc', 'pc.category_id = c.id');
        $this->db->join('posts p', 'p.id = pc.post_id');
        $this->db->where('c.slug', $slug);
        $this->db->order_by('post_views', 'DESC');
        $this->db->limit('3', 0);
        $qury = $this->db->get();

        return $qury->result_array();
    }

    function toGetTrendingPostDataByCategoryById($id) {
        $this->db->select('p.post_title,p.id as post_id,p.post_slug');
        $this->db->from('categories c');
        $this->db->join('post_categories pc', 'pc.category_id = c.id');
        $this->db->join('posts p', 'p.id = pc.post_id');
        $this->db->where('c.id', $id);
        $this->db->order_by('post_views', 'DESC');
        $this->db->limit('3', 0);
        $qury = $this->db->get();

        return $qury->result_array();
    }
    
    function toUpdatePostViewById($tbname, $id) {
        $this->db->set('post_views', 'post_views+1', FALSE);
        $this->db->where('id', $id);
        $this->db->update($tbname);

        return true;
    }

    public function toCheckPostTitle($pageTitle, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('post_title', $pageTitle);
        } else {
            $this->db->where('post_title', $pageTitle);
            $this->db->where("status != 'I'");
        }
        $this->db->from('posts');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }
    
    
    public function toCheckPostTitleForMobile($postTitleMobile, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('post_title_mobile', $postTitleMobile);
        } else {
            $this->db->where('post_title_mobile', $postTitleMobile);
            $this->db->where("status != 'I'");
        }
        $this->db->from('posts');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {
            return $num;
        } else {
            return 0;
        }
    }
    
    public function tocheckPostSlug($slug, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('post_slug', $slug);
        } else {
            $this->db->where('post_slug', $slug);
            $this->db->where("status != 'I'");
        }
        $this->db->from('posts');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    public function toGetSidebarDetails($slug) {
        $this->db->select('sb.*');
        $this->db->where('p.post_slug', $slug);
        $this->db->from('posts p');
        $this->db->join('side_bars sb', 'sb.id = p.sidebar_id');
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }

}

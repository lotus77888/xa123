<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Global_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
     * Function is used to insert data
     */

    public function insertData($tbname, $tbdata) {
        $this->db->insert($tbname, $tbdata);
        return $this->db->insert_id();
    }

    /*
     * Function is Used to Insert Batch(group of data) Data
     */

    public function insertBatchData($tbname, $tbdata) {
        $this->db->insert_batch($tbname, $tbdata);
    }

    /*
     * Function is Used to Update the Data
     */

    public function updateData($tbname, $tbdata, $tbl_where, $filed_name) {
        $this->db->where($filed_name, $tbl_where);
        $this->db->update($tbname, $tbdata);
        return true;
    }

    /*
     * Function is Used to UpdateBatch(Group of Data) Data
     */

    public function updateBatchData($tbname, $tbdata, $tbl_where, $filed_name) {
        $this->db->where($filed_name, $tbl_where);
        $this->db->update_batch($tbname, $tbdata);
    }

    /*
     * Function is Used to Delete data based on id
     */

    public function deleteData($tbname, $tbl_where, $filed_name) {
        $this->db->where($filed_name, $tbl_where);
        return $this->db->delete($tbname);
    }

    /*
     * Function is Used to Delete data based on id
     */

    public function deleteDataByType($tbname, $tbl_where, $filed_name, $tbl_where_one) {
        $field_name_one = 'type';
        $this->db->where($filed_name, $tbl_where);
        $this->db->where($field_name_one, $tbl_where_one);
        $this->db->delete($tbname);
    }

    /*
     * Function is Used to Delete multiple records 
     */

    public function multipledelete($tbname, $tbl_where, $filed_name) {
        $this->db->where_in($filed_name, $tbl_where);
        $this->db->delete($tbname);
        return true;
    }

    /*
     * Function is Used to Delete(Group of Data) Data
     */

    public function deleteAllData($tbname) {
        $this->db->empty_table($tbname);
    }

    /*
     * Function is Used to Get All The Data
     */

    public function getAllData($tbname) {
        $this->db->select('*');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    public function toGetSelectedPostData($tbname) {
        $this->db->select('id, post_title, publish_start_date, publish_end_date, status');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* to  Get All Active Sidebars Data */

    public function getAllActiveSidebarsData($tbname) {
        $this->db->select('id,sidebar_title');
        $this->db->where('status', 'A');
        $query = $this->db->order_by('sidebar_title', 'ASC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get All records based on the status
     */

    public function getAllDataStatus($tbname) {
        $this->db->select('*');
        $this->db->where('status', 'A');
        $query = $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);

        return $query->result_array();
    }

    public function getAllDataStatusByAsc($tbname) {
        $this->db->select('*');
        $this->db->where('status', 'A');
        $query = $this->db->order_by('name', 'ASC');
        $query = $this->db->get($tbname);

        return $query->result_array();
    }

    public function getSingleRecord($tbname) {
        $this->db->select('*');
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    /*
     * Function is Used to Get All The Data
     */

    public function getSelFields($tbname, $selArr) {
        $this->db->select($selArr);
        $this->db->where('status', 'A');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    public function getSelKeywordFields($tbname, $selArr) {
        $this->db->select($selArr);
        $this->db->where('status', '1');
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* to Get Parent Category Data  in Ascending order By category_name */

    public function getCategoryData($tbname, $selArr) {
        $this->db->select($selArr);
        $this->db->where('status', 'A');
        $this->db->order_by('category_name', 'ASC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get All The Data with limit
     */

    public function getSelFieldsLimit($tbname, $selArr) {
        $this->db->select($selArr);
        $this->db->limit(100, 0);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get All The Data based on ids
     */

    public function getFieldsby($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where_in($tbWhere, $tbWheref);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get All The Data based on ids and user type active
     */

    public function getUsersbyActive($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where_in($tbWhere, $tbWheref);
        $this->db->where('status', 'A');
        $query = $this->db->get($tbname); //echo $this->db->last_query();exit;
        return $query->result_array();
    }

    /*
     * Function to Get Data From DB Using Field Valie
     */

    public function getFieldsbyIds($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where($tbWheref, $tbWhere);
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    public function getPostDataByCategory($tbWhere, $tbWheref) {
//        echo $tbWhere;
//        echo $tbWheref;exit;
        $this->db->select('c.*,p.post_title,p.feature_image,p.thumbnail_image,p.post_slug');
        $this->db->from('categories c');
        $this->db->join('posts p', 'p.id = c.featured_post_id', 'LEFT');
        $this->db->where($tbWheref, $tbWhere);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        return $query->row_array();
    }

    public function getPostDataByCategoryId($tbWhere, $tbWheref) {
//        echo $tbWhere;
//        echo $tbWheref;exit;
        $this->db->select('c.*,p.post_title,p.feature_image,p.thumbnail_image,p.post_slug');
        $this->db->from('categories c');
        $this->db->join('posts p', 'p.id = c.featured_post_id', 'LEFT');
        $this->db->where('c.'.$tbWheref, $tbWhere);
        $query = $this->db->get();
//        echo $this->db->last_query();exit;
        return $query->row_array();
    }
    
    public function getFieldsbyIdsMultiplay($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where($tbWhere, $tbWheref);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get The Data
     */

    public function getData($tbname, $field_name, $tbl_where) {
        $this->db->select('*');
        $this->db->where($field_name, $tbl_where);
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    /*
     * Function is Used to Get The Data
     */

    public function getAllDataById($tbname, $field_name, $tbl_where) {
        $this->db->select('*');
        $this->db->where($field_name, $tbl_where);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function is Used to Get The Data based on id
     */

    public function getIdData($tbname, $field_name, $tbId, $selArr) {
        $this->db->select($selArr);
        $this->db->where($field_name, $tbId);
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    public function getHours($timestamp) {
        // to change timestamp to hours ago
        $date = new DateTime("$timestamp");

        $post_date = $date->format("U");
        $now = time();

        // will echo "2 hours ago" (at the time of this post)
        return timespan($post_date, $now) . ' ago';
    }

    /*
     * to Get Company Name in contact popup when no user logged in start
     */

    public function togetInfo($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        $this->db->where_in($tbWhere, $tbWheref);
        $query = $this->db->get($tbname);
        return $query->row_array();
    }

    /*
     * To get Data by Limitations
     */

    public function getSelectedDataByLimits($tbname, $selArr, $start, $end, $data = array(), $where = array(), $orWhr = 0, $indus_array = array(), $category_array = array(), $practicearea_array = array(), $userrating = '', $loginuser = '', $keyword = '') {
        $this->db->select($selArr);
        $this->db->join($data['table1'], $data['join']);
        $this->db->join($data['table10'], $data['join10']);
        if (isset($where) && !empty($where)) {
            $this->db->where($data['field_where'], $where);
        } elseif (empty($where)) {
            if ($orWhr == 1) {
                $Qry = "(" . $data['wr_field'] . "=" . $data['wr2'] . ")";
                $this->db->where($Qry);
            }
        }
        if (!empty($indus_array)) {
            $this->db->join($data['table2'], $data['join2']);
            $this->db->where_in($data['field_where2'], $indus_array);
        }
        if (!empty($category_array)) {
            $this->db->join($data['table3'], $data['join3']);
            $this->db->where_in($data['field_where3'], $category_array);
        }
        if (!empty($practicearea_array)) {
            $this->db->join($data['table4'], $data['join4']);
            $this->db->where_in($data['field_where4'], $practicearea_array);
        }
        if (isset($userrating) && !empty($userrating)) {
            $this->db->join($data['table5'], $data['join5']);
            $this->db->where_in($data['field_where5'], $userrating);
        }
//        if (!empty($loginuser)) {
//            $this->db->where($data['field_where6'] . '!=', $loginuser);
//        }
        if (!empty($keyword)) {
            if (empty($practicearea_array)) {
                $this->db->join($data['table4'], $data['join4']);
            }
            if (empty($indus_array)) {
                $this->db->join($data['table2'], $data['join2']);
            }
            $this->db->join($data['table8'], $data['join8']);
            $this->db->join($data['table9'], $data['join9']);
            $field7 = trim($data['field_where7']);
            $field8 = trim($data['field_where8']);
            $field9 = trim($data['field_where9']);
            $likeQry = "(" . "$field7" . " LIKE '%" . $keyword . "%' OR " . "$field8" . " LIKE '%" . $keyword . "%' OR " . "$field9" . " LIKE '%" . $keyword . "%')";
            $this->db->where($likeQry);
        }
        $this->db->limit($end, $start);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    function getFieldsById($tbname, $selArr, $tbWhere, $tbWheref) {
        $this->db->select($selArr);
        if (!empty($tbWheref)) {
            $this->db->where_in($tbWhere, $tbWheref);
        }
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    function getFieldsCategoriesById($tbname, $selArr, $tbWhere, $tbWheref, $tbWheree, $tbWhereff) {
        $this->db->select($selArr);
        if (!empty($tbWheref)) {
            $this->db->where_in($tbWhere, $tbWheref);
        }
        if (!empty($tbWhereff)) {
            $this->db->where_in($tbWheree, $tbWhereff);
        }
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /*
     * Function for get email template
     */

    public function toGetEmailTemplate($email_name) {
        $this->db->select(array('email_name', 'subject', 'htmlcontent'));
        $this->db->where('email_name', $email_name);
        $query = $this->db->get('email_templates');
        return $query->row_array();
    }

    /*
     * Function to get user details for email templates
     */

    public function toGetUserDetails($id) {
        $this->db->select('first_name,last_name,username,email');
        $this->db->where('id', $id);
        $this->db->from('users');
        $query = $this->db->get();
        return $query->row_array();
    }

    /* To Get Count */

    public function toGetAllTablesCount($table) {
        $this->db->select('*');
        $this->db->from($table);
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    // to get post data based on dates
    public function toGetPostPublishData($table, $lmt, $ofSet, $flag, $banner_type) {
        $dtn = date("Y-m-d");
        $this->db->select(array('id', 'post_title', 'feature_image', 'banner_featureImage', 'social_sharing_link', 'thumbnail_image', 'post_slug'));
        $this->db->from($table);
        $wr = 'status = "A" AND publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        if ($banner_type == 'Y') {
            $this->db->where('banner_option', 'Y');
        } else {
            $this->db->where('banner_option!=', 'Y');
        }
//        $this->db->where('banner_option!=', 'Y');
        $this->db->limit($lmt, $ofSet);
        $this->db->order_by('created_date', 'DESC');
        $query = $this->db->get();
        if ($flag) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    // to get post details view
    public function postDetailView($id, $flag) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->from('posts p');
        $wr = 'status = "A" AND publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        if ($flag && $id) {
            $this->db->where('p.id !=', $id);
        } elseif ($id) {
            $this->db->where('p.id', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /* to get Post preview Data */

    public function previewPostDetailView($id, $flag) {
        $this->db->select('*');
        $this->db->from('posts p');
        if ($flag && $id) {
            $this->db->where('p.id !=', $id);
        } elseif ($id) {
            $this->db->where('p.id', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /* end */

    public function postCategories($id) {
        $this->db->select(array('pc.category_id', 'c.category_name'));
        $this->db->join('categories c', 'c.id=pc.category_id');
        $this->db->where('pc.post_id', $id);
        $this->db->where('c.status', 'A');
        $query = $this->db->get('post_categories pc');
        return $query->result_array();
    }

    public function getPostBasedOnCategory($category_id, $post_id) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->where_in('pc.category_id', $category_id);
        $this->db->where('pc.post_id !=', $post_id);
        $this->db->where('p.status', 'A');
        $this->db->where('p.id !=', $post_id);
        $wr = 'p.status = "A" AND p.publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (p.publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR p.publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->join('posts p', 'p.id = pc.post_id');
        $this->db->order_by('p.created_date', 'DESC');
        $query = $this->db->get('post_categories pc');
        return $query->row_array();
    }

    public function relStories($category_id, $post_ids) {
        $dtn = date("Y-m-d");
        $this->db->select('*');
        $this->db->where_not_in('pc.post_id', $post_ids);
        $this->db->where_in('pc.category_id', $category_id);
        $this->db->join('post_categories pc', 'p.id = pc.post_id');
        $this->db->join('categories c', 'c.id = pc.category_id');
        $this->db->order_by('p.created_date', 'DESC');
        $wr = 'p.status = "A" AND p.publish_start_date<="' . date('Y-m-d', strtotime($dtn)) . '" AND (p.publish_end_date>="' . date('Y-m-d', strtotime($dtn)) . '" OR p.publish_end_date="0000-00-00" )';
        $this->db->where($wr);
        $this->db->limit(3);
        $this->db->group_by('p.id');
        $query = $this->db->get('posts p');


        return $query->result_array();
    }

    // to get shows data based on dates
    public function toGetShowPublishData($table, $lmt, $ofSet, $flag) {
        $dtn = date("Y-m-d");
        $this->db->select(array('id', 'show_name', 'featured_image', 'show_slug'));
        $this->db->from($table);
        $this->db->where('status', 'A');
        //$this->db->where('publish_start_date <=', date('Y-m-d', strtotime($dtn)));
        //$this->db->where('publish_end_date >=', date('Y-m-d', strtotime($dtn)));
        $this->db->limit($lmt, $ofSet);
        $this->db->order_by('sort_order', 'ASC');
        $query = $this->db->get();
        if ($flag) {
            return $query->result_array();
        } else {
            return $query->num_rows();
        }
    }

    /* To get all Category Data */

    public function getAllCategoryData($tbname) {
        $this->db->select('id,category_name,slug,is_home,status');
        $query = $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* To get all TXplorers Data */

    public function getAllTechxplorersData($tbname) {
        $this->db->select('id,name,title,status');
        $query = $this->db->order_by('id', 'DESC');
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* to get home categories data */

    public function toGetHomeCategroyData($tbname) {
        $this->db->select('*');
        $query = $this->db->where('is_home', 1);
        $query = $this->db->where('status', 'A');
        $query = $this->db->limit(3, 0);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* to get only Is_home categories data Count */

    public function toGetIsHomeCategroyData($tbname, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('is_home', 1);
        } else {
            $this->db->where('is_home', 1);
            $this->db->where('status', 'A');
        }
        $query = $this->db->get($tbname);
        $num = $query->num_rows();
        return $num;
    }

    /* to get home pages data */

    public function toGetHomePagesData($tbname, $val) {
        $currentDate = date('Y-m-d');
//        $currentDate = '2017-04-17';
        $this->db->select('id,page_title,slug');
        $query = $this->db->where('is_footer', $val);
        $query = $this->db->where('publish_start_date <=', $currentDate);
        $query = $this->db->where('publish_end_date >=', $currentDate);
        $query = $this->db->where('status', 'A');
        $query = $this->db->limit(3, 0);
        $query = $this->db->get($tbname);
        return $query->result_array();
    }

    /* to get single page data by slug */

    public function toGetPagesDataBySlug($slug) {
        $this->db->select('*');
        $query = $this->db->where('slug', $slug);
        $query = $this->db->get('pages');
        return $query->row_array();
    }

    /* to get only status active pages data Count */

    public function toGetActiveStatusData($tbname, $is_footer, $id, $startDate, $endDate) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('is_footer', $is_footer);
            $this->db->where('publish_start_date >=', $startDate);
            $this->db->where('publish_end_date <=', $endDate);
            $this->db->where('status', 'A');
        } else {
            $this->db->where('is_footer', $is_footer);
            $this->db->where('publish_start_date >=', $startDate);
            $this->db->where('publish_end_date <=', $endDate);
            $this->db->where('status', 'A');
        }
        $query = $this->db->get($tbname);
//        echo $this->db->last_query();exit;
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* To Check Category */

    public function tocheckCatData($cat, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('category_name', $cat);
        } else {
            $this->db->where('category_name', $cat);
            $this->db->where("status != 'I'");
        }
        $this->db->from('categories');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* To Check Page Title */

    public function toCheckPageTitle($pageTitle, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('page_title', $pageTitle);
        } else {
            $this->db->where('page_title', $pageTitle);
            $this->db->where("status != 'I'");
        }
        $this->db->from('pages');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }
    
    
    /* to Check Page Title for Mobile */
    public function toCheckPageTitleForMobile($pageTitleMobile, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('page_title_mobile', $pageTitleMobile);
            $this->db->where("status != 'I'");
        } else {
            $this->db->where('page_title_mobile', $pageTitleMobile);
            $this->db->where("status != 'I'");
        }
        $this->db->from('pages');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    {
            return $num;
        } else {
            return 0;
        }
    }
    /* To Check Slug */

    public function toCheckSlugData($slug, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('slug', $slug);
        } else {
            $this->db->where('slug', $slug);
            $this->db->where("status != 'I'");
        }
        $this->db->from('pages');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* to Get Preview Category Data in Post Preview */

    public function toGetPreviewCategoryDataByPostId($id) {
        $this->db->select('c.category_name');
        $this->db->from('post_categories pc');
        $this->db->where('pc.post_id', $id);
        $this->db->join('categories c', 'c.id=pc.category_id');
        $query = $this->db->get();
        return $query->row_array();
    }

    // to get list count using where
    public function toGetCount($tbl, $wrc, $wrf) {
        $this->db->select('*');
        $this->db->from($tbl);
        $this->db->where($wrc, $wrf);
        $query = $this->db->get();
        return $query->num_rows();
    }

    /* to Get Keyword Data */

    public function getKeywordData($tname, $keyword) {
        $this->db->select('*');
        $this->db->from($tname);
        $this->db->where('keyword_name', $keyword);
        $query = $this->db->get();
        $num = $query->num_rows();
        return $num;
    }

    /* To Get Newsletter Email */

    public function toGetNewslettersEmailData($email, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('user_email', $email);
        } else {
            $this->db->where('user_email', $email);
            $this->db->where("subscription_type != '0'");
        }
        $this->db->from('newsletter_subscriptions');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* to Get New Letters Search count */

//    public function toGetSearchCount($table, $fieldvalue, $field) {
//        $this->db->like($field, $fieldvalue);
//        $this->db->from($table);
//        $query = $this->db->get();
//        $num = $query->num_rows();
//        if ($num > 0) {
//            return $num;
//        } else {
//            return 0;
//        }
//    }
    public function toGetSearchCount($table, $fieldvalue, $field) {
        $this->db->select('id, post_title, publish_start_date, publish_end_date, status');
        $this->db->like($field, $fieldvalue);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result_array();
//        if ($num > 0) {
//            return $num;
//        } else {
//            return 0;
//        }
    }

    /* end here */
    
    /* to get Newsletter Search count */
    public function toGetNewsletterSearchCount($table, $fieldvalue, $field) {
        $this->db->select('*');
        $this->db->like($field, $fieldvalue);
        $this->db->from($table);
        $query = $this->db->get();
        return $query->result_array();
    }
    /* end */

    /* to Check Sidebar Title */

    public function toCheckSidebarTitle($sidebarTitle, $id) {
        if (isset($id) && !empty($id)) {
            $this->db->where('id !=', $id)->where('sidebar_title', $sidebarTitle);
        } else {
            $this->db->where('sidebar_title', $sidebarTitle);
            $this->db->where("status != 'I'");
        }
        $this->db->from('side_bars');
        $query = $this->db->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return $num;
        } else {
            return 0;
        }
    }

    /* end */
    /* to get side bar by side bar Id */

    public function toGetPageSidebarDetails($slug) {
        $this->db->select('sb.*');
        $this->db->where('p.slug', $slug);
        $this->db->from('pages p');
        $this->db->join('side_bars sb', 'sb.id = p.sidebar_id');
        $query = $this->db->get();
        $result = $query->row_array();
        return $result;
    }
    
    /* to  Seacrch Data from Posts by using Posts keywords*/
    
    public function toGetPostSearchData($keywords, $category_id = '') {
        $this->db->select('p.id as postId,p.id,p.post_title,p.feature_image,p.seo_description, p.post_slug');
        $this->db->from('post_keywords pk');
        $this->db->where_in('pk.post_keyword', $keywords);
        $this->db->join('posts p', 'p.id = pk.post_id');
        if(!empty($category_id)){
                $this->db->join('post_categories pc', 'pc.post_id = p.id');
                $this->db->where('pc.category_id',$category_id);
        }
        $this->db->group_by('p.id');
        $query = $this->db->get();
//       echo $this->db->last_query();exit;
        return $query->result_array();
    }
    
    
    /* to Get Search Data from Episodes by using Episode keywords */
    
    public function toGetEpisodeSearchData($keywords) {
        $this->db->select('e.id as episodeId, e.id,e.episode_title as post_title,e.feature_image,e.seo_description, e.episode_slug');
        $this->db->from('episode_keywords ek');
        $this->db->where_in('ek.episode_keyword', $keywords);
        $this->db->join('episodes e', 'e.id = ek.episode_id');
        $this->db->group_by('e.id');
        $query = $this->db->get();
//       echo $this->db->last_query();exit;
        return $query->result_array();
    }

}

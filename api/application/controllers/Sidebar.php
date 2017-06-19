<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
    }

    /* to get all images in add editor */

    public function index() {
        $files = scandir(FCPATH . 'uploads/mediauploads/images');
        array_splice($files, 0, 2);
        if (!empty($files)) {
            echo json_encode($files);
            exit;
        } else {
            echo "fail";
            exit;
        }
    }

    /* To get all sidebars Data */

    public function tosidebarsData() {
        $tablename = 'side_bars';
        $AllSidebarsData = $this->Global_model->getAllData($tablename);
        if (isset($AllSidebarsData) && !empty($AllSidebarsData)) {
            echo json_encode($AllSidebarsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    public function tosidebarsActiveData() {
        $tablename = 'side_bars';
        $AllSidebarsData = $this->Global_model->getAllActiveSidebarsData($tablename);
        if (isset($AllSidebarsData) && !empty($AllSidebarsData)) {
            echo json_encode($AllSidebarsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Add Sidebar Data in Database */

    public function toaddSidebar() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']['status']) && !empty($result['data']['status'])) {
            $Sidebar = array(
                "sidebar_title" => $result['data']['sidebar_title'],
                "mostpopular" => $result['data']['mostpopular'],
                "subscription_form" => $result['data']['subscription_form'],
                "sidebar_description" => $result['data']['sidebar_description'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $data_to_where = $result['data']['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('side_bars', $Sidebar, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('side_bars', $Sidebar);
            }
            if (isset($res) && !empty($res)) {
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to Delete Sidebar */

    public function toDeleteSidebarData($id) {
        if (isset($id) && !empty($id)) {
            $field_name = 'id';
            $update_data = array('sidebar_id' => 0);
            $this->Global_model->updateData('pages', $update_data, $id, 'sidebar_id');
            $this->Global_model->updateData('posts', $update_data, $id, 'sidebar_id');
            $delPageData = $this->Global_model->deleteData('side_bars', $id, $field_name);
            
            if (isset($delPageData) && !empty($delPageData)) {
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end */


    /* to Get Editable Sidebar Data */

    public function toEditSidebardata() {
//        $result = json_decode(file_get_contents("php://input"), true);
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetSidebarData = $this->Global_model->getFieldsbyIds('side_bars', '*', $data_to_where, $fieldname);
            if (isset($toGetSidebarData) && !empty($toGetSidebarData)) {
                echo json_encode($toGetSidebarData);
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end */

    /* to Check Side bar Title */

    public function toCheckSidebarTitle() {
        $result = json_decode(file_get_contents("php://input"), true);
//        echo "<pre>";        print_r($result);exit;
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $st = $this->Global_model->toCheckSidebarTitle($result['data']['sidebarTitle'], $id);
            if ($st == 0) {
                echo json_encode($st);
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end */
}

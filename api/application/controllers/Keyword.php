<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Keyword extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
    }

    /* To Add New Keyword Page */

    public function toAddNewKeyword() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']) && !empty($result['data'])) {
            $NewKeyword = array(
                "keyword_name" => $result['data']['keyword_name'],
                "keyword_slug" => $result['data']['keyword_slug'],
                "keyword_description" => $result['data']['keyword_description'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            $res = $this->Global_model->insertData('keywords', $NewKeyword);
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

    /* To Manage Keyword Data */

    public function toGetKeywordData() {
        $tablename = 'keywords';
        $AllKeywordsData = $this->Global_model->getAllData($tablename);
        if (isset($AllKeywordsData) && !empty($AllKeywordsData)) {
            echo json_encode($AllKeywordsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    /* To Manage Keyword Data */

    public function toGetSelectedKeywordData() {
        $tablename = 'keywords';
        $AllKeywordsData = $this->Global_model->getSelKeywordFields($tablename, 'keyword_name as text');
        if (isset($AllKeywordsData) && !empty($AllKeywordsData)) {
            echo json_encode($AllKeywordsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Edit Keyword Data */

    public function toEditKeywordData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditKeywordData = $this->Global_model->getFieldsbyIds('keywords', '*', $data_to_where, $fieldname);
            if (isset($toGetEditKeywordData) && !empty($toGetEditKeywordData)) {
                echo json_encode($toGetEditKeywordData);
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

    /* To Update Keyword Data */

    public function toUpdateKeywordData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']) && !empty($result['data'])) {
            $KeywordUpdateData = array(
                "keyword_name" => $result['data']['keyword_name'],
                "keyword_slug" => $result['data']['keyword_slug'],
                "keyword_description" => $result['data']['keyword_description'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('keywords', $KeywordUpdateData, $data_to_where, $field_name);
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

    /* To Delete Keyword Data */

    public function toDeleteKeywordData($id) {
        if (isset($id) && !empty($id)) {
            $data_to_where = $id;
            $field_name = 'id';
            $delKeywordsData = $this->Global_model->deleteData('keywords', $data_to_where, $field_name);
            if (isset($delKeywordsData) && !empty($delKeywordsData)) {
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

}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
        $this->load->model('Admin_model');
    }

    /* To Edit Settings Data */

    public function toEditSettingsData() {
        $toGetEditData = $this->Global_model->getSingleRecord('settings');
        if (isset($toGetEditData) && !empty($toGetEditData)) {
            echo json_encode($toGetEditData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Add New Settins Page */

    public function toUpdateSettings() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (!empty($result['data']['id']) && isset($result['data']['id'])) {
            $settings = array(
                "id" => $result['data']['id'],
                "site_tag_line" => $result['data']['site_tag_line'],
                "site_title" => $result['data']['site_title'],
                "site_url" => $result['data']['site_url'],
                "site_tags" => $result['data']['site_tags'],
                "site_description" => $result['data']['site_description'],
                "email_address" => $result['data']['email_address'],
                "date_format" => isset($result['data']['date_format']) ? $result['data']['date_format'] : '',
//                "custom_date" => isset($result['data']['custom_date']) ? $result['data']['custom_date'] : '',
                "time_format" => isset($result['data']['time_format']) ? $result['data']['time_format'] : '',
//                "custom_time" => isset($result['data']['custom_time']) ? $result['data']['custom_time'] : '',
                "updated_date" => date('Y-m-d')
            );
            $res = $this->Global_model->updateData('settings', $settings, $result['data']['id'], 'id');
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

}

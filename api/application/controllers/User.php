<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Global_model');
        $this->load->model('Admin_model');
    }

    /* To Add New User */

    public function toAddNewUser() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $NewUserData = array(
                "first_name" => $result['data']['first_name'],
                "last_name" => isset($result['data']['last_name']) ? $result['data']['last_name'] : '',
                "user_name" => $result['data']['user_name'],
                "email" => $result['data']['email'],
                "password" => md5($result['data']['password']),
                "address" => $result['data']['address'],
                "phone" => $result['data']['phone'],
                "status" => $result['data']['status']
            );
            $res = $this->Global_model->insertData('users', $NewUserData);
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

    /* To Get All Users Data */

    public function toGetAllUsersData() {
        $tablename = 'users';
        $AllUsersData = $this->Global_model->getAllData($tablename);
        if (isset($AllUsersData) && !empty($AllUsersData)) {
            echo json_encode($AllUsersData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Edit User */

    public function toEditUserData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditData = $this->Global_model->getFieldsbyIds('users', '*', $data_to_where, $fieldname);
            if (isset($toGetEditData) && !empty($toGetEditData)) {
                echo json_encode($toGetEditData);
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

    /* To Update User Data */

    public function toUpdateUser() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $userData = array(
                "first_name" => $result['data']['first_name'],
                "last_name" => isset($result['data']['last_name']) ? $result['data']['last_name'] : '',
                "user_name" => $result['data']['user_name'],
                "email" => $result['data']['email'],
                "address" => $result['data']['address'],
                "phone" => $result['data']['phone'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            if (!empty($result['data']['password'])) {
                $password = $result['data']['password'];
                $pwdarray = array("password" => $password);
                $userData = array_merge($userData, $pwdarray);
            }
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('users', $userData, $data_to_where, $field_name);
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

    /* To Delete User */

    public function toDeleteUser($id) {
        if (isset($id) && !empty($id)) {
            $data_to_where = $id;
            $field_name = 'id';
            $delSubAdminsData = $this->Global_model->deleteData('users', $data_to_where, $field_name);
            if (isset($delSubAdminsData) && !empty($delSubAdminsData)) {
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

    /* To Check User Email */

    public function toCheckUserEmail() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $emailChk = $this->Admin_model->toGetUserEmailData($result['data']['email'], $id);
            if ($emailChk) {
                echo json_encode($emailChk);
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

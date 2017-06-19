<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ManageAdmin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Global_model');
        $this->load->model('Admin_model');
    }

    /* To Add Admin Data */

    public function toAddAdminData() {
        $result = json_decode(file_get_contents("php://input"), true);
//        print_r($result);exit;
        if (isset($result['data']['id']) && !empty($result['data']['id'])) {
            $AdminData = array(
                "first_name" => $result['data']['first_name'],
                "last_name" => isset($result['data']['last_name']) ? $result['data']['last_name'] : '',
                "username" => $result['data']['username'],
                "email" => $result['data']['email'],
                "status" => $result['data']['status'],
                "admin_type" => $result['data']['admin_type'],
                "contact" => isset($result['data']['contact']) ? $result['data']['contact'] : '',
                "permissions" => json_encode($result['data']['permissions']),
                "updated_date" => date('Y-m-d')
            );
            if (!empty($result['data']['password'])) {
                $password = md5($result['data']['password']);
                $pwdarray = array("password" => $password);
                $AdminData = array_merge($AdminData, $pwdarray);
            }
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('admin_users', $AdminData, $data_to_where, $field_name);
            if (isset($res) && !empty($res)) {
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        } else {
            $AdminData = array(
                "first_name" => $result['data']['first_name'],
                "last_name" => isset($result['data']['last_name']) ? $result['data']['last_name'] : '',
                "username" => $result['data']['username'],
                "email" => $result['data']['email'],
                "password" => md5($result['data']['password']),
                "admin_type" => $result['data']['admin_type'],
                "status" => $result['data']['status'],
                "contact" => isset($result['data']['contact']) ? $result['data']['contact'] : '',
                "permissions" => json_encode($result['data']['permissions']),
                "updated_date" => date('Y-m-d')
            );
            $res = $this->Global_model->insertData('admin_users', $AdminData);
            if (isset($res) && !empty($res)) {
                echo 'success';
                exit;
            } else {
                echo 'fail';
                exit;
            }
        }
    }

    /* To Mange Sub Admins */

    public function toGetSubAdminsData() {
        $tablename = 'admin_users';
        $AllSubAdminsData = $this->Global_model->getAllData($tablename);
        if (isset($AllSubAdminsData) && !empty($AllSubAdminsData)) {
            echo json_encode($AllSubAdminsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Edit Sub Admins */

    public function toEditSubAdmin() {
//        $result = json_decode(file_get_contents("php://input"), true);
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetSubAdminData = $this->Global_model->getFieldsbyIds('admin_users', '*', $data_to_where, $fieldname);
            if (isset($toGetSubAdminData) && !empty($toGetSubAdminData)) {
                $toGetSubAdminData['permissions'] = json_decode($toGetSubAdminData['permissions'], true);
                echo json_encode($toGetSubAdminData);
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

    /* To Update Sub Admins */

    public function toUpdateSubAdmin() {
        $result = $this->input->post();
        if (isset($result) && !empty($result)) {
            $SubAdminData = array(
                "username" => $result['data']['username'],
                "first_name" => $result['data']['first_name'],
                "last_name" => $result['data']['last_name'],
                "contact" => $result['data']['contact'],
                'updated_date' => date("Y-m-d")
            );
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {

                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                $uploadFile = $this->do_upload('profile_pics', '*', 2048, $image_width_size, $image_height_size);
                $unlink_url = FCPATH . 'uploads/profile_pics/' . $uploadFile;
                $url = base_url() . 'uploads/profile_pics/' . $uploadFile;
                $image = array('profile_pic' => $url, 'unlink_url' => $unlink_url);
                $SubAdminData = array_merge($SubAdminData, $image);

                /* to unlink previous image */
                $toGetEditMediaData = $this->Global_model->getFieldsbyIds('admin_users', '*', $data_to_where, $field_name);
                $unlink_doc = $toGetEditMediaData['unlink_url'];
                if (file_exists($unlink_doc)) {
                    unlink($unlink_doc);
                }
                /* end here */
            }
            $res = $this->Global_model->updateData('admin_users', $SubAdminData, $data_to_where, $field_name);
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

    /* function is used to upload media */

    public function do_upload($uploadedPath, $allowTypes, $maxSize, $imgWidth = '', $imgheight = '') {
        $this->load->library('upload'); // Loading upload library to upload an image
        $this->load->library('image_lib'); // Loading image library to resize an image
        $imgName = $_FILES['file']['name'];

        $splittedArray = @explode(".", $imgName);
        if (!empty($splittedArray)) {

            $uploadedFile = rand() . '_' . time() . '.' . end($splittedArray);
        }
        $arr_config = array('allowed_types' => $allowTypes,
            'upload_path' => 'uploads/' . $uploadedPath . '/',
            'max_size' => $maxSize,
            'file_name' => $uploadedFile,
            'remove_spaces' => true,
            'overwrite' => true,
        );
        $this->upload->initialize($arr_config);

        if (!$this->upload->do_upload('file')) {
            return $this->upload->display_errors();
        } else {

            $resizeconfig = array();
            $resizeconfig['image_library'] = 'GD2';
            $resizeconfig['source_image'] = FCPATH . '/uploads/' . $uploadedPath . '/' . $uploadedFile;
            $resizeconfig['new_image'] = FCPATH . '/uploads/' . $uploadedPath . '/thumbnails/' . $uploadedFile;
            $resizeconfig['maintain_ratio'] = TRUE;

            if ($imgWidth < 100 && $imgheight < 100) {
                $resizeconfig['width'] = $imgWidth;
                $resizeconfig['height'] = $imgheight;
            } else {
                if ($imgWidth > $imgheight) {
                    $resizeconfig['width'] = 100;
                } elseif ($imgWidth < $imgheight) {
                    $resizeconfig['height'] = 100;
                } elseif ($imgWidth == $imgheight) {
                    $resizeconfig['width'] = 100;
                }
            }
            $resizeconfig['x_axis'] = '0';
            $resizeconfig['y_axis'] = '0';
            $resizeconfig['quality'] = '100%';
            $this->image_lib->initialize($resizeconfig);
            $this->load->library('image_lib', $resizeconfig);
            $this->image_lib->resize();
            $this->image_lib->clear();
            if (!$this->image_lib->resize()) {
                echo $this->image_lib->display_errors();
                exit;
            }
            return $uploadedFile;
        }
    }

    /* To Delete Sub Admins */

    public function toDeleteSubAdmins($id) {
        if (isset($id) && !empty($id)) {
            $data_to_where = $id;
            $field_name = 'id';
            $delSubAdminsData = $this->Global_model->deleteData('admin_users', $data_to_where, $field_name);
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

    /* To Check Admin Email */

    public function toCheckEmail() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $emailChk = $this->Admin_model->toGetEmailData($result['data']['email'], $id);
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

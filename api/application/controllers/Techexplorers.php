<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Techexplorers extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Global_model','Techexplorers_model'));
        $this->load->model('Admin_model');
    }

    /* To get All TechexplorerData */

    public function togetAllTechexplorersData() {
        $tablename = 'tech_explorers';
        $AllTechexplorerData = $this->Global_model->getAllTechxplorersData($tablename);
        if (isset($AllTechexplorerData) && !empty($AllTechexplorerData)) {
            echo json_encode($AllTechexplorerData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    public function togetAllTechexplorersDataByLimit() {
        $result = $this->input->get();
        $AllTechexplorerData = $this->Techexplorers_model->togetAllTechexplorersDataByLimit($result['lmt'], $result['slug']);
        if (isset($AllTechexplorerData) && !empty($AllTechexplorerData)) {
            echo json_encode($AllTechexplorerData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    public function toGetAllXplorersDataByLimit() {
        $result = $this->input->get();
        $AllTechexplorerData = $this->Techexplorers_model->toGetAllXplorersDataByLimitById($result['lmt'], $result['id']);
        if (isset($AllTechexplorerData) && !empty($AllTechexplorerData)) {
            echo json_encode($AllTechexplorerData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    /* To get All TechexplorerData Based on the Status*/

    public function togetAllTechexplorersDataStatus() {
        $tablename = 'tech_explorers';
        $AllTechexplorerData = $this->Global_model->getAllDataStatus($tablename);
        if (isset($AllTechexplorerData) && !empty($AllTechexplorerData)) {
            echo json_encode($AllTechexplorerData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    
    /* to Get Techxplorer Data in Ascending */
    public function togetAllTechexplorersDataStatusByAsc() {
        $tablename = 'tech_explorers';
        $AllTechexplorerData = $this->Global_model->getAllDataStatusByAsc($tablename);
        if (isset($AllTechexplorerData) && !empty($AllTechexplorerData)) {
            echo json_encode($AllTechexplorerData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    /* To Check Techexplorer Email */

    public function tocheckTechexplorerEmail() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $emailChk = $this->Admin_model->toGetTechexplorerEmailData($result['data']['email'], $id);
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

    /* To Add New Techexplorer */

    public function toaddNewTechexplorerData() {
        $result = $this->input->post();
        if (isset($result) && !empty($result)) {
            $TechexplorerData = array(
                "name" => $result['data']['name'],
                "title" => $result['data']['title'],
                "profile_pic" => $result['data']['profile_pic'],
                "status" => $result['data']['status'],
                "category_id" => $result['data']['category_id'],
                'description' => $result['data']['description'],
            );
//            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
//
//                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
//                $image_width_size = $imageDimensions[0];
//                $image_height_size = $imageDimensions[1];
//                $uploadFile = $this->do_upload('techexplorer_images', '*', 2048, $image_width_size, $image_height_size);
//                $unlink_url = FCPATH . 'uploads/techexplorer_images/' . $uploadFile;
//                $url = base_url() . 'uploads/techexplorer_images/' . $uploadFile;
//                $image = array('profile_pic' => $url, 'unlink_pic' => $unlink_url);
//                $TechexplorerData = array_merge($TechexplorerData, $image);
//            }
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
//                if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
//                    $data_to_where = $result['data']['id'];
//                    $fieldname = 'id';
//                    $toGetEditMediaData = $this->Global_model->getFieldsbyIds('tech_explorers', '*', $data_to_where, $fieldname);
//                    $unlink_doc = $toGetEditMediaData['profile_pic'];
//                    if (file_exists($unlink_doc)) {
//                        unlink($unlink_doc);
//                    }
//                }
                $data_to_where = $result['data']['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('tech_explorers', $TechexplorerData, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('tech_explorers', $TechexplorerData);
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

    /* function is used to upload image */

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

    public function toeditTechexplorerData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditData = $this->Global_model->getFieldsbyIds('tech_explorers', '*', $data_to_where, $fieldname);
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

    /* To Delete Techexplorer */

    public function todeleteTechexplorerData($id) {
        if (isset($id) && !empty($id)) {
            $delData = $this->Global_model->deleteData('tech_explorers', $id, 'id');
            $shows = $this->Global_model->getFieldsbyIdsMultiplay('shows', 'id as shows_id', 'xplorer_id', $id);
            foreach ($shows as $show):
                $this->Global_model->deleteData('shows_seasons', $show['shows_id'], 'shows_id');
                $this->Global_model->deleteData('shows_watchlinks', $show['shows_id'], 'shows_id');
                $episodes = $this->Global_model->getFieldsbyIdsMultiplay('episodes', 'id as episode_id', 'shows_id', $show['shows_id']);
                foreach ($episodes as $episode):
                    $this->Global_model->deleteData('episode_links', $episode['episode_id'], 'episode_id');
                endforeach;
                $this->Global_model->deleteData('episodes', $show['shows_id'], 'shows_id');
            endforeach;
            $this->Global_model->deleteData('shows', $id, 'xplorer_id');
            if (isset($delData) && !empty($delData)) {
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
    }

    /* To Add New Media Page */

    public function toAddNewMedia() {
        $result = $this->input->post();
        if (isset($result['status']) && !empty($result['status'])) {
            $NewMedia = array(
                "media_title" => $result['media_title'],
                "status" => $result['status'],
                "updated_date" => date('Y-m-d'),
                "media_type" => $result['media_type']
            );
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                if ($result['media_type'] === 'I') {
                    $uploadFile = $this->do_upload('mediauploads/images/', '*', 2048, $image_width_size, $image_height_size);
                    $url = $uploadFile;
                } else {
                    $uploadFile = $this->do_upload_files('mediauploads', '*', 2048);
                    $url = $uploadFile;
                }
                $image = array('media_url' => $url);
                $NewMedia = array_merge($NewMedia, $image);
            }
            if (isset($result['id']) && !empty($result['id'])) {
                $res = $this->Global_model->updateData('media', $NewMedia, $result['id'], 'id');
            } else {
                $res = $this->Global_model->insertData('media', $NewMedia);
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
            if ($imgWidth < 630 && $imgheight < 450) {
                $smallImage = $this->resizeImage('mediauploads/images/thumbnails_300_250', $uploadedFile, 300, 250);
                $bigImage = $this->resizeImage('mediauploads/images/thumbnails_630_465', $uploadedFile, 300, 250);
            } else {
                $smallImage = $this->resizeImage('mediauploads/images/thumbnails_300_250', $uploadedFile, 300, 250);
                $bigImage = $this->resizeImage('mediauploads/images/thumbnails_630_465', $uploadedFile, 630, 450);
            }
            return $smallImage;
        }
    }

    public function resizeImage($uploadedPath, $uploadedFile, $imgWidth, $imgheight) {
        $resizeconfig = array();
        $resizeconfig['image_library'] = 'GD2';
        $resizeconfig['source_image'] = FCPATH . '/uploads/mediauploads/images/' . $uploadedFile;
        $resizeconfig['new_image'] = FCPATH . '/uploads/' . $uploadedPath . '/' . $uploadedFile;
        $resizeconfig['maintain_ratio'] = TRUE;
        $resizeconfig['height'] = $imgheight;
        $resizeconfig['width'] = $imgWidth;
        $resizeconfig['maintain_ratio'] = FALSE;
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

    /* to upload files into folder */

    public function do_upload_files($uploadedPath, $allowTypes, $maxSize) {
        $imgName = $_FILES['file']['name'];
        $splittedArray = explode(".", $imgName);
        if (!empty($splittedArray)) {
            $uploadedFile = rand() . '_' . time() . '.' . end($splittedArray);
        }
        move_uploaded_file($_FILES['file']['tmp_name'], FCPATH . 'uploads/' . $uploadedPath . '/' . $uploadedFile);

        return $uploadedFile;
    }

    /* To Manage Media Data */

    public function toGetMediaData() {
        $tablename = 'media';
        $AllMediasData = $this->Global_model->getAllData($tablename);
        if (isset($AllMediasData) && !empty($AllMediasData)) {
            echo json_encode($AllMediasData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Edit Media Data */

    public function toEditMediaData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditMediaData = $this->Global_model->getFieldsbyIds('media', '*', $data_to_where, $fieldname);
            if (isset($toGetEditMediaData) && !empty($toGetEditMediaData)) {
                echo json_encode($toGetEditMediaData);
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

    /* To Update Media Data */

    public function toUpdateMediaData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']) && !empty($result['data'])) {
            $MediaUpdateData = array(
                "media_url" => $result['data']['media_url'],
                "media_type" => $result['data']['media_type'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('media', $MediaUpdateData, $data_to_where, $field_name);
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

    /* To Delete Media Data */

    public function toDeleteMediaData($id) {
        if (isset($id) && !empty($id)) {
            $field_name = 'id';
            $toGetEditMediaData = $this->Global_model->getFieldsbyIds('media', '*', $id, $field_name);
//            $unlink_doc[0] = FCPATH . 'uploads/mediauploads/images/' . $toGetEditMediaData['media_url'];
//            $unlink_doc[1] = FCPATH . 'uploads/mediauploads/images/thumbnails_300_250/' . $toGetEditMediaData['media_url'];
//            $unlink_doc[2] = FCPATH . 'uploads/mediauploads/images/thumbnails_630_465/' . $toGetEditMediaData['media_url'];
            $delMediasData = $this->Global_model->deleteData('media', $id, $field_name);
            if (isset($delMediasData) && !empty($delMediasData)) {
//                foreach ($unlink_doc as $ulink):
//                    if (file_exists($ulink)) {
//                        unlink($ulink);
//                    }
//                endforeach;
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

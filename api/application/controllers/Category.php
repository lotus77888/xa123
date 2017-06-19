<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Global_model');
        $this->load->model('Admin_model');
    }

    /* To Get Home Category Count */

    public function togetHomeCategoryCount() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $IsHomecategoryCount = $this->Global_model->toGetIsHomeCategroyData('categories', $id);
            if ($IsHomecategoryCount >= 3) {
                echo "success";
                exit;
            } else {
                echo "fail";
                exit;
            }
        } else {
            echo "fail";
            exit;
        }
    }

    /* To Add New Category Page */

    public function toAddNewCategory() {
//        $result = json_decode(file_get_contents("php://input"), true);
        $result = $this->input->post();
        if (isset($result['data']) && !empty($result['data'])) {
            $NewCategory = array(
                "category_name" => $result['data']['category_name'],
                "featured_post_id" => isset($result['data']['featured_post_id']) ? $result['data']['featured_post_id'] : '0',
                "slug" => $result['data']['slug'],
                "parent_category_id" => (isset($result['data']['parent_category_id']) && !empty($result['data']['parent_category_id'])) ?: '0',
                "category_description" => $result['data']['category_description'],
                "form_subscription" => (isset($result['data']['form_subscription']) == 'Y') ? $result['data']['form_subscription'] : 'N',
                "is_home" => $result['data']['is_home'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                $uploadFile = $this->do_upload('categoryUploads', '*', 2048, $image_width_size, $image_height_size);
                $cat_image = $uploadFile;
                $image = array('category_image' => $cat_image,);
                $NewCategory = array_merge($NewCategory, $image);
            }

            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
//                echo "<pre>";
//                print_r($result);
//                print_r($imageDimensions);exit;
                $data_to_where = $result['data']['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('categories', $NewCategory, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('categories', $NewCategory);
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

    /* To Manage Category Data */

    public function toGetCategoryData() {
        $tablename = 'categories';
        $AllCategoryData = $this->Global_model->getAllCategoryData($tablename);
        if (isset($AllCategoryData) && !empty($AllCategoryData)) {
            echo json_encode($AllCategoryData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Get Home Category Data */

    public function toGetHomeCategroyData() {
        $tablename = 'categories';
        $AllCategoryData = $this->Global_model->toGetHomeCategroyData($tablename);
        if (isset($AllCategoryData) && !empty($AllCategoryData)) {
            echo json_encode($AllCategoryData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To get Selected fields of  Category Data */

    public function toGetSelectCategoryData() {
        $tablename = 'categories';
        $selArr = array('id', 'category_name');
        $AllCategoryData = $this->Global_model->getCategoryData($tablename, $selArr);
        if (isset($AllCategoryData) && !empty($AllCategoryData)) {
            echo json_encode($AllCategoryData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Edit Category Data */

    public function toEditCategoryData() {

        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditCategoryData = $this->Global_model->getFieldsbyIds('categories', '*', $data_to_where, $fieldname);
            if (isset($toGetEditCategoryData) && !empty($toGetEditCategoryData)) {
                echo json_encode($toGetEditCategoryData);
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

    /* to get category data by using slug */

    public function toEditCategoryDataBySlug() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['slug'];
            $fieldname = 'slug';
//            $toGetEditCategoryData = $this->Global_model->getFieldsbyIds('categories', '*', $data_to_where, $fieldname);
            $toGetEditCategoryData = $this->Global_model->getPostDataByCategory($data_to_where, $fieldname);
            if (isset($toGetEditCategoryData) && !empty($toGetEditCategoryData)) {
                echo json_encode($toGetEditCategoryData);
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

    /* end here */
    
    
    /* to get Category data by using Id */

    public function toEditCategoryDataById() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
//            $toGetEditCategoryData = $this->Global_model->getFieldsbyIds('categories', '*', $data_to_where, $fieldname);
            $toGetEditCategoryData = $this->Global_model->getPostDataByCategoryId($data_to_where, $fieldname);
            if (isset($toGetEditCategoryData) && !empty($toGetEditCategoryData)) {
                echo json_encode($toGetEditCategoryData);
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

    /* end here */


    /* To Update Category Data */

    public function toUpdateCategoryData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
//            $IsHomecategoryCount = $this->Global_model->toGetIsHomeCategroyData('categories',$result['data']['id']);
//            if ($IsHomecategoryCount >= 3) {
//                $status = 'I';
//            } else {
//                $status = $result['data']['status'];
//            }
            $CategoryUpdateData = array(
                "category_name" => $result['data']['category_name'],
                "slug" => $result['data']['slug'],
                "parent_category_id" => $result['data']['parent_category_id'],
                "category_description" => $result['data']['category_description'],
                "form_subscription" => (isset($result['data']['form_subscription']) == 'Y') ? $result['data']['form_subscription'] : 'N',
                "status" => $result['data']['status'],
                "is_home" => $result['data']['is_home'],
                "updated_date" => date('Y-m-d')
            );
            $data_to_where = $result['data']['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('categories', $CategoryUpdateData, $data_to_where, $field_name);
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

    /* To Delete Category Data */

    public function toDeleteCategoryData($id) {
        if (isset($id) && !empty($id)) {
            $postdata = $this->Global_model->getFieldsbyIdsMultiplay('post_categories', 'post_id', 'category_id', $id);
            if (!empty($postdata) && isset($postdata)) {
                foreach ($postdata as $postId):
                    $this->Global_model->deleteData('posts', $postId['post_id'], 'id');
                endforeach;
                $this->Global_model->deleteData('post_categories', $id, 'category_id');
            }
            $update_data = array('parent_category_id' => 0);
            $this->Global_model->updateData('categories', $update_data, $id, 'parent_category_id');
            $delCategoryData = $this->Global_model->deleteData('categories', $id, 'id');
            if (isset($delCategoryData) && !empty($delCategoryData)) {
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

    /* To Check Category */

    public function tocheckCategory() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $catChk = $this->Global_model->tocheckCatData($result['data']['categoryName'], $id);
            if ($catChk) {
                echo json_encode($catChk);
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

    public function toCategoryDataByPostId() {
        $result = $this->input->get();
        $toGetPreviewCategoryDataByPostId = $this->Global_model->toGetPreviewCategoryDataByPostId($result['id']);
        if (isset($toGetPreviewCategoryDataByPostId) && !empty($toGetPreviewCategoryDataByPostId)) {
            echo json_encode($toGetPreviewCategoryDataByPostId);
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

}

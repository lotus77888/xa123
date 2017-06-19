<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
    }

    /* to get all images in add post editor */

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

    /* To get Post Data */

    public function toGetPageData() {
        $tablename = 'pages';
        $AllPagesData = $this->Global_model->getAllData($tablename);
        if (isset($AllPagesData) && !empty($AllPagesData)) {
            echo json_encode($AllPagesData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to get home pages data */

    public function toGetHomePagesData() {
        $tablename = 'pages';
        $result = $this->input->get();
        $AllPagesData = $this->Global_model->toGetHomePagesData($tablename, $result['val']);
        if (isset($AllPagesData) && !empty($AllPagesData)) {
            echo json_encode($AllPagesData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* to get home pages data */

    public function toGetPagesDataBySlug() {
        $result = json_decode(file_get_contents("php://input"), true);
        $pagesData = $this->Global_model->toGetPagesDataBySlug($result['slug']);
        if (isset($pagesData) && !empty($pagesData)) {
            echo json_encode($pagesData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* To Delete Page Data */

    public function toDeletePageData($id) {
        if (isset($id) && !empty($id)) {
            $field_name = 'id';
            $delPageData = $this->Global_model->deleteData('pages', $id, $field_name);
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

    /* To Edit Page Data */

    public function toEditPageData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetEditData = $this->Global_model->getFieldsbyIds('pages', '*', $data_to_where, $fieldname);
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

    /* To Get Check Option Data */

    public function toGetSelectOptionData() {
        $result = json_decode(file_get_contents("php://input"), true);
//        echo "<pre>";print_r($result);exit;
        if (!empty($result) && isset($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            if ($result['data']['is_footer'] === '0') {
                // for header menu checking
                $toGetMenuCount = $this->Global_model->toGetActiveStatusData('pages', $result['data']['is_footer'], $id, $result['data']['publish_start_date'], $result['data']['publish_end_date']);
                if ($toGetMenuCount >= 1) {
                    echo "successHeader";
                } else {
                    echo "fail";
                }
            } else if ($result['data']['is_footer'] === '1') {
                // for footer menu checking
                $toGetMenuCount = $this->Global_model->toGetActiveStatusData('pages', $result['data']['is_footer'], $id, $result['data']['publish_start_date'], $result['data']['publish_end_date']);
//                print_r($toGetMenuCount);exit;
                if ($toGetMenuCount >= 3) {
                    echo "successFooter";
                } else {
                    echo "fail";
                }
            } else {
                echo "fail";
            }
        } else {
            echo "fail";
            exit;
        }
    }

    /* To Add New Post Page */

    public function toAddNewPage() {
        $result = $this->input->post();
        if (isset($result['data']['status']) && !empty($result['data']['status'])) {
            $NewPage = array(
                "page_title" => $result['data']['page_title'],
                "page_title_mobile" => $result['data']['page_title_mobile'],
                "publish_start_date" => $result['data']['publish_start_date'],
                "publish_end_date" => $result['data']['publish_end_date'],
                "slug" => $result['data']['slug'],
                "page_description" => $result['data']['page_description'],
                "status" => $result['data']['status'],
                "sidebar" => $result['data']['sidebar'],
                "sidebar_id" => isset($result['data']['sidebar_id']) ? $result['data']['sidebar_id'] : '',
                "is_footer" => $result['data']['is_footer'],
//                "video_option" => isset($result['data']['video_option']) ? $result['data']['video_option'] : 0,
//                "video_info" => isset($result['data']['video_info']) ? $result['data']['video_info'] : '',
                "banner" => (isset($result['data']['banner']) && $result['data']['banner'] == "true" ) ? 1 : 0,
                "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
                "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
                "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
                "created_date" => date('Y-m-d')
            );
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                $uploadFile = $this->do_upload('pageuploads_banner', '*', 2048, $image_width_size, $image_height_size);
                $unlink_url = FCPATH . 'uploads/pageuploads_banner/' . $uploadFile;
                $url = base_url() . 'uploads/pageuploads_banner/' . $uploadFile;
                $image = array('banner_image' => $url, 'unlink_url' => $unlink_url);
                $NewPage = array_merge($NewPage, $image);
            }
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                    $data_to_where = $result['data']['id'];
                    $fieldname = 'id';
                    $toGetEditBannerData = $this->Global_model->getFieldsbyIds('pages', '*', $data_to_where, $fieldname);
                    $unlink_doc = $toGetEditBannerData['unlink_url'];
                    if (file_exists($unlink_doc)) {
                        unlink($unlink_doc);
                    }
                }
                $data_to_where = $result['data']['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('pages', $NewPage, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('pages', $NewPage);
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

    /* function is used to upload banner */

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

    /* update Page */

//    public function toUpdatePage() {
//        $result = json_decode(file_get_contents("php://input"), true);
//        if (!empty($result['data']['video_option']) && isset($result['data']['video_option'])) {
//            if (!empty($result['data']['video_info']) && isset($result['data']['video_info'])) {
//                $updatePage = array(
//                    "id" => $result['data']['id'],
//                    "page_title" => $result['data']['page_title'],
//                    "page_description" => $result['data']['page_description'],
////                    "video_option" => $result['data']['video_option'],true
//                    "video_option" => 'true',
//                    "video_info" => $result['data']['video_info'],
//                    "status" => $result['data']['status'],
//                    "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
//                    "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
//                    "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
//                    "updated_date" => date('Y-m-d')
//                );
//            }
//        }
//        $updatePage = array(
//            "id" => $result['data']['id'],
//            "page_title" => $result['data']['page_title'],
//            "page_description" => $result['data']['page_description'],
//            "status" => $result['data']['status'],
//            "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
//            "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
//            "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
//            "updated_date" => date('Y-m-d')
//        );
//        $data_to_where = $result['data']['id'];
//        $field_name = 'id';
//        $res = $this->Global_model->updateData('pages', $updatePage, $data_to_where, $field_name);
//        if (isset($res) && !empty($res)) {
//            echo 'success';
//            exit;
//        } else {
//            echo 'fail';
//            exit;
//        }
//    }

    /* To Check Page Title */

    public function toCheckPageTitle() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Global_model->toCheckPageTitle($result['data']['pageTitle'], $id);
            if ($slugChk) {
                echo json_encode($slugChk);
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
    
    /* To Check Page  Title  for Mobile */

    public function toCheckPageTitleForMobile() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $pageTilteMobile = $this->Global_model->toCheckPageTitleForMobile($result['data']['pageTitleMobile'], $id);
            if ($pageTilteMobile) {
                echo json_encode($pageTilteMobile);
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
    /* To Check Slug */

    public function tocheckPageSlug() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Global_model->toCheckSlugData($result['data']['pageSlug'], $id);
            if ($slugChk) {
                echo json_encode($slugChk);
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
    
    /* to get sidebar data by post id */

    public function toGetPageSidebarDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $sidebarData = $this->Global_model->toGetPageSidebarDetails($slug);
            if (!empty($sidebarData) && isset($sidebarData)) {
                echo json_encode($sidebarData);
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

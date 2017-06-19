<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

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

    /* To Add New Media Page */

    public function toAddNewPost() {
        $result = $this->input->post();
        $tagsArray = array();
        if (!empty($result['data']['tags']) && isset($result['data']['tags'])) {
            foreach ($result['data']['tags'] as $tags):
                foreach ($tags as $k => $tag):
                    $tagsArray[] = $tag;
                endforeach;
            endforeach;
            $tagsByc = implode(',', $tagsArray);
        }

        if (isset($result['data']['status']) && !empty($result['data']['status'])) {
            $NewPost = array(
                "post_title" => $result['data']['post_title'],
                "post_description" => $result['data']['post_description'],
                "publish_start_date" => $result['data']['publish_start_date'],
                "publish_end_date" => $result['data']['publish_end_date'],
                "tags" => isset($tagsByc) ? $tagsByc : '',
                "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
                "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
                "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
                "post_type" => 'P', //$result['data']['post_type'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            //print_r($NewPost); exit;
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {


                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                $uploadFile = $this->do_upload('postuploads', '*', 2048, $image_width_size, $image_height_size);
                $unlink_url = FCPATH . 'uploads/postuploads/' . $uploadFile;
                $url = base_url() . 'uploads/postuploads/' . $uploadFile;
                $image = array('feature_image' => $url, 'unlink_url' => $unlink_url);
                $NewPost = array_merge($NewPost, $image);
            }
            if (isset($result['id']) && !empty($result['id'])) {

                if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
                    $data_to_where = $result['id'];
                    $fieldname = 'id';
                    $toGetEditMediaData = $this->Global_model->getFieldsbyIds('posts', '*', $data_to_where, $fieldname);
                    $unlink_doc = $toGetEditMediaData['unlink_url'];
                    if (file_exists($unlink_doc)) {
                        unlink($unlink_doc);
                    }
                }

                $data_to_where = $result['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('posts', $NewPost, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('posts', $NewPost);
                print_r($res);
                exit;
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
            /* Water Mark */
//                $config['source_image'] = FCPATH . '/uploads/'.$uploadedPath.'/' . $uploadedFile;
//                $config['wm_text'] = 'evaartalu';
//                $config['wm_type'] = 'text';
//                $config['wm_font_path'] = './fonts/ostrich-black-webfont.ttf';
//                $config['wm_font_size'] = '16';
//                $config['wm_font_color'] = 'ffffff';
//                $config['wm_vrt_alignment'] = 'middle';
//                $config['wm_hor_alignment'] = 'center';
//                $config['wm_padding'] = '20';
//                $this->image_lib->initialize($config);
//
//                if(!$this->image_lib->watermark())
//                {
//                echo $this->image_lib->display_errors(); 
//                } 

            return $uploadedFile;
        }
    }

    /* To Manage Media Data */

    public function toGetPostData() {
        $tablename = 'posts';
        $AllPostsData = $this->Global_model->getAllData($tablename);
         if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
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
            $unlink_doc = $toGetEditMediaData['unlink_url'];
            $delMediasData = $this->Global_model->deleteData('media', $id, $field_name);
            if (isset($delMediasData) && !empty($delMediasData)) {
                if (file_exists($unlink_doc)) {
                    unlink($unlink_doc);
                }
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

    // to fetch posts data based on publish date
    public function toGetPostPublishData() {
        $lmt = $this->input->get('lmt');
        $ofSet = $this->input->get('ofSet');
        $tablename = 'posts';
        $AllPostsData = $this->Global_model->toGetPostPublishData($tablename, $lmt, $ofSet);
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    // to get post based on id
    public function getPostDetails(){
        $id = $this->input->get('id');
        if($id){
            $postsData = $this->Global_model->postDetailView($id);
            if (isset($postsData) && !empty($postsData)) {
                $postCategoryData = $this->Global_model->getPostBasedOnCategory($postsData['category_id'], $postsData['post_id']);
                if(isset($postCategoryData) && !empty($postCategoryData)){
                    $nextPostData = $this->Global_model->postDetailView($postCategoryData['post_id']);
                    echo "<pre>"; print_r($nextPostData); exit;
                }
                echo "<pre>"; print_r($postCategoryData); exit;
                echo json_encode($postsData);
                exit;
            } else {
                echo 'fail';
                exit;
            }
        }else{
            echo 'fail';
                exit;
        }
        
    }

}

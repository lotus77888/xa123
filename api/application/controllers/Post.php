<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Global_model', 'Post_model'));
    }

    /* to get all images in add post editor */

    public function index() {
//        $files = scandir(FCPATH . 'uploads/mediauploads/images/thumbnails_300_250');
        $files = array();
        $dir = FCPATH . 'uploads/mediauploads/images/thumbnails_300_250';
        $ignored = array('.', '..');
        foreach (scandir($dir) as $file) {
            if (in_array($file, $ignored))
                continue;
            $files[$file] = filemtime($dir . '/' . $file);
        }
        arsort($files);
        $latestfiles = array_keys($files);
        if (!empty($latestfiles)) {
            echo json_encode($latestfiles);
            exit;
        } else {
            echo "fail";
            exit;
        }
    }

    /* To get Post Data */

    public function toGetPostData() {
        $AllPostData = $this->Global_model->getAllData('posts');
        if (isset($AllPostData) && !empty($AllPostData)) {
            echo json_encode($AllPostData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    public function toGetSelectedPostData() {
        $AllPostData = $this->Global_model->toGetSelectedPostData('posts');
        if (isset($AllPostData) && !empty($AllPostData)) {
            echo json_encode($AllPostData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to get post data by category */

    public function toGetPostDataByCategory() {
        $result = $this->input->get();
        $AllPostData = $this->Post_model->toGetPostDataByCategory($result['id']);
        if (isset($AllPostData) && !empty($AllPostData)) {
            echo json_encode($AllPostData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* To Delete Post Data */

    public function toDeletePostData($id) {
        if (isset($id) && !empty($id)) {
//            $toGetEditPostData = $this->Global_model->getFieldsbyIds('posts', '*', $id, $field_name);
//            $unlink_doc = $toGetEditPostData['unlink_url'];
            $delPostData = $this->Global_model->deleteData('posts', $id, 'id');
            $delPostData = $this->Global_model->deleteData('post_categories', $id, 'post_id');
            $delPostData = $this->Global_model->deleteData('post_keywords', $id, 'post_id');
            if (isset($delPostData) && !empty($delPostData)) {
//                if (file_exists($unlink_doc)) {
//                    unlink($unlink_doc);
//                }
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

    /* to delete multiple post data */

    public function toDeleteMultiplePostData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['ids']) && !empty($result['ids'])) {
            foreach ($result['ids'] as $id):
                $this->Global_model->deleteData('posts', $id, 'id');
                $this->Global_model->deleteData('post_categories', $id, 'post_id');
            endforeach;
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* To Edit Post Data */

    public function toEditPostData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $id = $result['id'];
            $toGetEditMediaData = $this->Post_model->toEditPostData($id);
            $post_categories = $this->Post_model->toGetPostCategoriesById($id);
            if (!empty($post_categories) && isset($post_categories)) {
                $toGetEditMediaData['category_id'] = $post_categories;
            } else {
                $toGetEditMediaData['category_id'] = [];
            }
            if (isset($toGetEditMediaData) && !empty($toGetEditMediaData)) {
                $toGetEditMediaData['tags'] = json_decode($toGetEditMediaData['tags'], TRUE);
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

    /* To Check Post Title */

    public function toCheckPostTitle() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Post_model->toCheckPostTitle($result['data']['postTitle'], $id);
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

    /* To Check Post Title for Mobile*/

    public function toCheckPostTitleForMobile() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $postTilteMobile = $this->Post_model->toCheckPostTitleForMobile($result['data']['postTitleMobile'], $id);
            if ($postTilteMobile) {
                echo json_encode($postTilteMobile);
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
    
    /* To Check Post Slug */

    public function tocheckPostSlug() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Post_model->tocheckPostSlug($result['data']['postSlug'], $id);
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

    /* To Add New Post Page */

    public function toAddNewPost() {
        $result = $this->input->post();
//        echo "<pre>";print_r($result);exit;
        if (!empty($result['data']['tags']) && isset($result['data']['tags']) && $result['data']['tags'] !== 'null') {
            $tagsByc = json_encode($result['data']['tags']);
        }else {
            $tagsByc = '';
        }
        if (isset($result['data']['status']) && !empty($result['data']['status'])) {
            $NewPost = array(
                "post_title" => $result['data']['post_title'],
                "post_title_mobile" => $result['data']['post_title_mobile'],
                "post_slug" => $result['data']['post_slug'],
                "video_tag" => $result['data']['video_tag'],
                "post_description" => $result['data']['post_description'],
                "sidebar_id" => isset($result['data']['sidebar_id']) ? $result['data']['sidebar_id'] : '',
                "publish_start_date" => $result['data']['publish_start_date'],
                "publish_end_date" => $result['data']['publish_end_date'],
                "tags" => isset($tagsByc) ? $tagsByc : '',
                "feature_image" => $result['data']['feature_image'],
                'banner_option' => $result['data']['banner_option'],
                "social_sharing_link" => isset($result['data']['social_sharing_link']) ? $result['data']['social_sharing_link'] : '',
                "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
                "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
                "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
                "post_type" => 'P', //$result['data']['post_type'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {

                /* Update Previous Banner Feature Image */
                $toGetBannerPostData = $this->Global_model->getFieldsbyIds('posts', '*', 'Y', 'banner_option');
                if (!empty($toGetBannerPostData['id']) && isset($toGetBannerPostData['id'])) {
                    $bannerData = array('banner_option' => 'N', 'banner_featureImage' => '');
                    $res = $this->Global_model->updateData('posts', $bannerData, $toGetBannerPostData['id'], 'id');
                    $unlink_doc = FCPATH . 'uploads/postuploads/' . $toGetBannerPostData['banner_featureImage'];
                    if (file_exists($unlink_doc)) {
                        unlink($unlink_doc);
                    }
                }
                /* end */

                $imageDimensions = getimagesize($_FILES["file"]["tmp_name"]);
                $image_width_size = $imageDimensions[0];
                $image_height_size = $imageDimensions[1];
                $uploadFile = $this->do_upload('postuploads', '*', 5120, $image_width_size, $image_height_size, $_FILES["file"]["name"], 'file');
                $banner = $uploadFile;
                $image = array('banner_featureImage' => $banner);
                $NewPost = array_merge($NewPost, $image);
            }
            if (isset($_FILES['thumbnail_image']['name']) && !empty($_FILES['thumbnail_image']['name'])) {
                /* thumbnail image  */
                $timageDimensions = getimagesize($_FILES["thumbnail_image"]["tmp_name"]);
                $timage_width_size = $timageDimensions[0];
                $timage_height_size = $timageDimensions[1];
                $uploadFile = $this->do_upload('postuploads', '*', 5120, $timage_width_size, $timage_height_size, $_FILES["thumbnail_image"]["name"], 'thumbnail_image');
                $thumbnail = $uploadFile;
                $timage = array('thumbnail_image' => $thumbnail);
                $NewPost = array_merge($NewPost, $timage);
            }
            if (isset($result['data']['post_id']) && !empty($result['data']['post_id'])) {
//                if (isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
//                    $data_to_where = $result['data']['post_id'];
//                    $fieldname = 'id';
//                    $toGetEditMediaData = $this->Global_model->getFieldsbyIds('posts', '*', $data_to_where, $fieldname);
//                    $unlink_doc = $toGetEditMediaData['unlink_url'];
//                    if (file_exists($unlink_doc)) {
//                        unlink($unlink_doc);
//                    }
//                }
                $data_to_where = $result['data']['post_id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('posts', $NewPost, $data_to_where, $field_name);
                $this->Global_model->deleteData('post_categories', $data_to_where, 'post_id');
                $this->Global_model->deleteData('post_keywords', $data_to_where, 'post_id');
                $post_id = $result['data']['post_id'];
            } else {
                $res = $this->Global_model->insertData('posts', $NewPost);
                $post_id = $res;
            }
            if (!empty($result['data']['category_id']) && isset($result['data']['category_id'])) {
                foreach ($result['data']['category_id'] as $cat):
                    $catArray = array(
                        'post_id' => $post_id,
                        'category_id' => $cat['id'],
                        'updated_date' => date('Y-m-d')
                    );
                    $this->Global_model->insertData('post_categories', $catArray);
                endforeach;
            }
            if (!empty($result['data']['tags']) && isset($result['data']['tags']) && $result['data']['tags'] !== 'null') {
                foreach ($result['data']['tags'] as $tags):
                    foreach ($tags as $k => $tag):
                        $keyArray = array(
                            'post_id' => $post_id,
                            'post_keyword' => $tag,
                            'updated_date' => date('Y-m-d')
                        );
                        $this->Global_model->insertData('post_keywords', $keyArray);
                    endforeach;
                    foreach ($tags as $k => $tag):
                        $keywordsData = $this->Global_model->getKeywordData('keywords', str_replace('-', ' ', $tag));
                        if ($keywordsData == 0) {
                            $keywordarray = array(
                                'keyword_name' => str_replace('-', ' ', $tag),
                                'keyword_slug' => $tag,
                                'keyword_description' => str_replace('-', ' ', $tag),
                                'status' => 1,
                                'updated_date' => date('Y-m-d')
                            );
                            $this->Global_model->insertData('keywords', $keywordarray);
                        }
                    endforeach;

                endforeach;
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

    public function do_upload($uploadedPath, $allowTypes, $maxSize, $imgWidth = '', $imgheight = '', $imgName, $field) {
        $this->load->library('upload'); // Loading upload library to upload an image
        $this->load->library('image_lib'); // Loading image library to resize an image
//        $imgName = $_FILES['file']['name'];

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

        if (!$this->upload->do_upload($field)) {
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

    // to fetch posts data based on publish date
    public function toGetPostPublishData() {
        $lmt = $this->input->get('lmt');
        $ofSet = $this->input->get('ofSet');
        $banner_type = $this->input->get('banner_type');
        $tablename = 'posts';
        $AllPostsData = $this->Global_model->toGetPostPublishData($tablename, $lmt, $ofSet, 1, $banner_type);
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    // to fetch posts data based on publish date
    public function toGetPostPublishDataByCategory() {
        $lmt = $this->input->get('lmt');
        $ofSet = $this->input->get('ofSet');
        $catslug = $this->input->get('slug');
        $post_id = $this->input->get('post_id');
        $AllPostsData = $this->Post_model->toGetPostPublishDataByCategory($catslug, $post_id, $lmt, $ofSet);
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

     public function toGetPostPublishDataByCategoryById() {
        $lmt = $this->input->get('lmt');
        $ofSet = $this->input->get('ofSet');
        $catslug = $this->input->get('id');
        $post_id = $this->input->get('post_id');
        $AllPostsData = $this->Post_model->toGetPostPublishDataByCategoryById($catslug, $post_id, $lmt, $ofSet);
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    // to fetch posts data based on publish date
    public function toGetPostPublishDataByCategoryCount() {
        $catslug = $this->input->get('slug');
        $post_id = $this->input->get('post_id');
        $AllPostsData = $this->Post_model->toGetPostPublishDataByCategory($catslug, $post_id, $lmt = '', $ofSet = '');
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

   
    
    public function toGetPostPublishDataByCategoryCountById() {
        $catslug = $this->input->get('id');
        $post_id = $this->input->get('post_id');
        $AllPostsData = $this->Post_model->toGetPostPublishDataByCategoryById($catslug, $post_id, $lmt = '', $ofSet = '');
        if (isset($AllPostsData) && !empty($AllPostsData)) {
            echo json_encode($AllPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    // to fetch posts count based on publish date
    public function toGetPostPublishCount() {
        $lmt = '';
        $ofSet = '';
        $tablename = 'posts';
        $AllPostsCount = $this->Global_model->toGetPostPublishData($tablename, $lmt, $ofSet, 0, 'N');
        if (isset($AllPostsCount) && !empty($AllPostsCount)) {
            echo json_encode($AllPostsCount);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    // to get post based on id
    public function getPostDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $singlepost = $this->Global_model->getFieldsbyIds('posts', 'id', $slug, 'post_slug');
            if (!empty($singlepost['id']) && isset($singlepost['id'])) {
                $id = $singlepost['id'];
                $flag = 0;
                $post = array();
                $post_ids = array($id);
                if ($id) {
                    $postsData = $this->Global_model->postDetailView($id, $flag);
                    $categoriesData = $this->Global_model->postCategories($id);
                    if (!empty($categoriesData)) {
                        $cats = array_column($categoriesData, 'category_id');
                        $postsData['cats_name'] = array_column($categoriesData, 'category_name');
                    }
                    if (isset($postsData) && !empty($postsData)) {
                        $postCategoryData = $this->Global_model->getPostBasedOnCategory($cats, $id);
                        if (isset($postCategoryData) && !empty($postCategoryData)) {
                            $nextPostData = $this->Global_model->postDetailView($postCategoryData['post_id'], $flag);
                            if (isset($nextPostData) && !empty($nextPostData)) {
                                array_push($post_ids, $nextPostData['id']);
                                $post['nextPostData'] = $nextPostData;
                            }
                        } else {
                            $flag = 1;
                            $nextPostData = $this->Global_model->postDetailView($id, $flag);
                            if (isset($nextPostData) && !empty($nextPostData)) {
                                array_push($post_ids, $nextPostData['id']);
                                $post['nextPostData'] = $nextPostData;
                            }
                        }

                        $postsData['tags'] = json_decode($postsData['tags'], TRUE);
                        //echo "<pre>"; print_r($postsData['tags']); exit;
                        $relStories = $this->Global_model->relStories(array_column($categoriesData, 'category_id'), $post_ids);
                        $post['relStories'] = $relStories;
                        $post['postsData'] = $postsData;
                        echo json_encode($post);
                        exit;
                    } else {
                        echo 'fail';
                        exit;
                    }
                } else {
                    echo 'fail';
                    exit;
                }
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

    public function toGetSidebarDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $sidebarData = $this->Post_model->toGetSidebarDetails($slug);
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

    /* to Get Preview Post Data */

    public function toGetPreviewPostDetails() {
        $id = $this->input->get('id');
        $flag = 0;
        $post = array();
        $post_ids = array($id);
        if ($id) {
            $postsData = $this->Global_model->previewPostDetailView($id, $flag);
            $categoriesData = $this->Global_model->postCategories($id);
            if (!empty($categoriesData)) {
                $cats = array_column($categoriesData, 'category_id');
                $postsData['cats_name'] = array_column($categoriesData, 'category_name');
            }
            if (isset($postsData) && !empty($postsData)) {
                $postCategoryData = $this->Global_model->getPostBasedOnCategory($cats, $id);
                if (isset($postCategoryData) && !empty($postCategoryData)) {
                    $nextPostData = $this->Global_model->previewPostDetailView($postCategoryData['post_id'], $flag);
                    if (isset($nextPostData) && !empty($nextPostData)) {
                        array_push($post_ids, $nextPostData['id']);
                        $post['nextPostData'] = $nextPostData;
                    }
                } else {
                    $flag = 1;
                    $nextPostData = $this->Global_model->previewPostDetailView($id, $flag);
                    if (isset($nextPostData) && !empty($nextPostData)) {
                        array_push($post_ids, $nextPostData['id']);
                        $post['nextPostData'] = $nextPostData;
                    }
                }

                $postsData['tags'] = json_decode($postsData['tags'], TRUE);
                //echo "<pre>"; print_r($postsData['tags']); exit;
                $relStories = $this->Global_model->relStories(array_column($categoriesData, 'category_id'), $post_ids);
                $post['relStories'] = $relStories;
                $post['postsData'] = $postsData;
                echo json_encode($post);
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

    /* to Get Trending Post Data */

    public function toGetTrendingPosts() {
        $toGetTrendingPostsData = $this->Post_model->toGetTrendingPostData('posts');
        if (isset($toGetTrendingPostsData) && !empty($toGetTrendingPostsData)) {
            echo json_encode($toGetTrendingPostsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to Get Trending Post Data By Slug in Category */

    public function toGetTrendingPostByCategory() {
        $result = json_decode(file_get_contents("php://input"), true);
        $toGetTrendingPostsDataByCategory = $this->Post_model->toGetTrendingPostDataByCategory($result['slug']);
        if (isset($toGetTrendingPostsDataByCategory) && !empty($toGetTrendingPostsDataByCategory)) {
            echo json_encode($toGetTrendingPostsDataByCategory);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    public function getTrendingPostsByCategoryById() {
        $result = json_decode(file_get_contents("php://input"), true);
        $toGetTrendingPostsDataByCategory = $this->Post_model->toGetTrendingPostDataByCategoryById($result['id']);
        if (isset($toGetTrendingPostsDataByCategory) && !empty($toGetTrendingPostsDataByCategory)) {
            echo json_encode($toGetTrendingPostsDataByCategory);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    /* to Updates Post Views Value by Using Id */

    public function toUpdatePostViewById() {
        $result = json_decode(file_get_contents("php://input"), true);
        $toGetPostViewsCount = $this->Post_model->toUpdatePostViewById('posts', $result['id']);
        if (isset($toGetPostViewsCount) && !empty($toGetPostViewsCount)) {
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to Get Post Search Count */

    public function toGetPostSearchCount() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']) && !empty($result['data'])) {
            $postdata = $this->Global_model->toGetSearchCount('posts', $result['data'], 'post_title');
            echo json_encode($postdata);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

//    public function BannerOpt() {
//        $toGetBannerPostData = $this->Global_model->getFieldsbyIds('posts', '*', 'Y', 'banner_option');
//        if (!empty($toGetBannerPostData['id']) && isset($toGetBannerPostData['id'])) {
//            $NewPost = array('banner_option' => 'N', 'banner_featureImage' => '');
//            $res = $this->Global_model->updateData('posts', $NewPost, $toGetBannerPostData['id'], 'id');
//            $unlink_doc = FCPATH . 'uploads/postuploads/' . $toGetBannerPostData['banner_featureImage'];
//            if (file_exists($unlink_doc)) {
//                unlink($unlink_doc);
//            }
//        }
//    }
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Episode extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Global_model', 'Episode_model'));
    }

    /* To get All Episodes Data */

    public function toGetEpisodeData() {
        $AllGetEpisodesData = $this->Episode_model->toGetEpisodeData();
        if (isset($AllGetEpisodesData) && !empty($AllGetEpisodesData)) {
            echo json_encode($AllGetEpisodesData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* End here */

    /* To Add New Episode Data */

    public function toaddNewEpisode() {
        $result = $this->input->post();
        if (!empty($result['data']['tags']) && isset($result['data']['tags']) && $result['data']['tags'] != "null") {
            $tagsByc = json_encode($result['data']['tags']);
        }else {
            $tagsByc = '';
        }
        if (isset($result['data']['status']) && !empty($result['data']['status'])) {
            $NewEpisode = array(
                "episode_title" => $result['data']['episode_title'],
                "episode_slug" => $result['data']['episode_slug'],
                "feature_image" => $result['data']['feature_image'],
                "episode_description" => $result['data']['episode_description'],
                "episode_video_tag" => $result['data']['episode_video_tag'],
                "publish_start_date" => $result['data']['publish_start_date'],
                "publish_end_date" => $result['data']['publish_end_date'],
                "shows_id" => $result['data']['shows_id'],
                "seasons_id" => $result['data']['seasons_id'],
                "tags" => isset($tagsByc) ? $tagsByc : '',
                "social_sharing_link" => isset($result['data']['social_sharing_link']) ? $result['data']['social_sharing_link'] : '',
                "seo_tags" => isset($result['data']['seo_tags']) ? $result['data']['seo_tags'] : '',
                "seo_title" => isset($result['data']['seo_title']) ? $result['data']['seo_title'] : '',
                "seo_description" => isset($result['data']['seo_description']) ? $result['data']['seo_description'] : '',
                "episode_type" => 'P', //$result['data']['post_type'],
                "status" => $result['data']['status'],
                "updated_date" => date('Y-m-d')
            );
            /* thumbnail image  */
            if (isset($_FILES['thumbnail_image']['name']) && !empty($_FILES['thumbnail_image']['name'])) {
                $uploadFile = $this->do_thumbnail_upload('episodes/', $_FILES["thumbnail_image"]["tmp_name"], $_FILES["thumbnail_image"]["name"]);
                $thumbnail = $uploadFile;
                $timage = array('thumbnail_image' => $thumbnail);
                $NewEpisode = array_merge($NewEpisode, $timage);
            }
            if (isset($result['data']['episode_id']) && !empty($result['data']['episode_id'])) {
                $data_to_where = $result['data']['episode_id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('episodes', $NewEpisode, $data_to_where, $field_name);
                 $this->Global_model->deleteData('episode_keywords', $data_to_where, 'episode_id');
                $episode_id = $res;
                $this->Global_model->deleteData('episode_links', $data_to_where, 'episode_id');
                $episode_id = $result['data']['episode_id'];
                if (!empty($result['data']['watchlinks']) && isset($result['data']['watchlinks'])) {
                    foreach ($result['data']['watchlinks'] as $watchlinks):
                        if (!empty($watchlinks['link_url']) && isset($watchlinks['link_url'])) {
                            $episodelinksArray = array(
                                "episode_id" => $episode_id,
                                "show_watchlinks_id" => $watchlinks['show_watchlinks_id'],
                                "link_url" => $watchlinks['link_url'],
                                "updated_date" => date('Y-m-d')
                            );
                            $this->Global_model->insertData('episode_links', $episodelinksArray);
                        }
                    endforeach;
                }
            } else {
                $res = $this->Global_model->insertData('episodes', $NewEpisode);
                $episode_id = $res;
                if (!empty($result['data']['watchlinks']) && isset($result['data']['watchlinks'])) {
                    foreach ($result['data']['watchlinks'] as $watchlinks):
                        if (!empty($watchlinks['link_url']) && isset($watchlinks['link_url'])) {
                            $episodelinksArray = array(
                                "episode_id" => $episode_id,
                                "show_watchlinks_id" => $watchlinks['id'],
                                "link_url" => $watchlinks['link_url'],
                                "updated_date" => date('Y-m-d')
                            );
                            $this->Global_model->insertData('episode_links', $episodelinksArray);
                        }
                    endforeach;
                }
            }
            /* tags and keywords Inserting */
            if (!empty($result['data']['tags']) && isset($result['data']['tags']) && $result['data']['tags'] !== 'null') {
                foreach ($result['data']['tags'] as $tags):
                    foreach ($tags as $k => $tag):
                        $keyArray = array(
                            'episode_id' => $episode_id,
                            'episode_keyword' => $tag
                        );
                        $this->Global_model->insertData('episode_keywords', $keyArray);
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
            /* end here */
            
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

    /* function is used to upload Thumbnail Image */
    public function do_thumbnail_upload($uploadedPath, $imgName, $name) {
        $splittedArray = explode(".", $name);
        if (!empty($splittedArray)) {
            $uploadedFile = rand() . '_' . time() . '.' . end($splittedArray);
        }
        move_uploaded_file($imgName, FCPATH . 'uploads/' . $uploadedPath . $uploadedFile);
        return $uploadedFile;
    }
    /* end here */
    
    
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

    /* To Delete Episode Data */

    public function toDeleteEpisodeData($id) {
        if (isset($id) && !empty($id)) {
//            $toGetEpisodeData = $this->Global_model->getFieldsbyIds('episodes', '*', $id, 'id');
//            $unlink_doc = $toGetEpisodeData['unlink_url'];
            $delEpisodeData = $this->Global_model->deleteData('episodes', $id, 'id');
            $delEpisodeData = $this->Global_model->deleteData('episode_links', $id, 'episode_id');
            $delEpisodeData = $this->Global_model->deleteData('episode_keywords', $id, 'episode_id');
            if (isset($delEpisodeData) && !empty($delEpisodeData)) {
//                if (!empty($unlink_doc) && file_exists($unlink_doc)) {
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

    /* To Edit Episode Data */

    public function toeditEpisodeData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $id = $result['id'];
            $fieldname = 'id';
            $toGetEditEpisodeData = $this->Episode_model->toeditEpisodeData($id);
            $episode_links = $this->Episode_model->toGetEpisodeLinks($id);

            if (!empty($episode_links) && isset($episode_links)) {
                $toGetEditEpisodeData['watchlinks'] = $episode_links;
            } else {
                $toGetEditEpisodeData['watchlinks'] = [];
            }
            if (isset($toGetEditEpisodeData) && !empty($toGetEditEpisodeData)) {
                $toGetEditEpisodeData['tags'] = json_decode($toGetEditEpisodeData['tags'], TRUE);
                echo json_encode($toGetEditEpisodeData);
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

    /* To Manage Shows Data */

    public function toGetSelectedShowData() {
        $tablename = 'shows';
        $select = "id, show_name";
        $AllKeywordsData = $this->Global_model->getSelFields($tablename, $select);
        if (isset($AllKeywordsData) && !empty($AllKeywordsData)) {
            echo json_encode($AllKeywordsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* to get seasons by show */

    public function togetSeasonsByShow() {
        $result = $this->input->get();
        $data_to_where = $result['id'];
        $fieldname = 'shows_id';
        $AllSeasonsData = $this->Global_model->getFieldsbyIdsMultiplay('shows_seasons', 'id,season_name', $fieldname, $data_to_where);
        if (isset($AllSeasonsData) && !empty($AllSeasonsData)) {
            echo json_encode($AllSeasonsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* get show seasons by EpisodeId */

    public function togetShowSeasonsByEpisodeId() {
        $result = $this->input->get();
        $data_to_where = $result['episodeId'];
        if (!empty($result['episodeId']) && isset($result['episodeId'])) {
            $fieldname = 'id';
            $toGetShow = $this->Global_model->getFieldsbyIds('episodes', '*', $data_to_where, $fieldname);
            if (!empty($toGetShow['shows_id']) && isset($toGetShow['shows_id'])) {
                $AllSeasonsData = $this->Global_model->getFieldsbyIdsMultiplay('shows_seasons', 'id,season_name', 'shows_id', $toGetShow['shows_id']);
            }
            if (isset($AllSeasonsData) && !empty($AllSeasonsData)) {
                echo json_encode($AllSeasonsData);
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

    /* to get watch links by show */

    function togetWatchLinksByShow() {
        $result = $this->input->get();
        $data_to_where = $result['id'];
        $fieldname = 'shows_id';
        $seasonWatchLinks = $this->Global_model->getFieldsbyIdsMultiplay('shows_watchlinks', 'id,link_logo', $fieldname, $data_to_where);
        if (isset($seasonWatchLinks) && !empty($seasonWatchLinks)) {
            echo json_encode($seasonWatchLinks);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* to Check Episode Slug */

    public function toCheckEpisodeSlug() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Episode_model->toCheckEpisodeSlugData($result['data']['episodeSlug'], $id);
            if ($slugChk !== 0) {
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

    /* end here */


    /* to Get Trending Episode Data */

    public function togetTrendingEpisodes() {
        $togetTrendingEpisodes = $this->Episode_model->toGetTrendingEpiosdeData('episodes');
        if (isset($togetTrendingEpisodes) && !empty($togetTrendingEpisodes)) {
            echo json_encode($togetTrendingEpisodes);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to Get Episode Data */

    public function toGetEpisodeDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $episodeDataArray = array();
            $episode_ids = array();
            $toGetData = $this->Global_model->getFieldsbyIds('episodes', 'id,seasons_id', $slug, 'episode_slug');
            $seasonId = $toGetData['seasons_id'];
            if (!empty($toGetData['id']) && isset($toGetData['id'])) {
                $id = $toGetData['id'];
                $episodeData = $this->Episode_model->episodeDetailView($id);
                array_push($episode_ids, $id);
                $episodeDataArray['episodeData'] = $episodeData;
                $nextEpisodeData = $this->Episode_model->toGetNextEpisodeDetails($id, $seasonId);
                array_push($episode_ids, $nextEpisodeData['id']);
                $episodeDataArray['nextEpisodeData'] = $nextEpisodeData;
                $relatedEpisodeData = $this->Episode_model->toGetRelatedEpisodeDetails($seasonId, $id, $episode_ids);
                $episodeDataArray['relEpisodeData'] = $relatedEpisodeData;
                if (isset($episodeDataArray) && !empty($episodeDataArray)) {
                    echo json_encode($episodeDataArray);
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

    /* end here */

    /* to Get Next Episode Data */

    public function toGetNextEpisodeDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $toGetEpisodeId = $this->Global_model->getFieldsbyIds('episodes', 'id', $slug, 'episode_slug');
            if (!empty($toGetEpisodeId['id']) && isset($toGetEpisodeId['id'])) {
                $id = $toGetEpisodeId['id'];
                $nextEpisodeData = $this->Episode_model->toGetNextEpisodeDetails($id);
                if (isset($nextEpisodeData) && !empty($nextEpisodeData)) {
                    echo json_encode($nextEpisodeData);
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
    }

    /* end  here */

    /* to Get Related Episode Data */

    public function toGetRelatedEpisodeDetails() {
        $slug = $this->input->get('slug');
        if (!empty($slug) && isset($slug)) {
            $toGetData = $this->Episode_model->togetseasonId('episodes', 'seasons_id,id', $slug, 'episode_slug');
            if (!empty($toGetData['seasons_id']) && isset($toGetData['seasons_id'])) {
                $seasonId = $toGetData['seasons_id'];
                $id = $toGetData['id'];
                $relatedEpisodeData = $this->Episode_model->toGetRelatedEpisodeDetails($seasonId, $id);
                if (isset($relatedEpisodeData) && !empty($relatedEpisodeData)) {
                    echo json_encode($relatedEpisodeData);
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
    }

    /* end here */




    /* to Get Techxplorer Data by Season id */

    public function toGetXplorerDataBySeasonId() {
        $result = $this->input->get();
//        echo "<pre>";        print_r($result);exit;
        $xplorData = $this->Episode_model->toGetXplorerDataBySeasonId($result['id']);
        if (isset($xplorData) && !empty($xplorData)) {
            echo json_encode($xplorData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    public function toUpdateEpisodeViewCount() {
        $result = json_decode(file_get_contents("php://input"), true);
        $toGetEpisodeViewsCount = $this->Episode_model->toUpdateEpisodeViewBySlug('episodes', $result['slug']);
        if (isset($toGetEpisodeViewsCount) && !empty($toGetEpisodeViewsCount)) {
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

}

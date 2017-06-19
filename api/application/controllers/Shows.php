<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Shows extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Global_model', 'Shows_model'));
    }

    /* To Add New Show to database */

    public function toAddNewShow() {
        $result = $this->input->post();
        $img_array = array();
        $seasons_array = array();
        $watchlinks_array = array();
        if (!empty($_FILES['links']['name']) && isset($_FILES['links']['name'])) {
            foreach ($_FILES['links']['name'] as $k => $val):
                $imgArray['name'] = $val['link_logo'];
                $imgArray['type'] = $_FILES['links']['type'][$k]['link_logo'];
                $imgArray['tmp_name'] = $_FILES['links']['tmp_name'][$k]['link_logo'];
                $imgArray['error'] = $_FILES['links']['error'][$k]['link_logo'];
                $imgArray['size'] = $_FILES['links']['size'][$k]['link_logo'];
                array_push($img_array, $imgArray);
            endforeach;
        }

        if (!empty($result['seasons']) && isset($result['seasons'])) {

            foreach ($result['seasons'] as $k => $val):
                $seasonArray['season_name'] = $result['seasons'][$k]['season_name'];
                $seasonArray['show_xplorer_id'] = isset($result['seasons'][$k]['show_xplorer_id']) ? $result['seasons'][$k]['show_xplorer_id'] : '0';
                array_push($seasons_array, $seasonArray);
            endforeach;
        }
        if (!empty($result['links']) && isset($result['links'])) {
            foreach ($result['links'] as $k => $val):
                $linkArray['watch_link'] = $result['links'][$k]['watch_link'];
                array_push($watchlinks_array, $linkArray);
            endforeach;
        }
        if (isset($result['showdata']['status']) && !empty($result['showdata']['status'])) {
            $NewShow = array(
                "xplorer_id" => $result['showdata']['xplorer_id'],
                "show_name" => $result['showdata']['show_name'],
                "show_slug" => $result['showdata']['show_slug'],
                "featured_image" => $result['showdata']['featured_image'],
                "video" => $result['showdata']['video'],
                "show_description" => $result['showdata']['show_description'],
                "status" => $result['showdata']['status'],
                "updated_date" => date('Y-m-d')
            );
            /* thumbnail image  */
            if (isset($_FILES['thumbnail_image']['name']) && !empty($_FILES['thumbnail_image']['name'])) {
                $uploadFile = $this->do_upload('show_uploads/', $_FILES["thumbnail_image"]["tmp_name"], $_FILES["thumbnail_image"]["name"]);
                $thumbnail = $uploadFile;
                $timage = array('thumbnail_image' => $thumbnail);
                $NewShow = array_merge($NewShow, $timage);
            }
            if (isset($_FILES['bimage']['name']) && !empty($_FILES['bimage']['name'])) {
                $imageN = $_FILES['bimage']['tmp_name'];
                $uploadFile = $this->do_upload('show_uploads/', $imageN, $_FILES['bimage']['name']);
                $image = array('banner_image' => $uploadFile);
                $NewShow = array_merge($NewShow, $image);
            }

            $show_id = $this->Global_model->insertData('shows', $NewShow);
            if (isset($show_id) && !empty($show_id)) {
                if (!empty($seasons_array) && isset($seasons_array)) {
                    foreach ($seasons_array as $season):
                        $seasonData = array(
                            "shows_id" => $show_id,
                            "season_name" => $season['season_name'],
                            "show_xplorer_id" => $season['show_xplorer_id'],
                            "updated_date" => date('Y-m-d')
                        );
                        $show_seasons = $this->Global_model->insertData('shows_seasons', $seasonData);
                    endforeach;
                }
                if (!empty($img_array) && isset($img_array)) {
                    foreach ($img_array as $k => $image):
                        $watchLinkData = array(
                            "shows_id" => $show_id,
                            "updated_date" => date('Y-m-d'),
                            "watch_link" => $watchlinks_array[$k]['watch_link']
                        );
                        if (isset($image['name']) && !empty($image['name'])) {
                            $imageN = $image['tmp_name'];
                            $uploadFile = $this->do_upload('show_uploads/', $imageN, $image['name']);
                            $image = array('link_logo' => $uploadFile);
                            $watchLinkData = array_merge($watchLinkData, $image);
                        }
                        $this->Global_model->insertData('shows_watchlinks', $watchLinkData);
                    endforeach;
                }
            }

            if (isset($show_id) && !empty($show_id)) {
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

    /* function is used to upload Image */

    public function do_upload($uploadedPath, $imgName, $name) {
        $splittedArray = explode(".", $name);
        if (!empty($splittedArray)) {

            $uploadedFile = rand() . '_' . time() . '.' . end($splittedArray);
        }
        move_uploaded_file($imgName, FCPATH . 'uploads/' . $uploadedPath . $uploadedFile);
        return $uploadedFile;
    }

    /* end here */

    /* To Manage Show Data */

    public function toGetShowsData() {
        $AllShowsData = $this->Shows_model->toGetAllShowsData();
        if (isset($AllShowsData) && !empty($AllShowsData)) {
            echo json_encode($AllShowsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to get show links data by using show id */

    public function toGetShowWatchLinksDataByShowId() {
        $result = $this->input->get();
        $links = $this->Shows_model->toGetShowWatchLinksData($result['slug']);
        if (isset($links) && !empty($links)) {
            echo json_encode($links);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* end here */

    /* To Edit Show Data */

    public function toEditShowsData() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $toEditShowsData = $this->Shows_model->toGetShowData($result['id']);
            $toEditShowsData['seasons'] = $this->Shows_model->toGetShowSeasonsData($result['id']);
            $toEditShowsData['links'] = $this->Shows_model->toGetShowWatchLinksDataAdmin($result['id']);
            if (isset($toEditShowsData) && !empty($toEditShowsData)) {
                echo json_encode($toEditShowsData);
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

    /* to update show */

    public function toUpdateExistingShow() {
        $result = $this->input->post();
        if (isset($result['showdata']['show_id']) && !empty($result['showdata']['show_id'])) {
            $show_id = $result['showdata']['show_id'];

            /* seasons with xplorer code start here */
            $seasons = $this->Shows_model->toGetShowSeasonsData($show_id);
            $newShowIds = array_column($result['seasons'], 'show_season_id');
            $existShowIds = array_column($seasons, 'show_season_id');
            $result_diff = array_diff($existShowIds, $newShowIds);
            if (!empty($result_diff) && isset($result_diff)) {
                $this->Global_model->multipledelete('shows_seasons', $result_diff, 'id');
            }
            if (!empty($result['seasons']) && isset($result['seasons'])) {
                foreach ($result['seasons'] as $season):
                    if (!isset($season['show_season_id']) && empty($season['show_season_id'])) {
                        if (!empty($season['show_xplorer_id']) && isset($season['show_xplorer_id'])) {
                            $seasonData = array(
                                "shows_id" => $show_id,
                                "season_name" => $season['season_name'],
                                "show_xplorer_id" => $season['show_xplorer_id'],
                                "updated_date" => date('Y-m-d')
                            );
                            $show_seasons = $this->Global_model->insertData('shows_seasons', $seasonData);
                        }
                    } else {
                        if (!empty($season['show_xplorer_id']) && isset($season['show_xplorer_id'])) {
                            $season_data = array(
                                "season_name" => $season['season_name'],
                                "show_xplorer_id" => $season['show_xplorer_id'],
                                "updated_date" => date('Y-m-d')
                            );
                            $this->Global_model->updateData('shows_seasons', $season_data, $season['show_season_id'], 'id');
                        }
                    }
                endforeach;
            }
            /* end here */

            $NewShow = array(
                "xplorer_id" => $result['showdata']['xplorer_id'],
                "show_name" => $result['showdata']['show_name'],
                "show_slug" => $result['showdata']['show_slug'],
                "featured_image" => $result['showdata']['featured_image'],
                "video" => $result['showdata']['video'],
                "show_description" => $result['showdata']['show_description'],
                "status" => $result['showdata']['status'],
                "updated_date" => date('Y-m-d')
            );
            /* thumbnail image  */
            if (isset($_FILES['thumbnail_image']['name']) && !empty($_FILES['thumbnail_image']['name'])) {
                $uploadFile = $this->do_upload('show_uploads/', $_FILES["thumbnail_image"]["tmp_name"], $_FILES["thumbnail_image"]["name"]);
                $thumbnail = $uploadFile;
                $timage = array('thumbnail_image' => $thumbnail);
                $NewShow = array_merge($NewShow, $timage);
            }
            if (isset($_FILES['fimage']['name']) && !empty($_FILES['fimage']['name'])) {
                $imageN = $_FILES['fimage']['tmp_name'];
                $uploadFile = $this->do_upload('show_uploads/', $imageN, $_FILES['fimage']['name']);
                $image = array('banner_image' => $uploadFile);
                $NewShow = array_merge($NewShow, $image);
            }
            $updateResult = $this->Global_model->updateData('shows', $NewShow, $show_id, 'id');

            if (isset($updateResult) && !empty($updateResult)) {
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

    public function toUpdateOrAddWatchLinkUpload() {
        $result = $this->input->post();
//        print_r($result);exit;
        $NewShow = array();
        if (isset($_FILES['fimage']['name']) && !empty($_FILES['fimage']['name'])) {
            $imageN = $_FILES['fimage']['tmp_name'];
            $uploadFile = $this->do_upload('show_uploads/', $imageN, $_FILES['fimage']['name']);
            $image = array('link_logo' => $uploadFile);
            $NewShow = array_merge($NewShow, $image);
        }
        if (isset($result['show_watch_id']) && !empty($result['show_watch_id'])) {
            $updateData = array('watch_link' => $result['watchLink']);
            $NewShow = array_merge($NewShow, $updateData);

            $resultData = $this->Global_model->updateData('shows_watchlinks', $NewShow, $result['show_watch_id'], 'id');
//            echo $this->db->last_query(); exit;
            $id = $result['show_watch_id'];
        } else {
            $insertData = array('watch_link' => $result['watchLink'], 'shows_id' => $result['show_id']);
            $NewShow = array_merge($NewShow, $insertData);
            $id = $this->Global_model->insertData('shows_watchlinks', $NewShow);
        }
        $watchLinksData = $this->Shows_model->toGetShowWatchLinksDataAdmin($result['show_id']);
        if (!empty($watchLinksData) && isset($watchLinksData)) {
            echo json_encode($watchLinksData);
            exit;
        } else {
            echo "fail";
            exit;
        }
    }

    public function toDeleteShowsWatchLinksData($id) {
        $toGetData = $this->Global_model->getFieldsbyIds('shows_watchlinks', '*', $id, 'id');
        $unLinkImage = FCPATH . '/uploads/show_uploads/' . $toGetData['unlink_logo'];
        $deleted = $this->Global_model->deleteData('shows_watchlinks', $id, 'id');
        if (isset($deleted) && !empty($deleted)) {
            if (!empty($unLinkImage) && file_exists($unLinkImage)) {
                unlink($unLinkImage);
            }
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */

    /* To Delete Show Data */

    public function toDeleteShowsData($id) {
        if (isset($id) && !empty($id)) {
            $field_name = 'id';
            $toGetEditMediaData = $this->Global_model->getFieldsbyIds('shows', '*', $id, 'id');
            $unlink_doc = $toGetEditMediaData['unlink_url'];
            $delShowsData = $this->Global_model->deleteData('shows', $id, 'id');
            $this->Global_model->deleteData('shows_seasons', $id, 'shows_id');
            $showWatchLinks = $this->Global_model->getFieldsbyIdsMultiplay('shows_watchlinks', '*', 'id', $id);
            foreach ($showWatchLinks as $wl):
                if (!empty($wl['unlink_logo']) && file_exists($wl['unlink_logo'])) {
                    unlink($wl['unlink_logo']);
                }
            endforeach;
            $this->Global_model->deleteData('shows_watchlinks', $id, 'shows_id');
            $episodes = $this->Global_model->getFieldsbyIdsMultiplay('episodes', 'id as episode_id', 'shows_id', $id);
            foreach ($episodes as $episode):
                $this->Global_model->deleteData('episode_links', $episode['episode_id'], 'episode_id');
            endforeach;
            $this->Global_model->deleteData('episodes', $id, 'shows_id');
            if (isset($delShowsData) && !empty($delShowsData)) {
                if (!empty($unlink_doc) && file_exists($unlink_doc)) {
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

    /* end here */

    // to fetch shows data based on publish date
    public function toGetShowPublishData() {
        $lmt = $this->input->get('lmt');
        $ofSet = $this->input->get('ofSet');
        $tablename = 'shows';
        $AllShowsData = $this->Global_model->toGetShowPublishData($tablename, $lmt, $ofSet, 1);
        if (isset($AllShowsData) && !empty($AllShowsData)) {
            echo json_encode($AllShowsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    // to fetch shows count based on publish date
    public function toGetShowPublishCount() {
        $lmt = '';
        $ofSet = '';
        $tablename = 'shows';
        $AllShowsCount = $this->Global_model->toGetShowPublishData($tablename, $lmt, $ofSet, 0);
        if (isset($AllShowsCount) && !empty($AllShowsCount)) {
            echo json_encode($AllShowsCount);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Get Xplorer Data By using Shows Id */

    public function toGetXplorerDataById() {
        $result = $this->input->get();
        $xplorData = $this->Shows_model->toGetXplorerDataByUsingShowId($result['slug']);
        if (isset($xplorData) && !empty($xplorData)) {
            echo json_encode($xplorData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Get Shows Data By Using Shows Id */

    public function toGetOnlyShowsData() {
        $result = $this->input->get();
        $showsData = $this->Shows_model->toGetShowsDataByUsingShowId($result['slug']);
        if (isset($showsData) && !empty($showsData)) {
            echo json_encode($showsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    // to Get Seasons based on showId
    public function togetSeasonsBasedOnShowId() {
        $result = $this->input->get();
        $seasonsData = $this->Shows_model->togetSeasonsBasedOnShowId($result['slug']);
        if (isset($seasonsData) && !empty($seasonsData)) {
            echo json_encode($seasonsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Get seasons Data By Using Shows Id */

    public function toGetEpisodeData() {
        $result = $this->input->get();
        $SeasonsData = $this->Shows_model->toGetSeasonsDataByUsingShowId($result['slug'], $result['limit'], $result['offset'], $result['seasonId']);
        if (isset($SeasonsData) && !empty($SeasonsData)) {
            echo json_encode($SeasonsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }
    
    public function toGetEpisodeDataCount() {
        $result = $this->input->get();
        $SeasonsData = $this->Shows_model->toGetSeasonsDataByUsingShowId($result['slug'], '', '', $result['seasonId']);
        if (isset($SeasonsData) && !empty($SeasonsData)) {
            echo json_encode($SeasonsData);
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
    
    /* To Check Slug */

    public function toCheckShowSlug() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Shows_model->toCheckShowSlugData($result['data']['showSlug'], $id);
            if ($slugChk !== 0) {
//                echo json_encode($slugChk);
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
    
    /* To Check Name of the shows */

    public function toCheckShowName() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $slugChk = $this->Shows_model->toCheckNameOfShow($result['data']['showName'], $id);
            if ($slugChk) {
//                echo json_encode($slugChk);
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

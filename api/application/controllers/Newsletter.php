<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include(FCPATH . 'application/third_party/MailChimp.php');

class Newsletter extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
    }

    /* To Manage Keyword Data */

    public function tonewsletterData() {
        $tablename = 'newsletter_subscriptions';
        $AllNewsletterData = $this->Global_model->getAllData($tablename);
        if (isset($AllNewsletterData) && !empty($AllNewsletterData)) {
            echo json_encode($AllNewsletterData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    public function addnewsletter() {
        $tablename = 'newsletter_subscriptions';
        $res = json_decode(file_get_contents("php://input"), true);
//        echo "<pre>";print_r($res['email']);exit;
        $inData['user_email'] = $res['email'];
        $inData['subscription_type'] = 1;
        if (!empty($inData['user_email'])) {
            $cnt = $this->Global_model->toGetCount($tablename, 'user_email', $res['email']);
//            echo "<pre>";print_r($cnt);exit;
            if (!$cnt) {
//                echo "fail";
//                exit;
                $result = $this->Global_model->insertData($tablename, $inData);
            }
            // list id : 614c45f2ce //  Xploration Station Newsletter List 
            // list id : 05ab9fa96b//Xploration Station Contest Mailing List 
            $MailChimp = new MailChimp('27849b20278862511aed567ba72e30ea-us15');
            $list_id = '614c45f2ce'; //  Xploration Station Newsletter List 
            //$result = $MailChimp->get('lists');
            //echo "<pre>"; print_r($result); exit;
            $res = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $inData['user_email'],
                'status' => 'subscribed',
            ]);
            if ($res['status'] === 400) {
                echo "exist";
                exit;
            } else {
                echo "success";
                exit;
            }

            if ($result) {
                echo "success";
                exit;
            }
        } else {
            echo "fail";
            exit;
        }
    }

    /* to Insert contest Info */

    public function toContestContactInfo() {
        $tablename = 'contest_contact';
        $res = json_decode(file_get_contents("php://input"), true);
        $inData['user_email'] = $res['email'];
        $inData['status'] = 'A';
        if (!empty($inData['user_email'])) {
            $cnt = $this->Global_model->toGetCount($tablename, 'user_email', $res['email']);
            if (!$cnt) {
                $result = $this->Global_model->insertData($tablename, $inData);
            }
            // list id : 614c45f2ce //  Xploration Station Newsletter List 
            // list id : 05ab9fa96b//Xploration Station Contest Mailing List 
            $MailChimp = new MailChimp('27849b20278862511aed567ba72e30ea-us15');
            $list_id = '05ab9fa96b'; //  Xploration Station Newsletter List 
            //$result = $MailChimp->get('lists');
            //echo "<pre>"; print_r($result); exit;
            $res = $MailChimp->post("lists/$list_id/members", [
                'email_address' => $inData['user_email'],
                'status' => 'subscribed',
            ]);
            if ($res['status'] === 400) {
                echo "exist";
                exit;
            } else {
                echo "success";
                exit;
            }
        } else {
            echo "fail";
            exit;
        }
    }

    /* To Check Newsletter Email */

    public function toCheckNewletterEmail() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $id = $result['data']['id'];
            } else {
                $id = '';
            }
            $emailChk = $this->Global_model->toGetNewslettersEmailData($result['data']['email'], $id);
            if ($emailChk == 0) {
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

    /* To Add Newsletter */

    public function toAddnewsletterdata() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']['user_email']) && !empty($result['data']['user_email'])) {
            $Newsletter = array(
                "user_email" => $result['data']['user_email'],
                "subscription_type" => $result['data']['subscription_type'],
                "created_date" => date('Y-m-d')
            );
            if (isset($result['data']['id']) && !empty($result['data']['id'])) {
                $data_to_where = $result['data']['id'];
                $field_name = 'id';
                $res = $this->Global_model->updateData('newsletter_subscriptions', $Newsletter, $data_to_where, $field_name);
            } else {
                $res = $this->Global_model->insertData('newsletter_subscriptions', $Newsletter);
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

    public function toeditNewsletterdata() {
//        $result = json_decode(file_get_contents("php://input"), true);
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $data_to_where = $result['id'];
            $fieldname = 'id';
            $toGetNewsletterData = $this->Global_model->getFieldsbyIds('newsletter_subscriptions', '*', $data_to_where, $fieldname);
            if (isset($toGetNewsletterData) && !empty($toGetNewsletterData)) {
                echo json_encode($toGetNewsletterData);
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

    /* To Delete Newsletter */

    public function todeleteNewsletterdata($id) {
        if (isset($id) && !empty($id)) {
            $data_to_where = $id;
            $field_name = 'id';
            $delNewsletterData = $this->Global_model->deleteData('newsletter_subscriptions', $data_to_where, $field_name);
            if (isset($delNewsletterData) && !empty($delNewsletterData)) {
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

    /* to delete multiple Newletters data */

    public function toDeleteMultipleNLData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['ids']) && !empty($result['ids'])) {
            foreach ($result['ids'] as $id):
                $this->Global_model->deleteData('newsletter_subscriptions', $id, 'id');
            endforeach;
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to get search count */

    public function toGetSearchCount() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result['data']) && !empty($result['data'])) {
            $count = $this->Global_model->toGetNewsletterSearchCount('newsletter_subscriptions', $result['data'], 'user_email');
            echo json_encode($count);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* end here */
}

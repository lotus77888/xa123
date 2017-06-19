<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('Global_model');
        $this->load->model('Admin_model');
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function login() {

        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $res = $this->Auth_model->authIn($result['data']);
            if (isset($res) && !empty($res)) {
                $res['permissions'] = json_decode($res['permissions'], TRUE);
                echo json_encode($res);
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

    public function forgotPwd() {
        $getData = json_decode(file_get_contents("php://input"), TRUE);
        $reset_code = $this->get_random_password(10, 20, '', '', '');
        if (isset($getData) && !empty($getData)) {
            $user_data = $this->Global_model->getFieldsby('admin_users', '*', 'email', $getData['data']);
            $data_to_store = array(
                "resetkey" => $reset_code
            );
            $this->Global_model->updateData("admin_users", $data_to_store, $getData['data'], "email");
            $data = $this->Global_model->getData("admin_users", "email", $getData['data']);
            if (!empty($data['id'])) {

                $resetkey = $data['resetkey'];
                $referalTime = time();

                $msg = "To reset your Password click below link</br>";
                $emailPath = EMAIL_BASEPATH . 'access/resetpassword';
                $urlLink = "<a href='" . $emailPath . "?resetKey=" . $resetkey . "'>" . $emailPath . '?resetKey=' . $resetkey . "</a>";

                $msg = $msg . " " . $urlLink;
                $data = array(
                    'to' => $getData['data'],
                    'from' => "noreply@xploration.com",
                    'subject' => "ResetPassword",
                    'message' => $msg
                );
                $status = $this->toSendMail($data);
                if ($status) {
                    echo "success";
                    exit;
                } else {
                    echo "fail";
                    exit;
                }
            }
        }
    }

    /* to send mail using SMTP Server */

    public function toSendMail($data = '') {

        if (isset($data) && !empty($data)) {
            // config smtp

            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.googlemail.com',
                'smtp_port' => 465,
                'smtp_user' => 'aaran.randy@gmail.com',
                'smtp_pass' => 'randy2015',
                'mailtype' => 'html',
                'charset' => 'iso-8859-1'
            );

            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");

            // Set to, from, message, etc.
            $this->email->to($data['to']);
            if (isset($data['from']) && !empty($data['from'])) {
                $this->email->from($data['from']);
            }
            if (isset($data['bcc']) && !empty($data['bcc'])) {
                $this->email->bcc($data['bcc']);
            }
            if (isset($data['cc']) && !empty($data['cc'])) {
                $this->email->cc($data['cc']);
            }
            $this->email->subject($data['subject']);
            $this->email->message($data['message']);
            if (isset($data['attachment']) && !empty($data['attachment'])) {
                $this->email->attach($data['attachment']);
            }
            return $this->email->send();
            /* local email ends */
        } else {
            return 0;
        }
    }

    /* update new password to user */

    public function toGetUserDataUsingResetKey() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $tbWheref = $result['resetKey'];
            $tbWhere = 'resetKey';
            $res = $this->Global_model->getFieldsbyIds('admin_users', '*', $tbWheref, $tbWhere);
            if (isset($res) && !empty($res)) {
                echo json_encode($res);
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

    public function toUpdateUserPassword() {
        $getData = json_decode(file_get_contents("php://input"), TRUE);
        if (isset($getData) && !empty($getData)) {
            $data_to_store = array(
                "password" => md5($getData['data']['password'])
            );
            $this->Global_model->updateData("admin_users", $data_to_store, $getData['data']['id'], "id");
            echo 'success';
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* to generate random string */

    public function get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false) {
        $length = rand($chars_min, $chars_max);
        $selection = 'aeuoyibcdfghjklmnpqrstvwxz';
        if ($include_numbers) {
            $selection .= "1234567890";
        }
        if ($include_special_chars) {
            $selection .= "!@04f7c318ad0360bd7b04c980f950833f11c0b1d1quot;#$%&[]{}?|";
        }
        $password = "";
        for ($i = 0; $i < $length; $i++) {
            $current_letter = $use_upper_case ? (rand(0, 1) ? strtoupper($selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))]) : $selection[(rand() % strlen($selection))];
            $password .= $current_letter;
        }
        return $password;
    }

    /* check users Existing Password */

    public function chkUserExisitingPassword() {
        $result = $this->input->get();
        if (isset($result) && !empty($result)) {
            $res = $this->Admin_model->chkUserExisitingPassword($result['id'], $result['password']);
            if (isset($res) && !empty($res)) {
                echo json_encode($res);
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

    /* Change Password */

    public function changePassword() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $data_to_update = array(
                "password" => $result['new_password']
            );
            $data_to_where = $result['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('admin_users', $date_to_update, $data_to_where, $filed_name);
            if (isset($res) && !empty($res)) {
                echo json_encode($res);
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

    /* To Update user Profile */

    public function toUpdateUserProfile() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $data_to_update = array(
                "first_name" => $result['first_name'],
                "last_name" => $result['last_name'],
                "email" => $result['email'],
                "username" => $result['username'],
                "address" => $result['address'],
                "phone" => $result['phone']
            );
            $data_to_where = $result['id'];
            $field_name = 'id';
            $res = $this->Global_model->updateData('admin_users', $date_to_update, $data_to_where, $filed_name);
            if (isset($res) && !empty($res)) {
                echo json_encode($res);
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

    /* To get User Data */

    public function toGetUserData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (isset($result) && !empty($result)) {
            $userData = $this->Admin_model->toGetUserData($result['id']);
            if (isset($userData) && !empty($userData)) {
                echo json_encode($userData);
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

    /* To Get Count */

    public function toGetAllTablesCount() {
        $data['adminUsersCount'] = $this->Global_model->toGetAllTablesCount('admin_users');
        $data['usersCount'] = $this->Global_model->toGetAllTablesCount('users');
        $data['categoriesCount'] = $this->Global_model->toGetAllTablesCount('categories');
        $data['mediasCount'] = $this->Global_model->toGetAllTablesCount('media');
        $data['pagesCount'] = $this->Global_model->toGetAllTablesCount('pages');
        $data['postsCount'] = $this->Global_model->toGetAllTablesCount('posts');
        $data['sideBarsCount'] = $this->Global_model->toGetAllTablesCount('side_bars');
        $data['contactFormCount'] = $this->Global_model->toGetAllTablesCount('contact_form');
        $data['newsLetterCount'] = $this->Global_model->toGetAllTablesCount('newsletter_subscriptions');
        $data['episodesCount'] = $this->Global_model->toGetAllTablesCount('episodes');
        $data['showsCount'] = $this->Global_model->toGetAllTablesCount('shows');
        $data['xplorersCount'] = $this->Global_model->toGetAllTablesCount('tech_explorers');
        if (isset($data) && !empty($data)) {
            echo json_encode($data);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* Search Functionality */

    public function toGetSearchData() {
        $result = json_decode(file_get_contents("php://input"), true);
        if (!empty($result['search_word']) && isset($result['search_word'])) {
            $srchArray = explode(" ", $result['search_word']);
            if ($result['type'] === 'S') {
                $searchData = $this->Global_model->toGetPostSearchData($srchArray, $result['category_id']);
            } else if ($result['type'] === 'E') {
                $searchData = $this->Global_model->toGetEpisodeSearchData($srchArray);
            } else {
                $postSearchData = $this->Global_model->toGetPostSearchData($srchArray, $result['category_id']);
                $episodeSearchData = $this->Global_model->toGetEpisodeSearchData($srchArray);
                $searchData = array_merge($postSearchData, $episodeSearchData);
            }
            if (isset($searchData) && !empty($searchData)) {
                echo json_encode($searchData);
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Global_model');
        $this->load->model('Auth_model');
    }

    /* To Manage Keyword Data */

    public function toGetContactsData() {
        $tablename = 'contact_form';
        $AllContactsData = $this->Global_model->getAllData($tablename);
        if (isset($AllContactsData) && !empty($AllContactsData)) {
            echo json_encode($AllContactsData);
            exit;
        } else {
            echo 'fail';
            exit;
        }
    }

    /* To Insert and Send Mail */

    public function toSendContactUsData() {
        $result = json_decode(file_get_contents("php://input"), true);
        $fname = $result['data']['firstname'];
        $lname = isset($result['data']['lastname']) ? $result['data']['lastname'] : '';
        $tbData = array(
            'name' => $fname . " " . $lname,
            'email' => $result['data']['email'],
            'phone' => isset($result['data']['phone']) ? $result['data']['phone'] : '',
            'comments' => $result['data']['comments']
        );
        $tablename = 'contact_form';
        $ContactsData = $this->Global_model->insertData($tablename, $tbData);
        $data = $this->Global_model->getData("contact_form", "email", $result['data']['email']);
        if (!empty($data['id'])) {
//            $data = array(
//                'to' => 'xplorationstation@gmail.com',
//                'from' => $data['email'],
//                'subject' => "Contact",
//                'message' => 'You are Contacted with Xploration, We will Get in Touch.....'
//            );
            $status = $this->toSendMail($tbData);
            if ($status) {
                echo "success";
                exit;
            } else {
                echo "fail";
                exit;
            }
        }
    }

    /* to send mail using SMTP Server */

    public function toSendMail($tbData) {


        if (isset($tbData) && !empty($tbData)) {
            // config smtp
            $contactInfo = '<thead style="background:#f3f3f3">';
            $contactInfo .= '<th>'.$tbData['name'].'</th>';
            $contactInfo .= '<th>'.$tbData['email'].'</th>';
            $contactInfo .= '<th>'.$tbData['phone'].'</th>';
            $contactInfo .= '<th>'.$tbData['comments'].'</th></thead>';
            
            $result = mail('xplorationstation@gmail.com', 'Contact', $contactInfo);
            if ($result) {
                echo "success";
                exit;
            } else {
                echo "fail";
                exit;
            }
//            $config = Array(
//                'protocol' => 'smtp',
//                'smtp_host' => 'ssl://smtp.googlemail.com',
//                'smtp_port' => 465,
//                'smtp_user' => 'aaran.randy@gmail.com',
//                'smtp_pass' => 'randy2015',
//                'mailtype' => 'html',
//                'charset' => 'iso-8859-1'
//            );
//
//            $this->load->library('email', $config);
//            $this->email->set_newline("\r\n");
//
//            // Set to, from, message, etc.
//            $this->email->to($data['to']);
//            if (isset($data['from']) && !empty($data['from'])) {
//                $this->email->from($data['from']);
//            }
//            if (isset($data['bcc']) && !empty($data['bcc'])) {
//                $this->email->bcc($data['bcc']);
//            }
//            if (isset($data['cc']) && !empty($data['cc'])) {
//                $this->email->cc($data['cc']);
//            }
//            $this->email->subject($data['subject']);
//            $this->email->message($data['message']);
//            if (isset($data['attachment']) && !empty($data['attachment'])) {
//                $this->email->attach($data['attachment']);
//            }
//            return $this->email->send();
            /* local email ends */
        } else {
            return 0;
        }
    }

}

<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('DbHandler');
        $this->conn = "";
    }


    public function userSignIn() {
        $email = $_REQUEST['email'];
        $password = $_REQUEST['password'];
        $response = $this->dbhandler->sign_in($email, $password);
        echo json_encode($response);

    }

    public function set_session() {
        echo "callling session";
        $userData = array();

        $userData = array(
            'email' => $_REQUEST['email'],
            'password' => $_REQUEST['password']
        );

        $this->session->set_userdata('isAlreadyLogin', 'true');
        $this->session->set_userdata('userData', $userData);

        echo json_encode($this->session->userdata('userData'));


    }

    public function index() {
        if ($this->session->userdata('isAlreadyLogin') == 'true') {

            $this->load->view('header');
            $this->load->view('sidebar');
            $this->load->view('navbarTop');
            $this->load->view('packet');
            $this->load->view('footer');
        } else {

            $this->load->view('header');
            $this->load->view('signIn');
            $this->load->view('footer');

        }

    }

    public function logout() {
        $this->session->sess_destroy();
    }

}
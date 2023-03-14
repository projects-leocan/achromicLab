<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('DbHandler');
        $this->load->library('session');
        $this->conn = "";
    }


    // index
    public function index()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('footer');
    }

    // sidebar menu view for packet 
    public function packet_menu()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('packetList');
        $this->load->view('footer');
    }
    public function packet_form()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('packet');
        $this->load->view('footer');
    }

    // sidebar menu view for company list
    public function company_menu()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('companyMenu');
        $this->load->view('footer');
    }

    public function fatchAllCompanyName()
    {

        $response = $this->dbhandler->fatchAllCompanyName();
        echo json_encode($response);

    }


    public function addCategories()
    {
        $catName = $_REQUEST["categoryName"];
        $response = $this->dbhandler->category($catName);
        echo json_encode($response);
    }


    public function fetchAllCategories()
    {
        $response = $this->dbhandler->fetchAllCategories();
        // echo $response;
        echo json_encode($response);
    }
    



}
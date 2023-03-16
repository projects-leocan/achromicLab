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


    public function addCompany()
    {
        $catName = $_REQUEST["company_name"];
        $response = $this->dbhandler->addCompany($catName);
        echo json_encode($response);
    }

    public function uniqueName()
    {
        $catName = $_REQUEST["company_name"];
        $response = $this->dbhandler->uniqueCompanyName($catName);
        echo json_encode($response);
    }

    public function fatchSelectedCompanyData()
    {
        $company_name = $_REQUEST["c_name"];
        $response = $this->dbhandler->fatchSelectedCompany($company_name);
        echo json_encode($response);
    }

    public function deleteCompany()
    {
        $company_id = $_REQUEST["company_id"];
        $response = $this->dbhandler->deleteCompany($company_id);
        echo json_encode($response);
    }

    public function updateCompany()
    {
        $company_id = $_REQUEST["company_id"];
        $catName = $_REQUEST["company_name"];
        $response = $this->dbhandler->updateCompany($company_id,$catName);
        echo json_encode($response);
    }


    public function fetchAllCategories()
    {
        $response = $this->dbhandler->fetchAllCategories();
        echo json_encode($response);
    }

    public function fetchAllPackets()
    {
        $response = $this->dbhandler->fatchPacketDetails();
        echo json_encode($response);
    }

    public function addPacketData()
    { 
        $selectedDate = $_REQUEST["selectedDate"];
        $company_id = $_REQUEST["company_id"];
        $inputedCompanyName = $_REQUEST["inputedCompanyName"];
        $packetNum = $_REQUEST["packetNum"];
        $quantity = $_REQUEST["quantity"];
        $total_carat = $_REQUEST["total_carat"];
        $pending_process_qty_diamond = $_REQUEST["pending_process_qty_diamond"];
        $pending_process_qty_carat = $_REQUEST["pending_process_qty_carat"];
        $broken_qty_diamond = $_REQUEST["broken_qty_diamond"];
        $broken_qty_carat = $_REQUEST["broken_qty_carat"];
        $price_per_carat = $_REQUEST["price_per_carat"];

        $response = $this->dbhandler->addPacketDetails($company_id,$selectedDate,$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$price_per_carat,$inputedCompanyName);
        echo json_encode($response);
    }


    



}


?>
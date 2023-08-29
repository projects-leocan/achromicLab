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

    public function invoice_form()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('invoice');
        $this->load->view('footer');
    }
    public function invoice_form_new()
    {
        $this->load->view('header');
        $this->load->view('sidebar');
        $this->load->view('invoice_new');
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

    public function print_invoice()
    {
        // $packet_id = $_REQUEST["packet_id"];
        $response = $this->dbhandler->print_invoice();
        echo json_encode($response);
    }
    public function show_invoice()
    {
        $response = $this->dbhandler->show_invoice();
        echo json_encode($response);
    }
    // public function redirect_invoice()
    // {
    //     $invoice_data = $_REQUEST["invoice_data"];
    //     $this->load->view('header');
    //     $this->load->view('sidebar');
    //     $this->load->view('invoice',$invoice_data);
    //     $this->load->view('footer');
    // }
    public function fatchSelectedCompanyData()
    {
        $company_id = $_REQUEST["company_id"];
        // $selected_date = isset($_REQUEST["selected_date"]) ? $_REQUEST["selected_date"] : null;
        $start_date = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : null;
        $end_date = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : null;
        $lastPacketId = $_REQUEST["lastPacketId"];
        $rowPerPage = $_REQUEST["rowPerPage"];
        $search_text = isset($_REQUEST["searchText"]) ? $_REQUEST["searchText"] : null;
        
        $response = $this->dbhandler->fatchSelectedCompany($company_id, $start_date,$end_date,$lastPacketId,$rowPerPage,$search_text);
        echo json_encode($response);
    }

    public function deleteCompany()
    {
        $company_id = $_REQUEST["company_id"];
        $response = $this->dbhandler->deleteCompany($company_id);
        echo json_encode($response);
    }

    public function deletePacket()
    {
        $packet_id = $_REQUEST["packet_id"];
        $response = $this->dbhandler->deletePacket($packet_id);
        echo json_encode($response);
    }

    public function updateCompany()
    {
        $company_id = $_REQUEST["company_id"];
        $catName = $_REQUEST["company_name"];
        $response = $this->dbhandler->updateCompany($company_id,$catName);
        echo json_encode($response);
    }
    public function updateChallanNo()
    {
        $challan_no = $_REQUEST["challan_no"];
        $response = $this->dbhandler->updateChallan($challan_no);
        echo json_encode($response);
    }

    public function updatePacket()
    {
        $packet_id = $_REQUEST["packet_id"];
        $broken_diamond_carat = $_REQUEST["broken_diamond_carat"];
        $broken_diamond_qty = $_REQUEST["broken_diamond_qty"];
        $date = $_REQUEST["date"];
        $company_id = $_REQUEST["company_id"];
        $packet_dimond_caret = $_REQUEST["packet_dimond_caret"];
        $packet_dimond_qty = $_REQUEST["packet_dimond_qty"];
        $pending_process_diamond_carat = $_REQUEST["pending_process_diamond_carat"];
        $pending_process_diamond_qty = $_REQUEST["pending_process_diamond_qty"];
        $cube_qty = $_REQUEST["cube_qty"];
        $cube_time = $_REQUEST["cube_time"];
        $price_per_carat = $_REQUEST["price_per_carat"];

        $response = $this->dbhandler->updatePacket($packet_id,$broken_diamond_carat,$broken_diamond_qty,$date,$company_id,$packet_dimond_caret,$packet_dimond_qty,$pending_process_diamond_carat,$pending_process_diamond_qty,$cube_qty,$cube_time,$price_per_carat);
        echo json_encode($response);
    }

    public function fatchPacketById()
    {
        $packet_id = $_REQUEST["packet_id"];
        $response = $this->dbhandler->fatchPacketByID($packet_id);
        echo json_encode($response);
    }

    public function searchPacket()
    {
        $company_id = $_REQUEST["company_id"];
        $start_date = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : null;
        $end_date = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : null;
        $lastPacketId = $_REQUEST["lastPacketId"];
        $rowPerPage = $_REQUEST["rowPerPage"];
        $search_text = isset($_REQUEST["searchText"]) ? $_REQUEST["searchText"] : null;
        
        $response = $this->dbhandler->searchPackets($company_id, $start_date,$end_date,$lastPacketId,$rowPerPage,$search_text);
        echo json_encode($response);
    }


    public function fetchAllCategories()
    {
        $response = $this->dbhandler->fetchAllCategories();
        echo json_encode($response);
    }

    public function fetchAllPackets()
    {
        $rowPerPage = $_REQUEST['rowPerPage'];
        $lastPacketId = $_REQUEST['lastPacketId'];
        $response = $this->dbhandler->fatchPacketDetails($rowPerPage,$lastPacketId);
        echo json_encode($response);
    }

    public function autoIncPacketNum()
    {
        $response = $this->dbhandler->autoPacketNum();
        echo json_encode($response);
    }


    public function getCount()
    {
        $response = $this->dbhandler->fetchCount();
        echo json_encode($response);
    }

    public function getCountForFilter()
    {
        $start_date = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : null;
        $end_date = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : null;
        $company_id = $_REQUEST["company_id"];
        $search_text = isset($_REQUEST["searchText"]) ? $_REQUEST["searchText"] : null;
        $response = $this->dbhandler->getCountForFilter($start_date,$end_date,$company_id,$search_text);
        echo json_encode($response);
    }

    public function getPacketSumWithFilter()
    {
        $start_date = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : null;
        $end_date = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : null;
        $company_id = $_REQUEST["company_id"];
        $response = $this->dbhandler->getAllPacketSum($start_date,$end_date,$company_id);
        echo json_encode($response);
    }

    public function getCountForFilterWithSearch()
    {
        $start_date = isset($_REQUEST["startDate"]) ? $_REQUEST["startDate"] : null;
        $end_date = isset($_REQUEST["endDate"]) ? $_REQUEST["endDate"] : null;
        $company_id = $_REQUEST["company_id"];
        $search_text = isset($_REQUEST["searchText"]) ? $_REQUEST["searchText"] : null;
        $response = $this->dbhandler->getCountForFilterWithSearch($start_date,$end_date,$company_id,$search_text);
        echo json_encode($response);
    }
    
    public function importCSV()
    {
        $data = $_REQUEST['data'];
        $response = $this->dbhandler->importCSV($data);
        echo json_encode($response);
    }

    public function invoice_entry()
    {
        $data = $_REQUEST['data'];
        $response = $this->dbhandler->invoiceEntry($data);
        echo json_encode($response);
    }

    public function newCompanies()
    {
        $company_names = $_REQUEST['company_names'];
        $response = $this->dbhandler->bulkInsertNewCompany($company_names);
        echo json_encode($response);
    }

    public function addPacketData()
    { 
        $selectedDate = $_REQUEST["selectedDate"];
        $company_id = $_REQUEST["company_id"];
        $packetNum = $_REQUEST["packetNum"];
        $quantity = $_REQUEST["quantity"];
        $total_carat = $_REQUEST["total_carat"];
        $pending_process_qty_diamond = $_REQUEST["pending_process_qty_diamond"];
        $pending_process_qty_carat = $_REQUEST["pending_process_qty_carat"];
        $broken_qty_diamond = $_REQUEST["broken_qty_diamond"];
        $broken_qty_carat = $_REQUEST["broken_qty_carat"];
        $cube_qty = $_REQUEST["cube_qty"];
        $cube_time = $_REQUEST["cube_time"];
        $price_per_carat = $_REQUEST["price_per_carat"];

        $response = $this->dbhandler->addPacketDetails($company_id,$selectedDate,$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,$cube_time,$price_per_carat);
        echo json_encode($response);
    }




    



}


?>
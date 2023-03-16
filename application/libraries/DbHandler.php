<?php

class DbHandler
{
    private $conn;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();

    }

    function getBasePath()
    {
        $base_dir = __DIR__ . "/";
        $url = explode('/', $base_dir);
        array_pop($url);
        implode('/', $url);
        array_pop($url);

        implode('/', $url);
        array_pop($url);
        return implode('/', $url) . '/';
    }

    public function sign_in($email, $password)
    {
        $sql_query = "SELECT id,email,password from `admin` WHERE email= ? and password = ?";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        $response = array();

        while ($obj = $result->fetch_assoc()) {
            $response[] = $obj;
        }

        $stmt->close();

        if (count($response) > 0) {
            $result = array(
                'success' => true,
                'response' => $response,
                'message' => 'LOGIN_SUCCESS',

            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'LOGIN_FAIL'
            );
        }
        return $result;
    }

    public function fatchAllCompanyName()
    {
        $sql_query = "SELECT * FROM company ORDER BY company_id DESC";
        $stmt = $this->conn->prepare($sql_query);

        $stmt->execute();

        $result = $stmt->get_result();
        $nameArr = array();

        while ($obj = $result->fetch_assoc()) {
            $nameArr[] = $obj;
        }

        $stmt->close();

        if (count($nameArr) > 0) {
            $result = array(
                'success' => true,
                'CompanyNames' => $nameArr,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function fatchSelectedCompany($company_id)
    {
        $sql_query = "SELECT * FROM `packet` WHERE `company_id` = '$company_id'";
        $stmt = $this->conn->prepare($sql_query);

        $stmt->execute();

        $result = $stmt->get_result();
        $nameArr = array();

        while ($obj = $result->fetch_assoc()) {
            $nameArr[] = $obj;
        }

        $stmt->close();

        if (count($nameArr) > 0) {
            $result = array(
                'success' => true,
                'CompanyNames' => $nameArr,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function fatchPacketDetails()
    {

        $sql_query = "SELECT * FROM `packet` ORDER BY `packet_id`  DESC ";
        $stmt = $this->conn->prepare($sql_query);

        $stmt->execute();

        $result = $stmt->get_result();
        $packet = array();

        while ($obj = $result->fetch_assoc()) {
            $packet[] = $obj;
        }

        $stmt->close();

        if (count($packet) > 0) {
            $result = array(
                'success' => true,
                'packet' => $packet,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function print_invoice($packet_id)
    {

        $sql_query = "SELECT * FROM `packet` where `packet_id`= $packet_id ";
        $stmt = $this->conn->prepare($sql_query);

        $stmt->execute();

        $result = $stmt->get_result();
        $packet = array();

        while ($obj = $result->fetch_assoc()) {
            $packet[] = $obj;
        }

        $stmt->close();

        if (count($packet) > 0) {
            $result = array(
                'success' => true,
                'packet' => $packet,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function addPacketDetails($company_id,$selectedDate,$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$price_per_carat)
    {
        $sql_query = "insert into `packet` (`company_id`,`date`,`packet_no`,`packet_dimond_caret`,`packet_dimond_qty`,`pending_process_diamond_qty`,`pending_process_diamond_carat`,`broken_diamond_qty`,`broken_diamond_carat`,`price_per_carat`) VALUES ($company_id,'$selectedDate',$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$price_per_carat)";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();

        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'packet added successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'packet not added',
            );
        }
        return $result;
    }


    public function deleteCompany($id){
        $sql_query = "delete from `company` where `company_id` ='$id' ";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();
        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'Company deleted successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'compnay not deleted',
            );
        }
        return $result;
    }

    public function updateCompany($id,$company_name){
        $sql_query = "update `company` set 	`company_name`='$company_name'  where `company_id` ='$id' ";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();
        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'Company updated successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'compnay not update',
            );
        }
        return $result;
    }

    public function addCompany($name)
    {
        $sql_query = "insert into `company` (`company_name`) VALUES ('$name')";
        $stmt = $this->conn->prepare($sql_query);
        // $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();

        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'Company added successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'compnay not added',
            );
        }
        return $result;

    }

    public function uniqueCompanyName($company_name)
    {
        $sql_query = "SELECT COUNT(*) FROM company WHERE `company_name` = '$company_name'";
        $stmt = $this->conn->prepare($sql_query);

        $stmt->execute();

        $result = $stmt->get_result();
        $response = array();

        while ($obj = $result->fetch_assoc()) {
            $response[] = $obj;
        }

        $stmt->close();

        if($response[0]['COUNT(*)'] > 0){ // Access the count value from the response array
            $result = array(
                'success' => true,
                'response' => $response,
                'message' => 'Company name already exists'
            );
        }else {
            $result = array(
                'status' => true,
                'message' => 'company added successfully',
                'response' => $response,
            );
        }
    return $result;

}

    

    public function fetchAllCategories()
    {
        $sql_query = "CALL fetchAllCategories()"; //CALL PROCEDURE     
        $stmt = $this->conn->query($sql_query); // query fatch
        $this->conn->next_result(); // privous stement repeat for fatching multiple query
        $list = array();
        while ($row = $stmt->fetch_assoc()) { // fetching data as group           
            $list[] = $row;
        }

        $stmt->close();

        if (count($list) > 0) {
            $result = array(
                'success' => true,
                'list' => $list
            );
        } else {
            $result = array(
                'success' => false,
                'message' => "NOT_FOUND"
            );
        }
        return $result;
    }


}

?>
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
        $nameArr = usort($nameArr, "cmp");
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

    public function fatchSelectedCompany($company_id, $start_date,$end_date)
    {
       
        if( $company_id == "null"){
          
            $company_id = -1;
        }

        if($company_id > 0 && isset($start_date) && isset($end_date)){
            $sql_query = "SELECT c.company_name, p.* FROM company as c, packet p WHERE c.company_id = p.company_id and p.company_id = '$company_id' and is_delete = 0 and date BETWEEN '$start_date' and '$end_date' ";
        }
        else if(($company_id > 0)){
            $sql_query = "SELECT c.company_name, p.* FROM company as c, packet p WHERE c.company_id = p.company_id and p.company_id = '$company_id' and is_delete = 0 ";
        }
        else if(isset($start_date) && isset($end_date)){
            $sql_query = "SELECT c.company_name, p.* FROM company as c, packet p WHERE c.company_id = p.company_id  and is_delete = 0 and date BETWEEN '$start_date' and '$end_date'";
        }
        else{
            $sql_query = "SELECT c.company_name, p.* FROM company as c, packet p WHERE c.company_id = p.company_id  and is_delete = 0";
        }
       
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
                'packet' => $nameArr,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }
    function cmp($a, $b) {
        return strcmp($a->company_name, $b->company_name);
    }
    public function fatchPacketDetails()
    {

        $sql_query = "SELECT c.company_name, p.* FROM company as c, packet p WHERE c.company_id = p.company_id and is_delete = 0 order by p.packet_id DESC";
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
    
    public function fatchPacketByID($packet_id)
    {
        $sql_query = "select p.*, c.company_name from packet p , company c where p.company_id = c.company_id and p.packet_id ='$packet_id' and p.is_delete = 0";
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

    public function autoPacketNum()
    {

        $sql_query = "SELECT count(*) as packet_count FROM `packet` ";
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

    public function print_invoice()
    {

        $sql_query = "SELECT * FROM `invoice`" ;
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

    public function addPacketDetails($company_id,$selectedDate,$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,$cube_time,$price_per_carat)
    {
        $sql_query;
        if(($cube_time == null) || ($cube_time) == ""){
            $sql_query = "insert into `packet` (`company_id`,`date`,`packet_no`,`packet_dimond_qty`,`packet_dimond_caret`,`pending_process_diamond_qty`,`pending_process_diamond_carat`,`broken_diamond_qty`,`broken_diamond_carat`,`cube_qty`,`price_per_carat`) VALUES ($company_id,'$selectedDate',$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,$price_per_carat)";
        }
        else{
            $sql_query = "insert into `packet` (`company_id`,`date`,`packet_no`,`packet_dimond_qty`,`packet_dimond_caret`,`pending_process_diamond_qty`,`pending_process_diamond_carat`,`broken_diamond_qty`,`broken_diamond_carat`,`cube_qty`,`cube_time`,`price_per_carat`) VALUES ($company_id,'$selectedDate',$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,'$cube_time',$price_per_carat)";
        }
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

    public function importCSV($data)
    {
        $decodedData = json_decode($data, true);
        // var_dump($data);
        $sql_query = "INSERT INTO packet (`company_id`, `date`, `packet_no`, `packet_dimond_caret`, `packet_dimond_qty`,`price_per_carat`) VALUES ";

        $query_parts = array();
        foreach ($decodedData as $value) {
            $companyId = $value['Company Name'];
            $date = $value['Date'];
            $packetNo = $value['packet_no'];
            $packetDimondCaret = $value['Total Piece'];
            $packetDimondQty = $value['Total Carat'];
            $finalCaret = $value['Total Carat'];

            $query_parts[] = "('" . $companyId . "', '" . $date . "', '" . $packetNo . "', '" . $packetDimondQty . "', '" .$packetDimondCaret . "', '" .$finalCaret . "')";
        }
        $query = implode(',', $query_parts);
        $sql_query .= implode(',', $query_parts);
        
        // $stmt = $this->conn->prepare($sql_query);
        // $stmt->execute();
        // $stmt->close();

        try
        {
        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'query' => $sql_query,
                'message' => 'File imported successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'Please add valid company Id in excel sheet which is available in company list',
            );
        }
    } catch (Exception $e) {
        $result = array(
            'success' => false,
            'message' => 'Please add valid company Id in excel sheet which is available in company list.',
        );
    }

        return $result;
    }

    // public function bulkInsertNewCompany($company_names)
    // {
    //     $decodedData = json_decode($company_names, true);

    //     $sql_query = "INSERT INTO company (`company_name`) VALUES ";

    //     $query_parts = array();
    //     foreach ($decodedData as $value) {
    //         $query_parts[] = "('" . $value . "')";
    //     }
    //     $sql_query .= implode(',', $query_parts);
    //     // echo $sql_query;
    //     $stmt = $this->conn->prepare($sql_query);
    //     $stmt->execute();
    //     $stmt->close();

    //     if (mysqli_query($this->conn, $sql_query)) {
    //         $result = array(
    //             'success' => true,
    //             'message' => 'Companies added successfully',
    //         );
    //     } else {
    //         $result = array(
    //             'success' => false,
    //             'message' => 'Companies not added',
    //         );
    //     }
    //     return $result;
    // }



    public function deletePacket($id)
    {
        $sql_query = "update packet set is_delete=1 where packet_id=$id ";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();

        if($sql_query){
            $result = array(
                'success' => true,
                'message' => 'packet deleted successfully',
            );
        }else{
            $result = array(
                'success' => false,
                'message' => 'can not delete this packet.',
                
            );
        }
        return $result;
    }


    public function updatePacket($packet_id,$broken_diamond_carat,$broken_diamond_qty,$date,$company_id,$packet_dimond_caret,$packet_dimond_qty,$pending_process_diamond_carat,$pending_process_diamond_qty,$cube_qty,$cube_time,$price_per_carat)
    {

        if(($cube_time == null) || ($cube_time) == ""){
            $sql_query = "update `packet` set company_id=$company_id,	date='$date',packet_dimond_caret =$packet_dimond_caret ,packet_dimond_qty=$packet_dimond_qty, pending_process_diamond_qty =$pending_process_diamond_qty, pending_process_diamond_carat=$pending_process_diamond_carat,	broken_diamond_qty=$broken_diamond_qty,broken_diamond_carat=$broken_diamond_carat ,
            cube_qty=$cube_qty, price_per_carat=$price_per_carat  where packet_id=$packet_id";
    
        }
        else{
            
                    $sql_query = "update `packet` set company_id=$company_id,	date='$date',packet_dimond_caret =$packet_dimond_caret ,packet_dimond_qty=$packet_dimond_qty, pending_process_diamond_qty =$pending_process_diamond_qty, pending_process_diamond_carat=$pending_process_diamond_carat,	broken_diamond_qty=$broken_diamond_qty,broken_diamond_carat=$broken_diamond_carat ,
                    cube_qty=$cube_qty,cube_time='$cube_time', price_per_carat=$price_per_carat  where packet_id=$packet_id";

        }


        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();
        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'packet updated successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'packet can not update',
            );
        }
        return $result;
    }


    public function deleteCompany($id){
        $sql_query = "delete from `company` where `company_id` ='$id' ";
        // $stmt = $this->conn->prepare($sql_query);
        // $stmt->execute();
        // $stmt->close();

        if(mysqli_query($this->conn, $sql_query)){
            $result = array(
                'success' => true,
                'message' => 'Company deleted successfully'
            );
        }else{
            $result = array(
                'success' => false,
                'message' => 'Cannot delete this company because packet is already exist with this company.'
                
            );
        }

        
       
        // if ($sql_query) {
        //     $result = array(
        //         'success' => true,
        //         'message' => 'Company deleted successfully',
        //         'result' => $result
        //     );
        // } else {
        //     $result = array(
        //         'success' => false,
        //         'message' => 'compnay not deleted',
        //         'result' => $result
        //     );
        // }
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

    public function updateChallan($updateChallanNo){
        $sql_query = "update `invoice` set 	`challan_no`='$updateChallanNo'";
        $stmt = $this->conn->prepare($sql_query);
        $stmt->execute();
        $stmt->close();
        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'Challan no updated successfully',
            );
        } else {
            $result = array(
                'success' => false,
                'message' => 'Challan not update',
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

    public function show_invoice()
    {
        $sql_query = "SELECT * FROM `invoice`" ;
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
}


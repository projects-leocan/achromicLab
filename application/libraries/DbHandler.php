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

    public function fatchSelectedCompany($company_id, $start_date, $end_date, $lastPacketId, $rowPerPage,$search_text)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        if ($lastPacketId == "null" || $lastPacketId != null) {
            $lastPacketId = -1;
        }

        $query_params = "p.is_delete = 0";
        $query_lastPacketId = "";


        if ($company_id > 0 && isset($start_date) && isset($end_date)) {           

                $query_params = "p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        } 
        else if (($company_id > 0) || $lastPacketId == "null" ) 
        {

            $query_params = "p.company_id = '$company_id' AND p.is_delete = 0";

        } 
        else if (isset($start_date) && isset($end_date)) 
        {
            $query_params = "p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        } 
        else 
        {
            $query_params = "p.is_delete = 0";
        }


        if ($lastPacketId > 0) {

            $query_lastPacketId = " and p.packet_id < $lastPacketId";
        }
        else{
            $query_lastPacketId = "";
        }

        $sql_query = "SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE $query_params $query_lastPacketId ORDER BY p.packet_id DESC limit $rowPerPage ) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC";

        // echo $sql_query;

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

    // get all packet data
    public function fatchPacketDetails($rowPerPage, $lastPacketId)
    {
        if($lastPacketId == "null" || $lastPacketId == null){
            $lastPacketId = 0;
        }

        $query_params = " p.is_delete = 0";
        $query_lastPacketId = "";

        if ($lastPacketId > 0) {

            $query_lastPacketId = " and p.packet_id < $lastPacketId";
        }
        else{
            $query_lastPacketId = "";
        }

        $sql_query = "SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE $query_params $query_lastPacketId ORDER BY p.packet_id DESC limit $rowPerPage ) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC";
        

        // echo $sql_query;

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

    // get columns sum in footer
    public function getAllPacketSum($start_date,$end_date,$company_id)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        // filter logic
        $query_param_CO_SD_ED = "p.is_delete = 0";
        if ($company_id > 0 && isset($start_date) && isset($end_date)) {

            $query_param_CO_SD_ED = " p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 ";
       
        }
        else if($company_id > 0)
        {
            $query_param_CO_SD_ED = " p.company_id = '$company_id' AND p.is_delete = 0 ";
        } 
        elseif (isset($start_date) && isset($end_date))
        {
            $query_param_CO_SD_ED = " p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        }
        else{
            $query_param_CO_SD_ED = "p.is_delete = 0";
        }
        

        $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet p WHERE ". $query_param_CO_SD_ED; 

        // $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat from (SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE $query_param_CO_SD_ED ORDER BY p.packet_id DESC) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC) as all_packet";
        // echo $sql_query;

        $count_result = $this->conn->query($sql_query);
        $count_row = $count_result->fetch_assoc();

        $total_piece = intval($count_row['total_piece']);
        $total_carat = floatval($count_row['total_carat']);
        $none_process_piece = intval($count_row['none_process_piece']);
        $none_process_carat = floatval($count_row['none_process_carat']);
        $broken_piece = intval($count_row['broken_piece']);
        $broken_carat = floatval($count_row['broken_carat']);
        $final_carat = floatval($count_row['final_carat']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'total_piece' => $total_piece,
                'total_carat' => $total_carat,
                'none_process_piece' => $none_process_piece,
                'none_process_carat' => $none_process_carat,
                'broken_piece' => $broken_piece,
                'broken_carat' => $broken_carat,
                'final_carat' => $final_carat,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function getAllPacketSumOLD($start_date,$end_date,$company_id,$search_text)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        if ($company_id > 0 && isset($start_date) && isset($end_date)) {

            if($search_text){

                $sql_query = "SELECT SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat
                FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";

            }else{

                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet p WHERE p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 "; 
            }

        }
        else if($company_id > 0)
        {
            if($search_text){

                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = '$company_id' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";


            }else{

                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet p WHERE p.company_id = '$company_id' AND p.is_delete = 0 "; 
            }

        }
        elseif (isset($start_date) && isset($end_date)) {
            
            if($search_text){
                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat
                FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";
            }else{

                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet p  WHERE p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
            }
        }
        else{

            if($search_text){
                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat
                    FROM packet p
                    JOIN company c ON p.company_id = c.company_id
                    WHERE
                        p.is_delete = 0 
                        AND (
                            p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                        )";
            }else{
                
                $sql_query = "SELECT SUM( packet_dimond_qty) as total_piece, SUM(packet_dimond_caret) as total_carat, SUM(pending_process_diamond_qty) as none_process_piece, SUM(pending_process_diamond_carat) as none_process_carat, SUM(broken_diamond_qty) as broken_piece, SUM(broken_diamond_carat) as broken_carat, SUM(price_per_carat) as final_carat FROM packet where is_delete = 0";
            }

        }

        // echo $sql_query;

        $count_result = $this->conn->query($sql_query);
        $count_row = $count_result->fetch_assoc();

        $total_piece = intval($count_row['total_piece']);
        $total_carat = floatval($count_row['total_carat']);
        $none_process_piece = intval($count_row['none_process_piece']);
        $none_process_carat = floatval($count_row['none_process_carat']);
        $broken_piece = intval($count_row['broken_piece']);
        $broken_carat = floatval($count_row['broken_carat']);
        $final_carat = floatval($count_row['final_carat']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'total_piece' => $total_piece,
                'total_carat' => $total_carat,
                'none_process_piece' => $none_process_piece,
                'none_process_carat' => $none_process_carat,
                'broken_piece' => $broken_piece,
                'broken_carat' => $broken_carat,
                'final_carat' => $final_carat,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    // search with filter 
    public function searchPackets($company_id, $start_date,$end_date,$lastPacketId,$rowPerPage,$search_text)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        if ($lastPacketId == "null") {
            $lastPacketId = -1;
        }

        $query_params = "p.is_delete = 0";
        $query_lastPacketId = "";


        if ($company_id > 0 && isset($start_date) && isset($end_date)) {           

                $query_params = "p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        } 
        else if ($company_id > 0) 
        {

            $query_params = "p.company_id = '$company_id' AND p.is_delete = 0";

        } 
        else if (isset($start_date) && isset($end_date)) 
        {
            $query_params = "p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        } 
        else 
        {
            $query_params = "p.is_delete = 0";
        }


        if ($lastPacketId > 0) {

            $query_lastPacketId = " and p.packet_id < $lastPacketId";
        }
        else{
            $query_lastPacketId = " and";
        }

        $sql_query = "SELECT tempTb.*,MAX(ie.challan_no) AS challan_no,ie.delivery_date
        FROM(SELECT p.*,c.company_name FROM packet p LEFT JOIN company c ON p.company_id = c.company_id
        WHERE
            $query_params $query_lastPacketId (
                p.packet_no LIKE CONCAT('%', '". $search_text ."', '%') OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%') OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%') OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%') OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%') OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%') OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%') OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%') OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
            )ORDER BY p.packet_id DESC
        ) AS tempTb
        LEFT JOIN invoice_entry ie ON
            ie.packet_no = tempTb.packet_no
        GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id
        ORDER BY tempTb.packet_id DESC LIMIT $rowPerPage";

        // echo $sql_query;

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

    // fetch total packets count for all packet
    public function fetchCount()
    {

        $sql_query = "SELECT COUNT(*) as total_count FROM packet p WHERE p.is_delete = 0";
        // $sql_query = "SELECT COUNT(*) as total_count FROM (SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE is_delete = 0 ORDER BY p.packet_id DESC ) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC) AS totalCount";
        

        // echo $sql_query;

        $count_result = $this->conn->query($sql_query);
        $total_count = intval($count_result->fetch_assoc()['total_count']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'packet_count' => $total_count,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    // fetch count for filter 
    public function getCountForFilter($start_date,$end_date,$company_id)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        $query_param = "p.is_delete = 0";

        if ($company_id > 0 && isset($start_date) && isset($end_date)) {
            $query_param = " p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        }
        else if($company_id > 0)
        {
            $query_param = " p.company_id = '$company_id' AND p.is_delete = 0";
        }
        elseif (isset($start_date) && isset($end_date)) {
            $query_param = "p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        }
        else{
            $query_param = "p.is_delete = 0";
        }

        

        $sql_query = "SELECT COUNT(*) as total_count FROM packet p WHERE ".$query_param;
        // $sql_query = "SELECT count(*) as total_count from (SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE $query_param ORDER BY p.packet_id DESC) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC) as total_count";
        

        // echo $sql_query;
        $count_result = $this->conn->query($sql_query);
        $total_count = intval($count_result->fetch_assoc()['total_count']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'packet_count' => $total_count,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    // fetch count for filter with seach 
    public function getCountForFilterWithSearch($start_date,$end_date,$company_id,$search_text)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        $query_param = "p.is_delete = 0";

        if ($company_id > 0 && isset($start_date) && isset($end_date)) {
            $query_param = " p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        }
        else if($company_id > 0 && isset($search_text))
        {
            $query_param = " p.company_id = '$company_id' AND p.is_delete = 0";
        }
        elseif (isset($start_date) && isset($end_date)) {
            $query_param = "p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
        }
        else{
            $query_param = "p.is_delete = 0";
        }

        // $sql_query = "SELECT COUNT(*) as total_count FROM packet p WHERE ".$query_param;

        $sql_query = "SELECT count(*) as total_count from (SELECT tempTb.*,MAX(ie.challan_no) as challan_no,ie.delivery_date from (SELECT p.*, (SELECT company_name FROM company WHERE company_id = p.company_id) as company_name from packet p WHERE $query_param ORDER BY p.packet_id DESC) As tempTb LEFT JOIN invoice_entry ie ON ie.packet_no = tempTb.packet_no GROUP BY ie.delivery_date,tempTb.packet_no,tempTb.packet_id ORDER BY tempTb.packet_id DESC) as total_count";
        
        // echo $sql_query;
        $count_result = $this->conn->query($sql_query);
        $total_count = intval($count_result->fetch_assoc()['total_count']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'packet_count' => $total_count,
            );
        } else {
            $result = array(
                'success' => false,
            );
        }
        return $result;
    }

    public function getCountForFilterOLD($start_date,$end_date,$company_id,$search_text)
    {

        if ($company_id == "null") {
            $company_id = -1;
        }

        if ($company_id > 0 && isset($start_date) && isset($end_date)) {

            if($search_text){

                $sql_query = "SELECT COUNT(*) AS total_count
                FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";

            }else{
                $sql_query = "SELECT COUNT(*) as total_count FROM packet p
                    WHERE p.company_id = '$company_id' AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
            }

        }
        else if($company_id > 0)
        {
            if($search_text){

                $sql_query = "SELECT COUNT(*) AS total_count
                FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = '$company_id' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";


            }else{
                $sql_query = "SELECT COUNT(*) as total_count 
                FROM packet p
                WHERE p.company_id = '$company_id' AND p.is_delete = 0";
            }

        }
        elseif (isset($start_date) && isset($end_date)) {
            
            if($search_text){
                $sql_query = "SELECT COUNT(*) AS total_count
                FROM packet p
                JOIN company c ON p.company_id = c.company_id
                WHERE
                    p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0 
                    AND (
                        p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                        OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                    )";
            }else{
            $sql_query = "SELECT COUNT(*) as total_count 
            FROM packet p
            WHERE p.company_id = p.company_id AND date BETWEEN '$start_date' and '$end_date' AND p.is_delete = 0";
            }
        }
        else{

            if($search_text){
                $sql_query = "SELECT COUNT(*) AS total_count
                    FROM packet p
                    JOIN company c ON p.company_id = c.company_id
                    WHERE
                        p.is_delete = 0 
                        AND (
                            p.packet_no LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.packet_dimond_caret LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.packet_dimond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.pending_process_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.pending_process_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.broken_diamond_qty LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.broken_diamond_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR p.price_per_carat LIKE CONCAT('%', '". $search_text ."', '%')
                            OR c.company_name LIKE CONCAT('%', '". $search_text ."', '%')
                        )";
            }else{
                $sql_query = "SELECT COUNT(*) as total_count FROM packet p WHERE p.is_delete = 0";
            }

        }

        // echo $sql_query;
        $count_result = $this->conn->query($sql_query);
        $total_count = intval($count_result->fetch_assoc()['total_count']);

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'packet_count' => $total_count,
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

        $sql_query = "SELECT * FROM `invoice`";
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

    public function addPacketDetails($company_id, $selectedDate, $packetNum, $quantity, $total_carat, $pending_process_qty_diamond, $pending_process_qty_carat, $broken_qty_diamond, $broken_qty_carat, $cube_qty, $cube_time, $price_per_carat)
    {
        $sql_query;
        if (($cube_time == null) || ($cube_time) == "") {
            $sql_query = "insert into `packet` (`company_id`,`date`,`packet_no`,`packet_dimond_qty`,`packet_dimond_caret`,`pending_process_diamond_qty`,`pending_process_diamond_carat`,`broken_diamond_qty`,`broken_diamond_carat`,`cube_qty`,`price_per_carat`) VALUES ($company_id,'$selectedDate',$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,$price_per_carat)";
        } else {
            $sql_query = "insert into `packet` (`company_id`,`date`,`packet_no`,`packet_dimond_qty`,`packet_dimond_caret`,`pending_process_diamond_qty`,`pending_process_diamond_carat`,`broken_diamond_qty`,`broken_diamond_carat`,`cube_qty`,`cube_time`,`price_per_carat`) VALUES ($company_id,'$selectedDate',$packetNum,$quantity,$total_carat,$pending_process_qty_diamond,$pending_process_qty_carat,$broken_qty_diamond,$broken_qty_carat,$cube_qty,'$cube_time',$price_per_carat)";
        }

        // echo  $sql_query;
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

            $query_parts[] = "('" . $companyId . "', '" . $date . "', '" . $packetNo . "', '" . $packetDimondQty . "', '" . $packetDimondCaret . "', '" . $finalCaret . "')";
        }
        $query = implode(',', $query_parts);
        $sql_query .= implode(',', $query_parts);

        // $stmt = $this->conn->prepare($sql_query);
        // $stmt->execute();
        // $stmt->close();

        try {
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

    public function invoiceEntry($data)
    {
        $decodedData = json_decode($data, true);
        $sql_query = "INSERT INTO invoice_entry (`packet_no`, `company_id`, `delivery_date`, `challan_no`) VALUES ";

        $query_parts = array();
        foreach ($decodedData['data'] as $entry) {
            $company_id = $entry['company_id'];
            $delivery_date = date('Y-m-d', strtotime(str_replace('/', '-', $entry['delivery_date'])));
            $challan_no = $entry['challan_no'];
            $packet_no = $entry['packet_no'];

            $query_parts[] = "('" . $packet_no . "', '" . $company_id . "', '" . $delivery_date . "', '" . $challan_no . "')";
        }

        $query = implode(',', $query_parts);
        $sql_query .= implode(',', $query_parts);

        try {
            if (mysqli_query($this->conn, $sql_query)) {
                $result = array(
                    'success' => true,
                    'query' => $sql_query,
                    'message' => 'Invoice Entry Saved',
                );
            } else {
                $result = array(
                    'success' => false,
                    'message' => 'something went wrong ',
                );
            }
        } catch (Exception $e) {
            $result = array(
                'success' => false,
                'message' => 'something went wrong ',
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

        if ($sql_query) {
            $result = array(
                'success' => true,
                'message' => 'packet deleted successfully',
            );
        } else {
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



    public function deleteCompany($id)
    {
        $sql_query = "delete from `company` where `company_id` ='$id' ";
        // $stmt = $this->conn->prepare($sql_query);
        // $stmt->execute();
        // $stmt->close();

        if (mysqli_query($this->conn, $sql_query)) {
            $result = array(
                'success' => true,
                'message' => 'Company deleted successfully'
            );
        } else {
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

    public function updateCompany($id, $company_name)
    {
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

    public function updateChallan($updateChallanNo)
    {
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

        if ($response[0]['COUNT(*)'] > 0) { // Access the count value from the response array
            $result = array(
                'success' => true,
                'response' => $response,
                'message' => 'Company name already exists'
            );
        } else {
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
        $sql_query = "SELECT * FROM `invoice`";
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
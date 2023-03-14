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

        $sql_query = "SELECT company_name FROM `company`";
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


    public function category($name)
    {

        $sql_query = "insert into `company` (`company_name`) VALUES ('$name')";
        $stmt = $this->conn->prepare($sql_query);
        // $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->close();

        // $stmt1 = $this->conn->prepare("SELECT @is_done AS is_done,@f_id AS f_id");
        // $stmt1->execute();
        // $stmt1->bind_result($is_done, $f_id);
        // $stmt1->fetch();
        // $stmt1->close();

        // if ($is_done) {
        //     $result = array(
        //         'success' => true,
        //         'user_id' => $f_id,
        //         'message' => 'CATEGORY_SUCCESS'
        //     );
        // } else {
        //     $result = array(
        //         'success' => false,
        //         'message' => 'CATEGORY_FAIL'
        //     );
        // }
        // return $result;
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
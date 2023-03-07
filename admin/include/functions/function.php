<?php
    // THIS FUNCTION MAKE THE TITLE OF THE HEADER CHANGE AUTOMATIC DEPEND ON THE VALUE 
    // OF THE VARIABLE $setTitle THAT WE DIFINE IT IN EVERY PAGE    
    function getTitle() {
        // TAKE THE ATTRIBUTE FROM ANY PAGE AND MAKE DISPLAYED IN THE TITLE OF THE HEADER
        global $setTitle;
        if (isset($setTitle)) {
            echo $setTitle;
        }
        // IF THE ATTRIBUTE NOT FOUNT MAKE THIS AS DEFAULT
        else {
            echo 'Mo_Maresh Shopping';
        }
    }

    //--------------------------------------------------------

    // THIS FUNCTION SHOW YOU MESSAGE ERROR AND RETURN YOU TO THE HOME PAGE IF YOU TRIED TO GO TO SOME PAGES THAT YOU CAN EXCEPT BY REQUIRED FROM THE FORM
    // THIS TAKE TOW PARAMETER ONE FOR THE MESSAGE ERROR AND THE OTHER FOR THE SECONDS BEFOR REDIRCT YOU TO THE HOME PAGE
    function redirectToHome($errorMessage, $url= null, $seconds = 3) {
        if ($url === null) {
            $url = 'index.php';
        }
        else {
            if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                $url = $_SERVER['HTTP_REFERER'];
            }
            else {
                $url = 'index.php';
            }
        }
        echo "$errorMessage";
        echo "<div class='alert alert-info'>YOU WILL BE RETURNED AFTER $seconds</div>";

        header("refresh:$seconds; url=$url");
        exit();
    }

    // THIS FUNCTION CHECK IF THE USER IS IN THE DATABASE, WE MAKE THE ID VARIABLE BECUASE WE WANT TO CKECK FOR ALL USERNAME EXCEPT THIS THAT HAVE THE ID
    function checkUser($selection, $table, $value, $id = 0) {
        global $conn;
        $statment = $conn->prepare("SELECT $selection FROM $table WHERE $selection = ? && user_id != ?");
        $statment->execute(array($value, $id));

        return $statment->rowcount();
    }

    // THIS CHECK IF THE SUPPLIER IS IN THE DATABASE TO MAKE THE RELTIONAL INEGIRTY
    function checkSup($selection, $table, $value) {
        global $conn;
        $statment = $conn->prepare("SELECT $selection FROM $table WHERE $selection = ?");
        $statment->execute(array($value));

        return $statment->rowcount();
    }

    // v2
    //this function will count the statistic from tables
    // TAKE THE TABLE NAME AND RETURN HOW MANY ROWS IN IT
    function countItems($table) {
        global $conn;

        $statment = $conn->prepare("SELECT COUNT(*) FROM $table");
        $statment->execute();
        $row = $statment->fetchColumn();
        return $row;
    }

    function countSupItems($table, $pro_type, $sup_id) {
        global $conn;

        $statment = $conn->prepare("SELECT COUNT(*) FROM $table WHERE type = '$pro_type' AND sup_id = $sup_id");
        $statment->execute();
        $row = $statment->fetchColumn();
        return $row;
    }

    //this function will count the binding items from tables
    // THIS COUNT THE USER WHO ARE NOT ACTIVE YET 
    function countBind($table) {
        global $conn;
        $statment = $conn->prepare("SELECT COUNT(*) FROM $table WHERE reg_status = 0");
        $statment->execute();
        $row = $statment->fetchColumn();
        return $row;
    }

    // Function get the latest registerd in the table
    function getLatest($table, $order, $limit = 5) {
        global $conn;
        $statment = $conn->prepare("SELECT * FROM $table ORDER BY $order DESC LIMIT $limit");
        $statment->execute();
        $rows = $statment->fetchAll();
        return $rows;
    }

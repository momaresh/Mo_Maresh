<?php

    // THIS FUNCTION MAKE THE TITLE OF THE HEADER CHANGE AUTOMATIC DEPEND ON THE VALUE 
    // OF THE VARIABLE $setTitle THAT WE DIFINE IT IN EVERY PAGE    
    function getTitle() {
        global $setTitle;
        if (isset($setTitle)) {
            echo $setTitle;
        }
        else {
            echo 'Mo_Maresh Shopping';
        }
    }

    // THIS FUNCTION SHOW YOU MESSAGE ERROR AND RETURN YOU TO THE HOME PAGE IF YOU TRIED TO GO TO SOME PAGES THAT YOU CAN EXCEPT BY REQUIRED FROM THE FORM
    // THIS TAKE TOW PARAMETER ONE FOR THE MESSAGE ERROR AND THE OTHER FOR THE SECONDS BEFOR REDIRCT YOU TO THE HOME PAGE
    function redirectToHome($errorMessage, $url= null, $seconds = 3) {
        if ($url === null) {
            $url = 'Admin/index.php';
        }
        else {
            if ($url == 'back') {
                if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {
                    $url = $_SERVER['HTTP_REFERER'];
                }
                else {
                    $url = 'main.php';
                }
            }
        }
        echo "$errorMessage";
        echo "<div class='alert alert-info'>YOU WILL BE RETURNED AFTER $seconds</div>";

        header("refresh:$seconds; url=$url");
        exit();
    }


    function checkUser($selection, $table, $value) {
        global $conn;
        $statment = $conn->prepare("SELECT $selection FROM $table WHERE $selection = ? && user_id != ?");
        $statment->execute(array($value, $_SESSION['USER_ID']));

        return $statment->rowcount();
    }

    //this function will count the statistic from tables
    function countItems($table) {
        global $conn;
        $statment = $conn->prepare("SELECT COUNT(*) FROM $table");
        $statment->execute();
        $row = $statment->fetchColumn();
        return $row;
    }

    //FUNCTION PRINT RATE
    function getRate($rate) {
        if($rate == 5):
        ?>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
        <?php
        elseif($rate == 4):
        ?>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-regular fa-star"></i>
        <?php
        elseif($rate == 3):
        ?>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
        <?php
        elseif($rate == 2):
        ?>
            <i class="fa-solid fa-star"></i>
            <i class="fa-solid fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
        <?php
        elseif($rate == 1):
        ?>
            <i class="fa-solid fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
        <?php
        else:
        ?>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
            <i class="fa-regular fa-star"></i>
        <?php
        endif;
    }

    function star_fill ($rating)
    {
        $rate ='';
        for($i = 0; $i < 5; $i++)
        {
            $star_fill = (($rating > 0) ? "fa-solid" : "fa-regular");
            $rate .="<i class=\"$star_fill fa-star\" style =\"font-size:15px;\"></i>";
            $rating --;
        }
        return $rate;
    }

<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();


    //THIS IF YOU AREADY SIGN WILL CHANGE YOU TO THE DASHBOARD AUTOMATIC
    if (isset($_SESSION['USER_NAME'])) {
        include 'initial.php';

        $prodId = (isset($_GET['prodid'])) &&  is_numeric($_GET['prodid']) ? intval($_GET['prodid']) : 0;

        if (isset($_POST['COMMENT'])) {
            $comment = $_POST['comment'];
            $rate = $_POST['rate'];

            $stmt = $conn->prepare("INSERT INTO COMMENTS(user_id, prod_id, text, rate, date) VALUES(?, ?, ?, ?, now())");
            $stmt->execute(array($_SESSION['USER_ID'], $prodId, $comment, $rate));

        }

        if(isset($_POST['message'])) {
            $name = $_POST['user_name'];
            $email = $_POST['email'];
            $text = $_POST['text'];
    
            $stmt_message = $conn->prepare("INSERT INTO MESSAGES(name, email, text) VALUES(?, ?, ?)");
            $stmt_message->execute(array($name, $email, $text));
        }
        
        redirectToHome('', 'back' ,0);
    }
    else {
        header('location: admin/index.php');
        exit();
    }
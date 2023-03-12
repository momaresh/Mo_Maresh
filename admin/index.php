<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();

    //THIS IF YOU ALREADY SIGN AND YOU ARE ADMIN, WILL CHANGE YOU TO THE DASHBOARD AUTOMATICALLY
    if (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 1) {
        header('Location: dashboard.php');
        exit();
    }
    //THIS IF YOU ALREADY SIGN AND YOU ARE USER, WILL CHANGE YOU TO THE MAIN AUTOMATICALLY
    elseif (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 2) {
        header("Location: ../index.php");
        exit();
    }
    elseif (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 3) {
        header("Location: supplier_dashboard.php");
        exit();
    }

    $noNavbar = ''; // THIS VARIABLE PREVENT THE PAGE FROM THE NAVBAR TO BE INCLUDE
    $setTitle = 'Sign'; // THIS VARIABLE MAKE THE TITLE HEADER OF THE PAGE, WE HAVE MAKE FUNCTION TO DO THIS

    include 'initial.php'; // THIS TO INCLUDE ALL WE NEED LIKE THE HEADER, ROOTS, CONNECTION

    // IF THERE IS A USER COMING FROM POST REQUEST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $v_user_name = $_POST['user'];
        $v_password = $_POST['password'];
        $hash_password = md5($v_password);

        // CHECK IF THE USER EXISTS IN THE DATABASE
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_name = ? AND password = ?");
        $stmt->execute(array($v_user_name, $hash_password));
        $row = $stmt->fetch();


        if ($stmt->rowCount() > 0) {
            // IF I'M EXISTS I WILL MAKE A SESSION 
            $_SESSION['USER_NAME'] = $v_user_name; // WE ADD SESSION FOR THE USER
            $_SESSION['USER_ID'] = $row['user_id'];
            $_SESSION['GROUP_ID'] = $row['group_id'];
            // IF I AM ADMIN GO TO THE DASHBOARD
            if ($row['group_id'] == 1) {
                header('Location: dashboard.php');
                exit();
            }
            // ELSE TO THE MAIN PAGE
            elseif ($row['group_id'] == 2) {
                header("Location: ../index.php");
                exit();
            }
            elseif ($row['group_id'] == 3) {
                header("Location: dashboard.php");
                exit();
            }
        }
        else {
            $error = "The user_name or password NOT correct";
        }
    }
?>
    <!-- start sign in -->
    <div class="signin">
        <form class="fill" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST"> <!-- $_SERVER['PHP_SELF'], To make it go to the same page -->
            <i class="fa-solid fa-fingerprint"></i>

            <span class="error" style="display:block; width: fit-content; margin: auto; font-size: 18px"><?php if(isset($error)) echo $error ?></span>

            <div class="user">
                <input type="text"  name="user" id="user_name" placeholder="Your name" autocomplete="off" required>
                <i class="fa-solid fa-user"></i>
            </div>

            <div class="pass">
                <input type="password" name="password" id="pass" placeholder="Your password" required>
                <i class="fa-solid fa-lock"></i>
            </div>
            
            <input type="submit" value="Sign in" class="btn btn-outline-success rounded-pill fw-bold fs-5">
            <p>Create new account? <a href="signup.php">Sign up</a></p> <!-- If you don't have an account create new account -->
        </form>
    </div>
    <!-- end sign in -->

<?php include $tpl . 'footer.php' // Include the footer with all its links ?>
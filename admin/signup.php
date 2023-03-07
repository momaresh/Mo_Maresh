<?php
    session_start();
    $noNavbar = '';
    include 'initial.php';


    // CHECK IF COMING FROM REQUEST
    if($_POST['sign']):

        $avatarName = $_FILES['user_image']['name'];
        $avatarSize = $_FILES['user_image']['size'];
        $avatarTmp = $_FILES['user_image']['tmp_name'];
        $avatarType  = $_FILES['user_image']['type'];

        // the list of the image type that are allowed
        $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
        $avatarExtension1 = explode('.', $avatarName);
        $avatarExtension2 = end($avatarExtension1);
        $avatarExtension3 = strtolower($avatarExtension2 );

        //  print all the value from the form
        $user_name = $_POST['user_name'];
        $full_name = $_POST['full_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $hash_password = md5($password);
        $birth_date = $_POST['birth_date'];
        $nationality = $_POST['nationality'];

        // Make some valdiation for the form
        // Create array that will take all error
        $errors = array();
        // Make the user name between 4 to 15 character
        if(strlen($user_name) < 4 || strlen($user_name) > 15):
            $errors['user_name2'] = "The user name must be <strong>more than 4 and less than 15</strong>";
        endif;
        // Make the password 8 character and more
        if(strlen($password) < 8):
            $errors['pass2'] = "The password can't be <strong>less than 8 character</strong>";
        endif;
        if(empty($user_name)):
            $errors['user_name1'] = "The user name must not be <strong>empty</strong>";
        endif;
        if(empty($password)):
            $errors['pass1'] = "The password must not be <strong>empty</strong>";
        endif;
        if(empty($email)):
            $errors['email1'] = "The email must not be <strong>empty</strong>";
        endif;
        // Check if the email is exsits in the dadabase
        if(checkUser('email', 'users', $email) > 0):
            $errors['email2'] = "The email is <strong>already exists</strong>";
        endif;
        // Check if the user name is exsits in the dadabase
        if(checkUser('user_name', 'users', $user_name) > 0):
            $errors['user_name3'] = "The user_name is <strong>already exists</strong>";
        endif;
        if(empty($full_name)):
            $errors['name1'] = "The full name must not be <strong>empty</strong>";
        endif;
        if(!empty($avatarName) && !in_array($avatarExtension3, $avatarAllowedExtension)):
            $errors['image'] = "The extension not <strong>allowed</strong>";
        endif;

        // IF no error occur update in the database
        if(empty($errors)):
            if(!empty($avatarName)) {
                $avatar = rand(1, 1000000) . '_' . $avatarName;
                move_uploaded_file($avatarTmp, "..\docs\images\user_images\\" . $avatar);
            }

            // create the user and change it to the main
            $stmt = $conn->prepare("INSERT INTO users (user_name, full_name, email, password, birth_date, nationality, date, image)
                                    VALUES (:USER, :FULL, :EMAIL, :PASS, :BIRTH, :NATION, now(), :IMAGE)");
            $stmt->execute(array(
                'USER' => $user_name,
                'FULL' => $full_name,
                'EMAIL' => $email,
                'PASS' => $hash_password,
                'BIRTH' => $birth_date,
                'NATION' => $nationality,
                'IMAGE'  => $avatar
            ));

            $stmt = $conn->prepare("SELECT * FROM USERS WHERE USER_NAME = ?");
            $stmt->execute(array($user_name));
            $row = $stmt->fetch();

            $_SESSION['USER_NAME'] = $user_name;
            $_SESSION['USER_ID'] = $row['user_id'];
            $_SESSION['GROUP_ID'] = $row['group_id'];
            header('Location: ../index.php');
            exit();       
        endif;
    endif;
?>

    <div class="signup">
        <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">CREATE ACCOUNT</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" enctype="multipart/form-data" class="profile">
            <div class="image">
                <img src="./Themes/IMAGES/img.png" alt="">
                <span></span>
                <i class="fa-solid fa-camera"></i>
                <input style="opacity: 0" type="file" name="user_image" id="user-image">
                <span class="error">
                    <?php 
                    if(isset($errors['image'])) echo '* ' . $errors['image'] 
                    ?>
                </span>
            </div>

            <div class="info">
                <div class="name">
                    <label>Name:</label>
                    <input type="text" name="full_name" required='required' class="input" placeholder='Full Name'>
                    <span class="error">
                        <?php 
                        if(isset($errors['name1'])) echo '* ' . $errors['name1'] 
                        ?>
                    </span>
                </div>
                <div class="user-name">
                    <label>User name:</label>
                    <input type="text" name="user_name" required='required' placeholder='User Name' class="input">
                    <span class="error">
                        <?php 
                        if(isset($errors['user_name1'])) echo '* ' . $errors['user_name1']; 
                        elseif(isset($errors['user_name2'])) echo '* ' . $errors['user_name2'];
                        elseif(isset($errors['user_name3'])) echo '* ' . $errors['user_name3'];
                        ?>
                    </span>
                </div>
                <div class="email">
                    <label>Email:</label>
                    <input type="email" name="email" required='required' placeholder='Email' class="input">
                    <span class="error">
                        <?php 
                        if(isset($errors['email1'])) echo '* ' . $errors['email1']; 
                        elseif(isset($errors['email2'])) echo '* ' . $errors['email2'];
                        ?>
                    </span>
                </div>
                <div class="pass">
                    <label>Password:</label>
                    <input type="password" name="password" required='required' placeholder='Password' class="input">
                    <span class="error">
                        <?php 
                        if(isset($errors['pass1'])) echo '* ' . $errors['pass1']; 
                        elseif(isset($errors['pass2'])) echo '* ' . $errors['pass2'];
                        ?>
                    </span>
                </div>
                <div class="birth">
                    <label>Birth date:</label>
                    <input type="date" name="birth_date" class="input">
                </div>
                <div class="nation">
                    <label>Nationality:</label>
                    <input list="nationality" name="nationality" class="input">
                </div>
                <datalist id="nationality">
                    <option value="Yemeni">
                    <option value="Saudi">
                </datalist>

                <input class="submit" name="sign" type="submit" value="Create">
            </div>
        </form>

    </div>
<?php

    include $tpl . 'header.php';
?>
<?php
    session_start();
    $setTitle = 'Edit Profile';
    if (isset($_SESSION['USER_NAME'])) {
        include 'initial.php';

        $stmt = $conn->prepare("SELECT * FROM USERS WHERE user_id = ?");
        $stmt->execute(array($_SESSION['USER_ID']));
        $row = $stmt->fetch();

        if ($stmt->rowcount() > 0):

            if(isset($_POST['update'])):

                $found = false; // This is for check if the image not empty
                
                $avatarName = $_FILES['user_image']['name'];
                $avatarSize = $_FILES['user_image']['size'];
                $avatarTmp = $_FILES['user_image']['tmp_name'];
                $avatarType  = $_FILES['user_image']['type'];
                if (empty($avatarName)):
                    $found = true;
                endif;

                // the list of the image type that are allowed
                $avatarAllowedExtension = array("jpeg", "jpg", "png", "gif");
                $avatarExtension1 = explode('.', $avatarName);
                $avatarExtension2 = end($avatarExtension1);
                $avatarExtension3 = strtolower($avatarExtension2 );


                //  print all the value from the form
                $id = $_POST['user_id'];
                $user_name = $_POST['user_name'];
                $full_name = $_POST['full_name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $hash_password = md5($password);
                $birth_date = $_POST['birth_date'];
                $nationality = $_POST['nationality'];

                // Make some validation for the form
                // Create array that will take all error
                $errors = array();
                if(strlen($user_name) < 4 || strlen($user_name) > 15):
                    $errors['user_name1'] = "The user name must be <strong>more than 4 and less than 15</strong>";
                endif;
                if(strlen($password) < 8):
                    $errors['pass1'] = "The password can't be <strong>less than 8 character</strong>";
                endif;
                if(empty($user_name)):
                    $errors['user_name2'] = "The user name must not be <strong>empty</strong>";
                endif;
                if(empty($password)):
                    $errors['pass2'] = "The password must not be <strong>empty</strong>";
                endif;
                if(empty($email)):
                    $errors['email1'] = "The email must not be <strong>empty</strong>";
                endif;
                if(empty($full_name)):
                    $errors['name'] = "The full name must not be <strong>empty</strong>";
                endif;
                if(!empty($avatarName) && !in_array($avatarExtension3, $avatarAllowedExtension)):
                    $errors['image'] = "The extension not <strong>allowed</strong>";
                endif;
                if(checkUser('user_name', 'users', $user_name) > 0):
                    $errors['user_name3'] = "The user name is already <strong>exists</strong>";
                endif;
                if(checkUser('email', 'users', $email) > 0):
                    $errors['email2'] = "The email is already <strong>exists</strong>";
                endif;

                // IF no error occur update in the database
                if(empty($errors)):
                    $stmt = $conn->prepare("UPDATE users 
                                                    SET user_name =:USER, 
                                                        full_name =:FULL, 
                                                        email =:EMAIL, 
                                                        password =:PASS, 
                                                        birth_date =:BIRTH, 
                                                        nationality =:NATION,
                                                        last_update = now()
                                                    WHERE
                                                        user_id = :ID");
                    $stmt->execute(array(
                        'USER' => $user_name,
                        'FULL' => $full_name, 
                        'EMAIL' => $email, 
                        'PASS' => $hash_password, 
                        'BIRTH' => $birth_date, 
                        'NATION' => $nationality,
                        'ID' => $id
                    ));

                    if (!$found):
                        $avatar = rand(1, 1000000) . '_' . $avatarName;
                        move_uploaded_file($avatarTmp, "docs\images\user_images\\" . $avatar);
                        $stmt = $conn->prepare("UPDATE users 
                                                        SET
                                                            image = :IMAGE
                                                        WHERE
                                                            user_id = :ID");
                        $stmt->execute(array(
                            'IMAGE' => $avatar,
                            'ID' => $id
                        ));
                    endif;

                    echo "<script>
                        alert('SUCCESSFULLY UPDATED...!');
                        window.open('edit.php', '_self');
                        </script>";
                endif;
            endif;        

        ?>
            <h1 class="use-a-lot2 mt-4">Edit Your Profile</h1>
            <form action="" method="POST" enctype="multipart/form-data" class="profile">
                <div class="image">
                    <img src="<?php echo $user_img . $row['image'] ?>" alt=""> <!-- $user_img variable is come from the initial file that contain the root of the image -->
                    <span></span>
                    <i class="fa-solid fa-camera fa-3x"></i>
                    <input style="opacity: 0" type="file" name="user_image" id="user-image">

                </div>

                <div class="info">
                    <input type="hidden" name="user_id" value="<?php echo $row['user_id'] ?>">
                    <div class="name">
                        <label>Name:</label>
                        <input type="text" name="full_name" value="<?php echo $row['full_name'] ?>" class="input" required='required'>
                        <span class="error">
                            <?php 
                            if(isset($errors['name'])) echo '* ' . $errors['name'] 
                            ?>
                        </span>
                    </div>
                    <div class="user-name">
                        <label>User name:</label>
                        <input type="text" name="user_name" required='required'  value="<?php echo $row['user_name'] ?>" class="input">
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
                        <input type="email" name="email"  value="<?php echo $row['email'] ?>" class="input" required='required'>
                        <span class="error">
                            <?php 
                            if(isset($errors['email1'])) echo '* ' . $errors['email1']; 
                            elseif(isset($errors['email2'])) echo '* ' . $errors['email2'];
                            ?>
                        </span>
                    </div>
                    <div class="pass">
                        <label>Password:</label>
                        <input type="password" name="password"  value="<?php echo $row['password'] ?>" class="input" required='required'>
                        <span class="error">
                            <?php 
                            if(isset($errors['pass1'])) echo '* ' . $errors['pass1']; 
                            elseif(isset($errors['pass2'])) echo '* ' . $errors['pass2'];
                            ?>
                        </span>
                    </div>
                    <div class="birth">
                        <label>Birth date:</label>
                        <input type="date" name="birth_date" value="<?php echo $row['birth_date'] ?>" class="input">
                    </div>
                    <div class="nation">
                        <label>Nationality:</label>
                        <input list="nationality" name="nationality" value="<?php echo $row['nationality'] ?>" class="input">
                    </div>
                    <datalist id="nationality">
                        <option value="Yemeni">
                        <option value="Saudi">
                    </datalist>

                    <input class="submit" name="update" type="submit" value="Save">
                </div>
            </form>
        <?php 
        else:
            echo "<script>
                alert('YOU ARE NOT FOUND');
                window.open('index.php', '_self');
                </script>";
        endif;

        include($tpl . "script_only.php");
    }

    else {
        header('Location: Admin/index.php');
        exit();
    }

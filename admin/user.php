<?php
    session_start();
    // ONLY THE ADMIN CAN GO HERE
    if (isset($_SESSION['USER_NAME']) && ($_SESSION['GROUP_ID'] == 1 || $_SESSION['GROUP_ID'] == 3)) {
        include 'initial.php';

        $do = (isset($_GET['do']) ? $_GET['do'] : 'Manage');     

        if ($do == 'Manage') { ?>
        <div class="container mt-5">
            <h3 class="use-a-lot2 mb-2">Users</h3>

            <form class="search" action="" method='POST'>
                <input type="text" name="user_name" placeholder="Search by user name" id="search">
                <input type="submit" name="search" value="Search" id="button">
            </form>

            <a href="?do=Add" class="btn btn-primary mb-3">ADD USER</a>
            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <tr style="background-color: #19283f; color: white">
                        <th>User_Id</th>
                        <th>User_Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Full_Name</th>
                        <th>Birth_Date</th>
                        <th>Nationality</th>
                        <th>Insert_Date</th>
                        <th>Last_Update</th>
                        <th>Control</th>
                    </tr>
                    <?php 

                    // THIS FOR THE NON ACTIVE USER WE MAKE THEM SHARE THE SAME PAGE WITH ACTIVE USERS
                    // AND THIS CODE WILL DIFFER BETWEEN THEM
                    $bind = '';
                    if(isset($_GET['page'])) {
                        $bind = 'AND reg_status = 0';
                    }

                    $search = ''; 
                    if(isset($_POST['search'])) {
                        $user_name = $_POST['user_name'];
                        if(!empty($user_name)) {
                            $search = "AND user_name LIKE '%$user_name%'";
                        }
                    }

                    $stmt = $conn->prepare("SELECT * FROM users WHERE group_id != 1 $bind $search"); // We will NOT display the admins in the page
                    $stmt->execute();
                    
                    if($stmt->rowCount() > 0) {
                        $rows = $stmt->fetchAll();
                        foreach($rows as $row): ?>
                            <tr>
                                <td><?php echo $row['user_id']; ?></td>
                                <td><?php echo $row['user_name']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['password']; ?></td>
                                <td><?php echo $row['full_name']; ?></td>
                                <td><?php echo $row['birth_date']; ?></td>
                                <td><?php echo $row['nationality']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['last_update']; ?></td>
                                <td>
                                    <a href="?do=Edit&userid=<?php echo $row['user_id'];?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px;">Edit</a>
                                    <a href="?do=Delete&userid=<?php echo $row['user_id'];?>" class="btn confirm" style="background-color: #ff6a00; margin-bottom: 5px;">Delete</a>
    
                                    <?php // if is not active will display the button for activate
                                        if ($row['reg_status'] === 1):
                                            echo "<a href='?do=NonActive&userid=$row[user_id]' class='btn' style='background-color: var(--main-color); color: white; margin-bottom: 5px;'>Nonactive</a>";
                                        endif;
                                    ?>
                                    
                                    <?php // if is not active will display the button for activate
                                        if ($row['reg_status'] === 0):
                                            echo "<a href='?do=Active&userid=$row[user_id]' 
                                            class=btn style='background-color: #4eb67f'>Active</a>";
                                        endif;
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach;
                    }
                    else {
                        echo "<div class='container text-center alert alert-info'> There is no user.....!</div>";
                    }
                    ?>
                </table>
            </div>
        </div>

        <?php
        }
        elseif($do == 'Add'){ 
            if(isset($_POST['add'])):
                //  print all the value from the form
                $user_name = $_POST['user_name'];
                $full_name = $_POST['full_name'];
                $email = $_POST['email'];
                $password = $_POST['password'];
                $hash_password = md5($password);
                $birth_date = $_POST['birth_date'];
                $nationality = $_POST['nationality'];
                $role = $_POST['group'];
                //----------------------
                $name = $_FILES['user_image']['name'];
                $type = $_FILES['user_image']['type'];
                $tmp_name = $_FILES['user_image']['tmp_name'];
                $error = $_FILES['user_image']['error'];
                $size = $_FILES['user_image']['size'];

                $valid_extension = array('png', 'jpg', 'jpeg', 'gif');

                $explode_array = explode('.', $name);
                $ext = strtolower(end($explode_array));

                // Make some validation for the form
                // Create array that will take all error
                $errors = array();

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
                // Check if the email is exists in the database
                if(checkUser('email', 'users', $email) > 0):
                    $errors['email2'] = "The email is <strong>already exists</strong>";
                endif;
                // Check if the user name is exists in the database
                if(checkUser('user_name', 'users', $user_name) > 0):
                    $errors['user_name3'] = "The user_name is <strong>already exists</strong>";
                endif;
                if(empty($full_name)):
                    $errors['name1'] = "The full name must not be <strong>empty</strong>";
                endif;
                if(!empty($name) && !in_array($ext, $valid_extension)):
                    $errors['image'] = "The extension not <strong>allowed</strong>";
                endif;

                // IF no error occur update in the database
                if(empty($errors)):
                    if(empty($name)) $name = "img.png";
                    move_uploaded_file($tmp_name, "..\docs\images\user_images\\" . $name);

                    try {
                        $stmt = $conn->prepare("INSERT INTO users (user_name, full_name, email, password, birth_date, nationality, group_id, reg_status, image, date)
                                                VALUES (:USER, :FULL, :EMAIL, :PASS, :BIRTH, :NATION, :ROLE, 1, :IMAGE, now())");
                        $stmt->execute(array(
                            'USER' => $user_name,
                            'FULL' => $full_name,
                            'EMAIL' => $email,
                            'PASS' => $hash_password,
                            'BIRTH' => $birth_date,
                            'NATION' => $nationality,
                            'ROLE' => $role,
                            'IMAGE' => $name
                        ));
                        echo "<script>
                            alert('" . $stmt->rowcount() . " RECORD INSERTED...!');
                            window.open('user.php', '_self');
                            </script>";
                    }
                    catch(Exception $e) {
                        echo "<script>
                            alert(" . $e->getMessage() . ");
                            window.open('user.php?do=Add', '_self');
                            </script>";
                    }

                endif;
            endif;
            ?>

            <div class='signup'>
                <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">Add User</h1>

                <form class="profile" action="?do=Add" method="POST" enctype="multipart/form-data">
                    <div class="image">
                        <img src="../docs/images/user_images/img.png" alt="">
                        <span></span>
                        <i class="fa-solid fa-camera fa-3x"></i>
                        <input type="file" style="opacity: 0" name="user_image" id="user-image">
                        <span class="error">
                            <?php 
                            if(isset($errors['image'])) echo '* ' . $errors['image']; 
                            ?>
                        </span>
                    </div>

                    <div class="info">
                        <div class="name">
                            <label>Name:</label>
                            <input type="text" name="full_name" class="input" placeholder='Full Name' required='required'>
                            <span class="error">
                                <?php 
                                if(isset($errors['name1'])) echo '* ' . $errors['name1']; 
                                ?>
                            </span>
                        </div>
                        <div class="user-name">
                            <label>User name:</label>
                            <input type="text" name="user_name" required='required' placeholder='User Name' class="input">
                            <span class="error">
                                <?php 
                                if(isset($errors['user_name1'])) echo '* ' . $errors['user_name1']; 
                                if(isset($errors['user_name2'])) echo '* ' . $errors['user_name2']; 
                                if(isset($errors['user_name3'])) echo '* ' . $errors['user_name3']; 
                                ?>
                            </span>
                        </div>
                        <div class="email">
                            <label>Email:</label>
                            <input type="email" name="email" required='required' placeholder='Email' class="input">
                            <span class="error">
                                <?php 
                                if(isset($errors['email1'])) echo '* ' . $errors['email1']; 
                                if(isset($errors['email2'])) echo '* ' . $errors['email2']; 
                                ?>
                            </span>
                        </div>
                        <div class="pass">
                            <label>Password:</label>
                            <input type="password" name="password" required='required' placeholder='Password' class="input">
                            <span class="error">
                                <?php 
                                if(isset($errors['pass1'])) echo '* ' . $errors['pass1']; 
                                if(isset($errors['pass2'])) echo '* ' . $errors['pass2']; 
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
                        <div class="role">
                            <label>Role:</label>
                            <select style="width: 100%; height: 60%" id="card-type" name="group" required>
                                <option value="1">Admin</option>
                                <option value="2" selected>User</option>
                                <option value="3">Supplier</option>
                            </select>
                        </div>
                        <datalist id="nationality">
                            <option value="Yemeni">
                            <option value="Saudi">
                        </datalist>

                        <input class="submit" type="submit" value="Add" name='add'>
                    </div>
                </form>
            </div>
        <?php
        }
        elseif ($do == 'Edit') { 
            $userId = (isset($_GET['userid'])) &&  is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            $stmt = $conn->prepare("SELECT * FROM USERS WHERE user_id = ?");
            $stmt->execute(array($userId));
            $row = $stmt->fetch();
            if ($stmt->rowcount() > 0): 
                if(isset($_POST['edit'])):
                    //  print all the value from the form
                    $id = $_POST['user_id'];
                    $user_name = $_POST['user_name'];
                    $full_name = $_POST['full_name'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $hash_password = md5($password);
                    $birth_date = $_POST['birth_date'];
                    $nationality = $_POST['nationality'];
                    
                    if($_SESSION['GROUP_ID'] == 3) {
                        $role = 3;
                    }
                    else {
                        $role = $_POST['group'];
                    }

                    //----------------------
                    $name = $_FILES['user_image']['name'];
                    $type = $_FILES['user_image']['type'];
                    $tmp_name = $_FILES['user_image']['tmp_name'];
                    $error = $_FILES['user_image']['error'];
                    $size = $_FILES['user_image']['size'];

                    $found = false; // If the user update the image will updated else will not
                    if (empty($name)):
                        $found = true;
                    endif;
    
                    $valid_extension = array('png', 'jpg', 'jpeg', 'gif');
    
                    $explode_array = explode('.', $name);
                    $ext = strtolower(end($explode_array));
    
                    // Make some validation for the form
                    // Create array that will take all error
                    $errors = array();
    
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
                    // Check if the email is exists in the database
                    if(checkUser('email', 'users', $email, $userId) > 0):
                        $errors['email2'] = "The email is <strong>already exists</strong>";
                    endif;
                    // Check if the user name is exists in the database
                    if(checkUser('user_name', 'users', $user_name, $userId) > 0):
                        $errors['user_name3'] = "The user_name is <strong>already exists</strong>";
                    endif;
                    if(empty($full_name)):
                        $errors['name1'] = "The full name must not be <strong>empty</strong>";
                    endif;
                    if(!empty($name) && !in_array($ext, $valid_extension)):
                        $errors['image'] = "The extension not <strong>allowed</strong>";
                    endif;
    
                    // IF no error occur update in the database
                    if(empty($errors)):
    
                        try {
                            $stmt = $conn->prepare("UPDATE users 
                                                            SET user_name =:USER, 
                                                                full_name =:FULL, 
                                                                email =:EMAIL, 
                                                                password =:PASS, 
                                                                birth_date =:BIRTH, 
                                                                nationality =:NATION,
                                                                group_id = :ROLE,
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
                                'ROLE' => $role,
                                'ID' => $id
                            ));

                            if (!$found):
                                move_uploaded_file($tmp_name, "..\docs\images\user_images\\" . $name);
                                $stmt = $conn->prepare("UPDATE users 
                                                                SET
                                                                    image = :IMAGE
                                                                WHERE
                                                                    user_id = :ID");
                                $stmt->execute(array(
                                    'IMAGE' => $name,
                                    'ID' => $id
                                ));
                            endif;

                            echo "<script>
                                alert('" . $stmt->rowcount() . " RECORD UPDATED...! ');
                                window.open('user.php?do=Edit&userid=" . $userId . "', '_self');
                                </script>";
                        }
                        catch(Exception $e) {
                            echo "<script>
                            alert('" . $e->getMessage() . "');
                            window.open('user.php?do=Edit&userid=" . $userId . "', '_self');
                            </script>";
                        }
                        
                    endif;
                endif;
            ?>

                <div class='signup'>
                <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">Edit User</h1>
                <div class="container">
                    <?php
                    if(isset($success)) echo $success;
                    ?>
                </div>
                <form class="profile" action="?do=Edit&userid=<?php echo $userId ?>" method="POST" enctype="multipart/form-data">
                    <div class="image">
                        <img src="<?php echo "../docs/images/user_images/" . $row['image'] ?>" alt="">
                        <span></span>
                        <i class="fa-solid fa-camera fa-3x"></i>
                        <input type="file" style="opacity: 0" name="user_image" id="user-image">
                        <span class="error">
                            <?php 
                            if(isset($errors['image'])) echo '* ' . $errors['image']; 
                            ?>
                        </span>
                    </div>

                    <div class="info">
                        <input type="hidden" name="user_id" value="<?php echo $row['user_id'] ?>">
                        <div class="name">
                            <label>Name:</label>
                            <input type="text" name="full_name" value="<?php echo $row['full_name'] ?>" class="input" required='required'>
                            <span class="error">
                                <?php 
                                if(isset($errors['name1'])) echo '* ' . $errors['name1']; 
                                ?>
                            </span>
                        </div>
                        <div class="user-name">
                            <label>User name:</label>
                            <input type="text" name="user_name" required='required'  value="<?php echo $row['user_name'] ?>" class="input">
                            <span class="error">
                                <?php 
                                if(isset($errors['user_name1'])) echo '* ' . $errors['user_name1']; 
                                if(isset($errors['user_name2'])) echo '* ' . $errors['user_name2']; 
                                if(isset($errors['user_name3'])) echo '* ' . $errors['user_name3']; 
                                ?>
                            </span>
                        </div>
                        <div class="email">
                            <label>Email:</label>
                            <input type="email" name="email"  value="<?php echo $row['email'] ?>" class="input" required='required'>
                            <span class="error">
                                <?php 
                                if(isset($errors['email1'])) echo '* ' . $errors['email1']; 
                                if(isset($errors['email2'])) echo '* ' . $errors['email2']; 
                                ?>
                            </span>
                        </div>
                        <div class="pass">
                            <label>Password:</label>
                            <input type="password" name="password"  value="<?php echo $row['password'] ?>" class="input" required='required'>
                            <span class="error">
                                <?php 
                                if(isset($errors['pass1'])) echo '* ' . $errors['pass1']; 
                                if(isset($errors['pass2'])) echo '* ' . $errors['pass2']; 
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
                        
                        <?php
                        if($_SESSION['GROUP_ID'] == 1) {?>
                            <div class="role">
                                <label>Role:</label>
                                <select style="width: 100%; height: 60%" id="card-type" name="group" required>
                                    <option value="1">Admin</option>
                                    <option value="2" selected>User</option>
                                    <option value="3">Supplier</option>
                                </select>
                            </div>
                        <?php
                        }
                        ?>
                        <datalist id="nationality">
                            <option value="Yemeni">
                            <option value="Saudi">
                        </datalist>

                        <input class="submit" type="submit" value="Save" name='edit'>
                    </div>
                </form>
            </div>
            <?php 
            else:
                redirectToHome("<div class='alert alert-danger'>THE USER NOT FOUND</div>", 'back');
            endif;
            ?>
    <?php }
        elseif($do == 'Delete') {
            // CHECK IF THE COMING USER NAME IS NUMERIC AND STOR
            $userId = (isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0);

            // CHECK IF THE USER EXISTS
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute(array($userId));

            if($stmt->rowcount() > 0):
                $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
                $stmt->execute(array($userId));
                echo "<script>
                    alert('" . $stmt->rowcount() . " RECORD DELETED...! ');
                    window.open('user.php', '_self');
                    </script>";
            else:
                echo "<script>
                    alert('THE USER NOT FOUND');
                    window.open('user.php', '_self');
                    </script>";
            endif;
        }

        elseif($do == 'Active') {
            $userId = (isset($_GET['userid'])) &&  is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = $userId");
            $stmt->execute();
            $row = $stmt->fetch();

            if($stmt->rowcount() <= 0):
                echo "<script>
                    alert('THE USER NOT FOUND');
                    window.open('user.php', '_self');
                    </script>";

            elseif($stmt->rowcount() > 0 && $row['reg_status'] == 0):
                $stmt2 = $conn->prepare("UPDATE users 
                                                    SET 
                                                        reg_status = 1
                                                    WHERE 
                                                        user_id = ?");
                $stmt2->execute(array($userId));

                echo "<script>
                    alert('" . $stmt2->rowcount() . " RECORD ACTIVATED...!');
                    window.open('user.php?page=bind', '_self');
                    </script>";
            else:
                echo "<script>
                    alert('THE USER IS ALREADY ACTIVATE');
                    window.open('user.php', '_self');
                    </script>";
            endif;
        }

        elseif($do == 'NonActive') {
            $userId = (isset($_GET['userid'])) &&  is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;

            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = $userId");
            $stmt->execute();
            $row = $stmt->fetch();

            if($stmt->rowcount() <= 0):
                echo "<script>
                    alert('THE USER NOT FOUND');
                    window.open('user.php', '_self');
                    </script>";

            elseif($stmt->rowcount() > 0 && $row['reg_status'] == 1):
                $stmt2 = $conn->prepare("UPDATE users 
                                                    SET 
                                                        reg_status = 0
                                                    WHERE 
                                                        user_id = ?");
                $stmt2->execute(array($userId));

                echo "<script>
                    alert('" . $stmt2->rowcount() . " RECORD ACTIVATED...!');
                    window.open('user.php', '_self');
                    </script>";
            else:
                echo "<script>
                    alert('THE USER IS ALREADY Not ACTIVATE');
                    window.open('user.php', '_self');
                    </script>";
            endif;
        }

        include $tpl . 'footer.php';
    }
    else {
        header('Location: index.php');
        exit();
    }

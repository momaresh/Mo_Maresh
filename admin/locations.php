
<?php
    session_start();
    $setTitle = "Locations";

    if(isset($_SESSION['USER_NAME']) && isset($_SESSION['GROUP_ID']) == 1) {
        include('initial.php');

        $search = ''; 
        if(isset($_POST['search'])) {
            $city = $_POST['city'];
            if(!empty($city)) {
                $search = "WHERE city LIKE '%$city%'";
            }
        }

        $sql = "SELECT * FROM LOCATIONS $search";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $do = (isset($_GET['do']) ? $_GET['do'] : 'Manage');

        if($do == "Manage") {

            if(isset($_POST['insert'])) {
                $country = $_POST['country'];
                $city = $_POST['city'];
                $street = $_POST['street'];

                $errors = array();
                if(empty($country)) {
                    $errors['country'] = "Country is required";
                }
                if(empty($city)) {
                    $errors['city'] = "City is required";
                }

                if(empty($errors)){

                    try {
                        $sql2 = "INSERT INTO LOCATIONS(country, city, street) VALUES(?, ?, ?)";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute(array($country, $city, $street));
                        echo "<script>
                            alert('THE LOCATION INSERTED');
                            window.open('locations.php', '_self');
                            </script>";
                    }
                    catch(Exception $e) {
                        $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    }
                }
            }
            ?>

            <h3 class="use-a-lot2 mb-2 mt-5">ADD LOCATION</h3>
            <form class="form-row company-form" method="POST" action="">
                <?php
                if(isset($error)) echo $error;
                ?>
                <div class="form-group col-md-4">
                    <label for="">Country</label>
                    <input type="text" class="form-control" name="country" placeholder="Country" required="required">
                    <span class="error">
                        <?php 
                        if(isset($errors['country'])) echo '* ' . $errors['country']; 
                        ?>
                    </span>
                </div>

                <div class="form-group col-md-4">
                    <label for="">City</label>
                    <input type="text" class="form-control" name="city" placeholder="City" required="required">
                    <span class="error">
                        <?php 
                        if(isset($errors['city'])) echo '* ' . $errors['city']; 
                        ?>
                    </span>
                </div>

                <div class="form-group col-md-4">
                    <label for="">Street</label>
                    <input type="text" class="form-control" name="street" placeholder="Street">
                </div>

                <button type="submit" name="insert" class="btn btn-primary">Add</button>
            </form>

            <div class="container" style="margin-top: 100px">
                <hr>
                <h2 class="mb-2">LOCATIONS: </h2>

                <form class="search" action="#goto" method='POST'>
                    <input type="text" name="city" placeholder="Search by city" id="search">
                    <input type="submit" name="search" value="Search" id="button">
                </form>

                <div class="table-responsive" id='goto'>
                    <table class="table table-bordered text-center">
                        <tr style="background-color: #19283f; color: white">
                            <th>Location ID</th>
                            <th>Country</th>
                            <th>City</th>
                            <th>Street</th>
                            <th>Control</th>
                        </tr>

                        <?php
                        if($stmt->rowCount() > 0) {
                            foreach($stmt->fetchAll() as $row): ?>
                                <tr>
                                    <td><?php echo $row['loc_id'] ?></td>
                                    <td><?php echo $row['country'] ?></td>
                                    <td><?php echo $row['city'] ?></td>
                                    <td><?php echo $row['street'] ?></td>
                                    <td>
                                        <a href="?do=Edit&id=<?php echo $row['loc_id'] ?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px;">Edit</a>
                                        <a href="?do=Delete&id=<?php echo $row['loc_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                    </td>
                                </tr>
                            <?php
                            endforeach;
                        }
                        else {
                            echo "<div class='container text-center alert alert-info'> There is no location.....!</div>";
                        }
                        ?>
                    </table>
                </div>
            </div>
        <?php
        }
        elseif($do == "Edit") { 
            $id = (isset($_GET['id']) ? $_GET['id'] : 0);
            $stmt3 = $conn->prepare("SELECT * FROM LOCATIONS WHERE loc_id = ?");
            $stmt3->execute(array($id));

            if($stmt3->rowCount() > 0) {
                $row = $stmt3->fetch();

                if(isset($_POST['update'])) {
                    $country = $_POST['country'];
                    $city = $_POST['city'];
                    $street = $_POST['street'];

                    $errors = array();
                    if(empty($country)) {
                        $errors['country'] = "Country is required";
                    }
                    if(empty($city)) {
                        $errors['city'] = "City is required";
                    }

                    if(empty($errors)){
                        try {
                            $sql2 = "UPDATE LOCATIONS SET country = ?, city = ?, street = ?
                            WHERE loc_id = ?";
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->execute(array($country, $city, $street, $id));
                            echo "<script>
                                alert('THE LOCATION UPDATED');
                                window.open('locations.php#goto', '_self');
                                </script>";
                        }
                        catch(Exception $e) {
                            $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                        }
                    }
                }
            
            ?>

            <h3 class="use-a-lot2 mb-2 mt-5">EDIT LOCATION</h3>
            <form class="form-row company-form" method="POST" action="?do=Edit&id=<?php echo $id ?>">
                <?php
                if(isset($error)) echo $error;
                ?>
                <div class="form-group col-md-4">
                    <label for="">Country</label>
                    <input type="text" class="form-control" name="country" value="<?php echo $row['country'] ?>" required="required">
                    <span class="error">
                        <?php 
                        if(isset($errors['country'])) echo '* ' . $errors['country']; 
                        ?>
                    </span>
                </div>

                <div class="form-group col-md-4">
                    <label for="">City</label>
                    <input type="text" class="form-control" name="city" value="<?php echo $row['city'] ?>" required="required">
                    <span class="error">
                        <?php 
                        if(isset($errors['city'])) echo '* ' . $errors['city']; 
                        ?>
                    </span>
                </div>

                <div class="form-group col-md-4">
                    <label for="">Street</label>
                    <input type="text" class="form-control" name="street" value="<?php echo $row['street'] ?>">
                </div>

                <button type="submit" name="update" class="btn btn-primary">Save</button>
            </form>
        <?php
            }
            else {
                redirectToHome("<div class='alert alert-danger'>The Location not found</div>", 'back');
            }
        }
        elseif($do == 'Delete') {
            echo '<div class=container>';
            // CHECK IF THE COMING USER NAME IS NUMERIC AND STOR
            $id = (isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0);

            try {
                $stmt2 = $conn->prepare("DELETE FROM LOCATIONS WHERE loc_id = ?");
                $stmt2->execute(array($id));
                echo "<script>
                    alert('The location Deleted');
                    window.open('locations.php#goto', '_self');
                    </script>";
            }
            catch(Exception $e)
            {
                echo "<script>
                    alert(" . $e->getMessage() . ");
                    window.open('locations.php', '_self');
                    </script>";
            }
            
            echo '</div>';
        }


        include($tpl . "footer.php");
    }
    else {
        header('Location: index.php');
        exit();
    }

?>
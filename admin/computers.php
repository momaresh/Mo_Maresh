<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Computers';
    //THIS IF YOU ALREADY SIGN WILL CHANGE YOU TO THE DASHBOARD AUTOMATIC
    if (isset($_SESSION['USER_NAME']) && ($_SESSION['GROUP_ID'] == 1 || $_SESSION['GROUP_ID'] == 3)) {
        include 'initial.php';
    
        // WE MAKE THIS BECAUSE THE SUPPLIER AND ADMIN WILL USE THIS PAGE TO SHOW THE COMPUTERS
        // IF THE USER IS SUPPLIER IT WILL SHOW THE COMPUTER THAT HE HAS SUPPLIED ONLY
        $condition = '';
        if($_SESSION['GROUP_ID'] == 3){
            $condition = "WHERE SUP_ID = $_SESSION[USER_ID]";
        }
    

        $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { ?>
            <div class="container mt-5">
                <h3 class="use-a-lot2 mb-2">Computers</h3>
                <a href="?do=Add" class="btn btn-primary mb-2">ADD COMPUTER</a>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <tr style="background-color: #19283f; color: white">
                            <th>Computer_Id</th>
                            <th>Computer_Name</th>
                            <th>Price</th>
                            <th>Brand</th>
                            <th>Color</th>
                            <th>Screen_Size</th>
                            <th>Storage_Size</th>
                            <th>Storage_Type</th>
                            <th>OS</th>
                            <th>Ram_Size</th>
                            <th>Graphic_Brand</th>
                            <th>Graphic_Size</th>
                            <th>Control</th>
                        </tr>
                        <?php 
                        $stmt = $conn->prepare("SELECT * FROM products p JOIN computers c ON p.prod_id = c.computer_id $condition");
                        $stmt->execute();
                        $rows = $stmt->fetchAll();
                        foreach($rows as $row): ?>
                            <tr >
                                <td><?php echo $row['prod_id']; ?></td>
                                <td><?php echo substr($row['prod_name'], 0, 50); ?></td>
                                <td>$<?php echo $row['price']; ?></td>
                                <td><?php echo $row['brand']; ?></td>
                                <td><?php echo $row['color']; ?></td>
                                <td><?php echo $row['screen_size']; ?> In</td>
                                <td><?php echo $row['storage_size']; ?> GB</td>
                                <td><?php echo $row['storage_type']; ?></td>
                                <td><?php echo $row['os']; ?></td>
                                <td><?php echo $row['ram_size']; ?> GB</td>
                                <td><?php echo $row['graphic_brand']; ?></td>
                                <td><?php echo $row['graphic_size']; ?> GB</td>
                                <td>
                                    <a href="?do=Edit&compid=<?php echo $row['computer_id'];?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px;">Edit</a>
                                    <a href="?do=Delete&compid=<?php echo $row['computer_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        <?php
        }
        elseif($do == 'Add'){ 

            // CHECK IF COMING FROM REQUEST
            if(isset($_POST['insert'])):
                //  print all the value from the form
                $computer_name = $_POST['computer_name'];
                $desc1 = $_POST['description1'];
                $desc2 = $_POST['description2'];
                $desc3 = $_POST['description3'];
                $brand = $_POST['brand'];
                $color = $_POST['color'];
                $scr_size = $_POST['screen_size'];
                $storage_size = $_POST['storage_size'];
                $storage_type = $_POST['storage_type'];
                $price = $_POST['price'];
                $ram_size = $_POST['ram_size'];
                $os = $_POST['os'];
                $graphic_size = $_POST['graphic_size'];
                $graphic_type = $_POST['graphic_type'];

                if ($_SESSION['GROUP_ID'] == 3)
                    $sup_id = $_SESSION['USER_ID'];
                else
                    $sup_id = $_POST['sup_id'];
                

                $numbers = count($_FILES['image']['name']);
                
                $insert_errors = array();

                // IF supplier in the database
                if(checkSup('user_id', 'users', $sup_id) <= 0):
                    $insert_errors['sup'] = "THE SUPPLIER NOT EXISTS";
                endif;
                if(empty($brand)):
                    $insert_errors['brand'] = "THE SUPPLIER NOT EXISTS";
                endif;
                if(empty($price)):
                    $insert_errors['price'] = "THE SUPPLIER NOT EXISTS";
                endif;

                if(empty($insert_errors)):

                    $stmt = $conn->prepare("INSERT INTO products (prod_name, price, sup_id, sup_date, desc1, desc2, desc3, type)
                                            VALUES (:NAME, :PRICE, :SUPID, now(), :D1, :D2, :D3, :TYPE)");
                    $stmt->execute(array(
                        'NAME' => $computer_name,
                        'PRICE' => $price,
                        'SUPID' => $sup_id,
                        'D1' => $desc1,
                        'D2' => $desc2,
                        'D3' => $desc3,
                        'TYPE' => 'computer'
                    ));
                    
                    $stmt2 = $conn->prepare("SELECT MAX(prod_id) FROM products ");
                    $stmt2->execute();
                    $row_2 = $stmt2->fetchColumn();

                    $stmt = $conn->prepare("INSERT INTO computers (computer_id, brand, color, screen_size, storage_size, storage_type, ram_size, os, graphic_size, graphic_brand)
                                            VALUES (:COM_ID, :BR, :CO, :SCS, :STS, :STT, :RAS, :OS, :GRS, :GRT)");
                    $stmt->execute(array(
                        'COM_ID' => $row_2,
                        'BR' => $brand,
                        'CO' => $color,
                        'SCS' => $scr_size,
                        'STS' => $storage_size,
                        'STT' => $storage_type,
                        'RAS' => $ram_size,
                        'OS' => $os,
                        'GRS' => $graphic_size,
                        'GRT' => $graphic_type
                    ));

                    for ($i = 0; $i < $numbers; $i++) {
                        $name = $_FILES['image']['name'][$i];
                        $type = $_FILES['image']['type'][$i];
                        $tmp_name = $_FILES['image']['tmp_name'][$i];
                        $error = $_FILES['image']['error'][$i];
                        $size = $_FILES['image']['size'][$i];
    
                        $valid_extension = array('png', 'jpg', 'jpeg', 'gif');
    
                        $explode_array = explode('.', $name);
                        $ext = strtolower(end($explode_array));

                        if (!in_array($ext, $valid_extension)) {
                            $insert_errors['ext'] = 'The extension not allowed in image number' . $i + 1;
                        }
                        else {
                            move_uploaded_file($tmp_name, "..\docs\images\computer_images\\" . $name);
                            $insert = $conn->prepare("INSERT INTO product_images(prod_id, url) VALUES (?, ?)");
                            $insert->execute(array($row_2, $name));
                        }

                    }
                    $success = "<div class='alert alert-success'>" . $stmt->rowcount() . " RECORD INSERTED...! </div>";
                endif;
            endif;
            ?>
            <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">ADD COMPUTER</h1>
            <form class="content" action="?do=Add" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <?php
                        if(isset($success)) echo $success;
                    ?>
                    <div class="about-book">
                        <div class="image">
                            <img src="<?php echo 'Themes/IMAGES/computer.jpg'; ?>" alt="">
                        </div>
                        
                        <div class="info">
                            <div class="title">
                                <label for="">Computer Name: </label>
                                <input type="text" name="computer_name" placeholder="Book Name" required>
                                <?php
                                if ($_SESSION['GROUP_ID'] != 3) {
                                ?>
                                    <?php
                                    $sup_stmt = $conn->prepare('SELECT * FROM users WHERE GROUP_ID = 3');
                                    $sup_stmt->execute();
                                    $suppliers = $sup_stmt->fetchAll();
                                    ?>
                                    <label for="">Supplier Id: </label>

                                    <select class="btn select" name='sup_id' style='border: 1px solid var(--third-color)'>
                                    <?php
                                        foreach ($suppliers as $supplier):
                                    ?>
                                        <option value="<?php echo $supplier['user_id']; ?>"> <?php echo $supplier['full_name']; ?> </option>
                                    <?php
                                        endforeach;
                                    ?>
                                    </select>

                                    <span class="error">
                                        <?php
                                        if(isset($insert_errors['sup'])) echo '*' . $insert_errors['sup'];
                                        ?>
                                    </span>
                                <?php
                                }
                                ?>

                            </div>

                            <div class="mb-5 mt-4">
                                <input type="file" name="image[]" multiple>
                                <span class="error">
                                    <?php
                                    if(isset($insert_errors['ext'])) echo '*' . $insert_errors['ext'];
                                    ?>
                                </span>
                            </div>

                            <div class="text">
                                <label for="">Description One: </label>
                                <textarea name="description1" style="height: 100px;" placeholder="Description One Of The Product"></textarea>
                                <label for="">Description Tow: </label>
                                <textarea name="description2" style="height: 100px;" placeholder="Description Tow Of The Product"></textarea>
                                <label for="">Description Three: </label>
                                <textarea name="description3" style="height: 100px;" placeholder="Description Three Of The Product"></textarea>
                            </div>

                            <div class="data">
                                <div class="datum">
                                    <div class="datum-desc">
                                        <label for="brand">Brand:</label>
                                        <input type="text" name="brand" id="brand" placeholder="Brand" required>  
                                        <span class="error">
                                            <?php
                                            if(isset($insert_errors['brand'])) echo '*' . $insert_errors['brand'];
                                            ?>
                                        </span>                               
                                    </div> 
                                    
                                    <div class="datum-desc">
                                        <label for="color">Color:</label>
                                        <input type="text" name="color" id="color" placeholder="Color">                                 
                                    </div> 

                                    <div class="datum-desc">
                                        <label for="scrSize">Screen Size:</label>
                                        <input type="text" name="screen_size" id="scrSize" placeholder="Screen Size">                                 
                                    </div>
                                    
                                    <div class="datum-desc">
                                        <label for="stgSize">Storage Size:</label>
                                        <input type="text" name="storage_size" id="stgSize" placeholder="Storage Size">                                 
                                    </div>

                                    <div class="datum-desc">
                                        <label for="stgType">Storage Type:</label>
                                        <input type="text" name="storage_type" id="stgType" placeholder="Storage Type">                                 
                                    </div>

                                </div>

                                <div class="datum">
                                    <div class="datum-desc">
                                        <label for="price">Price:</label>
                                        <input type="text" name="price" id="price" placeholder="Price" required>
                                        <span class="error">
                                            <?php
                                            if(isset($insert_errors['price'])) echo '*' . $insert_errors['price'];
                                            ?>
                                        </span>                                 
                                    </div>
                                    <div class="datum-desc">
                                        <label for="ramSize">Ram Size:</label>
                                        <input type="text" name="ram_size" id="ramSize" placeholder="Ram Size">                                 
                                    </div>
                                    <div class="datum-desc">
                                        <label for="os">OS:</label>
                                        <input type="text" name="os" id="os" placeholder="OS">                                 
                                    </div>     

                                    <div class="datum-desc">
                                        <label for="graphicSize">Graphic Size:</label>
                                        <input type="text" name="graphic_size" id="graphicSize" placeholder="Graphic Size">                                 
                                    </div>

                                    <div class="datum-desc">
                                        <label for="graphicType">Graphic Type:</label>
                                        <input type="text" name="graphic_type" id="graphicType" placeholder="Graphic Type">                                 
                                    </div>

                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Add" name="insert">
                    </div>
                </div>
            </form>
        <?php
        }

        elseif($do == 'Edit') { 

            $compId = (isset($_GET['compid']) && is_numeric($_GET['compid'])) ? $_GET['compid'] : 0;

            $stmt = $conn->prepare("SELECT * 
                                    FROM products JOIN computers 
                                    ON prod_id = computer_id $condition 
                                    AND computer_id = $compId");
            $stmt->execute();
            $row = $stmt->fetch();

            $stmt_img = $conn->prepare("SELECT * FROM product_images WHERE prod_id = $compId LIMIT 1");
            $stmt_img->execute();
            $row_img = $stmt_img->fetch();

            if ($stmt->rowcount() > 0):
                if(isset($_POST['update'])):    
                    // TAKING THE VALUES FROM THE FORM
                    $computer_id = $_POST['computer_id'];
                    $computer_name = $_POST['computer_name'];
                    $desc1 = $_POST['description1'];
                    $desc2 = $_POST['description2'];
                    $desc3 = $_POST['description3'];
                    $brand = $_POST['brand'];
                    $color = $_POST['color'];
                    $scr_size = $_POST['screen_size'];
                    $storage_size = $_POST['storage_size'];
                    $storage_type = $_POST['storage_type'];
                    $price = $_POST['price'];
                    $ram_size = $_POST['ram_size'];
                    $os = $_POST['os'];
                    $graphic_size = $_POST['graphic_size'];
                    $graphic_brand = $_POST['graphic_brand'];

                    if ($_SESSION['GROUP_ID'] == 3)
                        $sup_id = $_SESSION['USER_ID'];
                    else
                        $sup_id = $_POST['sup_id'];
                    
    
                    $numbers = (!empty($_FILES['image']['name']) ? count($_FILES['image']['name']) : 0);
                    
                    $update_errors = array();
                    
                    if(checkSup('user_id', 'users', $sup_id) <= 0):
                        $update_errors['sup'] = "THE SUPPLIER NOT EXISTS";
                    else:
                        $stmt = $conn->prepare("UPDATE products
                                                                SET 
                                                                    prod_name = :NAME,
                                                                    price = :PRICE, 
                                                                    sup_id = :SUPID,  
                                                                    desc1 = :D1, 
                                                                    desc2 = :D2, 
                                                                    desc3 = :D3
                                                WHERE prod_id = :COMP_ID
                                            ");
                        $stmt->execute(array(
                            'NAME' => $computer_name,
                            'PRICE' => $price,
                            'SUPID' => $sup_id,
                            'D1' => $desc1,
                            'D2' => $desc2,
                            'D3' => $desc3,
                            'COMP_ID' => $computer_id
                        ));
    
                        $stmt = $conn->prepare("UPDATE computers
                                                                SET 
                                                                    brand = :BR, 
                                                                    color = :CO, 
                                                                    screen_size = :SCS, 
                                                                    storage_size = :STS, 
                                                                    storage_type = :STT, 
                                                                    ram_size = :RAS, 
                                                                    os = :OS, 
                                                                    graphic_size = :GRS,
                                                                    graphic_brand = :GRB
                                                WHERE computer_id = :COMP_ID
                                            ");
                        $stmt->execute(array(
                            'BR' => $brand,
                            'CO' => $color,
                            'SCS' => $scr_size,
                            'STS' => $storage_size,
                            'STT' => $storage_type,
                            'RAS' => $ram_size,
                            'OS' => $os,
                            'GRS' => $graphic_size,
                            'GRB' => $graphic_brand,
                            'COMP_ID' => $computer_id
                        ));
    
                        for ($i = 0; $i < $numbers; $i++) {
                            $name = $_FILES['image']['name'][$i];
                            $type = $_FILES['image']['type'][$i];
                            $tmp_name = $_FILES['image']['tmp_name'][$i];
                            $error = $_FILES['image']['error'][$i];
                            $size = $_FILES['image']['size'][$i];
        
                            $valid_extension = array('png', 'jpg', 'jpeg', 'gif');
        
                            $explode_array = explode('.', $name);
                            $ext = strtolower(end($explode_array));
    
                            if (!in_array($ext, $valid_extension)) {
                                $update_errors['ext'] = 'The extension not allowed in image number ' . $i + 1;
                            }
                            else {
                                $rand_name = rand(1, 1000000) . '_' . $name;
                                move_uploaded_file($tmp_name, "..\docs\images\computer_images\\" . $rand_name);
                                $insert = $conn->prepare("INSERT INTO product_images(prod_id, url) VALUES (?, ?)");
                                $insert->execute(array($computer_id, $rand_name));
                            }
    
                        }
    
                        $success = "<div class='alert alert-success'>RECORD UPDATED...!</div>'";
                    endif;
                endif;
                ?>
                <h1 class="text-center mt-5" style="color: #ff6a00; font-weight: bold;">EDIT COMPUTER</h1>
                <form class="content" action="?do=Edit&compid=<?php echo $compId; ?>" method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <?php
                        if(isset($success)) echo $success;
                        ?>
                        <div class="about-book">
                            <div class="image">
                                <img src="<?php echo '../docs/images/computer_images/' . $row_img['url']; ?>" alt="">
                            </div>
                            
                            <div class="info">
                                <div class="title">
                                    <input type="hidden" name="computer_id" value="<?php echo $row['prod_id'] ?>">
                                    <label for="">Computer Name: </label>
                                    <input type="text" name="computer_name" value="<?php echo $row['prod_name'] ?>" required>
                                    <?php
                                    if ($_SESSION['GROUP_ID'] != 3) {
                                    ?>
                                        <label for="">Supplier Id: </label>
                                        <input type="number" name="sup_id"value="<?php echo $row['sup_id'] ?>" required>
                                        <span class="error">
                                            <?php
                                            if(isset($update_errors['sup'])) echo '*' . $update_errors['sup'];
                                            ?>
                                        </span>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div class="mb-5 mt-4">
                                    <input type="file" name="image[]" multiple>
                                    <br>
                                    <span class="error">
                                        <?php
                                        if(isset($update_errors['ext'])) echo '*' . $update_errors['ext'];
                                        ?>
                                    </span>
                                </div>
                                <div class="text">
                                    <label for="">Description One: </label>
                                    <textarea name="description1" style="height: 100px;" ><?php echo $row['desc1'] ?></textarea>
                                    <label for="">Description Tow: </label>
                                    <textarea name="description2" style="height: 100px;" ><?php echo $row['desc2'] ?></textarea>
                                    <label for="">Description Three: </label>
                                    <textarea name="description3" style="height: 100px;" ><?php echo $row['desc3'] ?></textarea>
                                </div>

                                <div class="data">
                                    <div class="datum">
                                        <div class="datum-desc">
                                            <label for="brand">Brand:</label>
                                            <input type="text" name="brand" id="brand" value="<?php echo $row['brand'] ?>" required>                                 
                                        </div> 
                                        
                                        <div class="datum-desc">
                                            <label for="color">Color:</label>
                                            <input type="text" name="color" id="color" value="<?php echo $row['color'] ?>">                                 
                                        </div> 

                                        <div class="datum-desc">
                                            <label for="scrSize">Screen Size:</label>
                                            <input type="text" name="screen_size" id="scrSize" value="<?php echo $row['screen_size'] ?>">                                 
                                        </div>
                                        
                                        <div class="datum-desc">
                                            <label for="stgSize">Storage Size:</label>
                                            <input type="text" name="storage_size" id="stgSize" value="<?php echo $row['storage_size'] ?>">                                 
                                        </div>

                                        <div class="datum-desc">
                                            <label for="stgType">Storage Type:</label>
                                            <input type="text" name="storage_type" id="stgType" value="<?php echo $row['storage_type'] ?>">                                 
                                        </div>

                                    </div>

                                    <div class="datum">
                                        <div class="datum-desc">
                                            <label for="price">Price:</label>
                                            <input type="text" name="price" id="price" value="<?php echo $row['price'] ?>" required>                                 
                                        </div>
                                        <div class="datum-desc">
                                            <label for="ramSize">Ram Size:</label>
                                            <input type="text" name="ram_size" id="ramSize" value="<?php echo $row['ram_size'] ?>">                                 
                                        </div>
                                        <div class="datum-desc">
                                            <label for="os">OS:</label>
                                            <input type="text" name="os" id="os" value="<?php echo $row['os'] ?>">                                 
                                        </div>     

                                        <div class="datum-desc">
                                            <label for="graphicSize">Graphic Size:</label>
                                            <input type="text" name="graphic_size" id="graphicSize" value="<?php echo $row['graphic_size'] ?>">                                 
                                        </div>

                                        <div class="datum-desc">
                                            <label for="graphicType">Graphic Brand:</label>
                                            <input type="text" name="graphic_brand" id="graphicType" value="<?php echo $row['graphic_brand'] ?>">                                 
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Save" name="update">
                        </div>
                    </div>
                </form>
            <?php 
            else:
                redirectToHome("<div class='alert alert-info'>THE COMPUTER NOT FOUND</div>");
            endif;
            ?>

        <?php
        }

        elseif($do == 'Delete') {
            $compId = (isset($_GET['compid'])) &&  is_numeric($_GET['compid']) ? intval($_GET['compid']) : 0;

            $stmt = $conn->prepare("DELETE FROM computers WHERE computer_id = ?");
            $stmt->execute(array($compId));

            $stmt2 = $conn->prepare("DELETE FROM product_images WHERE prod_id = ?");
            $stmt2->execute(array($compId));

            $stmt2 = $conn->prepare("DELETE FROM products WHERE prod_id = ?");
            $stmt2->execute(array($compId));

            redirectToHome("<div class='alert alert-success'>" . $stmt2->rowcount() . ' RECORD DELETED...! </div>', 'back');
        }

        include $tpl . 'footer.php';
    }
    else {
        header('location: index.php');
        exit();
    }
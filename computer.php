
<?php

    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Computers';

    include 'initial.php';

    $compId = (isset($_GET['compid'])) &&  is_numeric($_GET['compid']) ? intval($_GET['compid']) : 0;
    $stmt = $conn->prepare("SELECT * FROM products JOIN computers ON prod_id = computer_id WHERE computer_id = ?");
    $stmt->execute(array($compId));
    $row = $stmt->fetch();


    if(isset($_POST['item'])) {
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];

            $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ?");
            $stmt->execute(array($_SESSION['USER_ID'], 'ordered'));

            if ($stmt->rowCount() > 0):
                $order = $stmt->fetch();

                $stmt_item = $conn->prepare("SELECT * FROM order_items WHERE prod_id = ? AND order_id = ?");
                $stmt_item->execute(array($compId, $order['order_id']));

                if($stmt_item->rowCount() > 0){
                    $error = "THE ITEM IS ALREADY IN THE CART";
                }
                else{
                $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                $stmt_p->execute(array($compId));

                $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                $stmt_ins->execute(array($order['order_id'], $compId, $quantity, ($quantity * $price)));
            }


            else:
                $stmt_order = $conn->prepare("INSERT INTO orders(user_id, status) VALUES(?, ?)");
                $stmt_order->execute(array($_SESSION['USER_ID'], 'ordered'));

                $stmt_id = $conn->prepare("SELECT MAX(order_id) FROM orders");
                $stmt_id->execute();
                $col = $stmt_id->fetchColumn();

                $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                $stmt_p->execute(array($compId));

                $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                $stmt_ins->execute(array($col, $compId, $quantity, ($quantity * $price)));
                $success = 'THE ITEM ADD SUCESSFULY';
            endif;
    }


    if(isset($_SESSION['USER_ID'])) {
        // this check if is it in the view list and also we will use it in change the color of bottom
        $stmt_view = $conn->prepare("SELECT * FROM list WHERE user_id = ? AND prod_id = ?");
        $stmt_view->execute(array($_SESSION['USER_ID'], $compId));
    }


    if (isset($_POST['AddList'])) {
        if ($stmt_view->fetch() == 0):
            $stmt2 = $conn->prepare("INSERT INTO list VALUES(? , ?, now())");
            $stmt2->execute(array($_SESSION['USER_ID'], $compId));
            header("location: computer.php?compid=$compId");
        endif;
    }

    if (isset($_POST['DeleteList'])) {
        $stmt = $conn->prepare("DELETE FROM list WHERE user_id = ? AND prod_id = ?");
        $stmt->execute(array($_SESSION['USER_ID'], $compId));
        header("location: computer.php?compid=$compId");
    }
    
    if ($stmt->rowCount() > 0) {

        $stmt_com = $conn->prepare("SELECT COUNT(*) FROM COMMENTS WHERE prod_id = ?");
        $stmt_com->execute(array($compId));
        $row_com = $stmt_com->fetchColumn();
?>

    <!-- start content -->

    <div class="content">
        <?php
        if(isset($_SESSION['USER_NAME'])) {
        ?>
        <div class="order">
            <h2>Add to your Cart</h2>
            <div>
                <i class="fa-solid fa-cart-shopping"></i>
                <span>$<?php echo $row['price'] ?></span>
            </div>
            <form action="computer.php?compid=<?php echo $compId; ?>" method="POST">
                <input class="form-control" style="width: 30%; margin: 10px auto" type="number" name="quantity" value="1">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <input class="btn" style="width: 70%; background-color: var(--third-color) " type="submit" name="item" value="Add">
                <?php if($stmt_view->fetch() == 0):?>
                    <button class="btn" style="color: var(--third-color)" type="submit" name="AddList"><i class="fa-regular fa-heart"></i></button>
                <?php else:?>
                    <button class="btn" style="color: var(--third-color)" type="submit" name="DeleteList"><i class="fa-solid fa-heart"></i></button>
                <?php endif;?>
                <br>
                <?php
                    if(isset($error)) echo '<span style=color:red;font-size:12px>' . $error . '</span>';
                    if(isset($success)) echo '<span style=color:green;font-size:12px>' . $success . '</span>';
                ?>
            </form>
        </div>
        <?php
        }
        ?>
        <div class="container">
            <div class="about-book">
                <div class="images" id="images">
                    <?php
                    $images1 = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ?");
                    $images1->execute(array($row['prod_id']));
                    $image_1 = $images1->fetchAll();
                    ?>
                    <div id="img1">
                        <img src="<?php echo $computer_img . $image_1[0]['url']; ?>" alt="">
                    </div>

                    <div id="img2">
                        <img src="<?php echo $computer_img . $image_1[1]['url']; ?>" alt="">
                    </div>

                    <div id="img3">
                        <img src="<?php echo $computer_img . $image_1[2]['url']; ?>" alt="">
                    </div>

                    <div id="img4">
                        <img src="<?php echo $computer_img . $image_1[3]['url']; ?>" alt="">
                    </div>

                    <div id="img5">
                        <img src="<?php echo $computer_img . $image_1[4]['url']; ?>" alt="">
                    </div>
                </div>

                <div class="image">
                    <img id="image" src="<?php echo $computer_img . $image_1[0]['url']; ?>" alt="">
                </div>
                <div class="info">
                    <div class="title">
                        <h2><?php echo $row['prod_name'] ?></h2>
                        <a href="">Visit The <?php echo $row['brand'] ?> Store</a>
                    </div>

                    <?php
                        $stmt_rate = $conn->prepare("SELECT AVG(rate) FROM comments WHERE prod_id = ?");
                        $stmt_rate->execute(array($row['prod_id']));
                        $row_rate = $stmt_rate->fetchColumn();
                        $row_rate = round($row_rate);
                    ?>

                    <div class="says">
                        <div class="star" style="color: var(--third-color)">
                            <?php 
                            if ($row_rate == 1):
                                getRate(1);
                            elseif ($row_rate == 2):
                                getRate(2);
                            elseif ($row_rate == 3):
                                getRate(3);
                            elseif ($row_rate == 4):
                                getRate(4);
                            elseif ($row_rate == 5):
                                getRate(5);
                            else:
                                getRate(0);
                            endif; 
                            ?>
                        </div>
                        <div class="comments">
                            <i class="fa-regular fa-message"></i>
                            <?php echo $row_com; ?>
                        </div>
                    </div>

                    <div class="text">
                        <p>
                            About this item
                        </p>

                        <p>
                            <ul style="list-style-type: disc;">
                                <li><?php echo $row['desc1'] ?></li>
                                <li><?php echo $row['desc2'] ?></li>
                                <li><?php echo $row['desc3'] ?></li>
                            </ul>
                        </p>
                    </div>

                    <div class="data">
                        <div class="datum">
                            <div class="datum-desc">
                                <div>Brand:</div>        
                                <div><?php echo $row['brand'] ?></div>                                                
                            </div>  
                            <div class="datum-desc">
                                <div>Color:</div>        
                                <div><?php echo $row['color'] ?></div>                                            
                            </div>  
                            <div class="datum-desc">
                                <div>Screen Size:</div>        
                                <div><?php echo $row['screen_size'] ?></div>                                                
                            </div>  
                            <div class="datum-desc">
                                <div>Storage:</div>        
                                <div><?php echo $row['storage_size'] ?> GB <?php echo $row['storage_type'] ?></div>                                                 
                            </div>  
                            <div class="datum-desc">
                                <div>Ram:</div>        
                                <div><?php echo $row['ram_size'] ?> GB</div>                                                 
                            </div>                         
                        </div>

                        <div class="datum">
                            <div class="datum-desc">
                                <div>Price:</div>        
                                <div>$<?php echo $row['price'] ?></div>                                                 
                            </div>  
                            <div class="datum-desc">
                                <div>OS:</div>        
                                <div><?php echo $row['os'] ?></div>                                                
                            </div>  
                            <div class="datum-desc">
                                <div>Graphic Card:</div>        
                                <div><?php echo $row['graphic_size'] ?> GB <?php echo $row['graphic_brand'] ?></div>                                                 
                            </div>  
                            <div class="datum-desc">
                                <div>Series:</div>        
                                <div><?php echo $row['sup_date'] ?></div>                                                 
                            </div>                           
                        </div>
                    </div> 
                </div>
            </div>
            <p class="most">You may be interested in</p>
            <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-2 row-cols-md-3">
                <?php
                $stmt = $conn->prepare("SELECT * FROM products JOIN computers ON prod_id = computer_id WHERE brand = ?");
                $stmt->execute(array($row['brand']));
                $rows = $stmt->fetchAll();
                foreach($rows as $row): 
                    $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? ORDER BY prod_id DESC LIMIT 1");
                    $images->execute(array($row['prod_id']));
                    $image = $images->fetch();
                ?>
                    <div class="computer col">
                        <div class="image">
                            <a href="computer.php?compid=<?php echo $row['computer_id']?>"><img src="<?php echo "docs/images/computer_images/" . $image['url'] ?>" alt="" style="width: 100%; height: 100%"></a>
                        </div>
                        <p><?php echo substr($row['prod_name'], 0, 50)?></p>
                    </div>
                <?php 
                endforeach;
                ?>
            </div>
            

            <!-- start Testimonials  -->
            <div class="testimonials" id="testimonials">
            <h2 class="use-a-lot2">Testimonials</h2>

                <?php
                if (isset($_SESSION['USER_NAME'])) {
                    $check_user = $conn->prepare("SELECT * FROM ORDER_ITEMS OI JOIN ORDERS O 
                                                    ON OI.ORDER_ID = O.ORDER_ID
                                                    WHERE OI.PROD_ID = ? AND O.USER_ID = ? AND O.STATUS = ?");
                    $check_user->execute(array($compId, $_SESSION['USER_ID'], 'buyed'));

                    if($check_user->rowCount() > 0) {

                    ?>
                        <h3>Your Comment: </h3>
                        <form class="comment" action="comments.php?prodid=<?php echo $compId ?>" method="POST">
                        <input type="text" name="comment" placeholder="Your Comment" class="form-control">
                        
                        <select name="rate" class="form-control" style="width: 50px">
                            <option value="0">....</option>
                            <option value="1">*</option>
                            <option value="2">**</option>
                            <option value="3">***</option>
                            <option value="4">****</option>
                            <option value="5" selected>*****</option>
                        </select>
                        <div class="chose">
                            <input type="submit" value="Comment" name='COMMENT'>
                        </div>
                        </form>
                    <?php
                    }
                }

                    $stmt = $conn->prepare("SELECT * FROM COMMENTS WHERE prod_id = ? LIMIT 5");
                    $stmt->execute(array($compId));
                    $rows = $stmt->fetchAll();
                
                    foreach($rows as $row):
                        $stmt_user = $conn->prepare("SELECT * FROM USERS U JOIN COMMENTS C ON U.USER_ID = C.USER_ID WHERE C.USER_ID = ?");
                        $stmt_user->execute(array($row['user_id']));
                        $row_user = $stmt_user->fetch();
                

                ?>
                <div class="testimonial">
                    <div class="image">
                    <img src="<?php echo "docs/images/user_images/" . $row_user['image']?>" alt="">
                    </div>
                    
                    <h3><?php echo $row_user['user_name'] ?></h3>
                    <?php 
                        if ($row['rate'] == '1'):
                            getRate(1);
                        elseif ($row['rate'] == '2'):
                            getRate(2);
                        elseif ($row['rate'] == '3'):
                            getRate(3);
                        elseif ($row['rate'] == '4'):
                            getRate(4);
                        elseif ($row['rate'] == '5'):
                            getRate(5);
                        else:
                            getRate(0);
                        endif;
                    ?>
                        <span style='color: #d49671; font-size: 14px'><?php echo $row['date'] ?></span>
                        <p class='mb-1'><?php echo $row['text'] ?></p>
                        <span class="line"></span>
                    </div>
                    <?php
                        endforeach; 
                    ?>
            </div>
            <!-- end Testimonials  -->
        </div>
    </div>
    <!-- end content -->

<?php
    }
    else {
        echo "<script>
            alert('THE COMPUTER NOT FOUND');
            window.open('computers.php', '_self');
            </script>";
    }

        include $tpl . 'footer.php';

    ?>          
    <script>
    $(function() {
        $("#img1").mouseenter(function() {
        $("#image").attr("src", "<?php echo "docs/images/computer_images/" . $image_1[0]['url'] ?>");
        });

        $("#img2").mouseenter(function() {
        $("#image").attr("src", "<?php echo "docs/images/computer_images/" . $image_1[1]['url'] ?>");
        });

        $("#img3").mouseenter(function() {
        $("#image").attr("src", "<?php echo "docs/images/computer_images/" . $image_1[2]['url'] ?>");
        });

        $("#img4").mouseenter(function() {
        $("#image").attr("src", "<?php echo "docs/images/computer_images/" . $image_1[3]['url'] ?>");
        });

        $("#img5").mouseenter(function() {
        $("#image").attr("src", "<?php echo "docs/images/computer_images/" . $image_1[4]['url'] ?>");
        });

    });
    </script>

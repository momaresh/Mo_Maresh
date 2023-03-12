<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();

    $setTitle = 'Mo_Maresh/Books';
    include 'initial.php';

    $bookId = (isset($_GET['bookid'])) &&  is_numeric($_GET['bookid']) ? intval($_GET['bookid']) : 0;

    $stmt = $conn->prepare("SELECT * FROM products JOIN books ON prod_id = book_id WHERE book_id = ?");
    $stmt->execute(array($bookId));
    $row = $stmt->fetch();


    if(isset($_POST['item'])) {
            $quantity = $_POST['quantity'];
            $price = $_POST['price'];

            $stmt_order = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ?");
            $stmt_order->execute(array($_SESSION['USER_ID'], 'ordered'));

            if ($stmt_order->rowCount() > 0):
                $order = $stmt_order->fetch();

                $stmt_item = $conn->prepare("SELECT * FROM order_items WHERE prod_id = ? AND order_id = ?");
                $stmt_item->execute(array($bookId, $order['order_id']));

                if($stmt_item->rowCount() > 0){
                    $error = "THE ITEM IS ALREADY IN THE CART";
                }
                else{
                    $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                    $stmt_p->execute(array($bookId));

                    $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                    $stmt_ins->execute(array($order['order_id'], $bookId, $quantity, ($quantity * $price)));
                }


            else:
                $stmt_order2 = $conn->prepare("INSERT INTO orders(user_id, status) VALUES(?, ?)");
                $stmt_order2->execute(array($_SESSION['USER_ID'], 'ordered'));

                $stmt_id = $conn->prepare("SELECT MAX(order_id) FROM orders");
                $stmt_id->execute();
                $col = $stmt_id->fetchColumn();


                $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                $stmt_p->execute(array($bookId));

                $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                $stmt_ins->execute(array($col, $bookId, $quantity, ($quantity * $price)));
                $success = 'THE ITEM ADD SUCCESSFULLY';
            endif;
    }

    

    if(isset($_SESSION['USER_NAME'])) {
        $stmt_view = $conn->prepare("SELECT * FROM list WHERE user_id = ? AND prod_id = ?");
        $stmt_view->execute(array($_SESSION['USER_ID'], $bookId));
    }

    if (isset($_POST['AddList'])) {
        if ($stmt_view->fetch() == 0):
            $stmt2 = $conn->prepare("INSERT INTO list VALUES(? , ?, now())");
            $stmt2->execute(array($_SESSION['USER_ID'], $bookId));
            header("location: book.php?bookid=$bookId");
        endif;
    }

    if (isset($_POST['DeleteList'])) {
        $stmt = $conn->prepare("DELETE FROM list WHERE user_id = ? AND prod_id = ?");
        $stmt->execute(array($_SESSION['USER_ID'], $bookId));
        header("location: book.php?bookid=$bookId");
    }
    

    if ($stmt->rowCount() > 0){

        $stmt_com = $conn->prepare("SELECT COUNT(*) FROM COMMENTS WHERE prod_id = ?");
        $stmt_com->execute(array($bookId));
        $row_com = $stmt_com->fetchColumn();

        $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ?");
        $images->execute(array($row['prod_id']));
        $image = $images->fetch();
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
            <form action="book.php?bookid=<?php echo $row['prod_id']; ?>" method="POST">
                <input class="form-control" style="width: 30%; margin: 10px auto" type="number" name="quantity" value="1">
                <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                <input class="btn" style="width: 70%; background-color: var(--third-color) " type="submit" name="item" value="Add" >
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
                <div class="image">
                    <img src="<?php echo "docs/images/book_images/" . $image['url'] ?>" alt="">
                </div>
                
                <div class="info">
                    <div class="title">
                        <h2><?php echo $row['prod_name'] ?></h2>
                        <a href="#"><?php echo $row['author'] ?></a>
                    </div>

                    <?php
                        $stmt_rate = $conn->prepare("SELECT AVG(rate) FROM comments WHERE prod_id = ?");
                        $stmt_rate->execute(array($row['prod_id']));
                        $row_rate = $stmt_rate->fetchcolumn();
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
                        <p><?php echo $row['desc1'] ?></p>

                        <p><?php echo $row['desc2'] ?></p>
                        
                        <?php
                        if(!empty($row['desc3'])) { ?>
                            <p id="about"><?php echo $row['desc3'] ?></p>
                            <span id="more">-------More-------</span>
                            <span id="less">-------Less-------</span>
                        <?php
                        }
                        ?>
                    </div>

                    <div class="data">
                        <div class="datum">
                            <div class="datum-desc">
                                <div>
                                    Categories:
                                </div>        
                                <div>
                                    <?php
                                    $stmt_cat = $conn->prepare("SELECT category_name FROM categories WHERE book_id = ?");
                                    $stmt_cat->execute(array($row['book_id']));
                                    $cat_rows = $stmt_cat->fetchAll();
                                    foreach ($cat_rows as $cat_row):
                                        echo $cat_row['category_name'] . ' '; 
                                    endforeach;
                                    ?>
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Publisher:
                                </div>        
                                <div>
                                    <?php
                                    
                                    $stmt2 = $conn->prepare("SELECT full_name FROM users WHERE user_id = ?");
                                    $stmt2->execute(array($row['sup_id']));
                                    $sup_row = $stmt2->fetch();
                                    echo($sup_row['full_name']) ?>
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Pages:
                                </div>        
                                <div>
                                    <?php echo $row['pages'] ?>
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Book_ISBN:
                                </div>        
                                <div>
                                    <?php echo $row['book_id'] ?>
                                </div>                                                  
                            </div>                           
                        </div>

                        <div class="datum">
                            <div class="datum-desc">
                                <div>
                                    Price:
                                </div>        
                                <div>
                                    $<?php echo $row['price'] ?>
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Size:
                                </div>        
                                <div>
                                    <?php echo $row['size'] ?> MB
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Language:
                                </div>        
                                <div>
                                    <?php echo $row['language'] ?>
                                </div>                                                  
                            </div>  
                            <div class="datum-desc">
                                <div>
                                    Year:
                                </div>        
                                <div>
                                    <?php echo $row['sup_date'] ?>
                                </div>                                                  
                            </div>                           
                        </div>
                    </div>
                </div>
            </div>
            <p class="most">You may be interested in</p>
            <div class="books">
                <?php
                $stmt = $conn->prepare("SELECT b.book_id FROM books b JOIN categories c
                                        ON b.book_id = c.book_id WHERE c.category_name IN
                                        (SELECT category_name FROM categories WHERE book_id = ?) 
                                        GROUP BY b.book_id LIMIT 10");
                $stmt->execute(array($row['book_id']));
                $rows = $stmt->fetchAll();
                foreach($rows as $row):

                    $stmt_like = $conn->prepare("SELECT * FROM products JOIN books ON prod_id = book_id WHERE book_id = ?");
                    $stmt_like->execute(array($row['book_id']));
                    $like_rows = $stmt_like->fetchAll();
                    foreach($like_rows as $like_row):
                        $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? ORDER BY prod_id DESC LIMIT 1");
                        $images->execute(array($row['book_id']));
                        $image = $images->fetch();
                ?>
                    <div class="book">
                        <div class="image">
                            <a href="book.php?bookid=<?php echo $like_row['book_id']?>"><img src="<?php echo "docs/images/book_images/" . $image['url'] ?>" alt=""></a>
                        </div>
                        <p><?php echo $like_row['prod_name']?></p>
                    </div>
                <?php 
                    endforeach;
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
                    $check_user->execute(array($bookId, $_SESSION['USER_ID'], 'buyed'));

                    if($check_user->rowCount() > 0) {

                ?>
                        <h3>Your Comment: </h3>
                        <form class="comment" action="comments.php?prodid=<?php echo $bookId ?>" method="POST">
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
                    $stmt->execute(array($bookId));
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
                    //  echo star_fill($rows['rate']);

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

<?php
    }
    else {
        echo "<script>
            alert('THE BOOK NOT FOUND');
            window.open('books.php', '_self');
            </script>";
    }

        include $tpl . 'footer.php';

?>
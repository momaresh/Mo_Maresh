<?php
    session_start();
    $setTitle = 'Payment';
    if(isset($_SESSION['USER_NAME'])) {
        include 'initial.php';

        $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ? ORDER BY order_id DESC LIMIT 1");
        $stmt->execute(array($_SESSION['USER_ID'], 'ordered'));

        if($stmt->rowCount() > 0):

            if(isset($_POST['check'])):
                $number = $_POST['number'];
                $PIN = $_POST['PIN'];
                //$type = $_POST['type'];
                //$holder = $_POST['holder'];
                $loc = $_POST['location'];
                $ship = $_POST['shipper'];
                $price = $_POST['price'];
                $Id = $_POST['order_id'];
            

                $errors = array();
                if(strlen($number) > 20):
                    $errors['num1'] = "THE NUMBER IS 16 DIGIT ONLY";
                endif;

                // if($type != 'Visa' && $type != 'Paypal' && $type != 'CardMaster'):
                //     $errors['type'] = "THE TYPE IS ONLY {VISA, CARDMASTER, PAYPAL}";
                // endif;

                $stmt2 = $conn->prepare("SELECT * FROM cards WHERE number = ? AND pin = ?");
                $stmt2->execute(array($number, $PIN));
                $row2 = $stmt2->fetch();

                if($stmt2->rowcount() <= 0):
                    $errors['card'] = "THE CARD NOT FOUND";
                
                elseif ($row2['amount'] < $price):
                    $errors['amount'] = "THE AMOUNT NOT ENOUGH";
                endif;
                
                        
                if(empty($errors)):

                    $stmt_c = $conn->prepare("UPDATE CARDS SET AMOUNT = AMOUNT - $price WHERE CARD_ID = ?");
                    $stmt_c->execute(array($row2['card_id']));

                    $stmt2 = $conn->prepare("UPDATE ORDERS SET  CARD_ID = ?, 
                                                                SHIP_ID = ?,
                                                                LOC_ID = ?,
                                                                STATUS = ?,
                                                                SHIP_DATE = now()
                                                                WHERE ORDER_ID = ?");
                    $stmt2->execute(array($row2['card_id'], $ship, $loc, 'buyed', $Id));
                    echo "<script>
                        alert('SUCCESSFULLY <br> THANKS FOR TRUST US');
                        window.open('orders.php', '_self');
                        </script>";
                endif;
            endif;
    

        
            $row = $stmt->fetch();

            $ship_stmt = $conn->prepare('SELECT * FROM SHIPPERS');
            $ship_stmt->execute();
            $ships = $ship_stmt->fetchAll();

            $loc_stmt = $conn->prepare('SELECT * FROM LOCATIONS');
            $loc_stmt->execute();
            $locs = $loc_stmt->fetchAll();
        
    ?>
            <!-- start price -->
            <div class="prices">
                <h2 class="use-a-lot2 mb-0">Payment</h2>
                <div class="container">
                    <form class="price" action="price.php" method="POST">
                        <input type="hidden" name="price" value="<?php echo $row['total_price'] ?>">
                        <input type="hidden" name="order_id" value="<?php echo $row['order_id'] ?>">
                        <span class="pri"><span style="color:black">Total:</span> $<?php echo $row['total_price'] ?></span>
                        <span class="error">
                            <?php 
                            if(isset($errors['amount'])) echo '* ' . $errors['amount'] 
                            ?>
                        </span>
                        <ul>
                            <!-- <li>
                                <i class="fa-solid fa-check"></i>
                                <select id="card-type" name="type" required>
                                    <option value="Paypal">Paypal</option>
                                    <option value="Visa">Visa</option>
                                    <option value="CardMaster">CardMaster</option>
                                </select>
                                <span class="error">
                                    <?php 
                                    //if(isset($errors['type'])) echo '* ' . $errors['type'] 
                                    ?>
                                </span>
                            </li> -->
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <input type="text" name="number" id="card-num" placeholder="1111 2222 3333 4444" required>
                                <span class="error">
                                    <?php 
                                    if(isset($errors['num'])) echo '* ' . $errors['num'];
                                    elseif(isset($errors['card'])) echo '* ' . $errors['card'];
                                    ?>
                                </span>
                            </li>

                            <li>
                                <i class="fa-solid fa-check"></i>
                                <input type="password" name="PIN" id="card-pin" placeholder="Card PIN" required>
                            </li>

                            <!-- <li>
                                <i class="fa-solid fa-check"></i>
                                <input type="text" name="holder" placeholder="Holder Name" required>
                            </li> -->

                            <label for="">CHOOSE THE LOCATION</label>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <select class="btn select" name='location'>
                                <?php
                                    foreach ($locs as $loc):
                                ?>
                                    <option value='<?php echo $loc['loc_id'] ?>'><?php echo "$loc[country] - $loc[city]"?></option>
                                <?php
                                    endforeach;
                                ?>
                                </select>
                            </li>

                            <label for="">CHOOSE THE SHIPPER</label>
                            <li>
                                <i class="fa-solid fa-check"></i>
                                <select class="btn select" name='shipper' style='border: 1px solid var(--third-color)'>
                                <?php
                                    foreach ($ships as $ship):
                                ?>
                                    <option value='<?php echo $ship['ship_id'] ?>'><?php echo $ship['name'] ?></option>
                                <?php
                                    endforeach;
                                ?>
                                </select>
                            </li>
                        </ul>
                        <?php
                        $stmt_reg = $conn->prepare("SELECT reg_status FROM USERS WHERE user_name = ?");
                        $stmt_reg->execute(array($_SESSION['USER_NAME']));
                        $row_reg = $stmt_reg->fetch();

                        if($row_reg['reg_status'] == 0) { ?>
                            <p class="btn btn-primary mb-4 btn-lg pl-5 pr-5" style="cursor: none">Pay</p>
                            <p>please message the admin to activate you</p>
                            <?php
                        }
                        else { ?>
                            <input class="btn btn-primary mb-4 btn-lg pl-5 pr-5" name='check' type="submit" value="Pay">
                        <?php
                        }
                        ?>
                    </form>
                </div>
            </div>
            <!-- end price -->
<?php

        else: 
            redirectToHome("<div class='alert alert-danger'>YOU DON NOT HAVE ANY ORDER YET</div>", 'main.php');
        endif; 
        include $tpl . 'script_only.php';
    }

    else {
        header('location: admin/index.php');
        exit();
    }

?>
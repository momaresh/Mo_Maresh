<?php

    session_start();
    $setTitle = 'Orders';

    if (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 1):
        include 'initial.php';
        $do = (isset($_GET['do']) ? $_GET['do'] : 'Manage');

        if(isset($_POST['updateItem'])) {
            $order_id = $_POST['order_id'];
            $prod_id = $_POST['prod_id'];
            $card_id = $_POST['card_id'];
            $quant = $_POST['quant'];
            $old_price = $_POST['price'] ;

            $stmt1 = $conn->prepare("SELECT price FROM products WHERE prod_id = ?");
            $stmt1->execute(array($prod_id));
            $row1 = $stmt1->fetch();

            $total_price = $row1['price'] * $quant;

            $stmt2 = $conn->prepare("SELECT * FROM cards WHERE card_id = ?");
            $stmt2->execute(array($card_id));
            $row2 = $stmt2->fetch();
                        
            if(($row2['amount'] + $old_price) < $total_price) {
                echo "<script>
                        alert('Amount NOT enough');
                        window.open('orders.php', '_self');
                        </script>";
            }
            else {
                try {
                    $stmt_c = $conn->prepare("UPDATE cards SET amount = ((amount + ?) - ?)  WHERE card_id = ?");
                    $stmt_c->execute(array($old_price, $total_price, $card_id));
    
                    $stmt_o = $conn->prepare("UPDATE orders SET total_price = ((total_price - ?) + ?)  WHERE order_id = ?");
                    $stmt_o->execute(array($old_price, $total_price, $order_id));
    
                    $stmt_oi = $conn->prepare("UPDATE order_items SET total_price = ?, quantity = ?  WHERE order_id = ? AND prod_id = ?");
                    $stmt_oi->execute(array($total_price, $quant, $order_id, $prod_id));
    
                    echo "<script>
                        alert('Updated Successfully');
                        window.open('orders.php', '_self');
                        </script>";
                }
                catch(Exception $e) {
                    $error_up = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                }
            }
        }

        if(isset($_POST['deleteItem'])) {
            $order_id = $_POST['order_id'];
            $prod_id = $_POST['prod_id'];

            try {
                $sql_del = "DELETE FROM order_items WHERE order_id = ? AND prod_id = ?";
                $stmt_del = $conn->prepare($sql_del);
                $stmt_del->execute(array($order_id, $prod_id));
                echo "<script>
                    alert('Deleted Successfully');
                    window.open('orders.php', '_self');
                    </script>";
                
            }
            catch(Exception $e) {
                $error_del = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
            }
        }

        if($do == "Manage") { ?>

            <h3 class="use-a-lot2 mb-2 mt-5">
                Orders
            </h3>

            <form class="search" action="" method='POST'>
                <input type="text" name="user_name" placeholder="Search by user name" id="search">
                <input type="submit" name="search" value="Search" id="button">
            </form>

            <div class="container">
                <a href="?do=AddOrder" class="btn btn-primary mb-2">ADD ORDER</a>
            </div>

            <?php
            if(isset($error_up)) echo $error_up;
            if(isset($error_del)) echo $error_del;

            $search = ''; 
            if(isset($_POST['search'])) {
                $user_name = $_POST['user_name'];
                
                if(!empty($user_name)) {
                    $stmt_cus = $conn->prepare("SELECT user_id FROM USERS WHERE user_name = ?");
                    $stmt_cus->execute(array($user_name));
                    $cus = $stmt_cus->fetch();

                    $search = "WHERE user_id = $cus[user_id]";
                }
            }

            $orders_stmt = $conn->prepare("SELECT * FROM ORDERS $search ORDER BY STATUS DESC, ORDER_ID DESC");
            $orders_stmt->execute();
            
            if($orders_stmt->rowCount() > 0):
                $orders = $orders_stmt->fetchAll();

                foreach($orders as $order):
                    $stmt_cus = $conn->prepare("SELECT user_name FROM USERS WHERE user_id = ?");
                    $stmt_cus->execute(array($order['user_id']));
                    $cus = $stmt_cus->fetch();

                    $stmt_item = $conn->prepare("SELECT * FROM ORDER_ITEMS WHERE order_id = ?");
                    $stmt_item->execute(array($order['order_id']));
                    $items = $stmt_item->fetchAll();

            ?>

                    <section class="h-100 gradient-custom">
                        <div class="container py-5 h-100">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="col-lg-10 col-xl-8">
                                    <div class="card" style="border-radius: 10px;">
                                        <div class="card-header px-5 py-4" style="background-color: var(--main-color);">
                                            <h5 class="mb-0" style="color: white;">This order was by, <span style="color: var(--third-color);"><?php echo $cus['user_name'] ?></span>!</h5>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-0">
                                                <p class="lead fw-normal mb-0" style="color: var(--third-color);">Receipt</p>
                                            </div>
                                        </div>
                                        <div class="card shadow-0 border mb-4">
                                            <?php
                                            foreach($items as $item):
                                                $stmt_prod = $conn->prepare("SELECT * FROM PRODUCTS WHERE prod_id = ?");
                                                $stmt_prod->execute(array($item['prod_id']));
                                                $prod = $stmt_prod->fetch();
                            
                                            ?>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                        <p class="text-muted mb-0"><?php echo substr($prod['prod_name'], 0, 20) ?></p>
                                                    </div>
                                                    <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                        <p class="text-muted mb-0 small"><span class="fw-bold me-4">Price:</span> $<?php echo round($item['total_price'], 2) ?></p>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <p class="text-muted mb-0"><span class="fw-bold me-4">Type:</span><?php echo substr($prod['type'], 0, 20) ?></p>
                                                    </div>
                                                    <?php
                                                    if($order['status'] == 'ordered'): ?>
                                                        <form action="orders.php" method="POST" class="col-md-6 d-flex justify-content-center align-items-center">
                                                            <input type="hidden" name="order_id" value="<?php echo $item['order_id'] ?>">
                                                            <input type="hidden" name="card_id" value="<?php echo $order['card_id'] ?>">
                                                            <input type="hidden" name="prod_id" value="<?php echo $item['prod_id'] ?>">
                                                            <input type="hidden" name="price" value="<?php echo $item['total_price'] ?>">
                                                            <div style="width: 90px">
                                                                <span class="fw-bold me-4">Qty:</span><input class="form-control form-control-lg text-center" type="number" name="quant" value="<?php echo $item['quantity'] ?>">
                                                            </div>
                                                            <button style="width: 60px; margin-top: 20px; margin-left: 10px" type="submit" name="updateItem" class="btn btn-white border-secondary bg-white btn-md">
                                                                <i class="fas fa-sync"></i>
                                                            </button>

                                                            <button style="width: 60px; margin-top: 20px; margin-left: 10px" type="submit" name="deleteItem" class="btn btn-white border-secondary bg-white btn-md">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php
                                                    elseif($order['status'] == 'buyed'): ?>
                                                        <div class="col-md-2">
                                                            <span class="fw-bold me-4">Qty:</span><?php echo $item['quantity'] ?>
                                                        </div>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </div>
                                            </div>
                                            <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                                            <?php
                                            endforeach;
                                            ?>
                                        </div>
                                            <?php
                                            $stmt_ship = $conn->prepare("SELECT * FROM SHIPPERS WHERE ship_id = ?");
                                            $stmt_ship->execute(array($order['ship_id']));
                                            $ship = $stmt_ship->fetch();

                                            $stmt_loc = $conn->prepare("SELECT * FROM LOCATIONS WHERE loc_id = ?");
                                            $stmt_loc->execute(array($order['loc_id']));
                                            $loc = $stmt_loc->fetch();

                                            ?>

                                        <div class="d-flex justify-content-between p-2">
                                            <p class="fw-bold mb-0">Order Details</p>
                                            <p class="text-muted mb-0"><span class="fw-bold me-4">Total: </span> $<?php echo $order['total_price'] ?></p>
                                            <p class="text-muted mb-0"><span class="fw-bold me-4">Invoice Date:</span> <?php echo $order['order_date'] ?></p>
                                        </div>

                                        <div class="d-flex justify-content-between p-2">
                                            <p class="text-muted mb-0"><span class="fw-bold me-4">Location:</span> <?php if(isset($loc['country']) && (isset($loc['city']))) echo $loc['country']. ' ' . $loc['city']; ?></p>
                                            <p class="text-muted mb-0"><span class="fw-bold me-4">Shipper:</span> <?php if(isset($ship['name'])) echo $ship['name'];?></p>
                                            <p class="text-muted mb-0"><span class="fw-bold me-4">Ship Date:</span> <?php echo $order['ship_date'] ?></p>
                                        </div>
                                        <?php
                                            if($order['status'] == 'ordered'): ?>
                                                <div class="mt-2 mb-2">
                                                <a href="?do=AddItem&id=<?php echo $order['order_id'];?>" class="btn" style="background-color: #ff6a00; width: fit-content; display: inline">Add Item</a>
                                                <a href="?do=BuyOrder&id=<?php echo $order['order_id'];?>" class="btn" style="background-color: #4eb67f; width: fit-content; display: inline">Buy</a>
                                                </div>
                                        <?php
                                            endif;
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

            <?php
                endforeach;
            else:
                echo "<div class='container text-center alert alert-info'> There is no orders for this user.....!</div>";
            endif;
        }
        elseif($do == 'AddItem') {
            $orderId = (isset($_GET['id']) ? $_GET['id'] : 0);

            if(isset($_POST['add'])) {
                $prod_id = $_POST['prod_id'];
                $quant = $_POST['quantity'];

                $stmt1 = $conn->prepare("SELECT price FROM products WHERE prod_id = ?");
                $stmt1->execute(array($prod_id));
                $row1 = $stmt1->fetch();

                $total_price = $row1['price'] * $quant;

                $stmt_card = $conn->prepare("SELECT card_id FROM orders WHERE order_id = ?");
                $stmt_card->execute(array($orderId));
                $row_card = $stmt_card->fetch();

                $stmt2 = $conn->prepare("SELECT * FROM cards WHERE card_id = ?");
                $stmt2->execute(array($row_card['card_id']));
                $row2 = $stmt2->fetch();

                if($stmt2->rowCount() <= 0){
                    echo "<script>
                        alert('Card NOT Found');
                        window.open('orders.php', '_self');
                        </script>";
                }
                elseif($row2['amount'] < $total_price) {
                    echo "<script>
                        alert('Amount NOT enough');
                        window.open('orders.php', '_self');
                        </script>";
                }

                try {
                    $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                    $stmt_p->execute(array($prod_id));
    
                    $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                    $stmt_ins->execute(array($orderId, $prod_id, $quant, $total_price));

                    echo "<script>
                        alert('SUCCESSFULLY');
                        window.open('orders.php', '_self');
                        </script>";
                }
                catch(Exception $e) {
                    $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                }
            }

            ?>
            <h3 class="use-a-lot2 mb-2 mt-5">ADD ITEM</h3>
                <form class="form-row company-form" method="POST" action="?do=AddItem&id=<?php echo $orderId; ?>">
                    <?php
                    if(isset($error)) echo $error;
                    ?>
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Product Name</label>
                        <select class="form-control" name="prod_id" required>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM products");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row): ?>
                                <option value="<?php echo $row['prod_id'] ?>"><?php echo substr($row['prod_name'],0, 30) ?></option>
                            <?php
                            endforeach;
                        ?>
                        </select>
                        <span class="error">
                            <?php 
                            if(isset($errors['name'])) echo '* ' . $errors['name']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Quantity</label>
                        <input type="number" class="form-control" min="1" name="quantity" value="1" id="inputPassword4" placeholder="Quantity" required>
                        <span class="error">
                            <?php 
                            if(isset($errors['quant'])) echo '* ' . $errors['quant']; 
                            ?>
                        </span>
                    </div>

                    <button type="submit" name="add" class="btn btn-primary">Add</button>
                </form>
            <?php
        }

        elseif($do == 'AddOrder') {
            if(isset($_POST['add'])) {
                $cus_id = $_POST['cus_id'];
                $card_number = $_POST['card_number'];
                $card_pin = $_POST['card_pin'];
                $prod_id = $_POST['prod_id'];
                $quant = $_POST['quantity'];
                $loc_id = $_POST['loc_id'];
                $ship_id = $_POST['ship_id'];

                $errors = array();
                // Get the price from the product
                $stmt1 = $conn->prepare("SELECT price FROM products WHERE prod_id = ?");
                $stmt1->execute(array($prod_id));
                $row1 = $stmt1->fetch();

                $total_price = $row1['price'] * $quant;

                $stmt2 = $conn->prepare("SELECT * FROM cards WHERE number = ? AND pin = ?");
                $stmt2->execute(array($card_number, $card_pin));
                $row2 = $stmt2->fetch();

                if($stmt2->rowCount() <= 0){
                    $errors['cardExists'] = "<div class='alert alert-danger'>The card NOT found</div>";
                }
                elseif($row2['amount'] < $total_price) {
                    $errors['amount'] = "<div class='alert alert-danger'>The amount NOT enough</div>";
                }
               
                if(empty($cus_id)) {
                    $errors['name'] = "The customer is required";
                }
                if(empty($card_number)) {
                    $errors['card'] = "The card number is required";
                }
                if(empty($prod_id)) {
                    $errors['prod'] = "The product is required";
                }
                if(empty($quant)) {
                    $errors['quant'] = "The quantity is required";
                }

                if(empty($errors)) {
                    try {
                        // We don't need to insert the total price manual we had made a trigger to do that
                        $stmt3 = $conn->prepare("INSERT INTO ORDERS(user_id, card_id, loc_id, ship_id, status)
                                                VALUES(?, ?, ?, ?, ?)");
                        $stmt3->execute(array($cus_id, $row2['card_id'], $loc_id, $ship_id, 'ordered'));

                        $stmt_id = $conn->prepare("SELECT MAX(order_id) FROM orders");
                        $stmt_id->execute();
                        $col = $stmt_id->fetchColumn();
        
                        $stmt_p = $conn->prepare("UPDATE products SET buying = buying + 1 WHERE prod_id = ?");
                        $stmt_p->execute(array($prod_id));
        
                        $stmt_ins = $conn->prepare("INSERT INTO order_items(order_id, prod_id, quantity, total_price) VALUES(?, ?, ?, ?)");
                        $stmt_ins->execute(array($col, $prod_id, $quant, $total_price));

                        echo "<script>
                            alert('SUCCESSFULLY');
                            window.open('orders.php', '_self');
                            </script>";
                    }
                    catch(Exception $e) {
                        $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    }
                } 
            }

            ?>
            <h3 class="use-a-lot2 mb-2 mt-5">ADD ORDER</h3>
                <form class="form-row company-form" method="POST" action="?do=AddOrder">
                    <?php
                    if(isset($errors['cardExists'])) echo $errors['cardExists'];
                    if(isset($errors['amount'])) echo $errors['amount'];
                    ?>
                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Customer Name</label>
                        <select class="form-control" name="cus_id" required>
                        <?php
                            $stmt = $conn->prepare("SELECT * FROM users ORDER BY user_name");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row): ?>
                                <option value="<?php echo $row['user_id'] ?>"><?php echo $row['user_name'] ?></option>
                            <?php
                            endforeach;
                        ?>
                        </select>
                        <span class="error">
                            <?php 
                            if(isset($errors['name'])) echo '* ' . $errors['name']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Card Number</label>
                        <input type="text" class="form-control" name="card_number" id="inputPassword4" placeholder="1111 2222 3333 4444" required>
                        <span class="error">
                            <?php 
                            if(isset($errors['card'])) echo '* ' . $errors['card']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Card PIN</label>
                        <input type="password" class="form-control" name="card_pin" id="inputPassword4" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputEmail4">Product Name</label>
                        <select class="form-control" name="prod_id" required>
                        <?php
                            $stmt2 = $conn->prepare("SELECT * FROM products ORDER BY prod_name");
                            $stmt2->execute();
                            $rows2 = $stmt2->fetchAll();
                            foreach($rows2 as $row2): ?>
                                <option value="<?php echo $row2['prod_id'] ?>"><?php echo substr($row2['prod_name'], 0, 30) ?></option>
                            <?php
                            endforeach;
                        ?>
                        </select>
                        <span class="error">
                            <?php 
                            if(isset($errors['prod'])) echo '* ' . $errors['prod']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="inputPassword4">Quantity</label>
                        <input type="number" class="form-control" min="1" name="quantity" value="1" id="inputPassword4" placeholder="Quantity" required>
                        <span class="error">
                            <?php 
                            if(isset($errors['quant'])) echo '* ' . $errors['quant']; 
                            ?>
                        </span>
                    </div>

                    <div>
                        <label for="">Location</label>
                        <select class="form-control" name='loc_id'>
                            <?php
                                $loc_stmt = $conn->prepare('SELECT * FROM LOCATIONS');
                                $loc_stmt->execute();
                                $locs = $loc_stmt->fetchAll();

                                foreach ($locs as $loc):
                            ?>
                                <option value='<?php echo $loc['loc_id'] ?>'><?php echo "$loc[country] ... $loc[city]"?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>

                    <div>
                        <label for="">Shipper</label>
                        <select class="form-control" name='ship_id'>
                            <?php
                                $ship_stmt = $conn->prepare('SELECT * FROM SHIPPERS');
                                $ship_stmt->execute();
                                $ships = $ship_stmt->fetchAll();

                                foreach ($ships as $ship):
                            ?>
                                <option value='<?php echo $ship['ship_id'] ?>'><?php echo $ship['name']?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>

                <button type="submit" name="add" class="btn btn-primary">Add</button>
                </form>
            <?php
        }

        elseif($do == 'BuyOrder') {
            $id = (isset($_GET['id']) ? $_GET['id'] : 0);
            try {
                $stmt = $conn->prepare("UPDATE orders SET status= ? WHERE order_id = ?");
                $stmt->execute(array('buyed', $id));
                echo "<script>
                    alert('SUCCESSFULLY');
                    window.open('orders.php', '_self');
                    </script>";
            }
            catch(Exception $e) {
                echo "<script>
                    alert(" . $e->getMessage() . ");
                    window.open('orders.php', '_self');
                    </script>";
            }
        }
        
        include($tpl . 'footer.php');

    else:
        header('location: admin/index.php');
        exit();
    endif;
?>
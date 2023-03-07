<?php

    session_start();
    $setTitle = 'Your Orders';

    if (isset($_SESSION['USER_NAME'])):
        include 'initial.php';

        $orders_stmt = $conn->prepare('SELECT * FROM ORDERS WHERE USER_ID = ? AND status = ? ORDER BY ORDER_ID DESC');
        $orders_stmt->execute(array($_SESSION['USER_ID'], 'buyed'));
        
        if($orders_stmt->rowCount() > 0):
            $orders = $orders_stmt->fetchAll();
        ?>
        
        <h3 class="use-a-lot2 mb-2 mt-5">
            Your Orders
        </h3>

        <?php
            foreach($orders as $order):
                $stmt_item = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
                $stmt_item->execute(array($order['order_id']));
                $items = $stmt_item->fetchAll();

?>



<section class="h-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-10 col-xl-8">
                <div class="card" style="border-radius: 10px;">
                    <div class="card-header px-4 py-5" style="background-color: var(--main-color);">
                        <h5 class="mb-0" style="color: white;">Thanks for your Order, <span style="color: var(--third-color);"><?php echo $_SESSION['USER_NAME'] ?></span>!</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <p class="lead fw-normal mb-0" style="color: var(--third-color);">Receipt</p>
                        </div>
                    </div>
                    <div class="card shadow-0 border mb-4">
                        <?php
                        foreach($items as $item):
                            $stmt_prod = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");
                            $stmt_prod->execute(array($item['prod_id']));
                            $prod = $stmt_prod->fetch();
        
                            $stmt_image = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? LIMIT 1");
                            $stmt_image->execute(array($item['prod_id']));
                            $img = $stmt_image->fetch();

                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <?php
                                    if($prod['type'] == 'book') {
                                        echo "<a href='book.php?bookid=$prod[prod_id]'><img class='img-fluid d-none d-md-block rounded mb-2 shadow' src='docs/images/book_images/$img[url]' alt=''></a>";
                                    }
                                    else {
                                        echo "<a href='computer.php?compid=$prod[prod_id]'><img class='img-fluid d-none d-md-block rounded mb-2 shadow' src='docs/images/computer_images/$img[url]' alt=''></a>";
                                    }
                                    ?>
                                </div>
                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                    <p class="text-muted mb-0"><?php echo substr($prod['prod_name'], 0, 20) ?></p>
                                </div>
                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                    <p class="text-muted mb-0 small">Qty: <?php echo $item['quantity'] ?></p>
                                </div>
                                <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                    <p class="text-muted mb-0 small">$<?php echo $item['total_price'] ?></p>
                                </div>
                            </div>
                        </div>
                        <hr class="mb-4" style="background-color: #e0e0e0; opacity: 1;">
                        <?php
                        endforeach;
                        ?>
                    </div>

                    <div class="d-flex justify-content-between p-2">
                        <p class="fw-bold mb-0">Order Details</p>
                        <p class="text-muted mb-0"><span class="fw-bold me-4">Total</span> $<?php echo $order['total_price'] ?></p>
                    </div>

                    <div class="d-flex justify-content-between p-2">
                        <p class="text-muted mb-0">Invoice Date : <?php echo $order['order_date'] ?></p>
                        <p class="text-muted mb-0"><span class="fw-bold me-4">Number</span> <?php echo $order['order_id'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
            endforeach;
        
        else:
            echo '<div class="alert alert-info mt-5" style="width: 50%; margin: auto">THE ORDERS IS EMPTY</div>';
        endif;

        include($tpl . 'footer.php');

    else:
        header('location: admin/index.php');
        exit();
    endif;
?>
<?php
    session_start();
    $setTitle = 'YOUR CART';
    if (isset($_SESSION['USER_NAME'])) {
        include 'initial.php';
    

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ? AND status = ?");
    $stmt->execute(array($_SESSION['USER_ID'], 'ordered'));

    if ($stmt->rowCount() > 0):
        $order = $stmt->fetch();

        if(isset($_POST['delete'])) {
            $prodId = $_POST['prod_id'];

            $stmt_p = $conn->prepare("UPDATE products SET buying = buying - 1 WHERE prod_id = ?");
            $stmt_p->execute(array($prodId));

            $stmt2 = $conn->prepare("DELETE FROM ORDER_ITEMS WHERE ORDER_ID = ? AND PROD_ID = ?");
            $stmt2->execute(array($order['order_id'], $prodId));

            // CHECK if all the order_items was deleted then the order has no item so it should be deleted
            $stmt3 = $conn->prepare("SELECT * FROM ORDER_ITEMS WHERE ORDER_ID = ?");
            $stmt3->execute(array($order['order_id']));
            if ($stmt3->rowCount() == 0):
                $stmt4 = $conn->prepare("DELETE FROM ORDERS WHERE ORDER_ID = ?");
                $stmt4->execute(array($order['order_id']));
                header('location: cart.php');
                exit();
            endif;
        }

        if(isset($_POST['update'])) {
            $prodId = $_POST['prod_id'];
            $quant = $_POST['quantity'];
            $price = $_POST['price'];
            $price = $price * $quant;

            $stmt3 = $conn->prepare("UPDATE ORDER_ITEMS SET quantity = ?, total_price = ? WHERE PROD_ID = ? AND ORDER_ID = ?");
            $stmt3->execute(array($quant, $price, $prodId, $order['order_id']));
        }



        $sum = 0;

        $stmt_item = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmt_item->execute(array($order['order_id']));
        $items = $stmt_item->fetchAll();


?>

<section class="pt-5 pb-5">
  <div class="container">
    <div class="row w-100">
        <div class="col-lg-12 col-md-12 col-12">
            <div class="use-a-lot text-center pb-1 position-relative">
                <i class="fa-solid fa-5x fa-cart-plus mb-2"></i>
                <h2 class="text-uppercase">Shopping Cart</h2>
                <p class="text-uppercase text-black-50"><i class="text-info font-weight-bold"><?php echo $stmt_item->rowCount()?></i> items in your cart</p>
            </div>
            <table id="shoppingCart" class="table table-condensed table-responsive">
                <thead>
                    <tr>
                        <th style="width:60%">Product</th>
                        <th style="width:12%">Price</th>
                        <th style="width:10%">Quantity</th>
                        <th style="width:16%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($items as $item):
                        $stmt_prod = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");
                        $stmt_prod->execute(array($item['prod_id']));
                        $prod = $stmt_prod->fetch();

                        $stmt_image = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? LIMIT 1");
                        $stmt_image->execute(array($item['prod_id']));
                        $img = $stmt_image->fetch();

                        $sum = $sum + $item['total_price']; // to get the total price of all the order
                    ?>
                    <tr>
                        <td data-th="Product">
                            <div class="row">
                                <div class="col-md-3 text-left">
                                    <?php
                                    if($prod['type'] == 'book') {
                                        echo "<a href='book.php?bookid=$prod[prod_id]'><img src='docs/images/book_images/$img[url]' alt='' class='img-fluid d-none d-md-block rounded mb-2 shadow'></a>";
                                    }
                                    else {
                                        echo "<a href='computer.php?compid=$prod[prod_id]'><img src='docs/images/computer_images/$img[url]' alt='' class='img-fluid d-none d-md-block rounded mb-2 shadow'></a>";
                                    }
                                    ?>
                                </div>
                                <div class="col-md-9 text-left mt-sm-2">
                                    <h4><?php echo substr($prod['prod_name'], 0, 20) ?></h4>
                                </div>
                            </div>
                        </td>
                        <td data-th="Price">$<?php echo $prod['price'] ?></td>

                        <form action="cart.php" method="POST">
                        <td data-th="Quantity">
                            <input type="number" name="quantity" class="form-control form-control-lg text-center" value="<?php echo $item['quantity'] ?>">
                            <input type="hidden" name="prod_id" value="<?php echo $prod['prod_id']; ?>">
                            <input type="hidden" name="price" value="<?php echo $prod['price']; ?>">
                        </td>
                        <td class="actions" data-th="">
                            <div class="text-right">
                                <button type="submit" name="update" class="btn btn-white border-secondary bg-white btn-md mb-2">
                                    <i class="fas fa-sync"></i>
                                </button>

                                <button type="submit" name="delete" class="btn btn-white border-secondary bg-white btn-md mb-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                        </form>

                    </tr>
                    <?php
                        endforeach;
                    ?>
                </tbody>
            </table>
            <div class="float-right text-right">
                <h4>Total Price:</h4>
                <h2 style="color: var(--third-color)">$<?php echo $sum ?></h2>
            </div>
        </div>
    </div>
    <div class="row mt-4 d-flex align-items-center">
        <div class="col-sm-6 order-md-2 text-right">
            <a href="price.php?id=<?php echo $order['order_id']; ?>" class="btn btn-primary mb-4 btn-lg pl-5 pr-5">Payment</a>
        </div>
        <div class="col-sm-6 mb-3 mb-m-1 order-md-1 text-md-left">
            <a href="index.php">
                <i class="fas fa-arrow-left mr-2"></i> Continue Shopping</a>
        </div>
    </div>
</div>
</section>

<?php
        else:
            echo '<div class="alert alert-info mt-5" style="width: 50%; margin: auto">THE CART IS EMPTY</div>';
        endif;
        include ($tpl . 'script_only.php');
    }
    else {
        header('Location: Admin/index.php');
        exit();
    }


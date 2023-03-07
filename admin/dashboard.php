<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Dashboard';
    //THIS IF YOU ALREADY SIGN AND YOU ARE ONLY ADMIN, WILL CHANGE YOU TO THE DASHBOARD AUTOMATIC
    if (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 1) {
        include 'initial.php';
        
?>
    <div class="dash">
        <h1 class="text-left ms-5 mt-5 mb-5" style="color: #ff6a00; font-weight: bold;"><i class="fa fa-dashboard"></i>Dashboard</h1>
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="stat">
                        <i class="fa-solid fa-user"></i>
                        Total Users
                        <span><a href="user.php"><?php echo countItems('users'); ?></a></span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="stat">
                        <i class="fa-solid fa-user-lock"></i>
                        Total Bending
                        <span><a href="user.php?do=Manage&page=bind"><?php echo countBind('users'); ?></a></span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="stat">
                        <i class="fa-solid fa-book"></i>
                        Total Books
                        <span><a href="books.php"><?php echo countItems('books'); ?></a></span>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="stat">
                        <i class="fa-solid fa-computer"></i>
                        Total Computers
                        <span><a href="computers.php"><?php echo countItems('computers'); ?></a></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="last">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-user"></i>
                                Latest Registered Users
                            </div>
                            <div class="table-responsive latest">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #5c5c5c; color: white">
                                        <th>User_Name</th>
                                        <th>Insert_Date</th>
                                        <th>Control</th>
                                    </tr>
                                    <?php 

                                    $lasts = getLatest("users", "user_id");
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo $last['user_name']; ?></td>
                                            <td><?php echo $last['date']; ?></td>
                                            <td>
                                                <a href="user.php?do=Edit&userid=<?php echo $last['user_id'];?>" class="btn" style="background-color: #4eb67f">Edit</a>
                                                <a href="user.php?do=Delete&userid=<?php echo $last['user_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-book"></i>
                                Latest Registered Books
                            </div>
                            <div class="table-responsive latest">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #5c5c5c; color: white">
                                        <th>Book_Name</th>
                                        <th>Insert_Date</th>
                                        <th>Control</th>
                                    </tr>
                                    <?php 

                                    $statement = $conn->prepare("SELECT * FROM products WHERE type = 'book' ORDER BY prod_id DESC LIMIT 5");
                                    $statement->execute();
                                    $lasts = $statement->fetchAll();
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo substr($last['prod_name'],0,30); ?></td>
                                            <td><?php echo $last['sup_date']; ?></td>
                                            <td>
                                                <a href="books.php?do=Edit&bookid=<?php echo $last['prod_id'];?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px">Edit</a>
                                                <a href="books.php?do=Delete&bookid=<?php echo $last['prod_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-computer"></i>
                                Latest Registered Computers
                            </div>
                            <div class="table-responsive latest">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #5c5c5c; color: white">
                                        <th>Computer</th>
                                        <th>Insert_Date</th>
                                        <th>Control</th>
                                    </tr>
                                    <?php 

                                    $statement = $conn->prepare("SELECT * FROM products WHERE type = 'computer' ORDER BY prod_id DESC LIMIT 5");
                                    $statement->execute();
                                    $lasts = $statement->fetchAll();
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo substr($last['prod_name'],0,30); ?></td>
                                            <td><?php echo $last['sup_date']; ?></td>
                                            <td>
                                                <a href="computers.php?do=Edit&compid=<?php echo $last['prod_id'];?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px">Edit</a>
                                                <a href="computers.php?do=Delete&compid=<?php echo $last['prod_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-computer"></i>
                                Latest Registered Orders
                            </div>
                            <div class="table-responsive latest">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #5c5c5c; color: white">
                                        <th>Order_Id</th>
                                        <th>User_Name</th>
                                        <th>Product_Name</th>
                                        <th>Quantity</th>
                                    </tr>
                                    <?php 

                                    $statement = $conn->prepare("SELECT oi.order_id, p.prod_name, oi.quantity, u.full_name FROM order_items oi JOIN products P
                                                                ON oi.prod_id = p.prod_id JOIN orders o
                                                                ON oi.order_id = o.order_id JOIN users u 
                                                                ON o.user_id = o.user_id ORDER BY oi.add_date DESC LIMIT 5");
                                    $statement->execute();
                                    $lasts = $statement->fetchAll();
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo $last['order_id']; ?></td>
                                            <td><?php echo $last['full_name']; ?></td>
                                            <td><?php echo $last['prod_name']; ?></td>
                                            <td><?php echo $last['quantity']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>


<?php
        include $tpl . 'footer.php';
    }
    else {
        header('location: index.php');
        exit();
    }
?>
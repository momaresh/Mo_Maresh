<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Supplier_Dashboard';
    //THIS IF YOU AREADY SIGN AND YOU ARE ONLY ADMIN, WILL CHANGE YOU TO THE DASHBOARD AUTOMATIC
    if (isset($_SESSION['USER_NAME']) && $_SESSION['GROUP_ID'] == 3) {
        include 'initial.php';

        $sup_id = (isset($_SESSION['USER_ID']) ? $_SESSION['USER_ID'] : 0);

?>

    <div class="dash">
        <h1 class="text-left ms-5 mt-5 mb-5" style="color: #ff6a00; font-weight: bold;"><i class="fa fa-dashboard"></i>Dashboard</h1>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="stat">
                        <i class="fa-solid fa-book"></i>
                        Total Books
                        <span><a href="books.php"><?php echo countSupItems('products', "book", $sup_id); ?></a></span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="stat">
                        <i class="fa-solid fa-computer"></i>
                        Total Computers
                        <span><a href="computers.php"><?php echo countSupItems('products', 'computer', $sup_id); ?></a></span>
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

                                    $statment = $conn->prepare("SELECT * FROM products WHERE type = 'book' AND sup_id = $sup_id ORDER BY prod_id DESC LIMIT 5");
                                    $statment->execute();
                                    $lasts = $statment->fetchAll();
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo $last['prod_name']; ?></td>
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
                                Latest Registerd Computers
                            </div>
                            <div class="table-responsive latest">
                                <table class="table table-bordered text-center">
                                    <tr style="background-color: #5c5c5c; color: white">
                                        <th>Copmputer_Name</th>
                                        <th>Insert_Date</th>
                                        <th>Control</th>
                                    </tr>
                                    <?php 

                                    $statment = $conn->prepare("SELECT * FROM products WHERE type = 'computer' AND sup_id = $sup_id ORDER BY prod_id DESC LIMIT 5");
                                    $statment->execute();
                                    $lasts = $statment->fetchAll();
                                    foreach($lasts as $last): ?>
                                        <tr>
                                            <td><?php echo $last['prod_name']; ?></td>
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
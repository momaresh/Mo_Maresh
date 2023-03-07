
<?php
    session_start();
    $setTitle = "Credit cards";

    if(isset($_SESSION['USER_NAME']) && isset($_SESSION['GROUP_ID']) == 1) {
        include('initial.php');

        $sql = "SELECT * FROM CARDS";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $do = (isset($_GET['do']) ? $_GET['do'] : 'Manage');

            if($do == "Manage") {

                if(isset($_POST['insert'])) {
                    $number = $_POST['number'];
                    $pin = $_POST['pin'];
                    $holder = $_POST['holder'];
                    $expire = $_POST['expire'];
                    $type = $_POST['type'];
                    $amount = $_POST['amount'];

                    $errors = array();

                    if(empty($number)) {
                        $errors['number'] = "Number is required";
                    }
                    if(empty($pin)) {
                        $errors['pin'] = "PIN is required";
                    }
                    if(empty($holder)) {
                        $errors['holder'] = "Holder is required";
                    }
                    if(empty($expire)) {
                        $errors['expire'] = "Expire Date is required";
                    }
                    if(empty($type)) {
                        $errors['type'] = "Type is required";
                    }
                    if(empty($amount)) {
                        $errors['amount'] = "Amount is required";
                    }

                    if(empty($errors)){

                        try {
                            $sql2 = "INSERT INTO CARDS(number, pin, holder, expire, type, amount) VALUES(?, ?, ?, ?, ?, ?)";
                            $stmt2 = $conn->prepare($sql2);
                            $stmt2->execute(array($number, $pin, $holder, $expire, $type, $amount));
                            echo "<script>
                                alert('THE CARD INSERTED');
                                window.open('cards.php', '_self');
                                </script>";
                        }
                        catch(Exception $e) {
                            $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                        }
                    }
                }

                if(isset($_POST['updateCard'])) {
                    $card_id = $_POST['card_id'];
                    $amount = $_POST['amount'];

                    try {
                        $sql2 = "UPDATE CARDS SET amount = ?
                        WHERE card_id = ?";
                        $stmt2 = $conn->prepare($sql2);
                        $stmt2->execute(array($amount, $card_id));
                        echo "<script>
                            alert('THE AMOUNT UPDATED');
                            window.open('cards.php', '_self');
                            </script>";
                    }
                    catch(Exception $e) {
                        $error = "<div class='alert alert-danger'>" . $e->getMessage() . "</div>";
                    }
                    
                }

                if(isset($_POST['deleteCard'])) {
                    $id = $_POST['card_id'];
                    try {
                        $stmt2 = $conn->prepare("DELETE FROM CARDS WHERE card_id = ?");
                        $stmt2->execute(array($id));
                        echo "<script>
                            alert('The CARD Deleted');
                            window.open('cards.php#goto', '_self');
                            </script>";
                    }
                    catch(Exception $e)
                    {
                        redirectToHome("<div class='alert alert-danger'>" . $e->getMessage() . "</div>", 'locations.php');
                    }
                }

                ?>

                <h3 class="use-a-lot2 mb-2 mt-5">ADD CARD</h3>
                <form class="form-row company-form" method="POST" action="">
                    <?php
                    if(isset($error)) echo $error;
                    ?>
                    <div class="form-group col-md-4">
                        <label for="">Number</label>
                        <input type="text" class="form-control" name="number" placeholder="1111 2222 3333 4444" required="required">
                        <span class="error">
                            <?php 
                            if(isset($errors['number'])) echo '* ' . $errors['number']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Holder Name</label>
                        <input type="text" class="form-control" name="holder" placeholder="Holder Name" required="required">
                        <span class="error">
                            <?php 
                            if(isset($errors['holder'])) echo '* ' . $errors['holder']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">PIN</label>
                        <input type="password" class="form-control" name="pin" placeholder="PIN" required="required">
                        <span class="error">
                            <?php 
                            if(isset($errors['pin'])) echo '* ' . $errors['pin']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Expire</label>
                        <input type="date" class="form-control" name="expire" placeholder="Expire" required="required">
                        <span class="error">
                            <?php 
                            if(isset($errors['expire'])) echo '* ' . $errors['expire']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Type</label>
                        <select class="form-control" name="type" id="">
                            <option value="Visa">Visa</option>
                            <option value="Master">Master</option>
                            <option value="PayPal">PayPal</option>
                        </select>
                        <span class="error">
                            <?php 
                            if(isset($errors['type'])) echo '* ' . $errors['type']; 
                            ?>
                        </span>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="">Amount</label>
                        <input type="text" class="form-control" name="amount" placeholder="Amount" required="required">
                        <span class="error">
                            <?php 
                            if(isset($errors['amount'])) echo '* ' . $errors['amount']; 
                            ?>
                        </span>
                    </div>

                    <button type="submit" name="insert" class="btn btn-primary">Add</button>
                </form>

                <div class="container" style="margin-top: 100px">
                    <hr>
                    <h2 class="mb-2">CREDIT CARDS: </h2>
                    <div class="table-responsive" id='goto'>
                        <table class="table table-bordered text-center">
                            <tr style="background-color: #19283f; color: white">
                                <th>Card ID</th>
                                <th>Number</th>
                                <th>Holder Name</th>
                                <th>PIN</th>
                                <th>Expire Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Control</th>
                            </tr>

                            <?php
                            foreach($stmt->fetchAll() as $row): ?>
                                <tr>
                                    <td><?php echo $row['card_id'] ?></td>
                                    <td><?php echo $row['number'] ?></td>
                                    <td><?php echo $row['holder'] ?></td>
                                    <td><?php echo $row['pin'] ?></td>
                                    <td><?php echo $row['expire'] ?></td>
                                    <td><?php echo $row['type'] ?></td>
                                    <form action="cards.php" method="POST" class="col-md-6 d-flex justify-content-center align-items-center">
                                        <input type="hidden" name="card_id" value="<?php echo $row['card_id'] ?>">
                                        <td><input class="form-control form-control-lg text-center" type="text" name="amount" value="<?php echo $row['amount'] ?>"></td>
                                        <td>
                                            <button style="width: 60px; margin-top: 20px; margin-left: 10px" type="submit" name="updateCard" class="btn btn-white border-secondary bg-white btn-md">
                                                <i class="fas fa-sync"></i>
                                            </button>

                                            <button style="width: 60px; margin-top: 20px; margin-left: 10px" type="submit" name="deleteCard" class="btn btn-white border-secondary bg-white btn-md">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                            <?php
                            endforeach;
                            ?>
                        </table>
                    </div>
                </div>
            <?php
            }
        }
        else {
            echo "<div class='alert alert-info'>THERE IS NO CARDS YET</div>";
        }
?>


<?php

        include($tpl . "footer.php");
    }
    else {
        header('Location: index.php');
        exit();
    }

?>
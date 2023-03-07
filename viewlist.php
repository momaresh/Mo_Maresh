<?php

    session_start();

    $setTitle = 'View List';

    //THIS IF YOU AREADY SIGN WILL CHANGE YOU TO THE DASHBOARD AUTOMATIC
    if (isset($_SESSION['USER_NAME'])) {
        include 'initial.php';
        
        $do = (isset($_GET['do']) ? $_GET['do'] : 'Manage');
        
        if ($do == 'Manage') {

            $stmt = $conn->prepare("SELECT * FROM list WHERE user_id = ?");
            $stmt->execute(array($_SESSION['USER_ID']));
            $lists = $stmt->fetchAll();

            if ($stmt->rowCount() == 0){
                echo '<h1 class="text-center mt-5">You list is empty try to add items</h1>';
            }

            
?>

    <div class="container mt-5 text-center">
        <h2 class="use-a-lot2">View List</h2>
        <div class="row row-cols-lg-5 row-cols-sm-2 row-cols-2 row-cols-md-3">
            <?php
            foreach ($lists as $list){
                $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? ORDER BY prod_id DESC LIMIT 1");
                $images->execute(array($list['prod_id']));
                $image = $images->fetch();

                $stmt_list = $conn->prepare("SELECT * FROM products WHERE prod_id = ?");
                $stmt_list->execute(array($list['prod_id']));
                $row_list = $stmt_list->fetch();
            ?>
                <div class="col list mb-5">
                    <div class="image">
                    <?php 
                        if ($row_list['type'] == 'book'):
                    ?>
                            <a href="book.php?bookid=<?php echo $row_list['prod_id']?>"><img src="<?php echo "docs/images/book_images/" . $image['url']; ?>" alt=""></a>
                    <?php 
                        elseif ($row_list['type'] == 'computer'):
                    ?>
                            <a href="computer.php?compid=<?php echo $row_list['prod_id']?>"><img src="<?php echo "docs/images/computer_images/" . $image['url']; ?>" alt=""></a>
                    <?php endif; ?>
                    </div>
                    <p><?php echo substr($row_list['prod_name'], 0, 50); ?></p>
                    <span style="color: var(--third-color)">
                    <?php
                        $stmt_rate = $conn->prepare("SELECT AVG(rate) FROM comments WHERE prod_id = ?");
                        $stmt_rate->execute(array($list['prod_id']));
                        $row_rate = $stmt_rate->fetchColumn();
                        $row_rate = round($row_rate);
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
                    </span>
                    <a class='a1' href="?do=Delete&prodid=<?php echo $row_list['prod_id']; ?>">Delete from list</a>
                    <a class='a2' href="?do=Delete&prodid=<?php echo $row_list['prod_id']; ?>">Delete</a> <!-- This just for responsive for mobile will appear this link -->
                </div>

            <?php
            }
            ?>
        </div>
    </div>

<?php

        }
        elseif ($do == 'Delete') {
            if (isset($_GET['prodid'])) {
                $stmt = $conn->prepare("DELETE FROM list WHERE user_id = ? AND prod_id = ?");
                $stmt->execute(array($_SESSION['USER_ID'], $_GET['prodid']));
                if ($stmt->fetchAll() > 0):
                    echo "<script>
                        alert('THE ITEM DELETED SUCCESSFULLY');
                        window.open('viewlist.php', '_self');
                        </script>";
                endif;
            }
            
        }
        include $tpl . 'script_only.php';
    }
    else {
        header('location: admin/index.php');
        exit();
    }
?>
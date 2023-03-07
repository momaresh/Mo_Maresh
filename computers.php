<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Mo_Maresh/Computers';

    include 'initial.php';

    $do = (isset($_GET['do']) ? $_GET['do'] : 'All');

?>
    <!-- start content -->
    <div class="content">
        <h3 class="use-a-lot2">
            Find Your Computer
        </h3>
        <div class="container">
        <?php 
            if ($do == 'All'):
            ?>
            <form class="search" action="?do=Search" method='POST'>
                <input type="text" name="search" placeholder="Search by name" id="search">
                <input type="submit" value="Search" id="button">
            </form>
            <p class="most">Most Popular</p>
            <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-2 row-cols-md-3">
                <?php
                $stmt = $conn->prepare("SELECT * FROM products JOIN computers ON prod_id = computer_id ORDER BY computer_id DESC");
                $stmt->execute();
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
                <?php endforeach;?>
            </div>

            <?php 
                elseif($do == 'Search'):
                    if ($_SERVER['REQUEST_METHOD'] == 'POST'):
                        $search = $_POST['search'];
                    ?>
                        <form class="search" action="?do=Search" method='POST'>
                            <input type="text" name="search" placeholder="Search by name, authors or publisher" id="search">
                            <input type="submit" value="Search" id="button">
                        </form>
                        <p class="most">Most Popular</p>
                        <div class="row row-cols-lg-4 row-cols-sm-2 row-cols-2 row-cols-md-3">
                            <?php
                            $stmt = $conn->prepare("SELECT * FROM products JOIN computers ON prod_id = computer_id WHERE prod_name LIKE '%$search%' AND type = 'computer' ");
                            $stmt->execute();
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row):
                                $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ?");
                                $images->execute(array($row['prod_id']));
                                $image = $images->fetch();
                                ?>
                                
                                <div class="computer col">
                                    <div class="image">
                                        <a href="computer.php?compid=<?php echo $row['computer_id']?>"><img src="<?php echo "docs/images/computer_images/" . $image['url'] ?>" alt="" style="width: 100%; height: 100%"></a>
                                    </div>
                                    <p><?php echo substr($row['prod_name'], 0, 50)?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php
                    else:
                        echo "<script>
                            alert('YOU CAN NOT GET HERE DIRECT');
                            window.open('computers.php', '_self');
                            </script>";
                    endif;
                else:
                    echo "<script>
                        alert('THE OPTION NOT CORRECT');
                        window.open('computers.php', '_self');
                        </script>";
                endif;
            ?>

        </div>
    </div>
    <!-- end content -->

    <?php

    include $tpl . 'footer.php'; 
<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Mo_Maresh/Books';

    include 'initial.php';

    $do = (isset($_GET['do']) ? $_GET['do'] : 'All');

?>

    <div class="content">
        <div class="container">
            <h3 class="use-a-lot2">
                Find Your Book
            </h3>
            <div class="container">
                
                <?php 
                    if ($do == 'All'):
                ?>
                <form class="search" action="?do=Search" method='POST'>
                    <input type="text" name="search" placeholder="Search by name, authors or publisher" id="search">
                    <input type="submit" value="Search" id="button">
                </form>
                <p class="most">Most Popular</p>
                <div class="books">
                    <?php
                    $stmt = $conn->prepare("SELECT * FROM products JOIN books ON prod_id = book_id ORDER BY book_id DESC");
                    $stmt->execute();
                    $rows = $stmt->fetchAll();
                    foreach($rows as $row): 
                        $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ?");
                        $images->execute(array($row['prod_id']));
                        $image = $images->fetch();
                    ?>
                        <div class="book">
                            <div class="image">
                                <a href="book.php?bookid=<?php echo $row['book_id']?>"><img src="<?php echo "docs/images/book_images/" . $image['url'] ?>" alt=""></a>
                            </div>
                            <p><?php echo $row['prod_name']?></p>
                        </div>
                    <?php endforeach; ?>
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
                            <div class="books">
                                <?php
                                $stmt = $conn->prepare("SELECT * FROM products JOIN books ON prod_id = book_id WHERE prod_name LIKE '%$search%' AND type = 'book' ");
                                $stmt->execute();
                                $rows = $stmt->fetchAll();
                                foreach($rows as $row):
                                    $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ?");
                                    $images->execute(array($row['prod_id']));
                                    $image = $images->fetch();
                                    ?>

                                    <div class="book">
                                        <div class="image">
                                            <a href="book.php?bookid=<?php echo $row['book_id']?>"><img src="<?php echo "docs/images/book_images/" . $image['url'] ?>" alt=""></a>
                                        </div>
                                        <p><?php echo $row['prod_name']?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                        <?php
                        else:
                            echo "<script>
                                alert('YOU CAN NOT GET HERE DIRECT');
                                window.open('books.php', '_self');
                                </script>";
                        endif;
                    else:
                        echo "<script>
                            alert('THE OPTION NOT CORRECT');
                            window.open('books.php', '_self');
                            </script>";
                    endif;
                ?>
            </div>
        </div>
    </div>
    <!-- end content -->

<?php
    include $tpl . 'footer.php';
?>
<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Mo_Maresh';
    include 'initial.php';
?>

    <i class="fa-solid fa-angles-up scroll-up"></i>
    
    <!-- start main -->
    <div class="main  d-flex justify-content-center align-items-center">
        <div class="text-center">
            <h2 class="text-white">Welcome, To <span style="color: var(--third-color);">Mo_Maresh</span> Shopping </h2>
            <p class="text-light">Here is the place that you can find any <span class="text-uppercase" style="color: var(--third-color);">books</span>, <span class="text-uppercase" style="color: var(--third-color);">phones</span>, or <span class="text-uppercase" style="color: var(--third-color);">computers</span>.
                <br>
                Also with convince price
            </p>

            <a class="btn btn-outline-success rounded-pill text-capitalize">enjoy your shopping</a>
        </div>
    </div>
    <!-- end main -->
    <!-- start services -->
    <div class="services section" id="services">
        <div class="container">
            <div class="use-a-lot text-center pb-1 position-relative">
                <i class="fa-solid fa-5x fa-truck-fast mb-2"></i>
                <h2 class="text-uppercase">what you can find</h2>
                <p class="text-uppercase text-black-50">our products</p>
            </div>

            <div class="row">
                <div class="col-md-6 col-lg-4">
                <div class="service text-center">
                    <i class="fa-solid fa-4x fa-mobile-screen-button mb-4"></i>
                    <h3 class="text-uppercase ">phones</h2>
                    <p class="text-black-50 text-capitalize lh-lg">we offer for you any types of phones. with all properties, brands, and we deliver it to any location you want</p>
                </div> 
                </div>

                <div class="col-md-6 col-lg-4">
                <div class="service text-center">
                    <i class="fa-solid fa-4x fa-computer mb-4"></i>
                    <h3 class="text-uppercase">computers</h3>
                    <p class="text-black-50 text-capitalize lh-lg">we offer for you any types of computers. with all properties, brands, and we deliver it to any location you want</p>
                </div> 
                </div>

                <div class="col-md-6 col-lg-4">
                <div class="service text-center">
                    <i class="fa-solid fa-4x fa-book mb-4"></i>
                    <h3 class="text-uppercase">books</h3>
                    <p class="text-black-50 text-capitalize lh-lg">we offer for you any types of books. with all catagories, authors, and we deliver it to any location you want</p>
                </div> 
                </div>
            </div>
        </div>
    </div>
    <!-- end services -->

    <!-- start popular -->
    <div class="popular section" id="popular">
    <div class="container">
        <div class="use-a-lot text-center pb-1 position-relative">
            <i class="fa-solid fa-money-bill-trend-up fa-5x mb-2"></i>
            <h2 class="text-uppercase">the must buying</h2>
            <p class="text-uppercase text-black-50">order now</p>
        </div>

        <form action="index.php#goto" method="GET">
        <ul class="filter list-unstyled d-flex justify-content-center mb-5 position-relative" id="goto">
            <input type="submit" name="all" id="all-li" class="btn active btn-outline-success" value="All">
            <input type="submit" name="book" class="btn" id="book-li" value="Books">
            <input type="submit" name="computer" class="btn" id="computer-li" value="Computers">
        </ul>
        </form>
        
        <div class="row ">
            <?php
            $sql = '';
            if(isset($_GET['book'])) {
                $sql = "WHERE TYPE ='book'";  
            }
            if(isset($_GET['computer'])) {
                $sql = "WHERE TYPE ='computer'";
            }
            if(isset($_GET['all'])) {
                $sql = '';
            }

            $stmt = $conn->prepare("SELECT * FROM products $sql ORDER BY buying DESC LIMIT 8");
            $stmt->execute();
            $rows = $stmt->fetchAll();
            foreach($rows as $row): 
                $images = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? ORDER BY prod_id ASC LIMIT 1");
                $images->execute(array($row['prod_id']));
                $image = $images->fetch();

                $stmt_rate = $conn->prepare("SELECT AVG(rate) FROM comments WHERE prod_id = ?");
                $stmt_rate->execute(array($row['prod_id']));
                $row_rate = $stmt_rate->fetchColumn();
                $row_rate = round($row_rate);
                ?>
                
                <div class="prod col-md-4 col-lg-3 mb-5 col-sm-6">
                    <?php if ($row['type'] == 'book'): ?>
                        <a href="book.php?bookid=<?php echo $row['prod_id']?>"><img src="<?php echo "docs/images/book_images/" . $image['url'] ?>" alt=""></a>
                    <?php elseif ($row['type'] == 'computer'): ?>
                        <a href="computer.php?compid=<?php echo $row['prod_id']?>"><img src="<?php echo "docs/images/computer_images/" . $image['url'] ?>" alt=""></a>
                    <?php endif; ?>
                    <span style="color: #ff6a00;display: flex; justify-content: space-between;">
                        <span>
                        <?php 
                            echo star_fill($row_rate);
                        ?>
                        </span>
                        <span>$<?php echo $row['price']?></span>
                    </span>
                </div>
                
            <?php endforeach; ?>
        </div>
    </div>
    </div>
    <!-- end popular -->


<?php
    include $tpl . 'footer.php';
?>

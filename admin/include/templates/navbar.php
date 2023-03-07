<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fa-solid fa-cart-shopping me-2" style="font-size: 35px;"></i>Mo_Maresh</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-li" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="nav-li">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link ps-lg-3 active" aria-current="page" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link ps-lg-3" href="books.php">Books</a>
                </li>
                <li class="nav-item">
                <a class="nav-link ps-lg-3" href="computers.php">Computers</a>
                </li>
                
                <?php
                if($_SESSION['GROUP_ID'] != 3){
                ?>
                    <li class="nav-item">
                    <a class="nav-link ps-lg-3" href="user.php" data-scroll="footer" id="contact">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link ps-lg-3" href="orders.php" data-scroll="footer" id="contact">Orders</a>
                    </li>
                    <li class="dropdown nav-item mt-1">
                        <button style="background: none; border: none;" class=" prof btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Others
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="locations.php"><i class="fa fa-location" style="margin-right: 5px"></i> Locations</a></li>
                            <li><a class="dropdown-item" href="cards.php"><i class="fa fa-credit-card" style="margin-right: 5px"></i> Cards</a></li>
                        </ul>
                    </li>
                <?php
                }
                ?>

                <?php
                $stmt = $conn->prepare("SELECT image FROM USERS WHERE USER_ID = ?");
                $stmt->execute(array($_SESSION['USER_ID']));
                $img = $stmt->fetch();
                ?>
                <li class="dropdown nav-item ms-5 mx-5">
                    <button style="background: none; border: none;" class=" prof btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="img-fluid rounded-circle" style="height: 34px;" src="../docs/images/user_images/<?php echo $img['image'] ?>" alt="">
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item mb-1" href="user.php?do=Edit&userid=<?php echo $_SESSION['USER_ID'];?>"><i class="fa fa-edit" style="margin-right: 5px"></i> Edit Profile</a></li>
                        <li><a class=" ms-2 btn btn-outline-success rounded-pill" href="logout.php">Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


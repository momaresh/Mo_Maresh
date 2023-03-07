<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#"><i class="fa-solid fa-cart-shopping me-2" style="font-size: 35px;"></i>Mo_Maresh</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav-li" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="nav-li">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="ps-lg-3 active" aria-current="page" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="ps-lg-3" href="books.php">Books</a>
                </li>
                <li class="nav-item">
                    <a class="ps-lg-3" href="computers.php">Computers</a>
                </li>

                <?php
                    if(isset($_SESSION['USER_NAME'])) {
                        $stmt = $conn->prepare("SELECT image FROM USERS WHERE USER_ID = ?");
                        $stmt->execute(array($_SESSION['USER_ID']));
                        $img = $stmt->fetch();
                ?>
                    <li class="dropdown nav-item ms-5 mx-5">
                        <button style="background: none; border: none;" class=" prof btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="img-fluid rounded-circle" style="height: 34px;" src="docs/images/user_images/<?php echo $img['image'] ?>" alt="">
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="edit.php"><i style="color: var(--second-color)" class="fa-solid fa-user-pen"></i> Edit Profile</a></li>
                            <li><a class="dropdown-item" href="orders.php"><i style="color: var(--second-color)" class="fa-solid fa-tags"></i> Orders</a></li>
                            <li><a class="dropdown-item" href="cart.php"><i style="color: var(--second-color)" class="fa-solid fa-cart-plus"></i> Cart</a></li>
                            <li><a class="dropdown-item" href="viewlist.php"><i style="color: var(--second-color)" class="fa-solid fa-basket-shopping"></i> List</a></li>
                            <li><a class=" ms-2 btn btn-outline-success rounded-pill" href="./admin/logout.php">Sign Out</a></li>
                        </ul>
                    </li>
                <?php
                    }
                    else {
                ?>
                        <li class="nav-item">
                            <a href="Admin/index.php" class="btn btn-outline-success rounded-pill ms-lg-3">Sign In</a>
                        </li>
                <?php
                    }
                ?>
<!--                 

                <li class="nav-item">
                    <a href="" class="ps-lg-3" style="padding: 0; margin-left: 20px; font-size: 30px; color:#ff6a00;"><i class="fa-solid fa-gear"></i></i></a>
                </li> -->
            </ul>
        </div>
    </div>
</nav>


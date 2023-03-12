<?php
    // WHEN YOU LOGIN TO THE PAGE YOU START THE SESSION
    session_start();
    $setTitle = 'Books';
    // AONLY THE ADMIN AND SUPPLIER CAN GO HERE
    if (isset($_SESSION['USER_NAME']) && ($_SESSION['GROUP_ID'] == 1 || $_SESSION['GROUP_ID'] == 3)) {
        include 'initial.php';
    
        $condition = '';
        if($_SESSION['GROUP_ID'] == 3){
            $condition = "WHERE SUP_ID = $_SESSION[USER_ID]";
        }

        $do = (isset($_GET['do'])) ? $_GET['do'] : 'Manage';

        if ($do == 'Manage') { ?>
            <div class="container mt-5">
            <h3 class="use-a-lot2 mb-2">Books</h3>

            <form class="search" action="" method='POST'>
                <input type="text" name="book_name" placeholder="Search by book name" id="search">
                <input type="submit" name="search" value="Search" id="button">
            </form>

            <a href="?do=Add" class="btn btn-primary mb-3">ADD BOOK</a>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <tr style="background-color: #19283f; color: white">
                            <th>Book_Id</th>
                            <th>Book_Name</th>
                            <th>Price</th>
                            <th>Language</th>
                            <th>Size</th>
                            <th>Category</th>
                            <th>Pages</th>
                            <th>Author</th>
                            <th>Supplier_Id</th>
                            <th>Inserted_Date</th>
                            <th>Control</th>
                        </tr>
                        <?php

                        $search = ''; 
                        if(isset($_POST['search'])) {
                            $book_name = $_POST['book_name'];

                            if(!empty($book_name) && $_SESSION['GROUP_ID'] == 3) {
                                $search = "AND prod_name LIKE '%$book_name%'";
                            }
                            elseif(!empty($book_name) && $_SESSION['GROUP_ID'] != 3) {
                                $search = "WHERE prod_name LIKE '%$book_name%'";
                            }
                        }

                        $stmt = $conn->prepare("SELECT * FROM products p JOIN books b ON p.prod_id = b.book_id $condition $search");
                        $stmt->execute();

                        if($stmt->rowCount() > 0) {
                            $rows = $stmt->fetchAll();
                            foreach($rows as $row): ?>
                                <tr >
                                    <td><?php echo $row['book_id']; ?></td>
                                    <td><?php echo substr($row['prod_name'], 0, 50); ?></td>
                                    <td>$<?php echo $row['price']; ?></td>
                                    <td><?php echo $row['language']; ?></td>
                                    <td><?php echo $row['size']; ?>MB</td>
                                    <?php
                                        $stmt_cat = $conn->prepare("SELECT category_name FROM categories WHERE book_id = ?");
                                        $stmt_cat->execute(array($row['book_id']));
                                        $cat_rows = $stmt_cat->fetchAll();
                                        echo "<td>";
                                        foreach ($cat_rows as $cat_row):
                                            echo $cat_row['category_name'] . ' '; 
                                        endforeach;
                                        echo "</td>";
                                    ?>
                                    <td><?php echo $row['pages']; ?></td>
                                    <td><?php echo $row['author']; ?></td>
                                    <td><?php echo $row['sup_id']; ?></td>
                                    <td><?php echo $row['sup_date']; ?></td>
                                    <td>
                                        <a href="?do=Edit&bookid=<?php echo $row['book_id'];?>" class="btn" style="background-color: #4eb67f; margin-bottom: 5px">Edit</a>
                                        <a href="?do=Delete&bookid=<?php echo $row['book_id'];?>" class="btn confirm" style="background-color: #ff6a00">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach;
                        }
                        else {
                            echo "<div class='container text-center alert alert-info'> There is no book.....!</div>";
                        }
                        ?>
                    </table>
                </div>               
            </div>
        <?php
        }
        elseif($do == 'Add'){ 
            // CHECK IF COMING FROM REQUEST
            if(isset($_POST['insert'])):
                //  print all the value from the form
                $book_name = $_POST['book_name'];
                $author = $_POST['author'];
                $desc1 = $_POST['description1'];
                $desc2 = $_POST['description2'];
                $desc3 = $_POST['description3'];
                $category1 = $_POST['category1'];
                $category2 = $_POST['category2'];
                $category3 = $_POST['category3'];
                $pages = $_POST['pages'];
                $price = $_POST['price'];
                $size = $_POST['size'];
                $language = $_POST['language'];

                if ($_SESSION['GROUP_ID'] == 3)
                    $sup_id = $_SESSION['USER_ID'];
                else
                    $sup_id = $_POST['sup_id'];
                
                $insert_errors = array();
                // IF supplier in the database
                if(checkSup('user_id', 'users', $sup_id) <= 0){
                    $insert_errors['sup'] = "THE SUPPLIER NOT EXISTS";
                }

                if(empty($insert_errors)){
                    $stmt_prod = $conn->prepare("INSERT INTO products (prod_name, price, sup_id, sup_date, desc1, desc2, desc3)
                                            VALUES (:NAME, :PRICE, :SUPID, now(), :D1, :D2, :D3)");
                    $stmt_prod->execute(array(
                        'NAME' => $book_name,
                        'PRICE' => $price,
                        'SUPID' => $sup_id,
                        'D1' => $desc1,
                        'D2' => $desc2,
                        'D3' => $desc3
                    ));

                    $stmt2 = $conn->prepare("SELECT MAX(prod_id) FROM products LIMIT 1");
                    $stmt2->execute();
                    $row_2 = $stmt2->fetchColumn();

                    $stmt_book = $conn->prepare("INSERT INTO books (book_id, language, size, pages, author)
                                            VALUES (:BOOK_ID, :LANG, :SIZE, :PAGES, :AUTHOR)");
                    $stmt_book->execute(array(
                        'BOOK_ID' => $row_2,
                        'LANG' => $language,
                        'SIZE' => $size,
                        'PAGES' => $pages,
                        'AUTHOR' => $author
                    ));

                    if (!empty($category1)){
                        $stmt3 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                VALUES (? , ?)");
                        $stmt3->execute(array($row_2, $category1));
                    }
                    if (!empty($category2)){
                        $stmt4 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                VALUES (? , ?)");
                        $stmt4->execute(array($row_2, $category2));
                    }
                    if (!empty($category3)){
                        $stmt5 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                VALUES (? , ?)");
                        $stmt5->execute(array($row_2, $category3));
                    }

                    $name = $_FILES['image']['name'];
                    $type = $_FILES['image']['type'];
                    $tmp_name = $_FILES['image']['tmp_name'];
                    $error = $_FILES['image']['error'];
                    $size = $_FILES['image']['size'];

                    $valid_extension = array('png', 'jpg', 'jpeg', 'gif');

                    $explode_array = explode('.', $name);
                    $ext = strtolower(end($explode_array));

                    if (!in_array($ext, $valid_extension)) {
                        $insert_errors['ext'] = 'The extension not allowed in image';
                    }
                    else {
                        $rand_name = rand(1, 1000000) . '_' . $name;
                        move_uploaded_file($tmp_name, "..\docs\images\book_images\\" . $rand_name);
                        $insert = $conn->prepare("INSERT INTO product_images(prod_id, url) VALUES (?, ?)");
                        $insert->execute(array($row_2, $rand_name));
                    }
                    echo "<script>
                        alert('" . $stmt_prod->rowcount() . " RECORD INSERTED...!');
                        window.open('books.php', '_self');
                        </script>";

                }
            endif;
            ?>
            <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">Add BOOK</h1>
            <div class="container">
            </div>
            <form class="content" action="?do=Add" method="POST" enctype="multipart/form-data">
                <div class="container">
                    <div class="about-book">
                        <div class="image">
                            <img src="<?php echo 'Themes/IMAGES/book.jpg'; ?>" alt="">
                        </div>
                        
                        <div class="info">
                            <div class="title">
                                <label for="">Book Name: </label>
                                <input type="text" name="book_name" placeholder="Book Name">
                                <label for="">Author: </label>
                                <input type="text" name="author" placeholder="The Author">
                            </div>

                            <div class="text">
                                <label for="">Description One: </label>
                                <textarea name="description1" style="height: 100px;" placeholder="Description One Of The Product"></textarea>
                                <label for="">Description Tow: </label>
                                <textarea name="description2" style="height: 150px;" placeholder="Description Tow Of The Product"></textarea>
                                <label for="">Description Three: </label>
                                <textarea name="description3" style="height: 150px;" placeholder="Description Three Of The Product"></textarea>
                            </div>

                            <div class="data">
                                <div class="datum">
                                    <div class="datum-desc">
                                        <label for="cat">Categories:</label>
                                        <input type="text" name="category1" placeholder="Category1">                                 
                                        <input type="text" name="category2" placeholder="Category2">                                 
                                        <input type="text" name="category3" placeholder="Category3">                                 
                                    </div> 
                                    
                                    <?php
                                    if ($_SESSION['GROUP_ID'] != 3) {
    
                                        $sup_stmt = $conn->prepare('SELECT * FROM users WHERE GROUP_ID = 3');
                                        $sup_stmt->execute();
                                        $suppliers = $sup_stmt->fetchAll();
                                        ?>

                                        <label class="mt-3" for="">Supplier Id: </label>

                                        <select class="btn select mt-2" name='sup_id' style='border: 1px solid var(--third-color)'>
                                        <?php
                                            foreach ($suppliers as $supplier):
                                        ?>
                                            <option value="<?php echo $supplier['user_id']; ?>"> <?php echo $supplier['full_name']; ?> </option>
                                        <?php
                                            endforeach;
                                        ?>
                                        </select>

                                        <span class="error">
                                            <?php
                                            if(isset($insert_errors['sup'])) echo '*' . $insert_errors['sup'];
                                            ?>
                                        </span>

                                        <?php
                                    }
                                    ?>

                                    <div class="datum-desc">
                                        <label for="pages">Pages:</label>
                                        <input type="number" name="pages" id="pages" placeholder="Pages">                                 
                                    </div> 
                                    
                                    <div class="datum-desc">
                                        <label for="image">Image:</label>
                                        <input type="file" name="image" id="image" >                                 
                                    </div> 
                                </div>

                                <div class="datum">
                                    <div class="datum-desc">
                                        <label for="price">Price:</label>
                                        <input type="text" name="price" id="price" placeholder="Price">                                 
                                    </div>
                                    <div class="datum-desc">
                                        <label for="size">Size:</label>
                                        <input type="text" name="size" id="size" placeholder="Size">                                 
                                    </div>
                                    <div class="datum-desc">
                                        <label for="language">Language:</label>
                                        <input type="text" name="language" id="language" placeholder="Language">                                 
                                    </div>                      
                                </div>
                            </div>
                        </div>
                        <input type="submit" value="Add" name="insert">
                    </div>
                </div>
            </form>
        <?php
        }


        // EDIT
        elseif($do == 'Edit'){ 
            $bookId = (isset($_GET['bookid'])) &&  is_numeric($_GET['bookid']) ? intval($_GET['bookid']) : 0;

            $stmt = $conn->prepare("SELECT * FROM products JOIN books 
                                    ON prod_id = book_id
                                    $condition AND book_id = $bookId "); // THE variable condition is declare in the begin of the page
            $stmt->execute();
            $row = $stmt->fetch();

            $stmt_img = $conn->prepare("SELECT * FROM product_images WHERE prod_id = ? ORDER BY prod_id DESC LIMIT 1");
            $stmt_img->execute(array($bookId));
            $row_img = $stmt_img->fetch();

            if ($stmt->rowCount() > 0):
                // CHECK IF COMING FROM REQUEST
                if(isset($_POST['update'])):
                    //  print all the value from the form
                    $book_id = $_POST['book_id'];
                    $book_name = $_POST['book_name'];
                    $author = $_POST['author'];
                    $desc1 = $_POST['description1'];
                    $desc2 = $_POST['description2'];
                    $desc3 = $_POST['description3'];
                    $category1 = $_POST['category1'];
                    $category2 = $_POST['category2'];
                    $category3 = $_POST['category3'];
                    $pages = $_POST['pages'];
                    $price = $_POST['price'];
                    $size = $_POST['size'];
                    $language = $_POST['language'];
                    if ($_SESSION['GROUP_ID'] == 3)
                        $sup_id = $_SESSION['USER_ID'];
                    else
                        $sup_id = $_POST['sup_id'];


                    $update_errors = array();
                    // IF supplier in the database
                    if(checkSup('user_id', 'users', $sup_id) <= 0){
                        $update_errors['sup'] = "THE SUPPLIER NOT EXISTS";
                    }

                    if(empty($update_errors)){
                        $stmt = $conn->prepare("UPDATE products
                                                            SET prod_name = :NAME, 
                                                                price = :PRICE, 
                                                                sup_id = :SUPID, 
                                                                sup_date = now(),  
                                                                desc1 = :D1, 
                                                                desc2 = :D2, 
                                                                desc3 = :D3
                                                            WHERE 
                                                                prod_id = :PROD_ID" );
                        $stmt->execute(array(
                            'NAME' => $book_name,
                            'PRICE' => $price,
                            'SUPID' => $sup_id,
                            'D1' => $desc1,
                            'D2' => $desc2,
                            'D3' => $desc3,
                            'PROD_ID' => $book_id
                        ));


                        $stmt2 = $conn->prepare("UPDATE books
                                                            SET 
                                                                language = :LANG, 
                                                                size = :SIZE, 
                                                                pages = :PAGES, 
                                                                author = :AUTHOR
                                                            WHERE 
                                                                book_id = :BOOKID" );
                        $stmt2->execute(array(
                            'LANG' => $language,
                            'SIZE' => $size,
                            'PAGES' => $pages,
                            'AUTHOR' => $author,
                            'BOOKID' => $book_id
                        ));

                        $stmt1 = $conn->prepare("DELETE FROM categories WHERE book_id = ?");
                        $stmt1->execute(array($book_id));

                        if (!empty($category1) && $category1 != $category2 && $category1 != $category3){
                            $stmt3 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                    VALUES (? , ?)");
                            $stmt3->execute(array($book_id, $category1));
                        }
                        if (!empty($category2) && $category2 != $category1 && $category2 != $category3){
                            $stmt4 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                    VALUES (? , ?)");
                            $stmt4->execute(array($book_id, $category2));
                        }
                        if (!empty($category3) && $category3 != $category2 && $category3 != $category2){
                            $stmt5 = $conn->prepare("INSERT INTO CATEGORIES(book_id, category_name)
                                                    VALUES (? , ?)");
                            $stmt5->execute(array($book_id, $category3));
                        }

                        $name = $_FILES['image']['name'];
                        $type = $_FILES['image']['type'];
                        $tmp_name = $_FILES['image']['tmp_name'];
                        $error = $_FILES['image']['error'];
                        $size = $_FILES['image']['size'];

                        $valid_extension = array('png', 'jpg', 'jpeg', 'gif');

                        $explode_array = explode('.', $name);
                        $ext = strtolower(end($explode_array));

                        if (!in_array($ext, $valid_extension)) {
                            $insert_errors['ext'] = 'The extension not allowed in image';
                        }
                        else {
                            $rand_name = rand(1, 1000000) . '_' . $name;
                            move_uploaded_file($tmp_name, "..\docs\images\book_images\\" . $rand_name);
                            $insert = $conn->prepare("INSERT INTO product_images(prod_id, url) VALUES (?, ?)");
                            $insert->execute(array($book_id, $rand_name));
                        }

                        echo "<script>
                            alert(' RECORD UPDATE...!');
                            window.open('books.php', '_self');
                            </script>";
                    }
                endif;
            ?>
                <h1 class="text-center" style="color: #ff6a00; font-weight: bold;">Edit BOOK</h1>
                <form class="content" action="?do=Edit&bookid=<?php echo $bookId ?>" method="POST" enctype="multipart/form-data">
                    <div class="container">
                        <div class="about-book">
                            <div class="image">
                                <img src="<?php echo "../docs/images/book_images/" . $row_img['url']?>" alt="">
                            </div>
                            
                            <div class="info">
                                <div class="title">
                                    <input type="hidden" name="book_id" value="<?php echo $row['book_id']; ?>">
                                    <label for="">Book Name: </label>
                                    <input type="text" name="book_name" value="<?php echo $row['prod_name']; ?>">
                                    <label for="">Author: </label>
                                    <input type="text" name="author" value="<?php echo $row['author']; ?>">
                                </div>

                                <div class="text">
                                    <label for="">Description One: </label>
                                    <textarea name="description1" style="height: 100px;" ><?php echo $row['desc1']; ?></textarea>
                                    <label for="">Description Tow: </label>
                                    <textarea name="description2" style="height: 150px;" ><?php echo $row['desc2']; ?></textarea>
                                    <label for="">Description Three: </label>
                                    <textarea name="description3" style="height: 150px;" ><?php echo $row['desc3']; ?></textarea>
                                </div>

                                <div class="data">
                                    <div class="datum">
                                        <div class="datum-desc">
                                            <label for="cat">Category:</label>
                                            <?php
                                            $stmt_cat = $conn->prepare("SELECT category_name FROM categories WHERE book_id = ?");
                                            $stmt_cat->execute(array($row['book_id']));
                                            $cat_rows = $stmt_cat->fetchAll();
                                            ?>
                                            <input type="text" name="category1" value="<?php if(isset($cat_rows[0][0])) echo $cat_rows[0][0]?>" id="cat" >                                
                                            <input type='text' name='category2' value='<?php if(isset($cat_rows[1][0])) echo $cat_rows[1][0]?>' id='cat' >                                
                                            <input type='text' name='category3' value='<?php if(isset($cat_rows[2][0])) echo $cat_rows[2][0]?>' id='cat' >                            
                                        </div> 
                                        
                                        <?php
                                        if ($_SESSION['GROUP_ID'] != 3) {
                                        ?>
                                            <div class="datum-desc">
                                                <label for="sup">Supplier Id:</label>
                                                <input type="number" name="sup_id" id="sup" value="<?php echo $row['sup_id']; ?>">
                                                <span class="error">
                                                    <?php
                                                    if(isset($update_errors['sup'])) echo '*' . $update_errors['sup'];
                                                    ?>
                                                </span>
                                            </div> 
                                        <?php
                                        }
                                        ?>

                                        <div class="datum-desc">
                                            <label for="pages">Pages:</label>
                                            <input type="number" name="pages" value="<?php echo $row['pages']; ?>" id="pages" >                                 
                                        </div> 
                                        <div class="datum-desc">
                                            <label for="image">Image:</label>
                                            <input type="file" name="image" id="image" >
                                            <span class="error">
                                                <?php
                                                if(isset($update_errors['ext'])) echo '*' . $update_errors['ext'];
                                                ?>
                                            </span>
                                        </div>                             
                                    </div>

                                    <div class="datum">
                                        <div class="datum-desc">
                                            <label for="price">Price:</label>
                                            <input type="text" name="price" value="<?php echo $row['price']; ?>" id="price" >                                 
                                        </div>
                                        <div class="datum-desc">
                                            <label for="size">Size:</label>
                                            <input type="text" name="size" value="<?php echo $row['size']; ?>" id="size" >                                 
                                        </div>
                                        <div class="datum-desc">
                                            <label for="language">Language:</label>
                                            <input type="text" name="language" value="<?php echo $row['language']; ?>" id="language" >                                 
                                        </div>                      
                                    </div>
                                </div>
                            </div>
                            <input type="submit" value="Save" name="update">
                        </div>
                    </div>
                </form>

            <?php 
            else:
                echo "<script>
                    alert('THE BOOK NOT FOUND');
                    window.open('books.php', '_self');
                    </script>";
            endif;
            ?>
        <?php
        }


        elseif($do == 'Delete') {
            $bookId = (isset($_GET['bookid'])) &&  is_numeric($_GET['bookid']) ? intval($_GET['bookid']) : 0;

            $stmt = $conn->prepare("DELETE FROM categories WHERE book_id = ?");
            $stmt->execute(array($bookId));

            $stmt2 = $conn->prepare("DELETE FROM books WHERE book_id = ?");
            $stmt2->execute(array($bookId));

            $stmt2 = $conn->prepare("DELETE FROM product_images WHERE prod_id = ?");
            $stmt2->execute(array($bookId));

            $stmt3 = $conn->prepare("DELETE FROM products WHERE prod_id = ?");
            $stmt3->execute(array($bookId));

            echo "<script>
                alert('" . $stmt3->rowcount() . " RECORD DELETED...!');
                window.open('books.php', '_self');
                </script>";
        }



        include $tpl . 'footer.php';
    }
    else {
        header('location: index.php');
        exit();
    }
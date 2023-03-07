        <!-- start footer -->
        <div class="footer section" id="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-lg-4">
                    <div class="box">
                        <h3>Mo_Maresh</h3>
                        <ul class="social">
                            <li>
                                <a href="#" class="whatsapp"><i class="fa-brands fa-whatsapp"></i></a>
                            </li>
                            <li>
                                <a href="#" class="telegram"><i class="fa-brands fa-telegram"></i></a>
                            </li>
                            <li>
                                <a href="#" class="facebook"><i class="fa-brands fa-facebook"></i></a>
                            </li>
                            <li>
                                <a href="#" class="instagram"><i class="fa-brands fa-instagram"></i></a>
                            </li>
                        </ul>
                        <p class="text">We hope that you engoy your shopping and you find what you was looking for.</p>
                    </div>
                    </div>

                    <div class="col-md-6 col-lg-4">
                    <div class="box">
                        <ul class="link d-flex justify-content-center flex-column">
                            <li><a href="books.php">See All Books</a></li>
                            <li><a href="computers.php">See All Computers</a></li>
                            <li><a href="#">See All Phones</a></li>
                        </ul>
                    </div>
                    </div>


                    <div class="col-md-6 col-lg-4">
                        <div class="box">
                            <div class="line">
                                <i class="fas fa-map-marker-alt fa-fw"></i>
                                <div class="info">Sana'a, Hada, In Front Of Al-Kumaim Mool</div>
                            </div>
                            <div class="line">
                                <i class="far fa-clock fa-fw"></i>
                                <div class="info">Working Hour: 8:00 AM to 2:00 AM </div>
                            </div>
                            <div class="line">
                                <i class="fas fa-phone-volume fa-fw"></i>
                                <div class="info">
                                    <span><a href="tel:+967774415062">+967774415062</a></span>
                                    <span><a href="tel:+967733650436">+967733650436</a></span>
                                </div>
                            </div>
                            <div class="line">
                            <i class="fa-solid fa-message"></i>
                            <div class="info" id="message" style="cursor: pointer;">Message Us</div>
                            </div>
                        </div>
                    </div>

                </div> 
            </div>
            <p class="copy">&copy; Done By &nbsp;<span> Maresh</span>, All Right Reserved </p>
        </div>
        <!-- end footer -->

        <!-- pop up -->
        <div class="pop-up">
            <form action="comments.php" method="POST">
                <h2>Contact us</h2>
                <input type="text" name="user_name" id="user-name" placeholder="User Name" required>
                <input type="email" name="email" id="email" placeholder="Enter Email" required>
                <textarea name="text" id="message" cols="30" rows="10" placeholder="Your Message" required></textarea>
                <div class="ok">
                    <input type="submit" name="message" value="Send" class="btn btn-outline-success rounded-pill" id="submit">
                    <input type="button" value="Cancel" class="btn btn-outline-success rounded-pill" id="cancel">
                </div>
            </form>
        </div>
        
        <script src="<?php echo $js; ?>all.min.js"></script>
        <script src="<?php echo $js; ?>jquery-3.5.1.min.js"></script>
        <script src="<?php echo $js; ?>bootstrap.bundle.min.js"></script>
        <script src="<?php echo $js; ?>frontend.js"></script>
    </body>
</html>
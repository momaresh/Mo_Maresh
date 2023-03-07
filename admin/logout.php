<?php
    session_start(); // STAR THE SESSION, TO DELETE IT

    session_unset(); // UNSET FOR THE DATA, BUT THE SESSION STILL EXITS

    session_destroy(); // DESTROY THE SESSION, DELETE IT

    header('Location: ../index.php'); // RETURN IT TO THE MAIN
    exit();
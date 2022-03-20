<?php
            session_start();
            session_unset();
            session_destroy();
            header("location: index.php");
            exit;
            echo "<div class='alert alert-primary' role='alert'>
            <h4 class='alert-heading'>Logged Out</h4>
            <p>You have successfully logout from the system.</p>
            <hr>
            <p class='mb-0'>Please go to <a href='index.php' class='alert-link' </p>
          </div>"
    ?>
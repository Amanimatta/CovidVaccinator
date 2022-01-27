<?php
    session_start();
    unset($_SESSION["userId"]);  // where $_SESSION["nome"] is your own variable. if you do not have one use only this as follow **session_unset();**
    header("Location: ../view/index.html");
?>
<?php
session_start();

if (!isset($_SESSION['email']) || $_SESSION["role"] !== "customer") {
    header("location:login.php");
    exit();
}

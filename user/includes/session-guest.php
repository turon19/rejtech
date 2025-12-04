<?php
session_start();
if (isset($_SESSION["role"]) === "customer") {
    header("location: index.php");
    exit();
} else if (isset($_SESSION["admin"]) === "admin") {
    header("location: ../admin/admin.php");
    exit();
}

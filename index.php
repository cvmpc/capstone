<?php
session_start();
require 'includes/head.php';
// Check if the user is logged in
if (isset($_SESSION['user'])) {
    // Redirect to the correct dashboard based on the user's role
    if ($_SESSION['user']['role'] === 'Admin') {
        header('Location: admin/index.php');
    } elseif ($_SESSION['user']['role'] === 'Member') {
        header('Location: member_dashboard.php');
    } elseif ($_SESSION['user']['role'] === 'Staff') {
        header('Location: staff_dashboard.php');
    }
    exit(); // Stop further execution
} else {
    // Redirect to the login page if not logged in
    header('Location: login.php');
    exit();
}

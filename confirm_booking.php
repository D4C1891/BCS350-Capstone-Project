<?php
session_start();
require 'db.php';

// Must be logged in
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_POST['appointment_id'])) {
    die("Missing required fields.");
}

$appointmentID = $_POST['appointment_id'];
$userID = $_SESSION['UserID'];

// Update appointment row
$statement = $pdo->prepare("
    UPDATE Appointments
    SET UserID = ?, available = 0
    WHERE Appointment_ID = ? AND available = 1
");

if ($statement->execute([$userID, $appointmentID])) {
    header("Location: dashboard.php?success=booked");
    exit;
} else {
    die("Database error: Could not update appointment.");
}
?>
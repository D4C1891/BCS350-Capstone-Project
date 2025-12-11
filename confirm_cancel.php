<?php
session_start();
require_once "db.php";

// User must be logged in
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

// Validate form submission.
if (!isset($_POST["appointment_id"]) || empty($_POST["appointment_id"])) {
    header("Location: cancel.php?error=invalid");
    exit;
}

$appointmentID = intval($_POST["appointment_id"]);

// Confirm appointment belongs to user.
$checkStmt = $pdo->prepare("
    SELECT Appointment_ID 
    FROM Appointments 
    WHERE Appointment_ID = ? AND UserID = ?
");
$checkStmt->execute([$appointmentID, $userID]);

if ($checkStmt->rowCount() === 0) {
    // User tried to cancel an appointment they don't own.
    header("Location: cancel.php?error=unauthorized");
    exit;
}

// Perform cancellation: free appointment & remove user.
$cancelStmt = $pdo->prepare("
    UPDATE Appointments
    SET UserID = NULL,
        available = 1
    WHERE Appointment_ID = ?
");

$cancelStmt->execute([$appointmentID]);

// Redirect user back to dashboard with success message.
header("Location: dashboard.php?success=canceled");
exit;

?>
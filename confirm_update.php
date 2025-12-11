<?php
session_start();
require_once "db.php";

// Checks to make sure the user is logged in.
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

// Make sure form fields exist.
if (!isset($_POST["current_id"], $_POST["new_id"])) {
    die("Missing required fields.");
}

$currentID = $_POST["current_id"];
$newID     = $_POST["new_id"];

// Make sure they are not the same.
if ($currentID == $newID) {
    die("Cannot update to the same appointment.");
}

// Validate that the current appointment belongs to the user.
$checkStmt = $pdo->prepare("
    SELECT Appointment_ID 
    FROM Appointments 
    WHERE Appointment_ID = ? AND UserID = ?
");
$checkStmt->execute([$currentID, $userID]);

if ($checkStmt->rowCount() === 0) {
    die("You are not allowed to modify this appointment.");
}

// Validate that the new appointment is avalible. 
$availStmt = $pdo->prepare("
    SELECT Appointment_ID 
    FROM Appointments 
    WHERE Appointment_ID = ? AND available = 1
");
$availStmt->execute([$newID]);

if ($availStmt->rowCount() === 0) {
    die("Selected new appointment is no longer available.");
}


// 3) Release the old appointment so others can book it. (set available = 1, UserID = NULL)
$freeOld = $pdo->prepare("
    UPDATE Appointments
    SET available = 1, UserID = NULL
    WHERE Appointment_ID = ?
");
$freeOld->execute([$currentID]);

// 4) Assign selected new appointment to the user.
$assignNew = $pdo->prepare("
    UPDATE Appointments
    SET available = 0, UserID = ?
    WHERE Appointment_ID = ?
");
$assignNew->execute([$userID, $newID]);

// 5) Redirect back to dashboard with success message.
header("Location: dashboard.php?success=updated");
exit;

?>
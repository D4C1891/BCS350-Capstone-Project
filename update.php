<?php
session_start();
require_once "db.php";

// Redirect if not logged in
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

// Fetch user's current appointment(s).
$currentStmt = $pdo->prepare("
    SELECT Appointment_ID, AppointmentDate, AppointmentTime, DoctorID
    FROM Appointments
    WHERE UserID = ?
");
$currentStmt->execute([$userID]);
$currentAppointments = $currentStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all available appointments (that no one has booked).
$availableStmt = $pdo->prepare("
    SELECT Appointment_ID, AppointmentDate, AppointmentTime, DoctorID
    FROM Appointments
    WHERE available = 1
    ORDER BY AppointmentDate, AppointmentTime
");
$availableStmt->execute();
$availableAppointments = $availableStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctor names.
$doctorLookup = [];
$docStmt = $pdo->prepare("SELECT DoctorID, FirstName, LastName, Specialty FROM Doctors");
$docStmt->execute();
foreach ($docStmt->fetchAll(PDO::FETCH_ASSOC) as $doc) {
    $doctorLookup[$doc["DoctorID"]] = $doc["FirstName"] . " " . $doc["LastName"] . " (" . $doc["Specialty"] . ")";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Appointment</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>

<div class="container">
    <div class="formSection">

        <div class="headerText">
            <div class="text">
                <p>Update your appointment here!</p>
            </div>
        </div>

        <form action="confirm_update.php" method="POST">
            <fieldset>
                <p class="legend">Update Appointment</p>

                <div class="form-group-wrapper">
                    <div class="formColumn1">

                        <label for="current">Your Current Appointment</label>
                        <select name="current_id" id="current" required>
                            <option value="">-- Select appointment to change --</option>

                            <?php foreach ($currentAppointments as $row): ?>
                                <option value="<?= $row['Appointment_ID'] ?>">
                                    <?= htmlspecialchars(
                                        $row["AppointmentDate"] . " at " . $row["AppointmentTime"] .
                                        " with " . $doctorLookup[$row["DoctorID"]]
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="new">Available New Appointments</label>
                        <select name="new_id" id="new" required>
                            <option value="">-- Select a new appointment --</option>

                            <?php foreach ($availableAppointments as $row): ?>
                                <option value="<?= $row['Appointment_ID'] ?>">
                                    <?= htmlspecialchars(
                                        $row["AppointmentDate"] . " at " . $row["AppointmentTime"] .
                                        " with " . $doctorLookup[$row["DoctorID"]]
                                    ) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>

            </fieldset>

            <div class="submitButton">
                <button type="submit">Update Appointment</button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
<?php
session_start();
require_once "db.php";

// Make sure user is logged in
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

// Fetch available appointments
$statement = $pdo->prepare("
    SELECT Appointment_ID, AppointmentDate, AppointmentTime, DoctorID
    FROM Appointments
    WHERE available = 1
    ORDER BY AppointmentDate, AppointmentTime
");
$statement->execute();
$appointments = $statement->fetchAll(PDO::FETCH_ASSOC);

// Fetch doctors for display
$doctorStmt = $pdo->prepare("SELECT DoctorID, FirstName, LastName, Specialty FROM Doctors");
$doctorStmt->execute();
$doctors = $doctorStmt->fetchAll(PDO::FETCH_ASSOC);

// Convert doctor records to array for lookup
$doctorLookup = [];
foreach ($doctors as $doc) {
    $doctorLookup[$doc["DoctorID"]] = $doc["FirstName"] . " " . $doc["LastName"] . " (" . $doc["Specialty"] . ")";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>

    <div class="container">
        <div class="formSection">

            <div class="headerText">
                <div class="text">
                    <p>Book your appointment here!</p>
                </div>
            </div>
            
            <form id="appointmentForm" action="confirm_booking.php" method="POST" novalidate>
                <fieldset>
                    <p class="legend">Appointment Info</p>

                    <div class="form-group-wrapper">
                        <div class="formColumn1">

                            <label for="appointment">Available Appointments</label>
                            <select name="appointment_id" id="appointment">
                                <option value="">-- Select an appointment --</option>

                                <?php foreach ($appointments as $row): ?>
                                    <option value="<?= $row["Appointment_ID"] ?>">
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
                    <button type="submit">Book Appointment</button>
                </div>

            </form>

        </div>
    </div>

</body>
</html>

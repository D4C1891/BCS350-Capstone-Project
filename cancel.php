<?php
session_start();
require_once "db.php";

// Ensure user is logged in.
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

// Fetch user's booked appointments.
$statement = $pdo->prepare("
    SELECT 
        A.Appointment_ID, 
        A.AppointmentDate, 
        A.AppointmentTime,
        A.DoctorID,
        D.FirstName,
        D.LastName,
        D.Specialty
    FROM Appointments A
    JOIN Doctors D ON A.DoctorID = D.DoctorID
    WHERE A.UserID = ?
    ORDER BY A.AppointmentDate, A.AppointmentTime
");
$statement->execute([$userID]);
$appointments = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Appointment</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>

    <div class="container">
        <div class="formSection">

            <div class="headerText">
                <div class="text">
                    <p>Cancel an appointment</p>
                </div>
            </div>
            
            <form id="cancelForm" action="confirm_cancel.php" method="POST" novalidate>
                <fieldset>
                    <p class="legend">Your Appointments</p>

                    <div class="form-group-wrapper">
                        <div class="formColumn1">

                            <label for="appointment">Select Appointment to Cancel</label>
                            <select name="appointment_id" id="appointment" required>
                                <option value="">-- Select an appointment --</option>

                                <?php if (count($appointments) > 0): ?>
                                    <?php foreach ($appointments as $row): ?>
                                        <option value="<?= $row["Appointment_ID"] ?>">
                                            <?= htmlspecialchars(
                                                $row["AppointmentDate"] . " at " . $row["AppointmentTime"] . 
                                                " with Dr. " . $row["FirstName"] . " " . $row["LastName"] .
                                                " (" . $row["Specialty"] . ")"
                                            ) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option disabled>No appointments booked.</option>
                                <?php endif; ?>

                            </select>

                        </div>
                    </div>
                </fieldset>

                <div class="submitButton">
                    <button type="submit">Cancel Appointment</button>
                </div>

            </form>

        </div>
    </div>

</body>
</html>

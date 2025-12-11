<?php
session_start();
require_once "db.php";

// User must be logged in.
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

// Fetch the user's booked appointments.
$stmt = $pdo->prepare("
    SELECT 
        A.Appointment_ID,
        A.AppointmentDate,
        A.AppointmentTime,
        D.FirstName AS DocFirst,
        D.LastName AS DocLast,
        D.Specialty
    FROM Appointments A
    JOIN Doctors D ON A.DoctorID = D.DoctorID
    WHERE A.UserID = ?
    ORDER BY A.AppointmentDate, A.AppointmentTime
");

$stmt->execute([$userID]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Appointments</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>

<div class="container">
    <div class="formSection">

        <div class="headerText">
            <div class="text">
                <p>Your Upcoming Appointments</p>
            </div>
        </div>

        <fieldset>
            <p class="legend">Appointment List</p>

            <?php if (count($appointments) === 0): ?>
                <p style="font-size: 20px; padding: 20px;">
                    You currently have no booked appointments.
                </p>

            <?php else: ?>

                <ul>

                    <?php foreach ($appointments as $row): ?>
                        <li>
                            <strong>Date:</strong> <?= htmlspecialchars($row["AppointmentDate"]) ?><br>
                            <strong>Time:</strong> <?= htmlspecialchars($row["AppointmentTime"]) ?><br>
                            <strong>Doctor:</strong> 
                                <?= htmlspecialchars($row["DocFirst"] . " " . $row["DocLast"]) ?><br>
                            <strong>Specialty:</strong> <?= htmlspecialchars($row["Specialty"]) ?>
                        </li>
                    <?php endforeach; ?>

                </ul>

            <?php endif; ?>

        </fieldset>

        <div class="submitButton">
            <a href="dashboard.php">
                <button type="button">Back to Dashboard</button>
            </a>
        </div>

    </div>
</div>

</body>
</html>
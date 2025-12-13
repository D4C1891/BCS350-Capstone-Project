<?php
session_start();
require_once "db.php";

if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

$userID = $_SESSION["UserID"];

//* Detect if the user has submitted a search //*
$searchDate = $_GET['search_date'] ?? null;
$appointments = [];
$searched = false;

//* Once a date is provided query the database to find the appointment. //*
if ($searchDate) {
    $searched = true;

    $statement = $pdo->prepare("
        SELECT 
            A.Appointment_ID,
            A.AppointmentDate,
            A.AppointmentTime,
            D.FirstName AS DocFirst,
            D.LastName AS DocLast,
            D.Specialty
        FROM Appointments A
        JOIN Doctors D ON A.DoctorID = D.DoctorID
        WHERE A.UserID = ? AND A.AppointmentDate = ?
        ORDER BY A.AppointmentDate, A.AppointmentTime
    ");
    $statement->execute([$userID, $searchDate]);
    $appointments = $statement->fetchAll(PDO::FETCH_ASSOC);
}
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

            <!-- HTML content to display the search bar, search information, and the appointment info from the db. -->
            <form method="GET" style="margin-bottom: 20px;">
                <label for="search_date">Search by date:</label>
                <input type="date" name="search_date" id="search_date">
                <button type="submit">Search</button>
            </form>

            <?php if (!$searched): ?>
                <p style="font-size: 20px; padding: 20px;">
                Please select a date and click search to view your appointments.
                </p>

            <?php elseif (count($appointments) === 0): ?>
            <p style="font-size: 20px; padding: 20px;">
            No appointments found for the selected date.
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
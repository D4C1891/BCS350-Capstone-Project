<?php
session_start();

// If user is not logged in, send them away
if (!isset($_SESSION["UserID"])) {
    header("Location: login.php");
    exit;
}

// Get user's first name from session
$firstName = $_SESSION["FirstName"] ?? "User";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles/dashStyles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>
    
    <!--  PhP to display a success message upon successful booking, updating, or deleting. -->
    <?php if (isset($_GET["success"])): ?>
        <div class="success-box">
            <?php if ($_GET["success"] === "booked"): ?>
                Appointment successfully booked!
            <?php elseif ($_GET["success"] === "updated"): ?>
                Appointment successfully updated!
            <?php elseif ($_GET["success"] === "canceled"): ?>
                Appointment successfully canceled!
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="container">
        
        <nav>
            <h1>Dashboard</h1>
        </nav>

        <div class= "header">
            <div class = "greeter">
                <div class = "greeter-text">
                    <p class = "hi">Hi there,</p>
                    <!-- PhP to show the users firstname and a greeting upon login. -->
                    <p class="id"><?php echo htmlspecialchars($firstName); ?></p>
                </div>
            </div>
        </div>

        <div class = "article">
            <section class = "options">
                <h2>Book, Update, Cancel, or View your appointments.</h2>
                <div class= "card-container">
                    <div class = "card">
                        <div class = "option-text">
                            <p class = "title"><a href="book.php">Book a Appointment</a></p>
                            <p class = "option-subtext">Book an appointment with an Appointment Assistant verified physician.</p>
                        </div>
                    </div>
                    <div class = "card">
                        <div class = "option-text">
                            <p class = "title"><a href="update.php">Update your Appointment</a></p>
                            <p class = "option-subtext">Update your appointment's time or date.</p>
                        </div>
                    </div>
                    <div class = "card">
                        <div class = "option-text">
                            <p class = "title"><a href="cancel.php">Cancel Appointment</a></p>
                            <p class = "option-subtext">Cancel your appointment if you cannot make it, don't need it, or change your mind.</p>
                        </div>
                    </div>
                    <div class = "card">
                        <div class = "option-text">
                            <p class = "title"><a href="view.php">View Appointments</a></p>
                            <p class = "option-subtext">View upcoming appointments you have booked.</p>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Test</title>
</head>
<body>
    <?php
    $conn = new mysqli("localhost","root", "", "Appointment_Assistant");
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "Connected Successfully!"
    ?>
    
</body>
</html>
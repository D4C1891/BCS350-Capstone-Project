<?php
session_start();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    // Pulls values using the form's field names
    $firstName = trim($_POST["first_name"]);
    $lastName = trim($_POST["last_name"]);
    $email = trim($_POST["user_email"]);
    $phone = trim($_POST["phone_number"]);
    $password = trim($_POST["user_password"]);
    $confirm = trim($_POST["confirm_password"]);

    // This section will ensure the fields are not empty, and the input is valid.
    if (empty($firstName) || empty($lastName) || empty($email) 
        || empty($phone) || empty($password) || empty($confirm)) {
            die("All fields are required.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    if ($password !== $confirm) {
        die("Passwords do not match.");
    }

    // This section will check for an existing user in the database.
    $check = $pdo->prepare("SELECT UserID FROM Users WHERE Email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        die("Email is already registered.");
    }

    // Hash the password to ensure security.
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // This MySQL statement will insert the information inputted into the database.
    $stmt = $pdo->prepare(
        "INSERT INTO Users (FirstName, LastName, Email, PhoneNumber, password)
        VALUES (:first, :last, :email, :phone, :password)"
    );

    $stmt->execute([
        ":first" => $firstName,
        ":last" => $lastName,
        ":email" => $email,
        ":phone" => $phone,
        ":password" => $hashedPassword,
    ]);

    echo "Registration successful!";
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>
    <div class = "container">
    </div>
        <div class = "formSection">
            <div class = "headerText">
                <div class = "text">
                    <p>Register with Appointment Assistant today to start booking all of your medical appointments in one place!</p>
                </div>
            </div>
            
            <form action = "register.php" method="POST">
                <fieldset>
                <p class = "legend">Registration Information</p>
                    <div class = "form-group-wrapper">
                        <div class = "formColumn1">    
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" required placeholder="John">

                            <label for="user_email">Email</label>
                            <input type="email" id="user_email" name="user_email" required placeholder="johnsmith123@mail.com">

                            <label for="user_password">Password</label>
                            <input type="password" name="user_password" id="user_password" required>
                        </div>
                        <div class = "formColumn2">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" required placeholder="Smith">

                            <label for="phone_number">Phone Number</label>
                            <input type="tel" id="phone_number" name="phone_number" pattern="[0-9]{10}" required placeholder="123-456-7890">

                            <label for="confirm_password">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required>
                        </div>
                    </div>
                </fieldset>
                
                <div class = "submitButton">
                    <button type="submit">Create Account</button>
                    <p class = "submitText">Already have an account? <span class = "login"><a href = "login.php">Log in</a></span></p>
                </div>  
            </form>
        </div>
    </div>
</body>
</html>
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

    // Makes sure email format is valid.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format");
    }

    // Makes sure passwords match.
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
    $statement = $pdo->prepare(
        "INSERT INTO Users (FirstName, LastName, Email, PhoneNumber, password)
        VALUES (:first, :last, :email, :phone, :password)"
    );

    $statement->execute([
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
            
            <div id="errorBox" style="color: white; font-weight: bold;"></div>
            <form id ="regForm" action = "register.php" method="POST" novalidate>
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
    

    <script>
        document.getElementById("regForm").addEventListener("submit", function(e) {

            const first = document.getElementById("first_name").value.trim();
            const last = document.getElementById("last_name").value.trim();
            const email = document.getElementById("user_email").value.trim();
            const phone = document.getElementById("phone_number").value.trim();
            const password = document.getElementById("user_password").value;
            const confirm = document.getElementById("confirm_password").value;
            const errorBox = document.getElementById("errorBox");
            
            errorBox.textContent = "";

            // Email regex.
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Phone regex.
            const phonePattern = /^(\d{3}-?\d{3}-?\d{4})$/;

            // Required fields.
            if (!first || !last || !email || !phone || !password || !confirm) {
                errorBox.textContent = "Please fill out all fields.";
                e.preventDefault();
                return;
            }

            // Email validation.
            if (!emailPattern.test(email)) {
                errorBox.textContent = "Please enter a valid email address.";
                e.preventDefault();
                return;
            }

            // Phone number validation
            if (!phonePattern.test(phone)) {
                errorBox.textContent = "Phone number must be 123-456-7890 or 1234567890.";
                e.preventDefault();
                return;
            }

            // Strong Password Validation.
            let passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/;

            if (!passwordPattern.test(password)) {
                errorBox.textContent = "Password must be at least 8 characters long and include:\n- One uppercase letter\n- One lowercase letter\n- One number";
                e.preventDefault();
                return;
            }

            // Ensures Password length.
            if (password.length < 8) {
                errorBox.textContent = "Password must be at least 8 characters long.";
                e.preventDefault();
                return;
            }

            // Ensures Password match.
            if (password !== confirm) {
                errorBox.textContent = "Passwords do not match.";
                e.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>
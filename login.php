<?php
session_start();
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = trim($_POST["user_email"]);
    $password = trim ($_POST["user_password"]);

    if (empty($email) || empty($password)) {
        die("All fields are required");
    }

    // Selects the users information by email.
    $stmt = $pdo->prepare("SELECT UserID, password, FirstName FROM Users WHERE Email = ?");
    $stmt->execute([$email]);

    if($stmt->rowCount() === 0) {
        die("Invalid email or password.");
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    //Verifys password hash.
    if (!password_verify($password, $user["password"])) {
        die("Invalid email or password.");
    }

    //On successful login, stores the users info in a session.
    $_SESSION["UserID"] = $user["UserID"];
    $_SESSION["FirstName"] = $user["FirstName"];

    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>
    <div class = "container">
    </div>
        <div class = "formSection">
            <div class = "headerText">
                <div class = "text">
                    <p>Welcome back! Log in here with your email and password!</p>
                </div>
            </div>
            
            <form action = "login.php" method="POST">
                <fieldset>
                <p class = "legend">Login Information</p>
                    <div class = "form-group-wrapper">
                        <div class = "formColumn1">    
                            <label for="user_email">Email</label>
                            <input type="email" id="user_email" name="user_email" required placeholder="johnsmith123@mail.com">

                            <label for="user_password">Password</label>
                            <input type="password" name="user_password" id="user_password" required>
                        </div>
                    </div>
                </fieldset>
                
                <div class = "submitButton">
                    <button type="submit">Log in</button>
            </form>
        </div>
    </div>
</body>
</html>
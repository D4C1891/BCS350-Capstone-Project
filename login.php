<?php
session_start();
require_once "db.php";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = trim($_POST["user_email"]);
    $password = trim ($_POST["user_password"]);

    //  Verifys fields are not empty.
    if (empty($email) || empty($password)) {
        header("Location: login.php?error=empty");
        exit;
    }

    // Selects the users information by email.
    $statement = $pdo->prepare("SELECT UserID, password, FirstName FROM Users WHERE Email = ?");
    $statement->execute([$email]);

    if ($statement->rowCount() === 0) {
        header("Location: login.php?error=invalid");
        exit;
    }

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    //  Verifys password hash.
    if (!password_verify($password, $user["password"])) {
        header("Location: login.php?error=invalid");
        exit;
    }

    // On successful login, stores the users info in a session.
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
            
            <div id="errorBox" style="color: white; font-weight: bold;"></div>
            <form id = "loginForm" action = "login.php" method="POST" novalidate>
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

<script>
    
    document.getElementById("loginForm").addEventListener("submit", function(e) {
    const email = document.getElementById("user_email").value.trim();
    const password = document.getElementById("user_password").value;
    const errorBox = document.getElementById("errorBox");

    errorBox.textContent = "";

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    
    // Ensures both fields are not empty.
    if (!email || !password) {
        errorBox.textContent = "Please fill out all fields.";
        e.preventDefault();
        return;
    }

    // Ensures email address format is valid.
    if (!emailPattern.test(email)) {
        errorBox.textContent = "Please enter a valid email address.";
        e.preventDefault();
        return;
    }
});

// Ensures that server-side errors are displayed within the error box.
    document.addEventListener("DOMContentLoaded", function() {
        const error = "<?php echo $_GET['error'] ?? ''; ?>";
        const errorBox = document.getElementById("errorBox");

        if (error === "empty") {
            errorBox.textContent = "Please fill out all fields.";
        } else if (error === "invalid") {
        errorBox.textContent = "Invalid email or password.";
        }
    });

</script>
</body>
</html>
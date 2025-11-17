!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
    <link rel="stylesheet" href="styles/regStyles.css">
</head>
<body>
    <div class = "container">
        <div class = "logoSection">
            <div class = "logoSidebar">
                <h1>Appointment Assistant</h1>
            </div>

            <footer>
                <p> Photo by <a href=https://unsplash.com/@haliewestphoto>Haile West</a>
                on <a href="https://unsplash.com/photos/green-leaf-plant-in-close-up-photography-25xggax4bSA">Unsplash</a>
                </p>        
            </footer>
        </div>

        <div class = "formSection">
            <div class = "headerText">
                <div class = "text">
                    <p>This is not a real online service! You know you need something like this
                in your life to help you realize your deepest dreams. Sign up <em>now</em> to
                get started.</p>
                
                    <p>You <em>know</em> you want to.</p>
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
                    <p class = "submitText">Already have an account? <span class = "login">Log in</span></p>
                </div>  
            </form>
        </div>
    </div>
</body>
</html>
<?php
session_start();
include('db.php'); 

$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = hash('sha256', $_POST['password']);
        $confirm_password = $_POST['confirm_password'];

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error_message = "Invalid email format";
        }
        else if($confirm_password == $_POST['password']){
            $check_query = "SELECT * FROM users WHERE email='$email'";
            $result = mysqli_query($con, $check_query);

            if (mysqli_num_rows($result) == 0) {
                $query = "INSERT INTO users (email, password, username) VALUES ('$email', '$password', '$username')";
                if (mysqli_query($con, $query)) {
                    $_SESSION['user_id'] = mysqli_insert_id($con);
                    $_SESSION['user_email'] = $email;
                    $_SESSION['username'] = $username;
                    $_SESSION['user_logged_in'] = true;
                    $_SESSION['is_admin'] = false;
                    header("Location: home.php");
                    exit();
                } else {
                    $error_message = "Registration failed! Please try again.";
                }
            } else {
                $error_message = "Email already exists!";
            }
        } else {
            $error_message = "Passwords do not match";
        }
        
    } elseif (isset($_POST['login'])) {
        $email = $_POST['login_email'];
        $password = hash('sha256', $_POST['login_password']);
    
        $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
        $result = mysqli_query($con, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_logged_in'] = true;
        
            if ($user['email'] == 'admin@admin.com') {
                $_SESSION['is_admin'] = true;
                setcookie('user_logged_in', true, time() + (30 * 60), "/");
                setcookie('user_id', $user['id'], time() + (30 * 60), "/");
                setcookie('is_admin', $user['is_admin'], time() + (30 * 60), "/");
            } else {
                $_SESSION['is_admin'] = false;
            }
            header("Location: home.php");
            exit();
        } else {
            $error_message = "Invalid email or password!";
        }
    }

    if (!empty($error_message)) {
        echo '<script>alert("' . $error_message . '");</script>';
    }

}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Animal shelter</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
    <div class="container">
    <form name="registration_form" id="registration_form" method="post">
        <div class="row">
            <div class="col-sm-4">
                <h2>Registration</h2>
                
                <div class="form-group">
                    <span class="icon"><ion-icon name="person"></ion-icon></span>
                    <input type="text" name="username" id="username" class="form-control" required>
                    <label for="username">Enter your username</label>
                </div>
                <div class="form-group">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" name="email" id="email" class="form-control">
                    <label for="email">Enter your email</label>
                </div>

                <div class="form-group">
                    <input type="password" name="password" id="password" class="form-control">
                    <label for="password">Enter your password</label>
                    <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                        <ion-icon name="eye"></ion-icon>
                    </span>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                    <label for="confirm_password">Confirm your password</label>
                    <span class="toggle-password" onclick="togglePasswordVisibility('confirm_password')">
                        <ion-icon name="eye"></ion-icon>
                    </span>
                </div>
                <button type="submit" id="register" name="register" class="btn btn-success">Register</button>
                <div class="login-register">
                    <p>
                        <a href="#" class="login-link">Already have an account?</a>
                    </p>
                </div> 
            </div>
        </div>
    </form>

    <form name="login_form" id="login_form" method="post">
        <div class="row">
            <div class="col-sm-4">
                <h2>Login</h2>
                
                <div class="form-group">
                    <span class="icon"><ion-icon name="mail"></ion-icon></span>
                    <input type="text" name="login_email" id="login_email" class="form-control">
                    <label for="email">Enter your email</label>
                </div>

                <div class="form-group">
                    <input type="password" name="login_password" id="login_password" class="form-control">
                    <label for="login_password">Enter your password</label>
                    <span class="toggle-password" onclick="togglePasswordVisibility('login_password')">
                        <ion-icon name="eye"></ion-icon>
                    </span>
                </div>
                <button type="submit" id="login" name="login" class="btn btn-success">Login</button>
                <div class="login-register">
                    <p>
                        <a href="#" class="register-link">Not a member yet?</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
    </div>
    

    <script>
        function togglePasswordVisibility(fieldId) {
            var field = document.getElementById(fieldId);
            var icon = field.nextElementSibling.querySelector('ion-icon');
            if (field.type === "password") {
                field.type = "text";
                icon.name = "eye-off";
            } else {
                field.type = "password";
                icon.name = "eye";
            }
        }

        document.querySelector(".login-link").addEventListener("click", function() {
            document.querySelector('.container').classList.remove("active");
            document.querySelector('.container').classList.add("active-popup");
        });

        document.querySelector(".register-link").addEventListener("click", function() {
            document.querySelector('.container').classList.add("active");
        });

    

    </script>
    
</body>
</html>
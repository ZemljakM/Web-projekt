<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);
$username = $user['username'];


$query_adoptions = "SELECT animal_name, pickup_day 
                    FROM adoptions 
                    JOIN animals ON adoptions.animal_id = animals.id
                    WHERE adoptions.username = '$username'";
$result_adoptions = mysqli_query($con, $query_adoptions);


?>

<!DOCTYPE html>
<html>
<head>
    <title>Animal Shelter</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>

<nav>
    <h1>Animal Shelter</h1>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <?php if ($_SESSION['is_admin']): ?>
            <a href="new_post.php">New post</a>
        <?php endif; ?>
        <a href="adoption.php">Adoption</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="hamburger" onclick="toggleMenu()">
        <div></div>
        <div></div>
        <div></div>
    </div>
</nav>

<div class="container">
    <div class="profile-info">
        <h2>Personal Information</h2>
        <p><span>Username:</span> <?php echo $user['username']; ?></p>
        <p><span>Email:</span> <?php echo $user['email']; ?></p>
    </div>

    <div class="adoptions-list">
        <h2>Adopted Animals</h2>
        <ul>
            <?php while ($row = mysqli_fetch_assoc($result_adoptions)): ?>
                <li>
                    <span>Animal Name:</span> <?php echo $row['animal_name']; ?><br>
                    <span>Pickup Date:</span> <?php echo $row['pickup_day']; ?>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
    
</div>

<script src="script.js"></script>
</body>
</html>

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



$id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM animals WHERE id='$id'");
$animal = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $animal_id = $_POST['animal_id'];
    $animal_name = $_POST['animal_name'];
    $username = $_POST['username'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $pickup_day = $_POST['pickup_day'];

    
    $query = "INSERT INTO adoptions (animal_id, animal_name, user_id, username, city, country, pickup_day) 
              VALUES ('$animal_id', '$animal_name', $user_id, '$username', '$city', '$country', '$pickup_day')";
    $result = mysqli_query($con, $query);

    if ($result) {
        $update_query = "UPDATE animals SET adopted = 1 WHERE id = '$animal_id'";
        mysqli_query($con, $update_query);

        header("Location: adoption.php");
    } else {
        echo "Error adopting animal";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Adopt Animal</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="home.css">
    <link rel="stylesheet" type="text/css" href="form.css">
</head>
<body>

<nav>
    <h1>Animal shelter</h1>
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

<div class="form-container">
    <form method="post" action="">
        <header>
            <div class="set">
                <div class="pets-name">
                    <label for="animal_name" class="set-label">Animal Name</label>
                    <input type="text" id="animal_name" name="animal_name" value="<?php echo $animal['name']; ?>" readonly>
                </div>
                <div class="pets-name">
                    <label for="username" class="set-label">Username</label>
                    <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>" readonly>
                </div>
                <input type="hidden" name="animal_id" value="<?php echo $animal['id']; ?>">
            </div>
            <div class="set">
                <div class="pets-description">
                    <label for="city" class="set-label">City</label>
                    <input type="text" id="city" name="city" placeholder="Your city" required>
                </div>
                <div class="pets-age">
                    <label for="country" class="set-label">Country</label>
                    <input type="text" id="country" name="country" placeholder="Your country" required>
                </div>
            </div>
            <div class="set-pickup_day">
                <div class="pickup_day">
                    <label for="pickup_day" class="set-label">Pickup Day</label>
                    <div class="radio-container">
                        <input id="pickup-today" type="radio" name="pickup_day" value="<?php echo date('Y-m-d'); ?>" checked>
                        <label for="pickup-today"><?php echo date('M j, Y'); ?></label>
                        <input id="pickup-tomorrow" type="radio" name="pickup_day" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                        <label for="pickup-tomorrow"><?php echo date('M j, Y', strtotime('+1 day')); ?></label>
                        <input id="pickup-2days" type="radio" name="pickup_day" value="<?php echo date('Y-m-d', strtotime('+2 day')); ?>">
                        <label for="pickup-2days"><?php echo date('M j, Y', strtotime('+2 day')); ?></label>
                        <input id="pickup-3days" type="radio" name="pickup_day" value="<?php echo date('Y-m-d', strtotime('+3 day')); ?>">
                        <label for="pickup-3days"><?php echo date('M j, Y', strtotime('+3 day')); ?></label>
                    </div>
                </div>
            </div>
        </header>
        <footer>
            <div class="set">
                <button type="submit">Adopt</button>
            </div>
        </footer>
    </form>
</div>

<script src="script.js"></script>
</body>
</html>

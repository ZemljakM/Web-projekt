<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: index.php");
    exit();
}


$result = mysqli_query($con, "SELECT * FROM animals WHERE adopted = 0");
$animals = [];
while ($row = mysqli_fetch_assoc($result)) {
    $animals[] = $row;
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Animal Shelter</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="home.css">
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" type="text/css" href="home.css">
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

<?php if ($_SESSION['is_admin']): ?>
    <div class="add_animal">
        <a href="add_animal.php" class="add-animal-button">Add New Animal</a>
    </div>
    
<?php endif; ?>

<div class="animals-grid">
    <?php foreach ($animals as $animal): ?>
        <div class="animal-card">
            <img src="<?php echo $animal['image']; ?>" alt="<?php echo $animal['name']; ?>">
            <div class="animal-card-body">
                <h3><?php echo $animal['name']; ?></h3>
                <p><?php echo $animal['description']; ?></p>
                <p>Category: <?php echo $animal['category']; ?></p>
                <p>Gender: <?php echo $animal['gender']; ?></p>
                <p>Age: <?php echo $animal['age']; ?></p>
                <a href="adopt_animal.php?id=<?php echo $animal['id']; ?>">Adopt</a>
                <?php if ($_SESSION['is_admin']): ?>
                    <a href="edit_animal.php?id=<?php echo $animal['id']; ?>" class="edit-button">Edit</a>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script src="script.js"></script>

</body>
</html>
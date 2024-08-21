<?php
session_start();
include('db.php');

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: adoption.php");
    exit();
}

$id = $_GET['id'];
$result = mysqli_query($con, "SELECT * FROM animals WHERE id='$id'");
$animal = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_animal'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $description = $_POST['description'];
        $gender = $_POST['gender'];
        $age = $_POST['age'];

        if (!empty($_FILES["image"]["name"])) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES["image"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                echo "Sorry, only JPG, JPEG and PNG files are allowed.";
                $uploadOk = 0;
            }

            if ($uploadOk == 0) {
                echo "Sorry, your file was not uploaded.";
            } else {
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                    $image = $target_file;
                    $query = "UPDATE animals SET name='$name', category='$category', image='$image', description='$description', gender='$gender', age='$age' WHERE id='$id'";
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    exit();
                }
            }
        } else {
            $query = "UPDATE animals SET name='$name', category='$category', description='$description', gender='$gender', age='$age' WHERE id='$id'";
        }

        $result = mysqli_query($con, $query);

        if ($result) {
            echo "Post successfully updated!";
        } else {
            echo "Error updateing post";
        }

    } elseif (isset($_POST['delete_animal'])) {

        $query = "DELETE FROM animals WHERE id='$id'";
        $result = mysqli_query($con, $query);
        

        if ($result) {
            echo "Post successfully deleted!";
        } else {
            echo "Error deleting post";
        }
    }

    header("Location: adoption.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Animal Shelter</title>
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
    <form method="post" action="" enctype="multipart/form-data">
        <header>
            <div class="set">
                <div class="pets-name">
                    <label for="name" class="set-label">Name</label>
                    <input type="text" id="name" name="name" placeholder="Pet's name" value="<?php echo $animal['name']; ?>" required>
                </div>
                <div class="pets-gender">
                    <label for="pet-gender-male" class="set-label">Gender</label>
                    <div class="radio-container">
                        <input id="pet-gender-male" type="radio" name="gender" value="Male" <?php echo ($animal['gender'] == 'Male') ? 'checked' : ''; ?>>
                        <label for="pet-gender-male">Male</label>
                        <input id="pet-gender-female" type="radio" name="gender" value="Female" <?php echo ($animal['gender'] == 'Female') ? 'checked' : ''; ?>>
                        <label for="pet-gender-female">Female</label>
                    </div>
                </div>
            </div>
            <div class="set">
                <div class="pets-description">
                    <label for="description" class="set-label">Description</label>
                    <input type="text" id="description" name="description" placeholder="Pet's description" value="<?php echo $animal['description']; ?>" required>
                </div>
                <div class="pets-age">
                    <label for="pets-age" class="set-label">Age</label>
                    <input type="text" id="pets-age" name="age" placeholder="Pet's age" value="<?php echo $animal['age']; ?>" required>
                </div>
            </div>
            <div class="set">
                <div class="pets-breed">
                    <label for="pet-breed-dog" class="set-label">Category</label>
                    <div class="radio-container">
                        <input id="pet-breed-dog" type="radio" name="category" value="Dog" <?php echo ($animal['category'] == 'Dog') ? 'checked' : ''; ?>>
                        <label for="pet-breed-dog">Dog</label>
                        <input id="pet-breed-cat" type="radio" name="category" value="Cat" <?php echo ($animal['category'] == 'Cat') ? 'checked' : ''; ?>>
                        <label for="pet-breed-cat">Cat</label>
                        <input id="pet-breed-bird" type="radio" name="category" value="Bird" <?php echo ($animal['category'] == 'Bird') ? 'checked' : ''; ?>>
                        <label for="pet-breed-bird">Bird</label>
                        <input id="pet-breed-other" type="radio" name="category" value="Other" <?php echo ($animal['category'] == 'Other') ? 'checked' : ''; ?>>
                        <label for="pet-breed-other">Other</label>
                    </div>
                </div>
                <div class="photo">
                    <label for="image" id="file-label" class="custom-file-upload">
                        <i class="fa fa-camera"></i> <?php echo basename($animal['image']); ?>
                    </label>
                    <input type="file" id="image" name="image" accept="image/*" style="display:none;">
                </div>
            </div>
        </header>
        <footer>
            <div class="set">
                <button type="submit" name="edit_animal">Save changes</button>
                <button type="submit" name="delete_animal">Delete animal</button>
            </div>
        </footer>
    </form>
</div>

<script>
    document.getElementById('image').addEventListener('change', function() {
        var fileName = this.files[0].name;
        var label = document.getElementById('file-label');
        label.innerHTML = '<i class="fa fa-camera"></i> ' + fileName;
    });
</script>
<script src="script.js"></script>
</body>
</html>

<?php
session_start();
include('db.php'); 

if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $userId = $_SESSION['user_id'];
    $description = $_POST['description'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["image"]["tmp_name"]);
        if($check !== false) {
          $uploadOk = 1;
        } else {
          echo "File is not an image.";
          $uploadOk = 0;
        }
    }

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        echo "Sorry, only JPG, JPEG and PNG files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $sql = "INSERT INTO posts (user_id, image, description, created_at, likes, comments) VALUES ('$userId', '$target_file', '$description', CURRENT_TIMESTAMP, 0, 0)";
            if ($con->query($sql) === TRUE) {
                echo "New post created successfully";
                header("Location: home.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Animal shelter</title>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="home.css">
    <link rel="stylesheet" type="text/css" href="form.css">
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

    <nav>
        <h1>Animal shelter</h1>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="new_post.php">New post</a>
            <a href="profile.php">Profile</a>
            <a href="adoption.php">Adoption</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <div class="form-container-post">
        <h2>Create a New Post</h2>
        <form action="new_post.php" method="post" enctype="multipart/form-data">
            <header>
                <div class="set">
                    <div class="post-description">
                        <label for="description" class="set-label">Description</label>
                        <input type="text" id="description" name="description" placeholder="Write a description..." required>
                    </div>
                </div>
                <div class="set">
                    <div class="photo">
                        <label for="image" id="file-label" class="custom-file-upload">
                            <i class="fa fa-camera"></i> Upload a photo
                        </label>
                        <input type="file" id="image" name="image" accept="image/*" style="display:none;" required>
                    </div>
                    <footer>
                        <div class="set">
                            <button type="submit">Share post</button>
                        </div>
                    </footer>
                </div>
            </header>
            
        </form>
    </div>


    <script src="script.js"></script>
    <script>
        document.getElementById('image').addEventListener('change', function() {
            var fileName = this.files[0].name;
            var label = document.getElementById('file-label');
            label.innerHTML = '<i class="fa fa-camera"></i> ' + fileName;
        });
    </script>
</body>
</html>
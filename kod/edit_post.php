<?php
session_start();
include('db.php'); 
if (!isset($_SESSION['user_logged_in']) || !$_SESSION['is_admin']) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['post_id'];
$result = mysqli_query($con, "SELECT * FROM posts WHERE id = '$post_id'");
$post = mysqli_fetch_assoc($result);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_post'])) {
        $postId = $_POST['post_id'];
        $newDescription = $_POST['description'];

        $query = "UPDATE posts SET description = '$newDescription' WHERE id = $postId";
        $result = mysqli_query($con, $query);

        if ($result) {
            echo "Post successfully updated!";
        } else {
            echo "Error updating post";
        }
    } elseif (isset($_POST['delete_post'])) {

        $deleteLikesQuery = "DELETE FROM post_likes WHERE post_id = $post_id";
        $deleteLikesResult = mysqli_query($con, $deleteLikesQuery);

        $deleteQuery = "DELETE FROM posts WHERE id = $post_id";
        $deleteResult = mysqli_query($con, $deleteQuery);
        

        if ($deleteResult && $deleteLikesResult) {
            echo "Post successfully deleted!";
        } else {
            echo "Error deleting post";
        }
    }

    header("Location: home.php");
    exit;
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
</head>
<body>

    <nav>
        <h1>Animal shelter</h1>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="new_post.php">New post</a>
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

<div class="container-desc">
    <div class="post-desc">
        <div class="post-header">
            <span class="username">Animal shelter</span>
        </div>
        <div class="post-images">
            <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['description']; ?>">
        </div>
        <div class="post-likes"><?php echo $post['likes']; ?> likes</div>
        <div class="post-description"><?php echo $post['description']; ?></div>
        <div class="post-comments"><?php echo $post['comments']; ?> comments</div>
    </div>
    
    <div class="form-container-desc">
        <h2>New Description</h2>
        <form method="post" action="">
            <header>
                <div class="set">
                    <div class="post-description">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <input type="text" id="description" name="description" value="<?php echo $post['description']; ?>" required>
                    </div>
                </div>
                <div class="set">
                    <footer>
                        <button type="submit" name="edit_post">Edit Post</button>
                        <button type="submit" name="delete_post">Delete Post</button>
                    </footer>
                </div>
            </header>
        </form>
    </div>
</div>

<script src="script.js"></script>
</body>
</html>

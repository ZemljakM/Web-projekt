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
    <script src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</head>
<body>

    <nav>
        <h1>Animal shelter</h1>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="new_post.php">New post</a>
            <a href="profile.php">Profile</a>
            <a href="adoption.php">Adoption</a>
            <a href="donation.php">Donation</a>
            <a href="logout.php" class="logout">Logout</a>
        </div>
        <div class="hamburger" onclick="toggleMenu()">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </nav>

    <div class="post">
        <div class="post-header">
            <span class="username">Animal shelter</span>
            <ion-icon name="create-outline"></ion-icon>
        </div>
        <div class="post-images">
            <img src="<?php echo $post['image']; ?>" alt="<?php echo $post['description']; ?>">
        </div>
        <div class="post-actions">
            <ion-icon name="heart-outline" class="like-icon"></ion-icon>
            <ion-icon name="chatbubble-outline"></ion-icon>
        </div>
        <div class="post-likes"><?php echo $post['likes']; ?> likes</div>
        <div class="post-description"><?php echo $post['description']; ?></div>
        <div class="post-comments"><?php echo $post['comments']; ?> comments</div>
    </div>
    

    <form method="post" action="">
        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
        <textarea name="description"><?php echo $post['description']; ?></textarea>
        <button type="submit" name="edit_post">Edit Post</button>
        <button type="submit" name="delete_post">Delete Post</button>
    </form>

</body>
</html>

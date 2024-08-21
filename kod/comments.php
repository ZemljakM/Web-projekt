<?php
session_start();
include('db.php'); 
if (!isset($_SESSION['user_logged_in'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['post_id'];
$result = mysqli_query($con, "SELECT * FROM posts WHERE id = '$post_id'");
$post = mysqli_fetch_assoc($result);

$commentsQuery = "SELECT post_comments.comment, users.username 
                  FROM post_comments 
                  JOIN users ON post_comments.user_id = users.id 
                  WHERE post_comments.post_id = '$post_id' 
                  ORDER BY post_comments.created_at ASC";
$commentsResult = mysqli_query($con, $commentsQuery);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_comment'])) {
        $postId = $_POST['post_id'];
        $userId = $_SESSION['user_id']; 
        $comment = $_POST['comment'];

        $insertCommentQuery = "INSERT INTO post_comments (post_id, user_id, comment) VALUES ('$postId', '$userId', '$comment')";
        $insertCommentResult = mysqli_query($con, $insertCommentQuery);

        $updateCommentsCountQuery = "UPDATE posts SET comments = comments + 1 WHERE id = '$postId'";
        $updateCommentsCountResult = mysqli_query($con, $updateCommentsCountQuery);

        if ($insertCommentResult && $updateCommentsCountResult) {
            echo "Comment successfully saved!";
        } else {
            echo "Error saving comment";
        }
    }
    header("Location: home.php");
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Animal Shelter</title>
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

<div class="container-comments">
    <div class="post">
        <div class="post-header">
            <span class="username">Animal shelter</span>
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


    <div class="form-container-comments">
        <h2>Comments</h2>
        <form method="post" action="">
            <header>
                <div class="set">
                    <div class="post-description">
                        <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                        <input type="text" id="comment" name="comment" placeholder="Write a comment..." required>
                    </div>
                </div>
                <div class="set">
                    <footer>
                        <div class="set">
                            <button type="submit" name="save_comment">Save comment</button>
                        </div>
                    </footer>
                </div>
            </header>
        </form>
        <div class="comments-section">
            <ul>
                <?php while ($comment = mysqli_fetch_assoc($commentsResult)): ?>
                    <li>
                        <p> <strong><?php echo $comment['username']; ?>:  </strong>   <?php echo $comment['comment']; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>

</div>


<script src="script.js"></script>
</body>
</html>
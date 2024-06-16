<?php
session_start();
include('db.php'); // Adjust as per your database connection details

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id']) && isset($_POST['like_action'])) {
    $postId = $_POST['post_id'];
    $action = $_POST['like_action'];
    $userId = $_SESSION['user_id'];

    $postId = mysqli_real_escape_string($con, $postId);

    if ($action === 'like') {
        mysqli_query($con, "UPDATE posts SET likes = likes + 1 WHERE id = $postId");
        mysqli_query($con, "INSERT INTO post_likes (user_id, post_id) VALUES ($userId, $postId)");
    } elseif ($action === 'unlike') {
        mysqli_query($con, "UPDATE posts SET likes = likes - 1 WHERE id = $postId");
        mysqli_query($con, "DELETE FROM post_likes WHERE user_id = $userId AND post_id = $postId");
    }

    

    header("Location: home.php");
    exit;
}
?>
<?php
    session_start();
    include('db.php'); 

    if (!isset($_SESSION['user_logged_in'])) {
        header("Location: index.php");
        exit();
    }


    $isAdmin = isset($_SESSION['user_logged_in']) && $_SESSION['is_admin'];

    if (isset($_GET['getPosts']) && $_GET['getPosts'] == 'true') {
        $userId = $_SESSION['user_id'];
        $result = mysqli_query($con, "SELECT p.*, 
            (SELECT COUNT(*) FROM post_likes pl WHERE pl.post_id = p.id AND pl.user_id = '$userId') AS user_liked
            FROM posts p
            ORDER BY p.created_at DESC");
        $posts = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo json_encode($posts);
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
    <script>
        const isAdmin = <?php echo json_encode($isAdmin); ?>;
    </script>
</head>
<body>

    <nav>
        <h1>Animal shelter</h1>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <?php if ($isAdmin): ?>
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

    <div class="main-content"></div>
    

    <script src="script.js"></script>
    
</body>
</html>





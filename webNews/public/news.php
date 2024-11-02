<?php

    session_start(); // Start the session to track user actions

    include '../config/db_config.php'; // Ensure your database connection is established

    // Check if the ID is set in the URL
    if (isset($_GET['id'])) {
        $art_id = intval($_GET['id']); // Get the article ID from the URL

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM newstb WHERE id = ?");
        $stmt->bind_param("i", $art_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the article exists
        if ($result->num_rows > 0) {
            $article = $result->fetch_assoc(); // Fetch the article data

        // Track views in the session
        if (!isset($_SESSION['viewed_articles'])) {
            $_SESSION['viewed_articles'] = []; // Initialize as an empty array
        }

        // Increment views count only if the article has not been viewed by the user
        if (!in_array($art_id, $_SESSION['viewed_articles'])) {
            // Increment views count in the database
            $views_update_stmt = $conn->prepare("UPDATE newstb SET views = views + 1 WHERE id = ?");
            $views_update_stmt->bind_param("i", $art_id);
            $views_update_stmt->execute();

            // Store the article ID in the session to mark it as viewed
            $_SESSION['viewed_articles'][] = $art_id;
        }

        } else {
            echo "Article not found.";
            exit;
        }

        // Fetch comments for the article
        $comment_stmt = $conn->prepare("SELECT * FROM comments WHERE art_id = ?");
        $comment_stmt->bind_param("i", $art_id);
        $comment_stmt->execute();
        $comments_result = $comment_stmt->get_result();
        $comments = $comments_result->fetch_all(MYSQLI_ASSOC); // Fetch all comments as an associative array

    // Handle comment submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment']) && isset($_POST['art_id'])) {
        $comment_text = trim($_POST['comment']);
        $art_id = intval($_POST['art_id']); // Ensure article ID is an integer

        // Check that the comment is not empty
        if (!empty($comment_text)) {
            // Prepare the SQL statement to insert the comment
            $insert_stmt = $conn->prepare("INSERT INTO comments (art_id, comments) VALUES (?, ?)");
            $insert_stmt->bind_param("is", $art_id, $comment_text);
            if ($insert_stmt->execute()) {
                // Optionally, redirect to the same page to avoid re-submission
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $art_id);
                exit;
            } else {
                echo "Error saving comment.";
            }
        } else {
            $error_message = "Комментарий не может быть пустым.";
        }
    }

    // Initialize likes tracking in the session if it doesn't exist
    if (!isset($_SESSION['liked_articles'])) {
        $_SESSION['liked_articles'] = []; // Initialize as an empty array
    }

    // Check if the article has been liked in the current session
    $user_has_liked = in_array($art_id, $_SESSION['liked_articles']);

    // Handle like/unlike logic
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_action'])) {
        if ($user_has_liked) {
            // Unlike the article (remove article ID from session)
            if (($key = array_search($art_id, $_SESSION['liked_articles'])) !== false) {
                unset($_SESSION['liked_articles'][$key]); // Remove the article ID from the session
            }

            // Decrement likes count only if the current number of likes is greater than 0
            $likes_query = $conn->prepare("SELECT likes FROM newstb WHERE id = ?");
            $likes_query->bind_param("i", $art_id);
            $likes_query->execute();
            $likes_result = $likes_query->get_result();
            $current_likes = $likes_result->fetch_assoc()['likes'];

            if ($current_likes > 0) { // Ensure likes don't go below zero
                $unlike_stmt = $conn->prepare("UPDATE newstb SET likes = likes - 1 WHERE id = ?");
                $unlike_stmt->bind_param("i", $art_id);
                if ($unlike_stmt->execute()) {
                    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $art_id); // Refresh the page
                    exit;
                }
            }
        } else {
            // Like the article (add article ID to session and increment like count)
            $_SESSION['liked_articles'][] = $art_id; // Store liked article in the session

            // Increment likes count
            $like_stmt = $conn->prepare("UPDATE newstb SET likes = likes + 1 WHERE id = ?");
            $like_stmt->bind_param("i", $art_id);
            if ($like_stmt->execute()) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $art_id); // Refresh the page
                exit;
            }
        }
    }


} else {
        echo "Invalid article ID.";
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($article['art_title']); ?></title>

        <!-- FONT AWESOME ICONS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">

        <!-- GOOGLE FONTS -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="../includes/css/news-style.css">
    </head>
    <body>
        <div class="title-container">
            <button class="home-button" onclick="window.location.href='main.php';">На главную</button>
            <div class="title">
                <p><?php echo htmlspecialchars($article['art_title']); ?></p>
            </div>
            <button class="navigate-button" onclick="window.location.href='addNews.php';">Создать статью</button>
        </div>
        <div class="image">
            <img src="<?php echo htmlspecialchars($article['img']); ?>" alt="Image">
        </div>
        <div class="news">
            <div class="news-text">
                <!-- Output the article content without escaping HTML -->
                <?php echo $article['article']; ?> <!-- Display the full article text -->
            </div>
            <div class="meta-info">
                <span class="author"><?php echo htmlspecialchars($article['avtor']); ?></span>
                <span class="date"><?php echo date('d.m.Y', strtotime($article['data'])); ?></span>
            </div>
        </div>
        <div class="views-likes">
            <span class="views">Просмотры: <?php echo htmlspecialchars($article['views']); ?></span>
            <span class="likes">
        <span class="likes-count">Лайки: <?php echo htmlspecialchars($article['likes']); ?></span>
                <form action="" method="post" style="display: inline;">
                    <input type="hidden" name="art_id" value="<?php echo $art_id; ?>">
                    <?php if ($user_has_liked): ?>
                        <button type="submit" name="like_action" class="unlike-button">
                            <i class="fa-solid fa-thumbs-down"></i>
                        </button>
                    <?php else: ?>
                        <button type="submit" name="like_action" class="like-button">
                            <i class="fa-solid fa-thumbs-up"></i>
                        </button>
                    <?php endif; ?>
                </form>
            </span>
        </div>
        <button id="toggle-comments">Комментарии</button>
        <div class="comment-section" id="comment-section">
            <form action="" method="post">
                <input type="text" id="comment-input" name="comment" placeholder="Отправить комментарий..." autocomplete="off">
                <input type="hidden" name="art_id" value="<?php echo $art_id; ?>">
                <button id="submit-comment">Отправить</button>
            </form>
            <div class="comments" id="comments-list">
                <?php if ($comments): ?>
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <p><?php echo htmlspecialchars($comment['comments']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Нет комментариев.</p>
                <?php endif; ?>
            </div>
        </div>
        <script src="../includes/js/news.js"></script>
    </body>
</html>

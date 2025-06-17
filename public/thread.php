<?php
require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../src/functions.php';

$pdo = get_db_connection();
$thread_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($thread_id <= 0) {
    header("Location: index.php"); // Redirect if thread_id is invalid
    exit;
}

// Handle new post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['body'])) {
    $body = trim($_POST['body']);
    $author_name = isset($_POST['author_name']) && !empty(trim($_POST['author_name'])) ? trim($_POST['author_name']) : '名無しさん';
    $image_url = isset($_POST['image_url']) && !empty(trim($_POST['image_url'])) ? trim($_POST['image_url']) : null;

    if (!empty($body)) {
        if (create_post($pdo, $thread_id, $author_name, $body, $image_url)) {
            header("Location: thread.php?id=" . $thread_id); // Redirect to refresh and show new post
            exit;
        } else {
            $post_error_message = "Failed to create post.";
        }
    } else {
        $post_error_message = "Post body cannot be empty.";
    }
}

$thread = get_thread_details($pdo, $thread_id);
$posts = get_posts_for_thread($pdo, $thread_id);

if (!$thread) {
    // Optionally, show a "Thread not found" message instead of redirecting
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($thread['title']); ?> - Simple Bulletin Board</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Will create style.css later -->
</head>
<body>
    <header>
        <h1>Thread: <?php echo htmlspecialchars($thread['title']); ?></h1>
        <p><a href="index.php">Back to Board Index</a></p>
    </header>

    <main>
        <section id="posts-list">
            <h2>Posts</h2>
            <?php if (empty($posts)): ?>
                <p>No posts in this thread yet. Be the first to reply!</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <header>
                            <span class="author"><?php echo htmlspecialchars($post['author_name']); ?></span>
                            <span class="timestamp"><?php echo htmlspecialchars($post['created_at']); ?></span>
                        </header>
                        <div class="post-body">
                            <p><?php echo nl2br(htmlspecialchars($post['body'])); ?></p>
                            <?php if (!empty($post['image_url'])): ?>
                                <p>
                                    <a href="<?php echo htmlspecialchars($post['image_url']); ?>" target="_blank">
                                        View Image
                                    </a>
                                    <!-- Or display image directly:
                                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" alt="User uploaded image" style="max-width: 100%; height: auto;">
                                    -->
                                </p>
                            <?php endif; ?>
                        </div>
                    </article>
                    <hr>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <section id="reply-form">
            <h3>Reply to Thread</h3>
            <?php if (isset($post_error_message)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($post_error_message); ?></p>
            <?php endif; ?>
            <form action="thread.php?id=<?php echo $thread_id; ?>" method="POST">
                <div>
                    <label for="author_name">Name (optional):</label><br>
                    <input type="text" id="author_name" name="author_name" placeholder="名無しさん">
                </div>
                <div>
                    <label for="body">Post Body:</label><br>
                    <textarea id="body" name="body" rows="5" required></textarea>
                </div>
                <div>
                    <label for="image_url">Image URL (optional):</label><br>
                    <input type="text" id="image_url" name="image_url">
                </div>
                <button type="submit">Submit Reply</button>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Simple Bulletin Board</p>
    </footer>
</body>
</html>

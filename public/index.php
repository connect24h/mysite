<?php
// Ensure error reporting is on for debugging (useful for development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/database.php';
require_once __DIR__ . '/../src/functions.php';

$pdo = get_db_connection();
$post_count_checked = false; // Flag to ensure we only check count once if we have to reconnect

try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    $post_count = $stmt->fetchColumn();
    $post_count_checked = true;

    if ($post_count === 0) {
        echo "<p>No posts found. Generating sample data... This might take a moment. Please refresh if the page doesn't load automatically.</p>";
        if (function_exists('ob_flush')) { ob_flush(); }
        flush();

        // Disconnect, include large script, then reconnect
        $pdo = null;
        echo "<p>DB disconnected for including sample data script.</p>";
        if (function_exists('ob_flush')) { ob_flush(); }
        flush();

        require_once __DIR__ . '/../generate_sample_data.php';
        echo "<p>Sample data script included. Reconnecting to DB...</p>";
        if (function_exists('ob_flush')) { ob_flush(); }
        flush();

        $pdo = get_db_connection(); // Reconnect
        echo "<p>DB reconnected. Calling populate_database...</p>";
        if (function_exists('ob_flush')) { ob_flush(); }
        flush();

        populate_database(
            $pdo,
            $stories,
            $generic_thread_titles,
            $common_phrases,
            $ascii_arts,
            $joke_authors,
            $total_target_threads,
            $total_target_posts
        );
        echo "<p>Data generation complete. Redirecting...</p>";
        if (function_exists('ob_flush')) { ob_flush(); }
        flush();

        header("Location: index.php");
        exit;
    }
} catch (Exception $e) {
    error_log("Error during sample data generation or check: " . $e->getMessage());
    // If post count check failed, $post_count_checked would be false.
    // Display a more robust error page or message in a real app.
    if (!$post_count_checked) {
        die("Critical error: Could not check database status. Please contact administrator.");
    }
    // If it failed during generation itself, it's logged. The board might appear empty or partially filled.
}

// Handle new thread creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title']) && isset($_POST['body'])) {
    $title = trim($_POST['title']);
    $body = trim($_POST['body']);

    if (!empty($title) && !empty($body)) {
        $thread_id = create_thread($pdo, $title);
        if ($thread_id) {
            create_post($pdo, $thread_id, '名無しさん', $body, null); // Assuming default author and no image for the first post
            header("Location: index.php"); // Redirect to refresh and prevent form resubmission
            exit;
        } else {
            $error_message = "Failed to create thread.";
        }
    } else {
        $error_message = "Thread title and post body cannot be empty.";
    }
}

// Fetch all threads
// Order by the timestamp of the last post in each thread (descending), then by thread creation (descending)
$sql = "
    SELECT
        t.thread_id,
        t.title,
        t.created_at AS thread_created_at,
        MAX(p.created_at) AS last_post_at
    FROM threads t
    LEFT JOIN posts p ON t.thread_id = p.thread_id
    GROUP BY t.thread_id, t.title, t.created_at
    ORDER BY last_post_at DESC, thread_created_at DESC
";
$stmt = $pdo->query($sql);
$threads = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Bulletin Board</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1><a href="index.php">Simple Bulletin Board</a></h1>
    </header>

    <main>
        <section id="new-thread-form">
            <h2>Create New Thread</h2>
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div>
                    <label for="title">Thread Title:</label><br>
                    <input type="text" id="title" name="title" required>
                </div>
                <div>
                    <label for="body">First Post Body:</label><br>
                    <textarea id="body" name="body" rows="5" required></textarea>
                </div>
                <button type="submit">Create Thread</button>
            </form>
        </section>

        <hr>

        <section id="thread-list">
            <h2>Threads</h2>
            <?php if (empty($threads)): ?>
                <p>No threads yet. Be the first to create one!</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($threads as $thread): ?>
                        <li>
                            <a href="thread.php?id=<?php echo htmlspecialchars($thread['thread_id']); ?>">
                                <?php echo htmlspecialchars($thread['thread_id']); ?>. <?php echo htmlspecialchars($thread['title']); ?>
                            </a>
                            (Last post: <?php echo htmlspecialchars($thread['last_post_at'] ?? $thread['thread_created_at']); ?>)
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Simple Bulletin Board</p>
    </footer>
</body>
</html>

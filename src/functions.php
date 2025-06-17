<?php

// Basic input sanitization helper
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function create_thread($pdo, $title) {
    $title = sanitize_input($title);
    if (empty($title)) {
        return false;
    }
    try {
        $stmt = $pdo->prepare("INSERT INTO threads (title) VALUES (:title)");
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->execute();
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        error_log("Error creating thread: " . $e->getMessage());
        return false;
    }
}

function create_post($pdo, $thread_id, $author_name, $body, $image_url = null) {
    $author_name = sanitize_input($author_name);
    $body = sanitize_input($body);
    $image_url = $image_url ? sanitize_input($image_url) : null;

    if (empty($body) || empty($thread_id)) {
        return false;
    }
    if (empty($author_name)) {
        $author_name = '名無しさん'; // Default author name
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO posts (thread_id, author_name, body, image_url) VALUES (:thread_id, :author_name, :body, :image_url)");
        $stmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
        $stmt->bindParam(':author_name', $author_name, PDO::PARAM_STR);
        $stmt->bindParam(':body', $body, PDO::PARAM_STR);
        $stmt->bindParam(':image_url', $image_url, PDO::PARAM_STR);
        return $stmt->execute();
    } catch (PDOException $e) {
        error_log("Error creating post: " . $e->getMessage());
        return false;
    }
}

function get_thread_details($pdo, $thread_id) {
    if (!filter_var($thread_id, FILTER_VALIDATE_INT)) {
        return false;
    }
    try {
        $stmt = $pdo->prepare("SELECT thread_id, title, created_at FROM threads WHERE thread_id = :thread_id");
        $stmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching thread details: " . $e->getMessage());
        return false;
    }
}

function get_posts_for_thread($pdo, $thread_id) {
    if (!filter_var($thread_id, FILTER_VALIDATE_INT)) {
        return [];
    }
    try {
        $stmt = $pdo->prepare("SELECT post_id, thread_id, author_name, body, image_url, created_at FROM posts WHERE thread_id = :thread_id ORDER BY created_at ASC");
        $stmt->bindParam(':thread_id', $thread_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching posts: " . $e->getMessage());
        return [];
    }
}

?>

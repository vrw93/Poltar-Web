<?php
session_start();
require_once __DIR__ . "/../logic/database.php";

$needCSRF = $csrf ?? true;
$needLogin = $login ?? true;
$needAdmin = $admin ?? true;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(404);
    exit();
}

if ($needLogin) {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(404);
        exit();
    }
}

if ($needCSRF) {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

    if (
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        http_response_code(404);
        exit();
    }
}

if ($needLogin) {
    $latestAuthVer = getAuthVersion($database);

    if (
        !isset($_SESSION['auth_version']) ||
        $latestAuthVer !== (int) $_SESSION['auth_version']
    ) {
        header('Location: /logout');
        exit();
    }
}

if ($needAdmin) {
    if (
        !isset($_SESSION['role_id']) ||
        (int) $_SESSION['role_id'] !== 1
    ) {
        http_response_code(403);
        exit();
    }
}

function getAuthVersion(mysqli $database): int
{
    $query = "
        SELECT auth_version
        FROM Users
        WHERE id = ?
        LIMIT 1
    ";

    $userId = (int) $_SESSION['user_id'];
    $statement = $database->prepare($query);

    if ($statement === false) {
        http_response_code(500);
        exit();
    }
    
    $statement->bind_param('i', $userId);
    $statement->execute();
    $result = $statement->get_result();
    $row = $result->fetch_assoc();
    $statement->close();

    if ($row === null) {
        http_response_code(404);
        exit();
    }
    return (int) $row['auth_version'];
}
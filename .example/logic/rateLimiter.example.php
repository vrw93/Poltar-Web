<?php
# ==================================================================
# | If you want to use this code change the file name by removing  |
# | ".example" in it and move it to actual folder outside .example |
# ==================================================================

function getClientIp(): string
{
    #Example If You Using Reverser Proxy<nginx> And Cloudflare Tunnel
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
        return $_SERVER['HTTP_X_REAL_IP'];
    }

    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function isRateLimited(mysqli $db, string $action, int $max, int $windowSeconds): bool
{
    $ip = getClientIp();

    $stmt = $db->prepare("
        SELECT COUNT(*) 
        FROM YourTable<rate limit>
        WHERE YourColumn<ip addr> = ?
        AND YourColumn<ip action> = ?
        AND YourColumn<ip connection> > (NOW() - INTERVAL ? SECOND)
    ");

    $stmt->bind_param("ssi", $ip, $action, $windowSeconds);
    $stmt->execute();

    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count >= $max;
}

function recordAttempt(mysqli $db, string $action): void
{
    $ip = getClientIp();

    $stmt = $db->prepare("
        INSERT INTO YourTable<rate limit> (YourColumn<ip addr>, YourColumn<ip action>)
        VALUES (?, ?)
    ");

    $stmt->bind_param("ss", $ip, $action);
    $stmt->execute();
    $stmt->close();
}
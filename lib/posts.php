<?php
// lib/posts.php
require_once __DIR__ . '/../config/config.php';

function posts_all(): array {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM posts ORDER BY fecha DESC");
    return $stmt->fetchAll();
}

function post_find(int $id): ?array {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    return $p ?: null;
}

function post_create(string $titulo, string $contenido, ?string $imagen): int {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO posts (titulo, contenido, imagen) VALUES (?, ?, ?)");
    $stmt->execute([$titulo, $contenido, $imagen]);
    return (int)$pdo->lastInsertId();
}

function post_update(int $id, string $titulo, string $contenido, ?string $imagen = null): bool {
    global $pdo;
    if ($imagen !== null) {
        $stmt = $pdo->prepare("UPDATE posts SET titulo = ?, contenido = ?, imagen = ? WHERE id = ?");
        return $stmt->execute([$titulo, $contenido, $imagen, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET titulo = ?, contenido = ? WHERE id = ?");
        return $stmt->execute([$titulo, $contenido, $id]);
    }
}

function post_delete(int $id): bool {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    return $stmt->execute([$id]);
}

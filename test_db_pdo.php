<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=appointmen_db_4b;port=3306', 'hoshikokuro', 'KuroHoshiko12!');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query('SHOW TABLES');
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo implode("\n", $tables) . "\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}

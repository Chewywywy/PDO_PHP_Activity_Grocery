<?php
require 'dbconfig.php';
require 'layout.php';

page_start('Grocery Items Table', 'Result set rendered as an HTML table');

try {
    $stmt  = $pdo->query("SELECT * FROM `$table` ORDER BY category, item_name");
    $items = $stmt->fetchAll();
    echo '<div class="card">';
    items_table($items);          
    echo '</div>';
} catch (PDOException $e) {
    alert('Query error: ' . $e->getMessage(), 'err');
}

page_end();

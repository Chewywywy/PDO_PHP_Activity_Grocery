<?php
require 'dbconfig.php';
require 'layout.php';

page_start('fetchAll()', 'Every row returned at once');

try {
    $stmt = $pdo->prepare("SELECT * FROM `$table`");   
    $stmt->execute();                                  
    $items = $stmt->fetchAll();                        

    echo '<pre>';
    print_r($items);                                   
    echo '</pre>';
} catch (PDOException $e) {
    alert('Query error: ' . $e->getMessage(), 'err');
}

page_end();
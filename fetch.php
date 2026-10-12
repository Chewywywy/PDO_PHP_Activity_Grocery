<?php
require 'dbconfig.php';
require 'layout.php';

page_start('fetch()', 'One row returned by item ID');

$id = $_GET['id'] ?? 1;   
?>
<div class="card">
    <form method="get" class="inline">
        <div><label>Item ID</label><input type="number" name="id" min="1" value="<?= (int)$id ?>"></div>
        <button class="btn">Fetch Item</button>
    </form>
</div>
<?php
try {
    $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE item_id = :id");
    $stmt->execute(['id' => $id]);
    $item = $stmt->fetch();                            

    if ($item) {
        echo '<pre>';
        print_r($item);
        echo '</pre>';
    } else {
        alert("No item found with ID $id.", 'err');
    }
} catch (PDOException $e) {
    alert('Query error: ' . $e->getMessage(), 'err');
}

page_end();

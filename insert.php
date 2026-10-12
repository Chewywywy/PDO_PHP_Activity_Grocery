<?php
require 'dbconfig.php';
require 'layout.php';

page_start('Insert Record', 'Add a new grocery item');

$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare(
            "INSERT INTO `$table`
                (item_name, brand_name, category, unit_size, price, stock_quantity, expiry_date)
             VALUES (:item_name, :brand_name, :category, :unit_size, :price, :stock_quantity, :expiry_date)"
        );
        $stmt->execute(item_from_post());              
        $msg = 'Record inserted. New item ID: ' . $pdo->lastInsertId();   
    } catch (PDOException $e) {
        $err = 'Insert failed: ' . $e->getMessage();
    }
}

alert($msg);
alert($err, 'err');
?>
<div class="card">
    <form method="post">
        <?php item_fields(); ?>
        <button class="btn">Insert Item</button>
    </form>
</div>
<?php page_end();

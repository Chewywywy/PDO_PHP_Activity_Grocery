<?php
// index.php - home page with summary numbers and links to every feature
require 'dbconfig.php';
require 'layout.php';

page_start('Thai Grocery Items', 'PDO Methods Activity • Prices in Thai Baht (THB)');

try {
    // fetch() with an aggregate query returns ONE summary row
    $s = $pdo->query(
        "SELECT COUNT(*) AS total, COUNT(DISTINCT brand_name) AS brands,
                COUNT(DISTINCT category) AS cats, COALESCE(SUM(price * stock_quantity),0) AS value
         FROM `$table`"
    )->fetch();
    ?>
    <div class="stats">
        <div class="stat"><span>Total Items</span><strong><?= (int)$s['total'] ?></strong></div>
        <div class="stat"><span>Brands</span><strong><?= (int)$s['brands'] ?></strong></div>
        <div class="stat"><span>Categories</span><strong><?= (int)$s['cats'] ?></strong></div>
        <div class="stat"><span>Stock Value</span><strong>&#3647;<?= number_format($s['value']) ?></strong></div>
    </div>
    <?php
} catch (PDOException $e) {
    alert('Database error: ' . $e->getMessage(), 'err');
}
?>
<div class="tiles">
    <a class="tile" href="crud.php"><h3>Manage Items</h3><p>Add, edit and delete records in one page.</p></a>
    <a class="tile" href="table.php"><h3>HTML Table</h3><p>Query result rendered as a searchable table.</p></a>
    <a class="tile" href="fetch_all.php"><h3>fetchAll()</h3><p>All rows with print_r() inside pre tags.</p></a>
    <a class="tile" href="fetch.php"><h3>fetch()</h3><p>One row by item ID with print_r().</p></a>
    <a class="tile" href="insert.php"><h3>Insert</h3><p>Add a new record to the database.</p></a>
    <a class="tile" href="update.php"><h3>Update</h3><p>Change an existing record.</p></a>
    <a class="tile" href="delete.php"><h3>Delete</h3><p>Remove a record by item ID.</p></a>
</div>
<?php page_end();

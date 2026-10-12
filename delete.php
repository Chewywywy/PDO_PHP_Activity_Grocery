<?php
require 'dbconfig.php';
require 'layout.php';

page_start('Delete Record', 'Remove an item by ID');

$msg = $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM `$table` WHERE item_id = :id");
        $stmt->execute(['id' => $_POST['item_id']]);

        // rowCount() = how many rows were deleted
        if ($stmt->rowCount() > 0) $msg = 'Record #' . (int)$_POST['item_id'] . ' deleted.';
        else                       $err = 'No record found with that ID.';
    } catch (PDOException $e) {
        $err = 'Delete failed: ' . $e->getMessage();
    }
}

alert($msg);
alert($err, 'err');
?>
<div class="card">
    <form method="post" class="inline" onsubmit="return confirm('Delete this item permanently?');">
        <div><label>Item ID to delete</label><input type="number" name="item_id" min="1" required></div>
        <button class="btn btn-delete">Delete Item</button>
    </form>
</div>
<?php page_end();

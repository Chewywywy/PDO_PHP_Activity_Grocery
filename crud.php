<?php
// crud.php - one page to INSERT, UPDATE, DELETE and VIEW records
require 'dbconfig.php';
require 'layout.php';

$msg = $err = '';

// ---------- Handle form submissions ----------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $_POST['action'] ?? '';

        if ($action === 'insert') {                              // INSERT
            $stmt = $pdo->prepare(
                "INSERT INTO `$table` (item_name, brand_name, category, unit_size, price, stock_quantity, expiry_date)
                 VALUES (:item_name, :brand_name, :category, :unit_size, :price, :stock_quantity, :expiry_date)"
            );
            $stmt->execute(item_from_post());
            $msg = 'Item added.';

        } elseif ($action === 'update') {                        // UPDATE
            $data = item_from_post();
            $data['item_id'] = $_POST['item_id'];
            $stmt = $pdo->prepare(
                "UPDATE `$table` SET item_name = :item_name, brand_name = :brand_name, category = :category,
                        unit_size = :unit_size, price = :price, stock_quantity = :stock_quantity,
                        expiry_date = :expiry_date
                 WHERE item_id = :item_id"
            );
            $stmt->execute($data);
            $msg = 'Item updated.';

        } elseif ($action === 'delete') {                        // DELETE
            $stmt = $pdo->prepare("DELETE FROM `$table` WHERE item_id = :id");
            $stmt->execute(['id' => $_POST['item_id']]);
            $msg = 'Item deleted.';
        }
    } catch (PDOException $e) {
        $err = 'Database error: ' . $e->getMessage();
    }
}

// ---------- Load data ----------
$edit  = false;
$items = [];
try {
    if (isset($_GET['edit'])) {                                  // fetch() one row for the edit form
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE item_id = :id");
        $stmt->execute(['id' => $_GET['edit']]);
        $edit = $stmt->fetch();
    }
    $items = $pdo->query("SELECT * FROM `$table` ORDER BY item_id DESC")->fetchAll();   // fetchAll() for the table
} catch (PDOException $e) {
    $err = $err ?: 'Database error: ' . $e->getMessage();
}

page_start('Manage Grocery Items', 'Add, edit and delete records');
alert($msg);
alert($err, 'err');
?>
<div class="card">
    <h2 class="section-title"><?= $edit ? 'Edit Item #' . (int)$edit['item_id'] : 'Add New Item' ?></h2>
    <form method="post" action="crud.php">
        <input type="hidden" name="action" value="<?= $edit ? 'update' : 'insert' ?>">
        <?php if ($edit): ?><input type="hidden" name="item_id" value="<?= (int)$edit['item_id'] ?>"><?php endif; ?>
        <?php item_fields($edit ?: null); ?>
        <button class="btn"><?= $edit ? 'Save Changes' : 'Add Item' ?></button>
        <?php if ($edit): ?><a href="crud.php" class="btn btn-cancel">Cancel</a><?php endif; ?>
    </form>
</div>

<div class="card">
    <?php items_table($items, true); ?>
</div>
<?php page_end();

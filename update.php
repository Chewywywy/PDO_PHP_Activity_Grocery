<?php
require 'dbconfig.php';
require 'layout.php';

page_start('Update Record', 'Load an item by ID, then save changes');

$msg = $err = '';
$id  = $_GET['id'] ?? ($_POST['item_id'] ?? '');

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = item_from_post();
        $data['item_id'] = $_POST['item_id'];
        $stmt = $pdo->prepare(
            "UPDATE `$table`
             SET item_name = :item_name, brand_name = :brand_name, category = :category,
                 unit_size = :unit_size, price = :price,
                 stock_quantity = :stock_quantity, expiry_date = :expiry_date
             WHERE item_id = :item_id"
        );
        $stmt->execute($data);
        $msg = 'Record updated.';                      
    }

    $item = false;
    if ($id !== '') {
        $stmt = $pdo->prepare("SELECT * FROM `$table` WHERE item_id = :id");
        $stmt->execute(['id' => $id]);
        $item = $stmt->fetch();
        if (!$item) $err = "No item found with ID $id.";
    }
} catch (PDOException $e) {
    $err = 'Update failed: ' . $e->getMessage();
    $item = false;
}

alert($msg);
alert($err, 'err');
?>
<div class="card">
    <form method="get" class="inline">
        <div><label>Item ID to edit</label><input type="number" name="id" min="1" value="<?= htmlspecialchars($id) ?>"></div>
        <button class="btn">Load Item</button>
    </form>
</div>

<?php if ($item): ?>
<div class="card">
    <h2 class="section-title">Editing Item #<?= (int)$item['item_id'] ?></h2>
    <form method="post">
        <input type="hidden" name="item_id" value="<?= (int)$item['item_id'] ?>">
        <?php item_fields($item); ?>
        <button class="btn">Save Changes</button>
    </form>
</div>
<?php endif; ?>
<?php page_end();

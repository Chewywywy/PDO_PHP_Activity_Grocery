<?php
// layout.php - shared header, menu and footer so every page has the same design

function page_start($title, $subtitle = '') {
    $current = basename($_SERVER['PHP_SELF']);
    $links = [
        'index.php'     => 'Home',
        'crud.php'      => 'Manage Items',
        'table.php'     => 'HTML Table',
        'fetch_all.php' => 'fetchAll()',
        'fetch.php'     => 'fetch()',
        'insert.php'    => 'Insert',
        'update.php'    => 'Update',
        'delete.php'    => 'Delete',
    ];
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
    echo '<title>' . htmlspecialchars($title) . '</title>';
    echo '<link rel="stylesheet" href="style.css"></head><body>';
    echo '<div class="header"><h1>' . htmlspecialchars($title) . '</h1>';
    echo '<p>' . htmlspecialchars($subtitle) . '</p></div>';
    echo '<nav class="nav">';
    foreach ($links as $file => $label) {
        $active = ($file === $current) ? ' class="active"' : '';
        echo '<a href="' . $file . '"' . $active . '>' . $label . '</a>';
    }
    echo '</nav><div class="container">';
}

function page_end() {
    echo '</div></body></html>';
}

// Shows a green or red message box
function alert($text, $type = 'ok') {
    if ($text) {
        echo '<div class="alert alert-' . $type . '">' . htmlspecialchars($text) . '</div>';
    }
}

// Reads the posted form fields into one array (used by insert and update)
function item_from_post() {
    return [
        'item_name'      => trim($_POST['item_name']),
        'brand_name'     => trim($_POST['brand_name']),
        'category'       => trim($_POST['category']),
        'unit_size'      => trim($_POST['unit_size']),
        'price'          => $_POST['price'],
        'stock_quantity' => $_POST['stock_quantity'],
        'expiry_date'    => $_POST['expiry_date'],
    ];
}

// Prints the item form fields. $i = existing record (edit) or null (add)
function item_fields($i = null) {
    $v = function ($k) use ($i) { return $i ? htmlspecialchars($i[$k]) : ''; };
    ?>
    <div class="form-grid">
        <div><label>Item Name</label><input type="text" name="item_name" required value="<?= $v('item_name') ?>"></div>
        <div><label>Brand</label><input type="text" name="brand_name" required value="<?= $v('brand_name') ?>"></div>
        <div><label>Category</label><input type="text" name="category" required value="<?= $v('category') ?>"></div>
        <div><label>Unit Size</label><input type="text" name="unit_size" required placeholder="e.g. 500 g" value="<?= $v('unit_size') ?>"></div>
        <div><label>Price (THB)</label><input type="number" step="0.01" min="0" name="price" required value="<?= $v('price') ?>"></div>
        <div><label>Stock Quantity</label><input type="number" min="0" name="stock_quantity" required value="<?= $v('stock_quantity') ?>"></div>
        <div><label>Expiry Date</label><input type="date" name="expiry_date" required value="<?= $v('expiry_date') ?>"></div>
    </div>
    <?php
}

// Prints a styled, searchable table of rows. $actions = true adds Edit/Delete buttons
function items_table($items, $actions = false) {
    ?>
    <input type="text" class="search" placeholder="Search item, brand or category..."
           oninput="filterRows(this.value)">
    <div class="table-wrap">
    <table id="items-table">
        <thead><tr>
            <th>ID</th><th>Item</th><th>Brand</th><th>Category</th><th>Size</th>
            <th class="num">Price (THB)</th><th class="num">Stock</th><th>Expiry</th>
            <?php if ($actions): ?><th>Actions</th><?php endif; ?>
        </tr></thead>
        <tbody>
        <?php foreach ($items as $r): ?>
            <tr>
                <td><?= (int)$r['item_id'] ?></td>
                <td><?= htmlspecialchars($r['item_name']) ?></td>
                <td class="brand"><?= htmlspecialchars($r['brand_name']) ?></td>
                <td><span class="badge"><?= htmlspecialchars($r['category']) ?></span></td>
                <td><?= htmlspecialchars($r['unit_size']) ?></td>
                <td class="num"><?= number_format($r['price'], 2) ?></td>
                <td class="num">
                    <?php if ($r['stock_quantity'] < 20): ?>
                        <span class="low"><?= (int)$r['stock_quantity'] ?></span>
                    <?php else: ?><?= (int)$r['stock_quantity'] ?><?php endif; ?>
                </td>
                <td><?= htmlspecialchars(date('M d, Y', strtotime($r['expiry_date']))) ?></td>
                <?php if ($actions): ?>
                <td class="actions">
                    <a class="btn btn-edit" href="crud.php?edit=<?= (int)$r['item_id'] ?>">Edit</a>
                    <form method="post" action="crud.php" onsubmit="return confirm('Delete this item?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="item_id" value="<?= (int)$r['item_id'] ?>">
                        <button class="btn btn-delete">Delete</button>
                    </form>
                </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <script>
    // Hides rows that do not match the search text
    function filterRows(term) {
        term = term.toLowerCase();
        document.querySelectorAll('#items-table tbody tr').forEach(function (tr) {
            tr.style.display = tr.textContent.toLowerCase().includes(term) ? '' : 'none';
        });
    }
    </script>
    <?php
}

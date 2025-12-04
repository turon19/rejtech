<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$filter = $_POST["productFilter"] ?? "sales";
$status = "ACTIVE";

$pc = $pdo->prepare("SELECT * FROM products
WHERE status = :status
ORDER BY $filter DESC");
$pc->execute(["status" => $status]);
$productCategories = $pc->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Products | REJ | TECH</title>
    <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <?php require_once("includes/header.php") ?>

    <div class="flex flex-col gap-[12px] p-[12px]">

        <form method="post" class="user-filterForm flex justify-end">
            <select name="productFilter" class="user-selectFilter bg-transparent border-[3px] border-[rgb(10,10,10)] h-[48px] px-[12px] outline-none focus:border-[rgb(100,0,0)] rounded-[10px]">
                <option value="sales" <?= ($filter === "sales") ? "selected" : "" ?> class="bg-black">Best Sellers</option>
                <option value="release_date" <?= ($filter === "release_date") ? "selected" : "" ?> class="bg-black">New Arrivals</option>
                <option value="price" <?= ($filter === "price") ? "selected" : "" ?> class="bg-black">Price: High to Low</option>
            </select>
        </form>


        <div class="grid lg:grid-cols-5 sm:grid-cols-2 gap-[12px]">
            <?php foreach ($productCategories as $productCategory): ?>
                <a href="product-details.php?productId=<?= $productCategory['id'] ?>" class="flex flex-col gap-[12px]">
                    <div class="flex aspect-square">
                        <img src="../admin/assets/images/uploads/<?= $productCategory['image1'] ?>" alt="<?= $productCategory['name'] ?>" class="object-cover">
                    </div>
                    <span class="flex h-[48px] overflow-hidden"><?= $productCategory['name'] ?></span>
                    <span class="flex items-center h-[48px]">₱<?= number_format($productCategory['price'], 2) ?> </span>
                    <button class="user-button">View Product</button>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="assets/src/selectFilter.js"></script>
</body>

</html>
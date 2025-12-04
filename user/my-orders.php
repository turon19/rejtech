<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$orderStatus = $_POST["orderStatus"] ?? "ALL";

if ($orderStatus === "ALL") {
    $ord = $pdo->prepare("SELECT *, date(order_created) as order_date FROM orders
WHERE user_id = :userId
ORDER BY order_created DESC");
    $ord->execute([":userId" => $_SESSION["id"]]);
} else {
    $ord = $pdo->prepare("SELECT *, date(order_created) as order_date FROM orders
WHERE user_id = :userId and status = :status
ORDER BY order_created DESC");
    $ord->execute([":userId" => $_SESSION["id"], ":status" => $orderStatus]);
}
$orders = $ord->fetchAll();

if (isset($_POST["cancel"])) {
    $orderId =  $_POST["orderId"];
    $cancel = "Cancelled";

    $ord = $pdo->prepare("UPDATE orders
   SET status = :cancel
   WHERE user_id = :userId and id = :orderId");
    $ord->execute([":cancel" => $cancel, ":userId" => $_SESSION["id"], ":orderId" => $orderId]);

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders • Order History</title>
    <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <?php require_once("includes/header.php") ?>

    <?php if (count($orders) === 0) : ?>
        <div class="flex flex-col w-full min-h-dvh items-center justify-center gap-[24px]">
            <div class="flex flex-col items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[144px] h-[144px]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="flex text-[32px]">No orders yet</span>
                <span class="flex">Once you place an order, it will show up here.</span>
            </div>
            <a href="products.php" class="flex h-[48px] bg-[rgb(100,0,0)] items-center justify-center w-[180px] rounded-[10px]">Continue Shopping</a>
        </div>
    <?php endif; ?>


    <?php if (count($orders) > 0): ?>
        <div class="flex flex-col gap-[12px] p-[12px] justify-center items-center">
            <span class="flex text-[32px] font-bold">ORDER HISTORY</span>

            <form method="post" class="productStatusForm">
                <select name="orderStatus" class="productStatus border-[3px] border-[rgb(10,10,10)] h-[48px] bg-transparent px-[12px] rounded-[10px] focus:border-[rgb(100,0,0)] outline-none">
                    <option value="ALL" <?= ($orderStatus === "ALL") ? "selected" : "" ?> class="bg-black">ALL STATUS</option>
                    <option value="PENDING" <?= ($orderStatus === "PENDING") ? "selected" : "" ?> class="bg-black">PENDING</option>
                    <option value="COMPLETED" <?= ($orderStatus === "COMPLETED") ? "selected" : "" ?> class="bg-black">COMPLETED</option>
                    <option value="CANCELLED" <?= ($orderStatus === "CANCELLED") ? "CANCELLED" : "" ?> class="bg-black">CANCELLED</option>
                </select>
            </form>


            <div class="flex flex-col gap-[12px] w-full items-center">
                <?php foreach ($orders as $order): ?>
                    <?php $oi =  $pdo->prepare("SELECT orders_items.order_id as order_items_id , orders_items.product_id as order_productId, orders_items.quantity as order_quantity, orders_items.total_price as order_totalPrice, products.id as product_id, products.image1 as product_img1, products.name as product_name, products.price as product_price from orders_items
                    INNER JOIN products ON orders_items.product_id = products.id
                    WHERE orders_items.order_id = :orderId");
                    $oi->execute([":orderId" => $order["id"]]);
                    $orderItems = $oi->fetchAll();

                    $statusColor = match ($order["status"]) {
                        "PENDING" => "rgb(10,10,10)",
                        "COMPLETED" => "rgb(0,100,0)",
                        "CANCELLED" => "rgb(100,0,0)"
                    };

                    $statusSvg = match ($order["status"]) {
                        "PENDING" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
</svg>',
                        "COMPLETED" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
</svg>',
                        "CANCELLED" => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
</svg>'
                    }
                    ?>
                    <div class="flex flex-col gap-[12px] max-w-[1000px] w-full">

                        <div class="flex justify-between h-[48px] px-[12px] bg-[<?= $statusColor ?>] rounded-[10px]">
                            <div class="flex gap-[12px] items-center">
                                <span class="flex items-center"><?= $statusSvg ?></span>
                                <span class="font-bold"><?= $order["status"] ?></span>
                            </div>
                            <span class="flex items-center"><?= $order["order_date"] ?></span>
                        </div>

                        <div class="flex flex-col px-[12px] pb-[12px] gap-[12px]">
                            <?php foreach ($orderItems as $item): ?>
                                <div class="flex gap-[12px] justify-between">

                                    <div class="flex gap-[12px] items-center">
                                        <div class="aspect-square">
                                            <img src="../admin/assets/images/uploads/<?= $item["product_img1"] ?>" alt="<?= $item["product_name"] ?>" class="w-[72px] h-[72px] object-cover">
                                        </div>
                                        <span class="flex flex-1 overflow-hidden max-h-[48px]"><?= $item["product_name"] ?></span>
                                    </div>

                                    <div class="flex flex-col items-center justify-center">
                                        <span class="user-myOrders justify-end">x<?= $item["order_quantity"] ?></span>
                                        <span class="user-myOrders">₱<?= number_format($item["order_totalPrice"], 2) ?></span>
                                    </div>

                                </div>
                            <?php endforeach; ?>

                            <form method="post" class="flex flex-col items-end gap-[12px]">
                                <span class="flex items-center h-[48px]">Total: ₱<?= number_format($order["total_price"], 2) ?></span>
                                <input type="hidden" name="orderId" value="<?= $order["id"] ?>">
                                <?php if ($order["status"] === "PENDING"): ?>
                                    <button type="submit" name="cancel" class="flex bg-[rgb(100,0,0)] h-[48px] px-[12px] rounded-[10px] items-center justify-center gap-[6px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        <span>Cancel Order</span>
                                    </button>
                                <?php endif; ?>
                            </form>
                        </div>

                    </div>

                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <script>
        const selectStatus = document.querySelector(".productStatus");
        const selectStatusForm = document.querySelector(".productStatusForm");
        selectStatus.addEventListener("change", () => {
            selectStatusForm.submit();
        })
    </script>
</body>

</html>
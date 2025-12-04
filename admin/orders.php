<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$od = $pdo->prepare("SELECT COUNT(*) FROM orders");
$od->execute();
$totalOrder = $od->fetchColumn();

$orderStatus = ["PENDING", "COMPLETED", "CANCELLED"];

foreach ($orderStatus as $status) {
    $od = $pdo->prepare("SELECT COUNT(*) FROM orders
WHERE status = :status");
    $od->execute([":status" => $status]);
    $order[$status] = $od->fetchColumn();
}

$status = $_POST["status"] ?? "ALL";

if ($status === "ALL") {
    $od = $pdo->prepare("SELECT orders.id as order_id, orders.status as order_status, orders.total_price as order_totalPrice , date(orders.order_created) as order_created, users.first_name as user_firstName, users.phone as user_phone , users.address as user_address FROM orders
INNER JOIN users ON orders.user_id = users.id
ORDER BY order_created DESC");
    $od->execute();
} else {
    $od = $pdo->prepare("SELECT orders.id as order_id, orders.status as order_status, orders.total_price as order_totalPrice , orders.order_created as order_created, users.first_name as user_firstName, users.phone as user_phone , users.address as user_address FROM orders
INNER JOIN users ON orders.user_id = users.id
WHERE status = :status
ORDER BY order_created DESC");
    $od->execute([":status" => $status]);
}
$ordersInfo = $od->fetchAll();

if (isset($_POST["cancel"])) {

    $orderId = $_POST["orderId"];
    $status = $_POST["cancel"];

    $od = $pdo->prepare("UPDATE orders
    SET status = :status
    WHERE id = :id");
    $od->execute([":status" => $status, "id" => $orderId]);

    $od = $pdo->prepare("SELECT product_id, quantity from orders_items
    WHERE order_id = :id");
    $od->execute(["id" => $orderId]);
    $ordersItems = $od->fetchAll();

    if ($status === "COMPLETED") {

        foreach ($ordersItems as $items) {
            $od = $pdo->prepare("UPDATE products
            SET stock = stock - :stock
            WHERE id = :id");
            $od->execute([":stock" => $items["quantity"], ":id" => $items["product_id"]]);
        }
    }

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders | REJ | TECH</title>
    <link rel="icon" href="../user/assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <div class="admin-orderDetails fixed hidden w-full min-h-dvh bg-[rgba(0,0,0,0.90)] z-[100] items-center justify-center px-[12px] pb-[12px] rounded-[10px]">
        <div class="flex flex-col w-[432px] bg-black rounded-[10px] p-[12px] gap-[12px]">

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="admin-orderNumber flex items-center"></span>
                <button class="admin-orderDetails-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <span class="flex h-[48px] items-center">Items:</span>

            <div class="admin-itemsContainer flex flex-col gap-[12px]"></div>

            <div class="flex h-[48px] border-t-[3px] border-[rgb(10,10,10)] justify-between gap-[12px]">
                <span class="flex items-center">Order Date:</span>
                <span class="admin-orderDate flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="flex items-center">Order Status:</span>
                <span class="admin-orderStatus flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center border-t-[3px] border-[rgb(10,10,10)] justify-between gap-[12px]">
                <span class="flex items-center">Customer Name:</span>
                <span class="admin-orderCustomer flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="flex items-center">Contact Details:</span>
                <span class="admin-orderContact flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="flex items-center">Address:</span>
                <span class="admin-orderAddress flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center border-t-[3px] border-[rgb(10,10,10)] justify-between gap-[12px]">
                <span class="flex items-center">Subtotal:</span>
                <span class="admin-orderTotalPrice flex items-center"></span>
            </div>

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="flex items-center">Shipping fee:</span>
                <span class="flex items-center">FREE</span>
            </div>

            <div class="flex h-[48px] items-center justify-between gap-[12px]">
                <span class="flex items-center">Total</span>
                <span class="admin-orderTotalPrice flex items-center"></span>
            </div>

        </div>
    </div>

    <?php require_once("includes/header.php") ?>

    <div class="flex px-[12px] pb-[12px] gap-[12px]">
        <?php require_once("includes/sidebar.php") ?>

        <div class="flex flex-1 flex-col gap-[12px]">
            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1  gap-[12px]">

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Total Orders</span>
                        <span class="flex items-center"><?= $totalOrder ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 0 0 4.25 22.5h15.5a1.875 1.875 0 0 0 1.865-2.071l-1.263-12a1.875 1.875 0 0 0-1.865-1.679H16.5V6a4.5 4.5 0 1 0-9 0ZM12 3a3 3 0 0 0-3 3v.75h6V6a3 3 0 0 0-3-3Zm-3 8.25a3 3 0 1 0 6 0v-.75a.75.75 0 0 1 1.5 0v.75a4.5 4.5 0 1 1-9 0v-.75a.75.75 0 0 1 1.5 0v.75Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Pending Orders</span>
                        <span class="flex items-center"><?= $order["PENDING"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Completed Orders</span>
                        <span class="flex items-center"><?= $order["COMPLETED"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Cancelled Orders</span>
                        <span class="flex items-center"><?= $order["CANCELLED"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>

            <form method="post" class="admin-selectStatus-form flex">
                <select name="status" class="admin-select-status bg-transparent border-[3px] border-[rgb(10,10,10)] px-[12px] h-[48px] rounded-[10px]">
                    <option value="ALL" <?= ($status === "ALL") ? "selected" : "" ?> class="bg-black">All Status</option>
                    <option value="PENDING" <?= ($status === "PENDING") ? "selected" : "" ?> class="bg-black">Pending</option>
                    <option value="COMPLETED" <?= ($status === "COMPLETED") ? "selected" : "" ?> class="bg-black">Completed</option>
                    <option value="CANCELLED" <?= ($status === "CANCELLED") ? "selected" : "" ?> class="bg-black">Cancelled</option>
                </select>
            </form>

            <div class="flex flex-col gap-[12px]">
                <div class="grid lg:grid-cols-[1fr_1fr_1fr_1fr_1fr] sm:grid-cols-[1fr_1fr_1fr_1fr] grid-cols-[1fr_1fr_1fr]  h-[48px] border-b-[3px] border-[rgb(10,10,10)] gap-[12px] items-center">
                    <span>Order Date</span>
                    <span class="lg:flex hidden">Recipient</span>
                    <span class="sm:flex hidden">Total Price</span>
                    <span>Status</span>
                    <span>Action</span>
                </div>

                <div class="flex flex-col gap-[12px]">
                    <?php foreach ($ordersInfo as $info): ?>

                        <?php $matchStatus = match ($info["order_status"]) {
                            "PENDING" => "rgb(251,196,36)",
                            "COMPLETED" => "rgb(0,128,0)",
                            "CANCELLED" => "rgb(255,0,0)"
                        } ?>

                        <div class="grid lg:grid-cols-[1fr_1fr_1fr_1fr_1fr] sm:grid-cols-[1fr_1fr_1fr_1fr] grid-cols-[1fr_1fr_1fr] gap-[12px] items-center h-[48px]">
                            <span class="flex items-center"><?= $info["order_created"] ?></span>
                            <span class="lg:flex hidden items-center"><?= $info["user_firstName"] ?></span>
                            <span class="sm:flex hidden items-center">₱<?= number_format($info["order_totalPrice"], 2) ?></span>
                            <div class="flex items-center">
                                <span class="flex border-[3px] w-[108px] justify-center h-[40px] items-center rounded-[10px] text-[<?= $matchStatus ?>] border-[<?= $matchStatus ?>]"><?= $info["order_status"] ?></span>
                            </div>
                            <div class="relative flex items-center gap-[12px]">
                                <button type="button" class="admin-orderDetails-btn" data-id="<?= $info["order_id"] ?>" data-created="<?= $info["order_created"] ?>" data-status="<?= $info["order_status"] ?>" data-customer="<?= $info["user_firstName"] ?>" data-contact="<?= $info["user_phone"] ?>" data-address="<?= $info["user_address"] ?>" data-total-price="<?= $info["order_totalPrice"] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </button>
                                <?php if ($info["order_status"] === "PENDING"): ?>
                                    <button class="admin-status-btn">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                        </svg>
                                    </button>
                                    <form method="post" class="admin-status-list hidden flex-col absolute top-[72px] w-full bg-black z-50 justify-center gap-[12px]">
                                        <button type="submit" name="cancel" class="flex h-[48px] items-center gap-[12px]" value="CANCELLED">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-[rgb(255,0,0)]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>Cancel</span>
                                        </button>
                                        <input type="hidden" name="orderId" value="<?= $info["order_id"] ?>">
                                        <button type="submit" name="complete" class="flex h-[48px] items-center gap-[12px]" value="COMPLETED">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-[rgb(0,128,0)]">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <span>Complete</span>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
    <script src="assets/src/admin.js"></script>
    <script src="assets/src/orders.js"></script>
</body>

</html>
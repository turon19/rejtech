<?php
require_once("includes/config.php");
require_once("includes/auth.php");


$tb = $pdo->prepare("SELECT COUNT(*) as products, SUM(sales) as sales FROM products");
$tb->execute();
$total = $tb->fetch();

$tb = $pdo->prepare("SELECT COUNT(*) FROM orders");
$tb->execute();
$orders = $tb->fetchColumn();

$status = "PENDING";

$tb = $pdo->prepare("SELECT COUNT(*) FROM orders
WHERE status = :status");
$tb->execute([":status" => $status]);
$pendingOrders = $tb->fetchColumn();


$tb = $pdo->prepare("SELECT orders.id as order_id, orders.status as order_status, orders.total_price as order_totalPrice, users.first_name as user_firstName FROM orders
INNER JOIN users ON orders.user_id = users.id
LIMIT 6");
$tb->execute();
$orderItems = $tb->fetchAll();

$tb = $pdo->prepare("SELECT * FROM products
ORDER BY sales DESC
LIMIT 5");
$tb->execute();
$salesProducts = $tb->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REJ | TECH Computer Company</title>
    <link rel="icon" href="../user/assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <?php require_once("includes/header.php") ?>



    <div class="flex px-[12px] pb-[12px] gap-[12px]">
        <?php require_once("includes/sidebar.php") ?>

        <div class="flex flex-1 flex-col gap-[12px]">

            <div class="grid md:grid-cols-4 sm:grid-cols-2 grid-cols-1  gap-[12px]">

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Total Products</span>
                        <span class="flex items-center"><?= $total["products"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path d="M12.378 1.602a.75.75 0 0 0-.756 0L3 6.632l9 5.25 9-5.25-8.622-5.03ZM21.75 7.93l-9 5.25v9l8.628-5.032a.75.75 0 0 0 .372-.648V7.93ZM11.25 22.18v-9l-9-5.25v8.57a.75.75 0 0 0 .372.648l8.628 5.033Z" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Total Orders</span>
                        <span class="flex items-center"><?= $orders ?></span>
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
                        <span class="flex items-center"><?= $pendingOrders ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Total Sales</span>
                        <span class="flex items-center"><?= $total["sales"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

            </div>

            <div class="grid xl:grid-cols-[2fr_1fr] grid-cols-1 gap-[12px] items-start">

                <div class="flex flex-col border-[3px] border-[rgb(10,10,10)] rounded-[10px] px-[12px] pb-[12px]">
                    <div class="flex h-[48px] items-center gap-[12px]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" />
                            <path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 14.625v-9.75ZM8.25 9.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM18.75 9a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75V9.75a.75.75 0 0 0-.75-.75h-.008ZM4.5 9.75A.75.75 0 0 1 5.25 9h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H5.25a.75.75 0 0 1-.75-.75V9.75Z" clip-rule="evenodd" />
                            <path d="M2.25 18a.75.75 0 0 0 0 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 0 0-.75-.75H2.25Z" />
                        </svg>
                        <span>Latest Transaction</span>
                    </div>

                    <div class="grid sm:grid-cols-4 grid-cols-1 h-[48px] items-center gap-[12px]">
                        <span class="flex items-center">Order No.</span>
                        <span class="sm:flex hidden items-center">Recipient</span>
                        <span class="sm:flex hidden items-center">Total Price</span>
                        <span class="sm:flex hidden items-center">Status</span>
                    </div>

                    <div class="flex flex-col gap-[12px]">
                        <?php foreach ($orderItems as $item) : ?>
                            <?php $matchStatus = match ($item["order_status"]) {
                                "PENDING" => "rgb(251,196,36)",
                                "COMPLETED" => "rgb(0,128,0)",
                                "CANCELLED" => "rgb(255,0,0)"
                            } ?>
                            <div class="grid sm:grid-cols-4 grid-cols-3 gap-[12px] h-[48px] items-center">
                                <div class="flex flex-col">
                                    <span>#<?= $item["order_id"] ?></span>
                                    <span class="sm:hidden max-h-[24px] overflow-hidden"><?= $item["user_firstName"] ?></span>
                                </div>
                                <span class="sm:flex hidden"><?= $item["user_firstName"] ?></span>
                                <span class="flex items-center">₱<?= number_format($item["order_totalPrice"], 2) ?></span>
                                <div class="flex">
                                    <span class="flex border-[3px] h-[40px] w-[108px] items-center justify-center rounded-[10px] text-[<?= $matchStatus ?>] border-[<?= $matchStatus ?>]"><?= $item["order_status"] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

                <div class="flex flex-col flex-1 border-[3px] border-[rgb(10,10,10)] rounded-[10px] px-[12px]">
                    <div class="flex h-[48px] gap-[12px] items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path fill-rule="evenodd" d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z" clip-rule="evenodd" />
                        </svg>
                        <span>Top Selling Products</span>
                    </div>

                    <div class="flex flex-col gap-[12px]">
                        <?php foreach ($salesProducts as $sales): ?>
                            <div class="flex items-center justify-between gap-[12px]">
                                <div class="flex items-center gap-[12px]">
                                    <div class="aspect-square">
                                        <img src="assets/images/uploads/<?= $sales["image1"] ?>" alt="<?= $sales["name"] ?>" class="w-[72px] h-[72px] object-cover">
                                    </div>
                                    <span class="flex flex-1 max-h-[48px] overflow-hidden"><?= $sales["name"] ?></span>
                                </div>

                                <div class="flex flex-col items-center">
                                    <span>Sales</span>
                                    <span><?= $sales["sales"] ?></span>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>



            </div>
        </div>

    </div>
    <script src="assets/src/admin.js"></script>
</body>

</html>
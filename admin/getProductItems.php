<?php

require_once("includes/config.php");

$orderId = $_POST["orderId"];

$od = $pdo->prepare("SELECT orders_items.quantity as order_quantity, orders_items.total_price as order_totalPrice, products.image1 as product_img1, products.name as product_name FROM orders_items
INNER JOIN products ON orders_items.product_id = products.id
WHERE orders_items.order_id = :id");
$od->execute([":id" => $orderId]);
echo json_encode($od->fetchAll());

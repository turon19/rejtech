<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$ci = $pdo->prepare("SELECT cart_items.product_id as cart_productId, cart_items.user_id as cart_userId, cart_items.quantity as cart_quantity, products.id as products_id, products.image1 as products_image1, products.name as products_name,products.price as products_price from cart_items
INNER JOIN products ON products.id = cart_items.product_id
WHERE user_id = :userId");
$ci->execute(["userId" => $_SESSION['id']]);
$cartItems = $ci->fetchAll();

$grandTotal = 0;
foreach ($cartItems as $item) {
    $grandTotal += $item["cart_quantity"] * $item["products_price"];
}


if (isset($_POST["delete"])) {
    $productId = $_POST["productId"];
    $ci = $pdo->prepare("DELETE FROM cart_items
    WHERE product_id = :productId and user_id = :userId");
    $ci->execute([":productId" => $productId, ":userId" => $_SESSION['id']]);

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}

$ci = $pdo->prepare("SELECT COUNT(id) as total_items FROM cart_items
WHERE user_id = :userId");
$ci->execute([":userId" => $_SESSION["id"]]);
$total_items = $ci->fetch();

if (isset($_POST['checkout'])) {

    $ci = $pdo->prepare("INSERT INTO orders(user_id,total_price)
    VALUES (:userId , :totalPrice)");
    $ci->execute([":userId" => $_SESSION['id'], ":totalPrice" => $grandTotal]);
    $orderId = $pdo->lastInsertId();

    foreach ($cartItems as $item) {
        $totalPrice = $item["cart_quantity"] * $item["products_price"];
        $ci =  $pdo->prepare("INSERT INTO orders_items(order_id,product_id,quantity,total_price)
 VALUES (:orderId,:productId,:quantity,:totalPrice)");
        $ci->execute([":orderId" => $orderId, ":productId" => $item['cart_productId'], ":quantity" => $item['cart_quantity'], ":totalPrice" => $totalPrice]);
    }

    $delete = $pdo->prepare("DELETE FROM cart_items
    WHERE user_id = :userId");
    $delete->execute([":userId" => $_SESSION["id"]]);
    header("location: my-orders.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | REJ | TECH</title>
    <link rel="icon" href="assets/images/242916475_228188199358776_5781228318523028945_n.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <?php require_once("includes/header.php") ?>

    <?php if (count($cartItems) === 0): ?>
        <div class="flex flex-col gap-[24px] items-center min-h-dvh justify-center">
            <div class="flex flex-col gap-[12px] items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-[144px] w-[144px]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <span class="flex text-[32px] justify-center">Shopping Cart</span>
                <span class="flex justify-center">Your cart is empty</span>
            </div>
            <a href="products.php" class="flex h-[48px] bg-[rgb(100,0,0)] items-center justify-center w-[168px] rounded-[10px]">Continue Shopping</a>
        </div>
    <?php endif; ?>

    <?php if (count($cartItems) > 0): ?>
        <div class="flex flex-col gap-[12px] p-[12px]">
            <span class="flex text-[32px] font-bold justify-center">YOUR CART</span>

            <div class="grid lg:grid-cols-[666px_333px] grid-cols-1 lg:justify-center gap-[12px]">
                <div class="flex flex-col gap-[12px]">

                    <div class="flex h-[48px] border-b-[3px] border-[rgb(10,10,10)] gap-[12px] justify-between">
                        <span class="flex items-center">Product</span>
                        <span class="flex items-center">Price</span>
                    </div>

                    <div class="flex flex-col gap-[12px]">
                        <?php foreach ($cartItems as $cartItem): ?>
                            <?php $totalPrice = $cartItem["cart_quantity"] * $cartItem["products_price"]; ?>
                            <form method="post" class="flex gap-[12px] justify-between">
                                <input type="hidden" name="productId" value="<?= $cartItem["cart_productId"] ?>">

                                <div class="flex items-center gap-[12px]">
                                    <button type="submit" name="delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg1">
                                            <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div class="aspect-square">
                                        <img src="../admin/assets/images/uploads/<?= $cartItem['products_image1'] ?>" alt="<?= $cartItem["products_name"] ?>" class="h-[72px] w-[72px] object-cover">
                                    </div>
                                    <span class="flex flex-1 max-h-[48px] overflow-hidden"><?= $cartItem['products_name'] ?></span>
                                </div>

                                <div class="flex flex-col justify-center">
                                    <span class="user-cartItems-info justify-end"><?= $cartItem["cart_quantity"] ?>x</span>
                                    <span class="user-cartItems-info">₱<?= number_format($cartItem['products_price'], 2)  ?></span>
                                </div>

                            </form>
                        <?php endforeach; ?>

                    </div>
                </div>

                <form method="post" class="flex flex-col border-[3px] border-[rgb(10,10,10)] rounded-[10px] px-[12px] pb-[12px] gap-[12px]">
                    <span class="flex items-center h-[48px] border-b-[3px] border-[rgb(10,10,10)] ">Cart Summary</span>
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center h-[48px]"><?= $total_items['total_items'] ?> Products</span>
                        <div class="flex justify-between items-center h-[48px]">
                            <span>Estimated Delivery & Handling:</span>
                            <span>Free</span>
                        </div>
                    </div>

                    <div class="flex h-[48px] border-t-[3px] border-[rgb(10,10,10)] items-center justify-between">
                        <span>Total:</span>
                        <span>₱<?= number_format($grandTotal, 2) ?> </span>
                    </div>
                    <button type="submit" class="bg-[rgb(100,0,0)] h-[48px] rounded-[10px] w-full" name="checkout">Proceed to Checkout</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</body>

</html>
<?php
require_once("includes/config.php");

$budget = $_POST["budget"];
$category = "desktop";

$pd = $pdo->prepare("SELECT id,image1,name,price from products
WHERE price <= :budget and category = :category
ORDER BY price DESC
LIMIT 5");
$pd->execute([":budget" => $budget, ":category" => $category]);
$products = $pd->fetch();

header("Content-type: application/json");

if ($products) {
    echo json_encode([
        "message" => "Perfect match! We found something just for your budget.",
        "display" => "flex",
        "name" => $products["name"],
        "link" => "product-details.php?productId={$products["id"]}"
    ]);
} else {
    echo json_encode([
        "message" => "No matches yet… maybe try adjusting your budget? Min. available: ₱200,000",
        "display" => "hidden",
        "name" => "",
        "link" => "#"
    ]);
}

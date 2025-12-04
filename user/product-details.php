<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$productId = $_GET['productId'];
if (!isset($_GET["productId"])) {
  header("location: index.php");
  exit();
}

$status = "ACTIVE";

$pd = $pdo->prepare("SELECT * FROM products
WHERE id = :id");
$pd->execute([':id' => $productId]);
$productDetails = $pd->fetch();

$pd = $pdo->prepare("SELECT * FROM products
WHERE category = :category and status = :status
LIMIT 5");
$pd->execute([":category" => $productDetails["category"], ":status" => $status]);
$productItems = $pd->fetchAll();


if (isset($_POST['cart'])) {
  $productid = $_POST['productid'];
  $userId = $_SESSION['id'];

  $pd = $pdo->prepare("SELECT * FROM cart_items
WHERE product_id = :productId and user_id = :userId");
  $pd->execute([":productId" => $productid, ":userId" => $userId]);


  if ($pd->rowCount() > 0) {
    $pd = $pdo->prepare("UPDATE cart_items
  SET quantity = quantity + 1
  WHERE product_id = :productId and user_id = :userId ");
    $pd->execute([":productId" => $productid, ":userId" => $userId]);
    $_SESSION["success"] = "Product quantity updated in cart";
    header("location: product-details.php?productId={$productid}");
    exit();
  } else {
    $pd = $pdo->prepare("INSERT INTO cart_items(user_id,product_id)
  VALUES (:userId, :productId)");
    $pd->execute([':userId' => $userId, ':productId' => $productid]);
    $_SESSION["success"] = "Product added to cart!";
    header("location: product-details.php?productId={$productid}");
    exit();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $productDetails["name"] ?></title>
  <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body class="flex flex-col min-h-dvh bg-black">

  <?php require_once("includes/header.php") ?>

  <div class="flex flex-col gap-[12px] p-[12px] items-start">

    <div class="grid lg:grid-cols-2 grid-cols-1 gap-[12px] w-full">
      <div class="flex w-full gap-[12px]">
        <div class="lg:flex hidden flex-col gap-[12px] lg:w-[96px] justify-center">
          <div class="aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productDetails['image1'] ?>" alt="<?= $productDetails['name'] ?>" class="active user-pd-img" />
          </div>
          <div class="aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productDetails['image2'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
          </div>
          <div class="aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productDetails['image3'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
          </div>
          <div class="aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productDetails['image4'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
          </div>
          <div class="aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productDetails['image5'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
          </div>
        </div>

        <div class="flex flex-1 aspect-square">
          <img alt="<?= $productDetails['name'] ?>" class="user-pd-img-c1 object-cover">
        </div>
      </div>

      <div class="lg:hidden flex flex-row gap-[12px] w-full">
        <div class="aspect-square">
          <img src="../admin/assets/images/uploads/<?= $productDetails['image1'] ?>" alt="<?= $productDetails['name'] ?>" class="active user-pd-img" />
        </div>
        <div class="aspect-square">
          <img src="../admin/assets/images/uploads/<?= $productDetails['image2'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
        </div>
        <div class="aspect-square">
          <img src="../admin/assets/images/uploads/<?= $productDetails['image3'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
        </div>
        <div class="aspect-square">
          <img src="../admin/assets/images/uploads/<?= $productDetails['image4'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
        </div>
        <div class="aspect-square">
          <img src="../admin/assets/images/uploads/<?= $productDetails['image5'] ?>" alt="<?= $productDetails['name'] ?>" class="user-pd-img" />
        </div>
      </div>

      <div class=" flex flex-col w-full gap-[12px] justify-center">
        <?php if (isset($_SESSION["success"])):  ?>
          <div class="user-flash-msg user-header bg-[rgb(0,100,0)] rounded-[10px] gap-[12px]">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg1">
              <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
            </svg>
            <span><?= $_SESSION['success'] ?></span>
          </div>
          <?php unset($_SESSION["success"]); ?>
        <?php endif; ?>

        <div class="flex flex-col gap-[24px]">
          <div class="flex flex-col gap-[12px]">
            <span class=" flex text-[32px]"><?= $productDetails['name'] ?></span>
            <span class="flex"><?= $productDetails['description'] ?></span>
          </div>

          <form method="post" class="flex gap-[24px] items-center">
            <span class="flex text-[32px]">₱<?= number_format($productDetails['price'], 2) ?></span>
            <input type="hidden" name="productid" value="<?= $productDetails['id'] ?>">
            <button class="user-button w-[192px]" name="cart">Add to Cart</button>
          </form>
        </div>
      </div>
    </div>

    <span class="flex text-[32px] font-bold items-center border-b-[3px] border-[rgb(100,0,0)]">RELATED ITEMS</span>

    <div class="user-productScrollBar flex gap-[12px] overflow-x-auto snap-x snap-mandatory scroll-smooth w-full">
      <?php foreach ($productItems as $productItem): ?>
        <a href="product-details.php?productId=<?= $productItem['id'] ?>" class="flex-shrink-0 flex flex-col gap-[12px] w-[289px]">
          <div class="flex aspect-square">
            <img src="../admin/assets/images/uploads/<?= $productItem['image1'] ?>" alt="<?= $productItem["name"] ?>" class="object-cover" />
          </div>
          <span class="flex h-[48px] overflow-hidden"><?= $productItem['name'] ?></span>
          <span class="flex h-[48px] items-center">₱<?= number_format($productItem['price'], 2) ?></span>
          <button class="user-button">View Product</button>
        </a>
      <?php endforeach; ?>
    </div>

    <span class="user-footer w-full">© 2025 REJ | TECH. All rights reserved.</span>

  </div>



  <script src="assets/src/product-details.js"></script>
  <script src="assets/src/flash.js"></script>
</body>

</html>
<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$status = "ACTIVE";

$na = $pdo->prepare("SELECT * FROM products
WHERE status = :status
ORDER BY release_date DESC
LIMIT 5");
$na->execute([":status" => $status]);
$newArrivalProduct = $na->fetchAll();

$na = $pdo->prepare("SELECT * FROM products
WHERE status = :status
ORDER BY sales DESC
LIMIT 5");
$na->execute([":status" => $status]);
$topSellers = $na->fetchAll();


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>REJ | TECH | Explore the Latest Tech</title>
  <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body class="flex flex-col min-h-dvh bg-black">

  <button type="button" class="robot-btn flex justify-center items-center bg-black border-[3px] border-[rgb(100,0,0)]  rounded-[50px] fixed  bottom-[12px] right-[12px] w-[60px] h-[60px]">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[32px] h-[32px]">
      <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 3v1.5M4.5 8.25H3m18 0h-1.5M4.5 12H3m18 0h-1.5m-15 3.75H3m18 0h-1.5M8.25 19.5V21M12 3v1.5m0 15V21m3.75-18v1.5m0 15V21m-9-1.5h10.5a2.25 2.25 0 0 0 2.25-2.25V6.75a2.25 2.25 0 0 0-2.25-2.25H6.75A2.25 2.25 0 0 0 4.5 6.75v10.5a2.25 2.25 0 0 0 2.25 2.25Zm.75-12h9v9h-9v-9Z" />
    </svg>
  </button>

  <div class="robot-chat flex flex-col fixed right-0 bottom-0 w-full max-w-[384px] bg-black rounded-[10px] z-[100] px-[12px] pb-[12px] gap-[12px]">

    <div class="flex h-[48px] items-center justify-between gap-[12px]">
      <div class="flex gap-[12px]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="user-svg1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
        </svg>
        <span>Smart Budget Finder</span>
      </div>

      <button type="button" class="robot-btn">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="user-svg1">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <div class="ai-convo flex flex-col gap-[12px] items-start max-h-[650px] overflow-y-auto"></div>

    <div class="relative flex gap-[12px] ">
      <input type="number" class=" ai-input border-[3px] w-full h-[48px] bg-transparent border-[rgb(10,10,10)] focus:border-[rgb(100,0,0)] rounded-[10px] p-[24px] outline-none" placeholder="₱ Your budget…">
      <button class="ai-msg-send absolute right-1 top-[15px]">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[24px] h-[24px]">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
        </svg>
      </button>
    </div>

  </div>

  <?php require_once("includes/header.php") ?>

  <div class="user-banner lg:aspect-[20/7] aspect-[4/5] bg-cover bg-center bg-no-repeat"></div>

  <div class="flex flex-col p-[12px] gap-[12px] items-start">

    <div class="flex flex-col gap-[24px] items-center justify-center w-full">
      <div class="flex flex-col gap-[12px] items-center">
        <span class="text-[32px] font-bold">AMAZING OFFERS!</span>
        <span>Shop the latest PCs, laptops, and accessories at unbeatable prices. Discover top deals, upgrade your setup, and save on high-performance gear today!</span>
      </div>
      <a href="products.php" class="bg-[rgb(100,0,0)] h-[48px] items-center justify-center flex rounded-[10px] w-[168px]">View All Products</a>
    </div>

    <span class="flex text-[32px] font-bold items-center border-b-[3px] border-[rgb(100,0,0)]">NEW ARRIVALS</span>

    <div class="user-productScrollBar flex gap-[12px] overflow-x-auto snap-x snap-mandatory scroll-smooth w-full">
      <?php foreach ($newArrivalProduct as $newArrival): ?>
        <a href="product-details.php?productId=<?= $newArrival['id'] ?>" class="flex-shrink-0 flex flex-col gap-[12px] w-[289px]">
          <div class="flex aspect-square">
            <img src="../admin/assets/images/uploads/<?= $newArrival['image1'] ?>" alt="<?= $newArrival["name"] ?>" class="object-cover" />
          </div>
          <span class="flex h-[48px] overflow-hidden"><?= $newArrival['name'] ?></span>
          <span class="flex h-[48px] items-center">₱<?= number_format($newArrival['price'], 2) ?></span>
          <button class="user-button">View Product</button>
        </a>
      <?php endforeach; ?>
    </div>

    <span class="flex text-[32px] font-bold items-center border-b-[3px] border-[rgb(100,0,0)]">TOP SELLERS</span>

    <div class="user-productScrollBar flex gap-[12px] overflow-x-auto snap-x snap-mandatory scroll-smooth w-full whitespace-nowrap">
      <?php foreach ($topSellers as $topSeller): ?>
        <a href="product-details.php?productId=<?= $topSeller['id'] ?>" class="flex-shrink-0 flex flex-col gap-[12px] w-[289px]">
          <div class="flex aspect-square">
            <img src="../admin/assets/images/uploads/<?= $topSeller['image1'] ?>" alt="<?= $topSeller["name"] ?>" class="object-cover" />
          </div>
          <span class="flex h-[48px] overflow-hidden"><?= $topSeller['name'] ?></span>
          <span class="flex h-[48px] items-center">₱<?= number_format($topSeller['price'], 2) ?></span>
          <button class="user-button">View Product</button>
        </a>
      <?php endforeach; ?>
    </div>

    <span class="user-footer w-full">© 2025 REJ | TECH. All rights reserved.</span>

  </div>



















  <script>
    const phpSelf = "<?= $_SERVER["PHP_SELF"] ?>";
  </script>
  <script src="assets/src/index.js"></script>

</body>

</html>
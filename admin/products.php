<?php
require_once("includes/config.php");
require_once("includes/auth.php");

$category = $_POST["selectCategory"] ?? "ALL";

if ($category === "ALL") {
    $pd = $pdo->prepare("SELECT * FROM products");
    $pd->execute();
} else {
    $pd = $pdo->prepare("SELECT * FROM products
    WHERE category = :category");
    $pd->execute([":category"  => $category]);
}
$products = $pd->fetchAll();

$status = "OUT OF STOCK";
$pd = $pdo->prepare("UPDATE products
        SET status = :status
        WHERE stock = 0");
$pd->execute([":status" => $status]);

$pd = $pdo->prepare("SELECT COUNT(*) as products, SUM(stock) as stocks FROM products");
$pd->execute();
$total = $pd->fetch();

$lowstock = 5;

$pd = $pdo->prepare("SELECT COUNT(*) FROM products
WHERE stock <= :stock");
$pd->execute([":stock" => $lowstock]);
$lowStock = $pd->fetchColumn();

$outstock = 0;

$pd = $pdo->prepare("SELECT COUNT(*) FROM products
WHERE stock = :stock");
$pd->execute([":stock" => $outstock]);
$outStock = $pd->fetchColumn();

if (isset($_POST["productBtn"])) {
    $productName = $_POST["productName"];
    $productCategory = $_POST["productCategory"];
    $productPrice = $_POST["productPrice"];
    $productStock = $_POST["productStock"];
    $productStatus = $_POST["productStatus"];
    $productDescription = $_POST["productDescription"];

    $index = [1, 2, 3, 4, 5];
    $defaultImg = "images/desktops/14INF-1-1024x1024.png";

    foreach ($index as $i) {
        $key = "productImg$i";

        if (isset($_FILES[$key]) && $_FILES[$key]["error"] === 0) {
            $filename = uniqid() . "_$i_" . basename($_FILES[$key]["name"]);
            move_uploaded_file($_FILES[$key]["tmp_name"], "assets/images/uploads/" . $filename);
            $image[$i] = $filename;
        } else $image[$i] = $defaultImg;
    }

    $pd = $pdo->prepare("INSERT INTO products (image1,image2,image3,image4,image5,name,category,price,description,stock,status)
    VALUES (:image1, :image2, :image3, :image4, :image5, :name, :category, :price, :description, :stock, :status)");
    $pd->execute([":image1" => $image[1], ":image2" => $image[2], ":image3" => $image[3], ":image4" => $image[4], ":image5" => $image[5], ":name" => $productName, ":category" => $productCategory, ":price" => $productPrice, ":description" => $productDescription, ":stock" => $productStock, ":status" => $productStatus]);

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}

if (isset($_POST["delete"])) {
    $productId = $_POST["productId"];

    $pd = $pdo->prepare("DELETE FROM products
    WHERE id = :id");
    $pd->execute([":id" => $productId]);

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}

if (isset($_POST["editProductBtn"])) {
    $editProductName = $_POST["editProductName"];
    $editProductPrice = $_POST["editProductPrice"];
    $editProductCategory = $_POST["editProductCategory"];
    $editProductStock = $_POST["editProductStock"];
    $editProductStatus = $_POST["editProductStatus"];
    $editProductDescription = $_POST["editProductDescription"];
    $editProductId = $_POST["editProductId"];

    $pd = $pdo->prepare("UPDATE products
    SET name = :name, category = :category, price = :price, description = :description, stock = :stock, status = :status
    WHERE id = :id ");
    $pd->execute([":name" => $editProductName, ":category" => $editProductCategory, ":price" => $editProductPrice, ":description" => $editProductDescription, ":stock" => $editProductStock, ":status" => $editProductStatus, ":id" => $editProductId]);

    header("location: {$_SERVER["PHP_SELF"]}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products | REJ | TECH</title>
    <link rel="icon" href="../user/assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js"></script>
</head>

<body class="flex flex-col min-h-dvh bg-black">

    <div class="admin-product-modal hidden min-h-dvh fixed w-full bg-[rgba(0,0,0,0.90)] items-center justify-center px-[12px]">
        <form method="post" enctype="multipart/form-data" class="flex flex-col max-w-[756px] w-full bg-black rounded-[10px] px-[12px] pb-[12px] gap-[12px]">

            <div class="flex items-center justify-between">
                <span class="flex font-bold h-[48px] items-center">Add New Product</span>
                <button type="button" class="admin-product-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-[12px]">
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Name</span>
                    <input type="text" name="productName" class="admin-input h-[48px] bg-transparent border-[3px] border-[rgb(10,10,10)] rounded-[10px] outline-none px-[12px] focus:border-[rgb(100,0,0)]" placeholder="Enter product name" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Category</span>
                    <select name="productCategory" class="h-[48px] bg-transparent border-[3px] border-[rgb(10,10,10)] rounded-[10px] outline-none px-[12px] focus:border-[rgb(100,0,0)]" required>
                        <option value="" selected disabled>Select product category</option>
                        <option value="laptop" class="bg-[black]">Laptop</option>
                        <option value="desktop" class="bg-black">Desktop</option>
                        <option value="graphics-card" class="bg-black">Graphics Card</option>
                        <option value="monitor" class="bg-black">Monitor</option>
                        <option value="motherboard" class="bg-black">Motherboard</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-3 grid-cols-1 g gap-[12px]">
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Price</span>
                    <input type="number" name="productPrice" class="admin-input admin-productModalInput " placeholder="Enter product price" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Stock</span>
                    <input type="number" name="productStock" class="admin-input admin-productModalInput" placeholder="Enter stock quantity" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Status</span>
                    <select name="productStatus" id="" class="admin-productModalInput" required>
                        <option value="" selected disabled>Select product status</option>
                        <option value="ACTIVE" class="bg-[black]">Active</option>
                        <option value="DRAFT" class="bg-black">Draft</option>
                        <option value="OUT OF STOCK" class="bg-[black]">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-[12px]">
                <span class="flex items-center">Product Description</span>
                <textarea name="productDescription" class="admin-input admin-productModalInput h-[48px] pt-[12px]" placeholder="Enter product description" required></textarea>
            </div>

            <div class="flex flex-col gap-[12px]">
                <span class="flex items-center">Product Image</span>
                <div class="flex flex-col gap-[12px]">
                    <div class="grid md:grid-cols-2 grid-cols-1  gap-[12px]">
                        <input type="file" name="productImg1" required>
                        <input type="file" name="productImg2" required>

                    </div>
                    <div class="grid md:grid-cols-2 grid-cols-1 gap-[12px]">
                        <input type="file" name="productImg3" required>
                        <input type="file" name="productImg4" required>
                    </div>
                    <input type="file" name="productImg5" required>
                </div>
            </div>

            <div class="flex justify-end">
                <button name="productBtn" class="flex bg-[rgb(0,100,0)] p-[12px] rounded-[10px] gap-[6px] items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[24px] h-[24px]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Product</span>
                </button>
            </div>

        </form>
    </div>

    <div class="admin-editProduct-modal hidden min-h-dvh fixed w-full bg-[rgba(0,0,0,0.90)] items-center justify-center px-[12px]">
        <form method="post" class="flex flex-col max-w-[756px] w-full bg-black rounded-[10px] px-[12px] pb-[12px] gap-[12px]">

            <div class="flex items-center justify-between gap-[12px]">
                <span class="flex font-bold h-[48px] items-center">Edit Product</span>
                <button type="button" class="admin-edit-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="grid sm:grid-cols-2 grid-cols-1 gap-[12px]">
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Name</span>
                    <input type="text" name="editProductName" class="admin-input admin-edit-productName admin-productModalInput" placeholder="Enter product name" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Category</span>
                    <select name="editProductCategory" class="admin-edit-productCategory admin-productModalInput" required>
                        <option value="laptop" class="bg-[black]">Laptop</option>
                        <option value="desktop" class="bg-black">Desktop</option>
                        <option value="graphics-card" class="bg-black">Graphics Card</option>
                        <option value="monitor" class="bg-black">Monitor</option>
                        <option value="motherboard" class="bg-black">Motherboard</option>
                    </select>
                </div>
            </div>

            <div class="grid md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-[12px]">
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Price</span>
                    <input type="number" name="editProductPrice" class="admin-input admin-edit-productPrice admin-productModalInput" placeholder="Enter product price" step=".01" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Stock</span>
                    <input type="number" name="editProductStock" class="admin-input admin-edit-productStock admin-productModalInput" placeholder="Enter stock quantity" required>
                </div>
                <div class="flex flex-col gap-[12px]">
                    <span class="flex items-center">Product Status</span>
                    <select name="editProductStatus" class="admin-edit-productStatus admin-productModalInput" required>
                        <option value="ACTIVE" class="bg-[black]">Active</option>
                        <option value="DRAFT" class="bg-black">Draft</option>
                        <option value="OUT OF STOCK" class="bg-[black]">Out of Stock</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-[12px]">
                <span class="flex items-center">Product Description</span>
                <textarea name="editProductDescription" class="admin-input admin-edit-productDescription admin-productModalInput h-[144px] pt-[10px]" placeholder="Enter product description" required></textarea>
            </div>


            <div class="flex justify-end">
                <button type="submit" name="editProductBtn" class="flex bg-[rgb(0,0,100)] p-[12px] rounded-[10px] gap-[12px] items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                    </svg>
                    <span>Edit Product</span>
                </button>
                <input type="hidden" class="admin-edit-productId" name="editProductId">
            </div>
        </form>
    </div>

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
                        <span class="flex items-center">Low Stocks</span>
                        <span class="flex items-center"><?= $lowStock ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Product Stock</span>
                        <span class="flex items-center"><?= $total["stocks"] ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>

                <div class="flex border-[3px] border-[rgb(10,10,10)] p-[12px] rounded-[10px] justify-between">
                    <div class="flex flex-col gap-[12px]">
                        <span class="flex items-center">Out of Stock</span>
                        <span class="flex items-center"><?= $outStock ?></span>
                    </div>
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-[32px] h-[32px]">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
            </div>

            <form method="post" class="admin-selectCategory-form flex justify-between gap-[12px]">
                <select name="selectCategory" class="admin-select-category bg-transparent border-[3px] border-[rgb(10,10,10)] px-[12px] h-[48px] rounded-[10px]">
                    <option value="ALL" <?= ($category === "ALL") ? "selected" : "" ?> class="bg-black">All Products</option>
                    <option value="laptop" <?= ($category === "laptop") ? "selected" : "" ?> class="bg-black">Laptops</option>
                    <option value="desktop" <?= ($category === "desktop") ? "selected" : "" ?> class="bg-black">Desktops</option>
                    <option value="graphics-card" <?= ($category === "graphics-card") ? "selected" : "" ?> class="bg-black">Graphics Card</option>
                    <option value="monitor" <?= ($category === "monitor") ? "selected" : "" ?> class="bg-black">Monitor</option>
                    <option value="motherboard" class="bg-black">Motherboard</option>
                </select>

                <button type="button" class="admin-product-btn flex bg-[rgb(0,100,0)] items-center px-[12px] rounded-[10px] gap-[6px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[24px] h-[24px]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Product</span>
                </button>
            </form>

            <div class="flex flex-col gap-[12px]">
                <div class="grid xl:grid-cols-[4fr_1fr_1fr_1fr_1fr] sm:grid-cols-[4fr_1fr_1fr_1fr] grid-cols-[1fr_1fr_1fr] h-[48px] border-b-[3px] border-[rgb(10,10,10)] gap-[12px] items-center">
                    <span>Product</span>
                    <span class="sm:flex hidden">Price</span>
                    <span class="xl:flex hidden">Stock</span>
                    <span class="sm:flex hidden">Published</span>
                    <span class="sm:flex hidden">Action</span>
                </div>

                <div class="flex flex-col gap-[12px]">
                    <?php foreach ($products as $product): ?>
                        <?php $statusColor = match ($product["status"]) {
                            "ACTIVE" => "rgb(0,128,0)",
                            "DRAFT" => "rgb(128,128,128)",
                            "OUT OF STOCK" => "rgb(255,0,0)"
                        } ?>

                        <div class="grid xl:grid-cols-[4fr_1fr_1fr_1fr_1fr]  sm:grid-cols-[4fr_1fr_1fr_1fr] grid-cols-[1fr_1fr_1fr] gap-[12px] items-center">
                            <div class="flex gap-[12px] items-center">
                                <div class="aspect-square">
                                    <img src="assets/images/uploads/<?= $product["image1"] ?>" alt="<?= $product["name"] ?>" class="w-[72px] h-[72px] object-cover">
                                </div>
                                <span class="sm:flex hidden flex-1 max-h-[48px] overflow-hidden"><?= $product["name"] ?></span>
                            </div>

                            <span class="sm:flex hidden">₱<?= number_format($product["price"], 2) ?></span>
                            <span class="xl:flex hidden"><?= $product["stock"] ?></span>

                            <div class="flex">
                                <span class="flex border-[3px] w-[132px] justify-center h-[40px] items-center rounded-[10px] text-[<?= $statusColor ?>] border-[<?= $statusColor ?>]"><?= $product["status"] ?></span>
                            </div>
                            <form method="post" class="flex gap-[12px]">
                                <button type="button" name="edit" class="admin-edit-btn" data-name="<?= $product["name"] ?>" data-id="<?= $product["id"] ?>" data-category="<?= $product["category"] ?>" data-price="<?= $product["price"] ?>" data-description="<?= $product["description"] ?>" data-stock="<?= $product["stock"] ?>" data-status="<?= $product["status"] ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </button>
                                <button type="submit" name="delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>
    </div>
    </div>
    <script src="assets/src/products.js"></script>
</body>

</html>
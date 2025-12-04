<?php

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();

    header("location: ../user/login.php");
    exit();
}
?>

<div class="flex h-[60px] px-[12px] justify-between">
    <div class="flex gap-[12px] items-center">
        <button type="button" class="admin-nav-btn lg:hidden flex">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <a href="admin.php" class="flex px-[12px] items-center">REJ | TECH</a>
    </div>

    <div class="admin-nav-list fixed hidden left-0 top-0 w-full bottom-0 flex-col gap-[12px] bg-black z-[100]">
        <span class="flex h-[60px] justify-end px-[12px]">
            <button class="admin-nav-btn">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </span>
        <a href="admin.php" class="flex h-[60px] px-[12px] items-center">Dashboard</a>
        <a href="products.php" class="flex h-[60px] px-[12px] items-center">Products</a>
        <a href="orders.php" class="flex h-[60px] px-[12px] items-center">Orders</a>
    </div>

    <form method="post" class="flex px-[12px] items-center">
        <button type="submit" name="logout" class="flex gap-[12px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15M12 9l3 3m0 0-3 3m3-3H2.25" />
            </svg>
            <span>Logout</span>
        </button>
    </form>
</div>

<script>
    const navBtn = document.querySelectorAll(".admin-nav-btn");
    const navList = document.querySelector(".admin-nav-list");
    navBtn.forEach((btn) => {
        btn.addEventListener("click", () => {
            navList.classList.toggle("show");
        });
    });
</script>
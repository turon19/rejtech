<?php

if (session_status() === PHP_SESSION_NONE) session_start();

if (isset($_POST["logout"])) {
    session_destroy();
    header("location: login.php");
    exit();
}

$username = $_SESSION["name"];

?>

<div class="flex h-[60px] px-[12px] justify-between">

    <div class="flex gap-[12px]">
        <button type="button" class="lg:hidden user-nav-btn flex items-center justify-center px-[12px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>

        <div class="lg:hidden user-nav-list fixed hidden flex-col top-0 left-0 w-full h-full z-[100] bg-black">
            <button type="button" class="user-nav-btn flex justify-end h-[60px] items-center px-[12px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
            <a href="category.php?category=laptop" class="user-nav1">Laptop</a>
            <a href="category.php?category=desktop" class="user-nav1">Desktop</a>
            <a href="category.php?category=graphics-card" class="user-nav1">Graphics Card</a>
            <a href="category.php?category=monitor" class="user-nav1">Monitor</a>
            <a href="category.php?category=motherboard" class="user-nav1">Motherboard</a>
        </div>

        <a href="index.php" class="user-nav">REJ | TECH</a>

        <div class="lg:flex hidden gap-[12px]">
            <a href="category.php?category=laptop" class="user-nav">Laptop</a>
            <a href="category.php?category=desktop" class="user-nav">Desktop</a>
            <a href="category.php?category=graphics-card" class="user-nav">Graphics Card</a>
            <a href="category.php?category=monitor" class="user-nav">Monitor</a>
            <a href="category.php?category=motherboard" class="user-nav">Motherboard</a>
        </div>

    </div>

    <div class="flex gap-[12px]">
        <a href="cart.php" class="sm:flex hidden items-center justify-center px-[12px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="user-svg1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
            </svg>
        </a>

        <div class="relative flex">
            <div class="user-name user-nav gap-[12px]">
                <span><?= $username ?></span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg">
                    <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
                </svg>
            </div>

            <form method="post" class="user-menu hidden flex-col absolute top-[60px] w-full bg-black px-[12px] z-[100] gap-[12px]">

                <a href="cart.php" class="sm:hidden flex items-center gap-[12px] h-[48px]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    <span>Cart</span>
                </a>

                <a href="my-orders.php" class="user-menu-list">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                    </svg>
                    <span>Orders</span>
                </a>

                <button class="user-menu-list" name="logout">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M16.5 3.75a1.5 1.5 0 0 1 1.5 1.5v13.5a1.5 1.5 0 0 1-1.5 1.5h-6a1.5 1.5 0 0 1-1.5-1.5V15a.75.75 0 0 0-1.5 0v3.75a3 3 0 0 0 3 3h6a3 3 0 0 0 3-3V5.25a3 3 0 0 0-3-3h-6a3 3 0 0 0-3 3V9A.75.75 0 1 0 9 9V5.25a1.5 1.5 0 0 1 1.5-1.5h6Zm-5.03 4.72a.75.75 0 0 0 0 1.06l1.72 1.72H2.25a.75.75 0 0 0 0 1.5h10.94l-1.72 1.72a.75.75 0 1 0 1.06 1.06l3-3a.75.75 0 0 0 0-1.06l-3-3a.75.75 0 0 0-1.06 0Z" clip-rule="evenodd" />
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
    const userName = document.querySelector(".user-name");
    const userMenu = document.querySelector(".user-menu");

    userName.addEventListener("click", () => {
        userMenu.classList.toggle("dropdown");
    });

    const navBtn = document.querySelectorAll(".user-nav-btn");
    const navList = document.querySelector(".user-nav-list");

    navBtn.forEach(btn => {
        btn.addEventListener("click", () => {
            navList.classList.toggle("show");
        })
    })
</script>
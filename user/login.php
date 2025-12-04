<?php
require_once("includes/config.php");
require_once("includes/session-guest.php");


if (isset($_POST['login'])) {
    $secret = "6LeBph4sAAAAACtbi1mNkuVCBykRDnTPXwTPN4vg";
    $response = $_POST['g-recaptcha-response'];

    if (!$response) $error = "Please confirm that you are not a robot.";

    $verifyURL = "https://www.google.com/recaptcha/api/siteverify";
    $data = [
        'secret' => $secret,
        'response' => $response
    ];

    $options = [
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($data)
        ]
    ];

    $context  = stream_context_create($options);
    $verify = file_get_contents($verifyURL, false, $context);
    $captchaSuccess = json_decode($verify);

    if (!$captchaSuccess->success) $error =  "CAPTCHA failed. Please try again.";


    if (!isset($error)) {
        $email = $_POST['email'];
        $us = $pdo->prepare("SELECT * FROM users
    WHERE email = :email");
        $us->execute([":email" => $email]);


        if ($us->rowCount() > 0) {
            $user = $us->fetch();
            if (password_verify($_POST['password'], $user['password'])) {

                if ($user["role"] === "customer") {
                    $_SESSION['name'] = $user['first_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION["role"] = $user["role"];
                    header("location: index.php");
                    exit();
                } else if ($user["role"] === "admin") {
                    $_SESSION['name'] = $user['first_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION["role"] = $user["role"];
                    header("location: ../admin/admin.php");
                    exit();
                }
            } else $error = "Incorrect password. Please try again.";
        } else $error = "Try again or Create an account.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REJ | TECH - Log in and Sign up</title>
    <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="flex flex-col min-h-dvh bg-black items-center justify-center p-[12px]">

    <form method="post" class="flex flex-col w-full max-w-[326px] border-[rgb(10,10,10)] border-[3px] p-[12px] gap-[12px] rounded-[10px]">

        <span class="user-header">Sign in to REJ | TECH</span>
        <?php if (isset($error)): ?>
            <span class="user-flash-msg user-header bg-[rgb(100,0,0)] rounded-[10px]"><?= $error ?></span>
            <?php unset($error) ?>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Email" class="user-input" autocomplete="email" required>

        <div class="relative">
            <input type="password" name="password" placeholder="Password" class="user-input" required>
            <button type="button" class="showPassword">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg">
                    <path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                    <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                    <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />
                </svg>
            </button>
        </div>

        <div class="g-recaptcha flex" data-sitekey="6LeBph4sAAAAADP97EgH0t9dnDwEZZwitMW0tmK7"></div>

        <button class="user-button" name="login">Log in</button>

        <div class="user-container1">
            <span>Don't have an Account?</span>
            <a href="register.php">Register</a>
        </div>

    </form>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="assets/src/togglePassword.js"></script>
    <script src="assets/src/flash.js"></script>
</body>

</html>
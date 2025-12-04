<?php
require_once("includes/config.php");
require_once("includes/session-guest.php");

if (isset($_POST['register'])) {

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
        $ce = $pdo->prepare("SELECT email from users
    WHERE email = :email");
        $ce->execute(["email" => $_POST['email']]);

        if ($ce->rowCount() > 0) $error = "This email is already in use. Try logging in!";
        else if ($_POST['password'] !== $_POST['cPassword']) $error = "Passwords don’t match. Please try again.";
        else {
            $firstName = $_POST['firstName'];
            $lastName = $_POST['lastName'];
            $email = $_POST['email'];
            $contact = $_POST['phone'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $address = $_POST['address'];

            $ce = $pdo->prepare("INSERT INTO users (first_name,last_name,email,password,phone,address)
        VALUES (:firstname, :lastName, :email, :password, :phone, :address)");
            $ce->execute([":firstname" => $firstName,  ":lastName" => $lastName, ":email" => $email, ":password" => $password, ":phone" => $contact, ":address" => $address]);

            header("location: login.php");
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REJ | TECH Sign up</title>
    <link rel="icon" href="assets/images/icon/242916475_228188199358776_5781228318523028945_n.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="flex flex-col min-h-dvh bg-black items-center justify-center p-[12px]">

    <form method="post" class="flex flex-col w-full max-w-[438px] border-[3px] border-[rgb(10,10,10)] p-[12px] gap-[12px] rounded-[10px]">
        <span class="user-header">Sign up to REJ | TECH</span>
        <?php if (isset($error)) : ?>
            <span class="user-flash-msg user-header bg-[rgb(100,0,0)] rounded-[10px]"><?= $error ?></span>
            <?php unset($error) ?>
        <?php endif; ?>

        <div class="grid sm:grid-cols-2 grid-cols-1 gap-[12px]">
            <input type="text" placeholder="First Name" name="firstName" class="user-input" pattern="[A-Z a-z]{1,30}" title="Please use letters (A–Z) only. Spaces are allowed between words." maxlength="30" spellcheck="false" required>
            <input type="text" placeholder="Last Name" name="lastName" class="user-input" pattern="[A-Z a-z]{1,30}" title="Please use letters (A–Z) only. Spaces are allowed between words." maxlength="30" spellcheck="false" required>
        </div>

        <div class="grid sm:grid-cols-2 grid-cols-1 gap-[12px]">
            <input type="email" placeholder="Email" name="email" class="user-input" maxlength="255" required>
            <input type="tel" placeholder="Contact Details" name="phone" class="user-input" maxlength="11" pattern="[0-9]{11}" required>
        </div>

        <div class="grid sm:grid-cols-2 grid-cols-1 gap-[12px]">
            <div class="relative w-full">
                <input type="password" placeholder="Create Password" name="password" class="user-input" minlength="8" maxlength="64" required>
                <button type="button" class="showPassword">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg">
                        <path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                        <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                        <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />
                    </svg>
                </button>
            </div>
            <div class="relative w-full">
                <input type="password" placeholder="Confirm Password" name="cPassword" class="user-input" minlength="8" maxlength="64" required>
                <button type="button" class="showPassword">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="user-svg">
                        <path d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                        <path d="M15.75 12c0 .18-.013.357-.037.53l-4.244-4.243A3.75 3.75 0 0 1 15.75 12ZM12.53 15.713l-4.243-4.244a3.75 3.75 0 0 0 4.244 4.243Z" />
                        <path d="M6.75 12c0-.619.107-1.213.304-1.764l-3.1-3.1a11.25 11.25 0 0 0-2.63 4.31c-.12.362-.12.752 0 1.114 1.489 4.467 5.704 7.69 10.675 7.69 1.5 0 2.933-.294 4.242-.827l-2.477-2.477A5.25 5.25 0 0 1 6.75 12Z" />
                    </svg>
                </button>
            </div>
        </div>

        <input type="text" placeholder="Address" name="address" class="user-input" maxlength="100" spellcheck="false" required>

        <div class="g-recaptcha flex" data-sitekey="6LeBph4sAAAAADP97EgH0t9dnDwEZZwitMW0tmK7"></div>

        <button type="submit" class="user-button" name="register">Register</button>

        <div class="user-container1">
            <span>Have an Account?</span>
            <a href="login.php">Log in</a>
        </div>

    </form>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="assets/src/togglePassword.js"></script>
    <script src="assets/src/flash.js"></script>
</body>

</html>
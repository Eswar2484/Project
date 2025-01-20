<?php
session_start();
include_once "php/config.php";

if (!isset($_SESSION['reset_email'])) {
    header("location: login.php");
}

if (isset($_POST['password'])) {
    $password = mysqli_real_escape_string($conn, md5($_POST['password']));
    $email = $_SESSION['reset_email'];

    $sql = mysqli_query($conn, "UPDATE users SET password = '{$password}' WHERE email = '{$email}'");
    if ($sql) {
        unset($_SESSION['reset_email']);
        header("location: login.php");
    } else {
        $error = "Failed to reset password!";
    }
}
?>

<?php include_once "header.php"; ?>
<body>
    <div class="wrapper">
        <section class="form login">
            <header>Reset Password</header>
            <form action="#" method="POST">
                <div class="field input">
                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Enter your new password" required>
                </div>
                <div class="field button">
                    <input type="submit" value="Change Password">
                </div>
                <?php if (isset($error)) { ?>
                    <div class="error-text"><?php echo $error; ?></div>
                <?php } ?>
            </form>
        </section>
    </div>
</body>
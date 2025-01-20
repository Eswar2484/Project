<?php
session_start();
include_once "php/config.php";

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $sql = mysqli_query($conn, "SELECT * FROM users WHERE email = '{$email}'");
    
    if (mysqli_num_rows($sql) > 0) {
        $_SESSION['reset_email'] = $email;
        header("location: reset-password.php");
    } else {
        $error = "Email address not found!";
    }
}
?>

<?php include_once "header.php"; ?>
<body>
    <div class="wrapper">
        <section class="form login">
            <header>Forgot Password</header>
            <form action="#" method="POST">
                <div class="field input">
                    <label>Email Address</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="field button">
                    <input type="submit" value="Continue">
                </div>
                <?php if (isset($error)) { ?>
                    <div class="error-text"><?php echo $error; ?></div>
                <?php } ?>
            </form>
        </section>
    </div>
</body>
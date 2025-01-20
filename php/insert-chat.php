<?php
session_start();
include_once "config.php";

if (isset($_SESSION['unique_id'])) {
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $image_url = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $img_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $img_ext = pathinfo($img_name, PATHINFO_EXTENSION);
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($img_ext, $allowed_ext)) {
            $new_name = uniqid() . '.' . $img_ext;
            $upload_path = "uploads/" . $new_name;
            if (move_uploaded_file($tmp_name, $upload_path)) {
                $image_url = $upload_path;
            }
        }
    }

    if (!empty($message) || $image_url) {
        $sql = "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg, image_url)
                VALUES ('$incoming_id', '$outgoing_id', '$message', '$image_url')";
        mysqli_query($conn, $sql);
    }
} else {
    header("location: ../login.php");
}
?>
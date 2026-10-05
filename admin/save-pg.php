<?php
session_start();
include("../includes/db.php");

if(!isset($_SESSION['admin']))
{
    header("Location: admin-login.php");
    exit();
}

$pg_name = trim($_POST['pg_name']);
$city = trim($_POST['city']);
$rent = (int)$_POST['rent'];
$sharing = trim($_POST['sharing']);
$description = trim($_POST['description']);
$availability = trim($_POST['availability']);
$rating = isset($_POST['rating']) ? (float)$_POST['rating'] : 4.5;
$address = isset($_POST['address']) ? trim($_POST['address']) : "$city, India";
$amenities = isset($_POST['amenities']) ? trim($_POST['amenities']) : "";
$image1 = isset($_POST['image1']) && !empty($_POST['image1']) ? trim($_POST['image1']) : 'Images/pgs/pg_bed_1.jpg';
$image2 = isset($_POST['image2']) && !empty($_POST['image2']) ? trim($_POST['image2']) : 'Images/pgs/pg_mess_1.jpg';
$image3 = isset($_POST['image3']) && !empty($_POST['image3']) ? trim($_POST['image3']) : 'Images/pgs/pg_ext_1.jpg';

$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO pgs
    (pg_name, city, rent, sharing, description, availability, rating, address, amenities, image1, image2, image3)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
);

mysqli_stmt_bind_param(
    $stmt,
    "ssisssdsssss",
    $pg_name,
    $city,
    $rent,
    $sharing,
    $description,
    $availability,
    $rating,
    $address,
    $amenities,
    $image1,
    $image2,
    $image3
);

if(mysqli_stmt_execute($stmt))
{
    $new_pg_id = mysqli_insert_id($conn);
    mysqli_stmt_close($stmt);

    // Also populate pg_images table
    $img_stmt = mysqli_prepare($conn, "INSERT INTO pg_images (pg_id, image_url, caption, is_primary) VALUES (?, ?, ?, ?)");
    
    $c1 = 'Room & Bed View'; $p1 = 1;
    mysqli_stmt_bind_param($img_stmt, "issi", $new_pg_id, $image1, $c1, $p1);
    mysqli_stmt_execute($img_stmt);

    $c2 = 'Dining & Mess Hall'; $p2 = 0;
    mysqli_stmt_bind_param($img_stmt, "issi", $new_pg_id, $image2, $c2, $p2);
    mysqli_stmt_execute($img_stmt);

    $c3 = 'Building & Common Area'; $p3 = 0;
    mysqli_stmt_bind_param($img_stmt, "issi", $new_pg_id, $image3, $c3, $p3);
    mysqli_stmt_execute($img_stmt);

    mysqli_stmt_close($img_stmt);

    echo "<script>
    alert('PG Added Successfully with images and details!');
    window.location='manage-pgs.php';
    </script>";
}
else
{
    echo "Something went wrong: " . mysqli_error($conn);
}
?>
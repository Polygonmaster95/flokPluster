<?php require_once("connections/connection.php");
require_once ("functions.php");
//require_once("includes/session.php");
require_once("ImageResizer.php");

if (!logged_in()) {
    redirect_to("login.php"); // redirect if not logged in
}

define("MAX_SIZE", "3000"); //definition of may file size
$upmsg=[]; // for error messages

if(isset($_POST['submitMovie'])) {
    $text = $_POST['text'];
    $text = htmlspecialchars($_POST['text']);
    $text = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['text'])));
    $text = prep($_POST['text']);
    $dishName = trim(prep($_POST['movieName']));
    $file = $_FILES['Image'] ['tmp_name']; //handles the image
    $imageName = $_FILES['Image']['name']; // handles the image
    //Checking the size and type of the image-file
    $image_type = getimagesize($file); // will know here if an image has been inserted or not
    if ($image_type[2] = 1 || $image_type[2] = 2 || $image_type[2] = 3) {
        $size = filesize($_FILES['Image']['tmp_name']);
        if ($size < MAX_SIZE * 1024) { //checks if the size is too large
            $prefix = uniqid();
            $iName = $prefix . "_" . $imageName;
            $newName = "img/" . $iName; // image name + unique ID
            $resOBJ = new ImageResizer(); //referencing to ImageResizer.php
            $resOBJ->load($file);// from ImageResizer.php
            if ($_POST['wsize'] && $_POST['hsize']) {//defines with and height
                $width = $_POST['wsize'];
                $height = $_POST['hsize'];
                $resOBJ->resize($width, $height);
                array_push($upmsg, "Image resized to $width and $height px");
            } elseif ($_POST['wsize']) {
                $width = $_POST['wsize'];
                $resOBJ->resizeToWidth($width);
                array_push($upmsg, "Image resized to W of $width px");
            } elseif ($_POST['height']) {
                $height = $_POST['height'];
                $resOBJ->resizeToHeight($height);
                array_push($upmsg, "Image resized to H of $height px");
            } elseif ($_POST['ssize']) {
                $scale = $_POST['ssize'];
                $resOBJ->scale($scale);
                array_push($upmsg, "Image resized to scale of $scale %");
            }
        } else {
            array_push($upmsg, "Image is too big: MAY 3 Mb");
        }
    } else {
        array_push($upmsg, "Unknown image type");
    }

    $resOBJ->resizeToWidth(350); // uses resizeToWidth (will keep scale)
    $resOBJ->save($newName);
    $query = "INSERT INTO movies (movieName, Image) VALUES ('$movieName','$iName')";
    mysqli_query($conn, $query);
    array_push($upmsg, "Movie was uploaded!");


}

/*if(isset($_POST['submitNews'])){//when clicked submit, it will make a new entry in the db with a description
    $text = $_POST['text'];
    $text = htmlspecialchars($_POST['text']);
    $text = mysqli_real_escape_string($conn,htmlspecialchars(trim($_POST['text'])));
    $text = prep($_POST['text']);
    $descriptionNews = prep ($_POST['DescriptionNews']);
    $query="INSERT INTO news (Description) VALUES ('$descriptionNews')";
    mysqli_query($conn, $query);
}

if(isset($_POST['submitAbout'])) { //when clicked submit, it will make a new entry in the db with a description
    $text = $_POST['text'];
    $text = htmlspecialchars($_POST['text']);
    $text = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['text'])));
    $text = prep($_POST['text']);
    $descriptionAbout = prep($_POST['DescriptionAbout']);
    $query = "INSERT INTO about (Description) VALUES ('$descriptionAbout')";
    mysqli_query($conn, $query);
}

if(isset($_POST['submitOpenhours'])) { //when clicked submit, it will make a new entry in the db with the opening hours
    $text = $_POST['text'];
    $text = htmlspecialchars($_POST['text']);
    $text = mysqli_real_escape_string($conn, htmlspecialchars(trim($_POST['text'])));
    $text = prep($_POST['text']);
    $openHours = prep($_POST['openHours']);
    $query = "INSERT INTO openHours (Description) VALUES ('$openHours')";
    mysqli_query($conn, $query);
}

(isset($_POST['reset']));
$resetQuery = "UPDATE `reserveTime` SET  `isReserved` =  '0'";
mysqli_query($conn, $resetQuery); */
?>

<!DOCTYPE HTML>

<html>
<head>
    <title>Movies!</title>
    <meta charset="utf-8" />
    <link rel="stylesheet" href="style.css" />
</head>
<body>

<!-- Header -->
<header id="header">
    <a href="index.php" class="logo">flokPluster</a>
    </div></header>

<!-- Two -->
<section id="two" class="wrapper style1 special">
    <div class="inner">
        <?php if (!empty($upmsg)): ?>
            <div class="panel">
                <ul>
                    <li><?php echo implode('</li><li> ',$upmsg);?></li> <!-- we want to display the errors here -->
                </ul>
            </div>
        <?php endif;?>
        <div class="container">
            <br/><br/>
            Add a new Movie
            <br>
            <!-- The admin can add a new movie to the roster -->
            <form action="" method="post" enctype="multipart/form-data" name="Add a Movie">
                <br>
                <input type="text" name="movieName" placeholder="Enter the movie name" required>
                <br>
                <a>Upload image</a> <input type="file" name="Image" placeholder="Upload image"required>
                <br>
                <input type="submit" name="submitMovie" value="Add"/>
            </form>
           <!-- <br><br>
            Add News    <br>
            <!-- The admin can change the news -->
            <!-- <form action="" method="post" name="news"><br>
                <input type="text" name="DescriptionNews" placeholder="Enter some new news"required>
                <br>
                <input type="submit" name="submitNews" value="Add"/>
            </form>
            <br><br>
            Change the Description    <br> -->
            <!-- The admin can change the "about"-text -->
            <!--<form action="" method="post" name="about"><br>
                <input type="text" name="DescriptionAbout" placeholder="New stuff about you"required>
                <br>
                <input type="submit" name="submitAbout" value="Add"/>
            </form>
            <br><br>

            Change opening hours <br>
            <!-- The admin can change the opening hours -->
           <!-- <form action="" method="post" name="openCloseHours"><br>
                <input type="text" name="openHours" placeholder="F.ex: '14:00 - 22:00'"required>
                <br>
                <input type="submit" name="submitOpenhours" value="Add"/>
            </form>
            <br><br>

            Reset all reservations of the day
            <br><br>
            <form method="post" action="" onsubmit="return confirm('Are you sure you want to reset the whole damn thing?');">
                <input type="submit" name="reset" value="reset">
            </form>
            <br><br><br>
            <form action="logout.php" class="inline">
                <button class="float-left submit-button" >Logout</button>
            </form>
            <br/><br/>
        </div>
    </div> -->
</section>
<footer id="footer">
    <div class="copyright">
        We donut understand puns - Sorry.<br>
        &copy; By Sømaja.
    </div>
</footer>
<!--script stuff-->
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js'></script>
<script src="js/index.js"></script>
</body>
</html>
<?php require_once("connections/connection.php");
require_once("includes/session.php");
require_once("functions.php");
	if (logged_in()) {
		redirect_to("admin.php"); //redirects if already logged in
	}

$message=[];

	// START FORM PROCESSING
	if (isset($_POST['submit'])) { // Form has been submitted.
		$username = trim(mysqli_real_escape_string($conn, $_POST['User'])); //sets the $username equal to User
		$password = trim(mysqli_real_escape_string($conn,$_POST['Pass'])); // sets the $password equal to Pass

		$query = "SELECT ID, user, pass FROM login WHERE users = '".$username."' LIMIT 1"; // checks for the user
		$result = mysqli_query($conn, $query);
			if (mysqli_num_rows($result) == 1)
			{
				// username/password authenticated
				// and only 1 match
				$found_user = mysqli_fetch_array($result);
                if(password_verify($password, $found_user['Pass'])){ //checks if the entered password fits the one stored in the db
				    $_SESSION['User_id'] = $found_user['ID'];
				    $_SESSION['User'] = $found_user['users'];
				    redirect_to("admin.php");
				} 
				else {
				// username/password combo was not found in the database
				array_push($message, "Such a dill-breaker!<br>Username/password combination incarrot.<br>
					Please make sure your caps lock key is off and fry again.");
				}
			}
			else if(mysqli_num_rows($result) < 1){
                array_push($message, "SQL query with no results - Romaine calm.");
            }
	} else { // Form has not been submitted.
		if (isset($_GET['logout']) && $_GET['logout'] == 1) {
			array_push($message, "You are now logged out. Another one bites the crust.");
		} 
	}
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
        <a href="index.php" class="logo">FlokPluster</a>
        </div></header>

    <!-- Two -->
    <section id="two" class="wrapper style1 special">
        <div class="inner">
            <h2>Are you ready to crumble? </h2>
            <?php if (!empty($message)): ?>
                <div class="panel">
                    <ul>
                        <li><?php echo implode('</li><li> ',$message);?></li> <!-- we want to display the errors here -->
                    </ul>
                </div>
            <?php endif;?>
           Please enter:<br><br>
            Username:  admin<br>
            Password:  123<br><br><br><br>
                <form action="" method="post">
                    Username:
                    <input type="text" name="User" maxlength="30" value="" placeholder="Enter you username" required />
                    Password:
                    <input type="password" name="Pass" maxlength="30" value="" placeholder="Enter you password" required/>
                    <input type="submit" name="submit" value="Login" />
                </form>
            </div>
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
<?php
if (isset($conn)){mysqli_close($conn);}
?>
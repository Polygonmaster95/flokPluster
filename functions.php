<?php require_once("connections/connection.php");

function readAllMovieses()
{
    global $conn;
    $sqlDishes = "SELECT movieName, Image FROM movies"; /*SELECT column_name FROM table_name*/
    $resultDishes = $conn->query($sqlDishes);

    if ($resultDishes->num_rows > 0) {
        // output data of each row
        while($rowDishes = $resultDishes->fetch_assoc()) {
            echo $rowDishes["DishName"] . "<br>" .
                $rowDishes["Description"] . "<br>" .
                "Price: " . $rowDishes["Price"] . " Euro" . "<br>";
            echo '<img src="img/'.$rowDishes["Image"] . '" alt="Delicious food"><br><br><br>';
            ;
        }
    } else {
        echo "0 results";
    }
    $conn->close();
}

function readDailySpecial()
{
    global $conn1;
    $D= date("D");
    $sqlSpecial = "SELECT DailySpecialName, Description FROM dailyspecialtable WHERE CDate = '$D'"; //only selects the special in which CDate equals the date that is placed in the database
    $resultSpecial = $conn1->query($sqlSpecial);

    if ($resultSpecial->num_rows > 0) {
        // output data of each row
        while($rowSpecial = $resultSpecial->fetch_assoc()) {
            echo $rowSpecial["DailySpecialName"] . "<br>" .
                $rowSpecial["Description"] . "<br><br><br>";
        }
    } else {
        echo "0 results";
    }
    $conn1->close();
}
class newsAbout
{
    function __construct($table_name)
    {
        global $conn;
        $sqlNews = "SELECT * FROM `".$table_name."` ORDER BY `ID` LIMIT 1";
        $resultNews = $conn->query($sqlNews);

        if ($resultNews->num_rows > 0) {
            // output data of each row
            while ($rowNews = $resultNews->fetch_assoc()) {
                echo $rowNews["Description"] . "<br><br><br><br>";
            }
        } else {
            echo "0 results";
        }
    }
}

	function redirect_to($location) {
        header("Location: {$location}");
        exit;
    }

function text_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function prep($value) {
    global $conn;
    $value = mysqli_real_escape_string($conn,htmlspecialchars(trim($value)));
    return $value;
}

function e($string){
    return htmlentities($string, ENT_QUOTES, 'UTF-8', false);
}

//salt adding to password
//define("MAX_LENGTH", 6);
//
//function generateHashWithSalt($password) {
//    $intermediateSalt = md5(uniqid(rand(), true));
//    $salt = substr($intermediateSalt, 0, MAX_LENGTH);
//    return hash("sha256", $password . $salt);
//}

?>
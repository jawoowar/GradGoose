<!DOCTYPE html>
<html>
    <?php
    
    //error_reporting(E_ALL);
    //ini_set('display_errors', 1);

    session_start();

    $conn = mysqli_connect('ysjcs.net', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    //echo "Session ID: " . session_id() . "<br>";
    //echo "All Session Data: ";
    //print_r($_SESSION);
    //echo "<br><br>";

    //if (isset($_COOKIE["UserID"])) {
    //    $uid = $_COOKIE['UserID']; 
    //    $usr = $_COOKIE['Username'];
    if (isset($_SESSION['UserID'])) {
        $uid = $_SESSION['UserID'];
        $usr = $_SESSION['Username'];
    } else {
        header("Location: Login.php");
        exit();
    }

    $result = $conn->query("SELECT * FROM UserID WHERE UserID = {$uid}");
    $User = mysqli_fetch_assoc($result);

    $result = $conn->query("SELECT * FROM Favorites WHERE FavoriteId = {$User['FavoriteTableID']}");
    $Favorites = mysqli_fetch_assoc($result);

    if (isset($_POST['AddToFavs'])) {

        $result = $conn->query("SELECT * FROM Favorites WHERE FavoriteId = {$User['FavoriteTableID']}");
        $Favorites = mysqli_fetch_assoc($result);

        for ($counter = 1; $counter < 11; $counter++) {
            $FavoriteTableID = $Favorites["ItemID{$counter}"];
            if ($FavoriteTableID == NULL) {
                $NextFree = $counter;
                break;
            }
        }

        $ID = intval($_POST['ID']);

        $result = $conn->query("SELECT * FROM JointItems WHERE ItemID = {$ID}");
        $JointItems = mysqli_fetch_assoc($result);

        if (isset($NextFree)  AND ($NextFree <= 11)) {
                $Absent = true;
                for ($counter = 1; $counter < 11; $counter++) {
                    $FavoriteTableID = $Favorites["ItemID{$counter}"];
                    if ($FavoriteTableID == $JointItems['ItemID']) {
                        $_SESSION['alert'] = "Item already in favorites";
                        $Absent = false;
                        break;
                    }
                }
                if ($Absent) {
                    $Update = $conn->query("UPDATE Favorites SET ItemID{$NextFree} = {$ID} WHERE FavoriteId = {$User['FavoriteTableID']}");
                    if ($Update) {
                        $_SESSION['alert'] = "Item added to fovorites";
                    } else {
                        $_SESSION['alert'] = "Error: " . $conn->error;
                    }
                }
        } else {
            $_SESSION['alert'] = "Favorites full or error";
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING']);
        exit();

    }

    //echo "Sessino UID = ". $uid . "<br>";
    //echo "Session Username = ". $usr ."<br>";

    if (isset($_SESSION['alert'])) {
        echo "<script>alert('" . addslashes($_SESSION["alert"]) . "');</script>";
        unset($_SESSION["alert"]);
    }
    
    ?>
    <head>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>GradGoose</title>
    </head>

    <link rel="stylesheet" href="../Styles/Root.css">
    <link rel="stylesheet" href="../Styles/Items.css">
    <link rel="stylesheet" href="../Styles/Index.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

<body class="Website"> <!--Moves the whole website to the centre-->

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.php" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        
        <!--Buttons to move to a different page-->
        <a href="Lists.php"><i class="fa fa-navicon"></i></a>
        <a href="Favs.php"><i class="material-icons" style="font-size:55px">star</i></a>
        <a href="Profile.php"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.php"><button class="SignUpButton"> Sign in </button></a>

    </div>

    <div class="Items" >
        <div class="Filters"><!--The filters for searching an item-->
            <h1>Filters</h1>
            <div class="radial">
                <input type="radio" id="html" name="Filter" value="relevency" checked="checked">
                <label for="relevency"><p class="filterP">Revelevency</p></label>
            </div>
            
            <div class="radial">
                <input type="radio" id="html" name="Filter" value="Cost">
                <label for="Cost"><p class="filterP">Cost</p></label>
            </div>

            <div class="radial">
                <input type="radio" id="html" name="Filter" value="NumRatings">
                <label for="NumRatings"><p class="filterP"></p>Number of ratings</p></label>
            </div>
            
            <div class="radial">
                <input type="radio" id="html" name="Filter" value="Ratings">
                <label for="Ratings"><p class="filterP">Ratings</p></label>
            </div>
            

            <h1>Custom</h1>
            <p>Costs</p>
            <input type="range" min="0" max="100" values="100" class="slider" id="Cost" value="30">
            <p>Number of Ratings</p>
            <input type="range" min="0" max="100" values="100" class="slider" id="NumRatings" value="40">
            <p>Ratings</p>
            <input type="range" min="0" max="100" values="100" class="slider" id="Ratings" value="30">
            <p>Low Price</p>
            <input type="number" min="0" max="99999" id="lo">
            <p>High Price</p>
            <input type="number" min="0" max="99999"  id="hi">
            <button id="submit">submit</button>

        </div>

        <div class="Products">
            <div id="items">
                
            </div>
            <div class="buttonContainer">
                <button id="loadMore">Load More</button>
            </div>

        </div>
    </div>

<div id="FooterLine"></div>

<Footer>
    <div class="FooterBox1">
        <a href="AboutUs.html">About Us</a>
    </div>

    <div class="FooterBoxMiddle">
        <div id="GradGooseLogoFooter">
            <img src="../Media/GradGooseLogo.svg" alt="">
        </div>

        <div class="FooterBox2">
            <a href="HowToUse.html">How To Use</a>
        </div>
    </div>
    
    <div class="FooterBox3">
        <a href="Profile.html">Settings</a>
    </div>
    
</Footer> 

</body>

<script src="../Scripts/index.js"></script>

</html>
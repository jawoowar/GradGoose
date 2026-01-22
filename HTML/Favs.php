<!DOCTYPE html>
<html>
<?php
    session_start();

    $dbName = "jenniferwoodward_GradGoose";
    $conn = new mysqli("ysjcs.net", "jennifer.w", "EHEXYUE8",$dbName);

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    //$UID = 1;
    if (isset($_SESSION['UserID'])) {
        $UID = $_SESSION['UserID'];
    } else {
        echo "<script>alert('please log in to use this feature')</script>";
        header("Location: Login.php");
        exit();
    }
    //echo $UID;
    /*
    if (!empty($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
    }
    else {
        echo "<script>
        alert('please log in to use this feature');
        window.location.href = 'Login.php';
        </script>";
        exit();
    }*/
    $result = $conn->query("SELECT * FROM UserID WHERE UserID = {$UID}");
    $User = mysqli_fetch_assoc($result);
    //echo "user";

    $FavoritesID = intval($User['FavoriteTableID']);
    $FavQuery = $conn->query('SELECT * FROM Favorites WHERE FavoriteID = '.$FavoritesID);
    $FavList = mysqli_fetch_assoc($FavQuery);

    $FavIDs = [];
    for ($counter = 1; $counter < 11; $counter++) { // puts contence of list into array
        //echo $counter;
        $FavID = $FavList["ItemID{$counter}"];
        if ($FavID != NULL) {
            //echo $FavID;
            $FavIDs[] = $FavID;
        } else {
            break;
        }
    }
    //echo "favs IDS";

    $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['ListIDCurrentTesco']}");
    $Lists = mysqli_fetch_assoc($result);

    for ($counter = 1; $counter < 11; $counter++) { // check next free position in list
        $ListsTableID = $Lists["ItemID{$counter}"];
        if ($ListsTableID == NULL) {
            $NextFreeTesco = $counter;
            break;
        }
    }

    
    if (isset($_POST['AddToList'])) {

        $ID = intval($_POST['ID']);
        
        //echo "<script>alert('ID = {$ID}');</script>";

        $result = $conn->query("SELECT * FROM JointItems WHERE ItemID = {$ID}");
        $JointItems = mysqli_fetch_assoc($result);

        if (isset($NextFreeTesco)  AND ($NextFreeTesco <= 11)) {
                $Absent = true;
                for ($counter = 1; $counter < 11; $counter++) { // check if its already in List
                    $ListsTableID = $Lists["ItemID{$counter}"];
                    //echo "<script>alert('Checking ItemID{$counter} : {$ListsTableID}');</script>";
                    //echo "<script>alert('Checking Joint ItemID{$counter} : {$JointItems['ItemID']}');</script>";
                    if ($ListsTableID == $JointItems['ItemID']) {
                        echo "<script>alert('Item Already in your Current List');</script>";
                        $Absent = false;
                        break;
                    }
                }
                if ($Absent) { // if not in list adds to list
                $ItemToAdd = $JointItems['ItemID'];
                    $Update = $conn->query("UPDATE Lists SET ItemID{$NextFreeTesco} = {$ItemToAdd} WHERE ListID = {$User['ListIDCurrentTesco']}");
                    if ($Update) {
                        echo "<script>alert('Added to your Current List');</script>";
                    } else {
                        echo "<script>alert('Error: " . $conn->error . "');</script>";
                    }
                }
        } else {
            echo "<script>alert('Current List Full Or Error');</script>";
        }

    }

    if (isset($_POST['RemoveFromFav'])) { //deletes  current tesco items
        $posToDelete = intval($_POST['ID']);

        for ($i = $posToDelete; $i < 10; $i++) {
            $nextItem = $FavList["ItemID" . ($i + 1)];

            $update = $conn->query("UPDATE Favorites Set ItemID{$i} = " . ($nextItem ? $nextItem : 'NULL') . " WHERE FavoriteID = {$User['FavoriteTableID']}");
        }

        $conn->query("UPDATE Favorites SET ItemID10 = NULL WHERE FavoriteID = {$User['FavoriteTableID']}");

        header("Location: " . $_SERVER['PHP_SELF']);
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
    <link rel="stylesheet" href="../Styles/Favs.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

<body class="Website"> <!--Moves the whole website to the centre-->

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.html" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <form action=""><!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        </form>
        
        <!--Buttons to move to a different page-->
        <a href="Lists.php"><i class="fa fa-navicon"></i></a>
        <a href="Favs.php"><i class="material-icons" style="font-size:55px">star</i></a>
        <a href="Profile.html"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.php"><button class="SignUpButton"> Sign Up </button></a>

    </div>

    <div class="Items" >
        <div class="Products">
            <!--<div id="items">-->
                <?php
                //echo "main";
                for ($i = 0; $i < count($FavIDs); $i++) {
                    $FavID = $FavIDs[$i];
                    //echo "enter loop";

                    $result = $conn->query("SELECT * FROM JointItems WHERE ItemID = {$FavID}");
                    $FavItem = mysqli_fetch_assoc($result);
                    //echo "pass Lidl";

                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$FavItem['TescoItemID']}");
                    $TescoItems = mysqli_fetch_assoc($result);

                    $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$FavItem['LidlItemID']}");
                    $LidlItems = mysqli_fetch_assoc($result);

                    //echo "pass tesco";

                    $ItemID = $FavItem['TescoItemID'];

                    //echo "full pass";
                    echo '

                        <div class="item"> <!--item placeholder/ base design-->

                        <div class="store">
                            <div class="tesco">
                                <div class="TescoWrap">
                                    <a></a>
                                    <span class="TescoText">Tesxo</span>
                                </div>
                            </div>
                            <div class="lidl">
                                <div class="LidlWrap">
                                    <a></a>
                                    <span class="LidlText">Lidl</span>
                                </div>
                            </div>
                        </div>

                            <a href="${productLink}"><img src="' . $TescoItems["TescoImageURL"] . '" alt="placeholder"></a>
                            <p class="name">' . $FavItem["ItemName"] . '</p>';

                            if ($LidlItems["LidlPrice"] <= $TescoItems["TescoPrice"]) {
                                echo '
                                        <div class="lower">
                                        <div class="price">
                                            <h1>£' . $LidlItems["LidlPrice"] . '</h1>
                                            <p>£' . $LidlItems["LidlPrice"] . ' - £' . $TescoItems["TescoPrice"] . '</p>
                                        </div>

                                        <div class="Buttons">
                                            <form method="POST">
                                                <input type="hidden" name="ID" value="' . $FavID . '">
                                                <button type="submit" name="AddToList" class="" value="">
                                                    <i class="fa fa-plus fa-3x" alt=""></i>
                                                </button>
                                                <button type="submit" name="RemoveFromFav" class="" value="">
                                                    <i class="fa fa-times fa-3x" alt=""></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div> 
                                </div>';
                            }
                            else {
                                echo '
                                        <div class="lower">
                                        <div class="price">
                                            <h1>£' . $TescoItems["TescoPrice"] . '</h1>
                                            <p>£' . $TescoItems["TescoPrice"] . ' - £' . $LidlItems["LidlPrice"] . '</p>
                                        </div>

                                        <div class="Buttons">
                                            <form method="POST">
                                            <input type="hidden" name="ID" value="' . $FavID . '">
                                                <button type="submit" name="AddToList" id="" value="">
                                                    <i class="fa fa-plus fa-3x" alt=""></i>
                                                </button>
                                                <button type="submit" name="RemoveFromFav" class="" value="">
                                                    <i class="fa fa-times fa-3x" alt=""></i>
                                                </button>
                                            </form>
                                        </div>

                                    </div> 
                                </div>';
                            }

                //    echo 'fav ID '.$FavIDs[$i];
                }
                
                

                    ?>
                
                
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
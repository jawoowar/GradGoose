<?php

    session_start();

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $conn = mysqli_connect('ysjcs.net', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    //$uid = 1; 
    if (isset($_SESSION['UserID'])) {
        $uid = $_SESSION['UserID'];
        $usr = $_SESSION['Username'];
    } else {
        header("Location: Login.php");
        exit();
    }

    $result = $conn->query("SELECT * FROM UserID WHERE UserID = {$uid}");
    $User = mysqli_fetch_assoc($result);

    //$id = 1;
    //$id = $_GET["id"];
    $id = isset($_GET["id"]) ? intval($_GET["id"]) : 1;
    $result = $conn->query("SELECT * FROM JointItems WHERE ItemID = {$id}");
    $JointItems = mysqli_fetch_assoc($result);

    $TescoID = $JointItems['TescoItemID'];
    $LidlID = $JointItems['LidlItemID'];

    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$TescoID}");
    $TescoItems = mysqli_fetch_assoc($result);

    if ($TescoItems['Vegan'] == 1 AND $TescoItems['Vegatarian'] == 1) {
        $vegVeanStatus = "Veg/Vegan";
    } else if ($TescoItems['Vegan'] == 0 AND $TescoItems['Vegatarian'] == 1) {
        $vegVeanStatus = "Vegetarian";
    } else {
        $vegVeanStatus = "Non-Vegetarian";
    }


    $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$LidlID}");
    $LidlItems = mysqli_fetch_assoc($result);

    $WantedCatagoryID = $TescoItems['Catagory'];
    //echo "Searching for CatagoryID: " . $WantedCatagoryID . "<br>";
    $result = $conn->query("SELECT * FROM Catagories WHERE CatagoryID = {$WantedCatagoryID}");
    $Catagory = mysqli_fetch_assoc($result);

    //echo $Catagory['Catagory'];


    $result = $conn->query("SELECT * FROM Favorites WHERE FavoriteId = {$User['FavoriteTableID']}");
    $Favorites = mysqli_fetch_assoc($result);

    //Adding to FavoriteTable
   for ($counter = 1; $counter < 11; $counter++) {
        $FavoriteTableID = $Favorites["ItemID{$counter}"];
        if ($FavoriteTableID == NULL) {
            $NextFree = $counter;
            break;
        }
    }

    if (isset($_POST['addFavs'])) {
        if (isset($NextFree)  AND ($NextFree <= 11)) {
                $Absent = true;
                for ($counter = 1; $counter < 11; $counter++) {
                    $FavoriteTableID = $Favorites["ItemID{$counter}"];
                    if ($FavoriteTableID == $JointItems['ItemID']) {
                        echo "<script>alert('Item Already in Favorites');</script>";
                        $Absent = false;
                        break;
                    }
                }
                if ($Absent) {
                    $Update = $conn->query("UPDATE Favorites SET ItemID{$NextFree} = {$TescoID} WHERE FavoriteId = {$User['FavoriteTableID']}");
                    if ($Update) {
                        echo "<script>alert('Added to Favorites');</script>";
                    } else {
                        echo "<script>alert('Error: " . $conn->error . "');</script>";
                    }
                }
        } else {
            echo "<script>alert('Favorites Full Or Error');</script>";
        }

    }

    $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['ListIDCurrentTesco']}");
    $Lists = mysqli_fetch_assoc($result);

    //Adding to list Tesco
   for ($counter = 1; $counter < 11; $counter++) { // check next free position in list
        $ListsTableID = $Lists["ItemID{$counter}"];
        if ($ListsTableID == NULL) {
            $NextFreeTesco = $counter;
            break;
        }
    }

    if (isset($_POST['addListTesco'])) {
        if (isset($NextFreeTesco)  AND ($NextFreeTesco <= 11)) {
                $Absent = true;
                for ($counter = 1; $counter < 11; $counter++) { // check if its already in List
                    $ListsTableID = $Lists["ItemID{$counter}"];
                    if ($ListsTableID == $JointItems['ItemID']) {
                        echo "<script>alert('Item Already in your Current List');</script>";
                        $Absent = false;
                        break;
                    }
                }
                if ($Absent) { // if not in list adds to list
                    $Update = $conn->query("UPDATE Lists SET ItemID{$NextFreeTesco} = {$TescoID} WHERE ListID = {$User['ListIDCurrentTesco']}");
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


    $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['ListIDCurrentLidl']}");
    $Lists = mysqli_fetch_assoc($result);

    //Adding to list Lidl
   for ($counter = 1; $counter < 11; $counter++) { // checks next free position in Lidl list
        $ListsTableID = $Lists["ItemID{$counter}"];
        if ($ListsTableID == NULL) {
            $NextFreeLidl = $counter;
            break;
        }
    }

    if (isset($_POST['addListLidl'])) { 
        if (isset($NextFreeLidl)  AND ($NextFreeLidl <= 11)) {
                $Absent = true;
                for ($counter = 1; $counter < 11; $counter++) { // check if already in list/ list full
                    $ListsTableID = $Lists["ItemID{$counter}"];
                    if ($ListsTableID == $JointItems['ItemID']) {
                        echo "<script>alert('Item Already in your Current List');</script>";
                        $Absent = false;
                        break;
                    }
                }
                if ($Absent) { // if allowed adds to list
                    $Update = $conn->query("UPDATE Lists SET ItemID{$NextFreeLidl} = {$LidlID} WHERE ListID = {$User['ListIDCurrentLidl']}");
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


    $discount = $TescoItems['TescoStudentDiscountID'];
    echo "discount : "  . $discount;
    $result = $conn->query("SELECT * FROM StudentDiscount WHERE DiscountID = {$discount}");
    $TescoStudentDiscount = mysqli_fetch_assoc($result);

    $discount = $TescoItems['TescoStudentBeanDiscountID'];
    $result = $conn->query("SELECT * FROM StudentBeanDiscount WHERE DiscountID = {$discount}");
    $TescoStudentBeanDiscount = mysqli_fetch_assoc($result);

    $discount = $TescoItems['TescoStoreDiscountID'];
    $result = $conn->query("SELECT * FROM TescoStoreDiscount WHERE DiscountID = {$discount}");
    $TescoStoreDiscount = mysqli_fetch_assoc($result);
    

    $discount = $LidlItems['LidlStudentDiscountID'];
    $result = $conn->query("SELECT * FROM StudentDiscount WHERE DiscountID = {$discount}");
    $LidlStudentDiscount = mysqli_fetch_assoc($result);

    $discount = $LidlItems['LidlStudentBeanDiscountID'];
    $result = $conn->query("SELECT * FROM StudentBeanDiscount WHERE DiscountID = {$discount}");
    $LidlStudentBeanDiscount = mysqli_fetch_assoc($result);

    $discount = $LidlItems['LidlStoreDiscountID'];
    $result = $conn->query("SELECT * FROM LidlStoreDiscount WHERE DiscountID = {$discount}");
    $LidlStoreDiscount = mysqli_fetch_assoc($result);
        
?>


<!DOCTYPE html>
<html>
    <head>
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
    <link rel="stylesheet" href="../Styles/ProductPage.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.php" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <form action=""><!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        </form>
        
        <!--Buttons to move to a different page-->
        <a href="Lists.php"><i class="fa fa-navicon"></i></a>
        <a href="Favs.php"><i class="material-icons" style="font-size:48px">star</i></a>
        <a href="Profile.php"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.php"><button class="SignUpButton"> Sign In </button></a>

    </div>

<body class="Website"> <!--Moves the whole website to the centre-->
    <div class="productDisplay">
        <div class="basicInfo">

            

            <div class="imageContainer" id="slideshow">
                <img src="<?php echo $TescoItems['TescoImageURL']; ?>" class="productImage" alt="Product Image">
            </div>

            <h1><?php echo $JointItems['ItemName']; ?></h1>

        </div>


        <!-- product information perstore -->

        <div class="productInfo">
            <div class="storeSpesifics tesco">
                <div class="informationContainer">
                    <div class="dealsContainer">
                        <div class="deal tesco"> <h2>                        
                            <?php
                            if ($TescoStudentDiscount['Discount'] !== Null) {
                                echo $TescoStudentDiscount['Discount'];
                            }
                            elseif ($TescoStudentDiscount['Criteria'] !== Null) {
                                echo $TescoStudentDiscount['Criteria'];
                            }
                            ?>
                        </h2> </div>
                        <div class="deal tesco"> <h2>
                            <?php
                                if ($TescoStoreDiscount['Discount'] !== Null) {
                                    echo $TescoStoreDiscount['Discount'];
                                }
                                elseif ($TescoStoreDiscount['Criteria'] !== Null) {
                                    echo $TescoStoreDiscount['Criteria'];
                                }
                            ?>
                        </h2> </div>
                        <div class="deal tesco"> <h2>
                            <?php
                                if ($TescoStudentBeanDiscount['Discount'] !== Null) {
                                    echo $TescoStudentBeanDiscount['Discount'];
                                }
                                if ($TescoStudentBeanDiscount['Discount'] !== Null && $TescoStudentBeanDiscount['Criteria'] !== Null) {
                                    echo "<br>";
                                }
                                if ($TescoStudentBeanDiscount['Criteria'] !== Null) {
                                    echo $TescoStudentBeanDiscount['Criteria'];
                                }
                            ?>
                        </h2> </div>

                    </div>
                    
                    <div class="verticalLine tesco"></div>

                    <div class="productInfoContainer">

                        <div class="info tesco"> <h2><?php echo $Catagory['Catagory']; ?></h2> </div>
                        <div class="info tesco"> <h2><?php echo $vegVeanStatus; ?></h2> </div>
                        <div class="info tesco"> <h2><?php echo $TescoItems['Quantity']; ?></h2> </div>

                    </div>
                    <div class="productInfoContainer" style="margin-left: 20px;">

                        <div class="info tesco"> <h2><?php echo $TescoItems['Poundage']?>g</h2> </div>
                        <div class="info tesco"> <h2><?php echo $TescoItems['TescoRating']?>/5</h2> </div>
                        <div class="ingrediantInfo tesco"> <h2><?php echo $TescoItems['Ingrediants'] ?></h2> </div>

                    </div>
                </div>
                <div class="bottom">
                        <p>£<?php echo $TescoItems['TescoPrice']; ?></p>
                    <div class="buttonContainer">
                        <form method="POST"><button type="submit" name="addListTesco"><i class="fa fa-plus fa-3x tescoIcons"></i></button></form>
                        <form method="POST"><button type="submit" name="addFavs"><i class="fa fa-star fa-3x tescoIcons"></i></button></form>
                    </div>
                </div>

            </div>

            <hr>
            <div class="storeSpesifics lidl">
                <div class="informationContainer ">
                    <div class="dealsContainer">

                        <div class="deal lidl"> <h2>                        
                            <?php
                            if ($LidlStudentDiscount['Discount'] !== Null) {
                                echo $LidlStudentDiscount['Discount'];
                            }
                            elseif ($LidlStudentDiscount['Criteria'] !== Null) {
                                echo $LidlStudentDiscount['Criteria'];
                            }
                            ?>
                        </h2> </div>
                        <div class="deal lidl"> <h2>
                            <?php
                                if ($LidlStoreDiscount['Discount'] !== Null) {
                                    echo $LidlStoreDiscount['Discount'];
                                }
                                elseif ($LidlStoreDiscount['Criteria'] !== Null) {
                                    echo $LidlStoreDiscount['Criteria'];
                                }
                            ?>
                        </h2> </div>
                        <div class="deal lidl"> <h2>
                            <?php
                                if ($LidlStudentBeanDiscount['Discount'] !== Null) {
                                    echo $LidlStudentBeanDiscount['Discount'];
                                }
                                if ($LidlStudentBeanDiscount['Discount'] !== Null && $LidlStudentBeanDiscount['Criteria'] !== Null) {
                                    echo "<br>";
                                }
                                if ($LidlStudentBeanDiscount['Criteria'] !== Null) {
                                    echo $LidlStudentBeanDiscount['Criteria'];
                                }
                            ?>
                        </h2> </div>

                    </div>
                    
                    <div class="verticalLine lidl"></div>

                    <div class="productInfoContainer">

                        <div class="info lidl"> <h2><?php echo $Catagory['Catagory']; ?></h2> </div>
                        <div class="info lidl"> <h2><?php echo $vegVeanStatus; ?></h2> </div>
                        <div class="info lidl"> <h2><?php echo $LidlItems['Quantity']; ?></h2> </div>

                    </div>
                    <div class="productInfoContainer" style="margin-left: 20px;">

                        <div class="info lidl"> <h2><?php echo $LidlItems['Poundage']?>g</h2> </div>
                        <div class="info lidl"> <h2><?php echo $LidlItems['LidlRating']?>/5</h2> </div>
                        <div class="ingrediantInfo lidl"> <h2><?php echo $LidlItems['Ingrediants']?></h2> </div>

                    </div>
                </div>
                <div class="bottom">
                        <p>£<?php echo $LidlItems['LidlPrice']; ?></p>
                    <div class="buttonContainer">
                        <form method="POST"><button type="submit" name="addListLidl"><i class="fa fa-plus fa-3x lidlIcons"></i></button></form>
                        <form method="POST"><button type="submit" name="addFavs"><i class="fa fa-star fa-3x lidlIcons"></i></button></form>
                    </div>
                </div>

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

</html>


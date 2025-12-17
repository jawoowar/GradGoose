<!DOCTYPE html>

<?php
    $conn = mysqli_connect('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    $UID = 1;
    $result = $conn->query("SELECT * FROM UserID WHERE UserID = {$UID}");
    $User = mysqli_fetch_assoc($result);

    $listID = intval($User['ListIDCurrentTesco']);
    $result = $conn->query("SELECT * FROM Lists WHERE ListID = $listID");
    $TescoCurrentLists = mysqli_fetch_assoc($result);

    $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['pastList1TescoID']}");
    $TescoPastList1 = mysqli_fetch_assoc($result);

    $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['ListIDCurrentLidl']}");
    $LidlCurrentLists = mysqli_fetch_assoc($result);

    $TescoListIDs = [];
    for ($counter = 1; $counter < 11; $counter++) { // puts contence of list into array
        $ListsTableID = $TescoCurrentLists["ItemID{$counter}"];
        if ($ListsTableID != NULL) {
            $TescoListIDs[] = $ListsTableID;
        } else {
            break;
        }
    }

    $LidlListIDs = [];
    for ($counter = 1; $counter < 11; $counter++) { // puts contence of list into array
        $ListsTableID = $LidlCurrentLists["ItemID{$counter}"];
        if ($ListsTableID != NULL) {
            $LidlListIDs[] = $ListsTableID;
        } else {
            break;
        }
    }

    
    $TescoPastList1IDs = [];
    for ($counter = 1; $counter < 11; $counter++) { // puts contence of list into array
        $ListsTableID = $TescoPastList1["ItemID{$counter}"];
        if ($ListsTableID != NULL) {
            $TescoPastList1IDs[] = $ListsTableID;
        } else {
            break;
        }
    }

    $pastListCount = 1;
    for ($counter = 1; $counter < 4; $counter++) {
        if (!empty($User["pastList{$counter}LidlID"]) OR !empty($User["pastList{$counter}TescoID"])) {
            $pastListCount++;
        } 
    }

    //echo $pastListCount;
    //echo "</br>";


    if (isset($_POST['binItemTesco'])) { //deletes  current tesco items
        $posToDelete = intval($_POST['binItemTesco']);

        for ($i = $posToDelete; $i < 10; $i++) {
            $nextItem = $TescoCurrentLists["ItemID" . ($i + 1)];

            $update = $conn->query("UPDATE Lists Set ItemID{$i} = " . ($nextItem ? $nextItem : 'NULL') . " WHERE ListID = {$User['ListIDCurrentTesco']}");
        }

        $conn->query("UPDATE Lists SET ItemID10 = NULL WHERE ListID = {$User['ListIDCurrentTesco']}");

        header("Location: " . $_SERVER['PHP_SELF']);
    }



    if (isset($_POST['binItemLidl'])) { //deletes current lidl items
        $posToDelete = intval($_POST['binItemLidl']);

        for ($i = $posToDelete; $i < 10; $i++) {
            $nextItem = $LidlCurrentLists["ItemID" . ($i + 1)];

            $update = $conn->query("UPDATE Lists Set ItemID{$i} = " . ($nextItem ? $nextItem : 'NULL') . " WHERE ListID = {$User['ListIDCurrentLidl']}");
        }

        $conn->query("UPDATE Lists SET ItemID10 = NULL WHERE ListID = {$User['ListIDCurrentLidl']}");

        header("Location: " . $_SERVER['PHP_SELF']);
    }

    $TescoCurrentTotal = 0;
    for ($counter = 0; $counter < count($TescoListIDs); $counter++) {
        $result = $conn->query("SELECT * FROM TescoItems Where TescoItemID = {$TescoListIDs[$counter]}");
        $TescoPrices = mysqli_fetch_assoc($result);
        $TescoCurrentTotal = $TescoCurrentTotal + ($TescoPrices['TescoPrice']);
    }

    $LidlCurrentTotal = 0;
    for ($counter = 0; $counter < count($LidlListIDs); $counter++) {
        $result = $conn->query("SELECT * FROM LidlItems Where LidlItemID = {$LidlListIDs[$counter]}");
        $LidlPrices = mysqli_fetch_assoc($result);
        $LidlCurrentTotal = $LidlCurrentTotal + ($LidlPrices['LidlPrice']);
    }

    $TescoPastTotal1 = 0;
    for ($counter = 0; $counter < count($TescoPastList1IDs); $counter++) {
        $Id = $TescoPastList1IDs[$counter];
        $result = $conn->query("SELECT * FROM TescoItems Where TescoItemID = {$Id}");
        $TescoPrices = mysqli_fetch_assoc($result);
        $TescoPastTotal1 = $TescoPastTotal1 + ($TescoPrices['TescoPrice']);
    }

    /*
    // displays contence of arrays for testing
    for ($counter = 0; $counter < count($TescoListIDs); $counter++) {
        echo $TescoListIDs[$counter];
    }
    echo "<br>";
    for ($counter = 0; $counter < count($LidlListIDs); $counter++) {
        echo $LidlListIDs[$counter];
    }
        */

?>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        
        <link rel="stylesheet" href="../Styles/Root.css">
        <link rel="stylesheet" href="../Styles/Items.css">
        <link rel="stylesheet" href="../Styles/Lists.css">
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
        </style>

        <title>GradGoose</title>
    </head>

<body class="Website"> <!--Moves the whole website to the centre-->

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.html" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <form action=""><!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        </form>
        
        <!--Buttons to move to a different page-->
        <a href="Lists.html"><i class="fa fa-navicon"></i></a>
        <a href="Favs.html"><i class="material-icons" style="font-size:48px">star</i></a>
        <a href="Profile.html"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.html"><button class="SignUpButton"> Sign Up </button></a>

    </div>


    <main>
        
        <div class="mainLists">
        
            <div class="list">

                <div class="tesco"></div>

                    <div class="listContent">

                        <div class="items">

                            <?php

                                for ($counter = 0; $counter < count($TescoListIDs); $counter++) {
                                    $TescoID = $TescoListIDs[$counter];
                                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$TescoID}");
                                    $TescoItems = mysqli_fetch_assoc($result);
                                    $ItemID = $TescoItems['TescoItemID'];


                                    echo 
                                        '<div class="itemAlt"> <!--item placeholder/ base design-->

                                            <img src="' . $TescoItems['TescoImageURL'] . '" alt="placeholder">

                                            <aside>
                                                <p class="name">
                                                    ' . $TescoItems['TescoItemName'] . '
                                                </p>

                                                <div class="LowerListItem">

                                                    <div class="price">

                                                        <h1>£' . $TescoItems['TescoPrice'] . '</h1>
                                                        
                                                    </div>

                                                    <div class="Buttons">

                                                        <i class="fa fa-close" style="font-size:36px"></i>
                                                        <i class="fa fa-check" style="font-size:36px"></i>
                                                        <a href="Product.php?id=' . $ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                        <form method="POST">
                                                            <button  type="submit" name="binItemTesco" class="Bin" value="' . ($counter + 1) . '">
                                                                <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                            </button>
                                                        </form>

                                                    </div>

                                                </div>
                                                    
                                            </aside>
                                        </div>';

                                    }

                            ?>
                        </div>

                        <div class="prices">
                            <h2>Total: £<?php echo number_format($TescoCurrentTotal, 2) ?></h2>
                            </div>
                    </div>
                </div>

                <div class="list">
                    <div class="lidl"></div>
                    <div class="listContent">
                        <div class="items">

                                    <?php

                                    for ($counter = 0; $counter < count($LidlListIDs); $counter++) {
                                        $LidlID = $LidlListIDs[$counter];
                                        $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$LidlID}");
                                        $LidlItems = mysqli_fetch_assoc($result);
                                        $ItemID = $LidlItems['LidlItemID'];


                                        echo 
                                            '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                <img src="' . $LidlItems['LidlImageURL'] . '" alt="placeholder">

                                                <aside>
                                                    <p class="name">
                                                        ' . $LidlItems['LidlItemName'] . '
                                                    </p>

                                                    <div class="LowerListItem">

                                                        <div class="price">

                                                            <h1>£' . $LidlItems['LidlPrice'] . '</h1>
                                                            
                                                        </div>

                                                        <div class="Buttons">

                                                            <i class="fa fa-close" style="font-size:36px"></i>
                                                            <i class="fa fa-check" style="font-size:36px"></i>
                                                            <a href="Product.php?id=' . $ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                            <form method="POST">
                                                                <button  type="submit" name="binItemLidl" class="Bin" value="' . ($counter + 1) . '">
                                                                    <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                </button>
                                                            </form>

                                                        </div>

                                                    </div>
                                                        
                                                </aside>
                                            </div>';

                                        }

                                ?>
                        </div>
                        <div class="prices">
                            <h2>Total: £<?php echo number_format($LidlCurrentTotal, 2) ?></h2>
                        </div>
                    </div>
                </div>
            </div>


        <div class="listHeader">
            <a></a>
            <h1>Past Lists</h1>
            <a></a>
            <div class="listCount"><?php echo $pastListCount; ?>/3</div>
        </div>
        
        <div class="mainLists">
            
            <div class="list">
                <div class="tesco"></div>
                <div class="listContent">
                <div class="items">

                    <?php

                                for ($counter = 0; $counter < count($TescoPastList1IDs); $counter++) {
                                    $pastTesco1ID = $TescoPastList1IDs[$counter];
                                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$pastTesco1ID}");
                                    $TescoPastItems1 = mysqli_fetch_assoc($result);
                                    $ItemID = $TescoPastItems1['TescoItemID'];


                                    echo 
                                        '<div class="itemAlt"> <!--item placeholder/ base design-->

                                            <img src="' . $TescoPastItems1['TescoImageURL'] . '" alt="placeholder">

                                            <aside>
                                                <p class="name">
                                                    ' . $TescoPastItems1['TescoItemName'] . '
                                                </p>

                                                <div class="LowerListItem">

                                                    <div class="price">

                                                        <h1>£' . $TescoPastItems1['TescoPrice'] . '</h1>
                                                        
                                                    </div>

                                                    <div class="Buttons">

                                                        <i class="fa fa-close" style="font-size:36px"></i>
                                                        <i class="fa fa-check" style="font-size:36px"></i>
                                                        <a href="Product.php?id=' . $ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                        <form method="POST">
                                                            <button  type="submit" name="binItemTesco" class="Bin" value="' . ($counter + 1) . '">
                                                                <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                            </button>
                                                        </form>

                                                    </div>

                                                </div>
                                                    
                                            </aside>
                                        </div>';

                                    }

                            ?>
                </div>

                <div class="prices">
                    <h2>Total: £<?php echo number_format($TescoPastTotal1, 2) ?></h2>
                </div>
                </div>
            </div>

            <div class="list">
                <div class="lidl"></div>
                <div class="listContent">
                <div class="items">
                    <div class="itemAlt"> <!--item placeholder/ base design-->

                                <img src="../Media/PlaceHolder.png" alt="placeholder">

                                <aside>
                                    <p class="name">
                                        Item Name Item Name
                                    </p>

                                    <div class="LowerListItem">

                                        <div class="price">

                                            <h1>£00.00</h1>
                                            
                                        </div>

                                        <div class="Buttons">

                                            <i class="fa fa-close" style="font-size:36px"></i>
                                            <i class="fa fa-check" style="font-size:36px"></i>
                                            <i class="fa fa-info-circle" style="font-size:36px"></i>
                                            <i class="fa fa-trash-o" style="font-size:36px"></i>

                                        </div>

                                    </div>
                                        
                                </aside>
                            </div>

                            <div class="itemAlt"> <!--item placeholder/ base design-->

                                <img src="../Media/PlaceHolder.png" alt="placeholder">

                                <aside>
                                    <p class="name">
                                        Item Name Item Name
                                    </p>

                                    <div class="LowerListItem">

                                        <div class="price">

                                            <h1>£00.00</h1>
                                            
                                        </div>

                                        <div class="Buttons">

                                            <i class="fa fa-close" style="font-size:36px"></i>
                                            <i class="fa fa-check" style="font-size:36px"></i>
                                            <i class="fa fa-info-circle" style="font-size:36px"></i>
                                            <i class="fa fa-trash-o" style="font-size:36px"></i>

                                        </div>

                                    </div>
                                        
                                </aside>
                            </div>

                            <div class="itemAlt"> <!--item placeholder/ base design-->

                                <img src="../Media/PlaceHolder.png" alt="placeholder">

                                <aside>
                                    <p class="name">
                                        Item Name Item Name
                                    </p>

                                    <div class="LowerListItem">

                                        <div class="price">

                                            <h1>£00.00</h1>
                                            
                                        </div>

                                        <div class="Buttons">

                                            <i class="fa fa-close" style="font-size:36px"></i>
                                            <i class="fa fa-check" style="font-size:36px"></i>
                                            <i class="fa fa-info-circle" style="font-size:36px"></i>
                                            <i class="fa fa-trash-o" style="font-size:36px"></i>

                                        </div>

                                    </div>
                                        
                                </aside>
                            </div>
                </div> 
                <div class="prices">
                    <h2>Total: £00.00</h2>
                </div>
            </div>
            </div>

        </div>

        <div class="mainLists">
            <h1>No list found</h1>
        </div>

    </main>

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
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $dbName = "jenniferwoodward_GradGoose";
    $conn = new mysqli("ysjcs.net", "jennifer.w", "EHEXYUE8",$dbName);
    
    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
        
    }

    $UID = 1;
    //echo $UID;
    $result = $conn->query("SELECT * FROM UserID WHERE UserID = {$UID}");
    $User = mysqli_fetch_assoc($result);

    $listID = intval($User['ListIDCurrentTesco']);
    $result = $conn->query("SELECT * FROM Lists WHERE ListID = $listID");
    $TescoCurrentLists = mysqli_fetch_assoc($result);

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

    //echo"<br>".count($TescoListIDs)."";

    $LidlListIDs = [];
    for ($counter = 1; $counter < 11; $counter++) { // puts contence of list into array
        $ListsTableID = $LidlCurrentLists["ItemID{$counter}"];
        if ($ListsTableID != NULL) {
            $LidlListIDs[] = $ListsTableID;
        } else {
            break;
        }
    }

    //echo"<br>".count($LidlListIDs)."";

    $pastListCount = 0;
    for ($counter = 1; $counter < 4; $counter++) {
        //echo $User["pastList{$counter}LidlID"];
        //echo "<br>";
        //echo "pastList{$counter}LidlID";
        //echo "<br>";
        if (!empty($User["PastList{$counter}LidlID"]) OR !empty($User["PastList{$counter}TescoID"])) {
            $pastListCount++;
        } 
    }
    //echo"<br>".$pastListCount."";

    if ($pastListCount >= 1) {
        $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList1LidlID']}");
        $PastList1Lidl = mysqli_fetch_assoc($result);
        
        $LidlPastList1ItemIDs = [];
        for ($counter = 1; $counter < 11; $counter++) { // puts content of list into array
            $PastList1LidlItem = $PastList1Lidl["ItemID{$counter}"];
            if ($PastList1LidlItem != NULL) {
                $LidlPastList1ItemIDs[] = $PastList1LidlItem;
            } else {
                break;
            }
        }
        
        $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList1TescoID']}");
        $PastList1Tesco = mysqli_fetch_assoc($result);
        
        $TescoPastList1ItemIDs = [];
        for ($counter = 1; $counter < 11; $counter++) { // puts content of list into array
            $PastList1TescoItem = $PastList1Tesco["ItemID{$counter}"];
            if ($PastList1TescoItem != NULL) {
                $TescoPastList1ItemIDs[] = $PastList1TescoItem;
            } else {
                break;
            }
        }
    }

    //echo $User["PastList1LidlID"];
    //echo "<br>";
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

        if ($pastListCount >= 1) {
            $TescoPastList1Total = 0;
            for ($counter = 0; $counter < count($TescoPastList1ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM TescoItems Where TescoItemID = {$TescoPastList1ItemIDs[$counter]}");
                $TescoPrices = mysqli_fetch_assoc($result);
                $TescoPastList1Total = $TescoPastList1Total + ($TescoPrices['TescoPrice']);
            }

            $LidlPastList1Total = 0;
            for ($counter = 0; $counter < count($LidlPastList1ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM LidlItems Where LidlItemID = {$LidlPastList1ItemIDs[$counter]}");
                $LidlPrices = mysqli_fetch_assoc($result);
                $LidlPastList1Total = $LidlPastList1Total + ($LidlPrices['LidlPrice']);
            }
        }
//echo "pastListCount {$pastListCount}";


        if ($pastListCount >= 2) {

            $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList2LidlID']}");
            $PastList2LidlItems = mysqli_fetch_assoc($result);

            $LidlPastList2ItemIDs = [];
            for ($counter = 1; $counter < 11; $counter++) {
                $PastList2LidlItem = $PastList2LidlItems["ItemID{$counter}"];
                if ($PastList2LidlItem != NULL) {
                    $LidlPastList2ItemIDs[] = $PastList2LidlItem;
                } else {
                    break;
                }
            }
            $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList2TescoID']}");
            $PastList2TescoItems = mysqli_fetch_assoc($result);

            $TescoPastList2ItemIDs = [];
            for ($counter = 1; $counter < 11; $counter++) {
                $PastList2TescoItem = $PastList2TescoItems["ItemID{$counter}"];
                if ($PastList2TescoItem != NULL) {
                    $TescoPastList2ItemIDs[] = $PastList2TescoItem;
                } else {
                    break;
                }
            }

            $TescoPastList2Total = 0;
            for ($counter = 0; $counter < count($TescoPastList2ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM TescoItems Where TescoItemID = {$TescoPastList2ItemIDs[$counter]}");
                $TescoPrices = mysqli_fetch_assoc($result);
                $TescoPastList2Total = $TescoPastList2Total + ($TescoPrices['TescoPrice']);
            }

            $LidlPastList2Total = 0;
            for ($counter = 0; $counter < count($LidlPastList2ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM LidlItems Where LidlItemID = {$LidlPastList2ItemIDs[$counter]}");
                $LidlPrices = mysqli_fetch_assoc($result);
                $LidlPastList2Total = $LidlPastList2Total + ($LidlPrices['LidlPrice']);
            }
        }

        if ($pastListCount >= 3) {

            $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList3LidlID']}");
            $PastList3LidlItems = mysqli_fetch_assoc($result);

            $LidlPastList3ItemIDs = [];
            for ($counter = 1; $counter < 11; $counter++) {
                $PastList3LidlItem = $PastList3LidlItems["ItemID{$counter}"];
                if ($PastList3LidlItem != NULL) {
                    $LidlPastList3ItemIDs[] = $PastList3LidlItem;
                } else {
                    break;
                }
            }
            $result = $conn->query("SELECT * FROM Lists WHERE ListID = {$User['PastList3TescoID']}");
            $PastList3TescoItems = mysqli_fetch_assoc($result);

            $TescoPastList3ItemIDs = [];
            for ($counter = 1; $counter < 11; $counter++) {
                $PastList3TescoItem = $PastList3TescoItems["ItemID{$counter}"];
                if ($PastList3TescoItem != NULL) {
                    $TescoPastList3ItemIDs[] = $PastList3TescoItem;
                } else {
                    break;
                }
            }

            $TescoPastList3Total = 0;
            for ($counter = 0; $counter < count($TescoPastList3ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM TescoItems Where TescoItemID = {$TescoPastList3ItemIDs[$counter]}");
                $TescoPrices = mysqli_fetch_assoc($result);
                $TescoPastList3Total = $TescoPastList3Total + ($TescoPrices['TescoPrice']);
            }

            $LidlPastList3Total = 0;
            for ($counter = 0; $counter < count($LidlPastList3ItemIDs); $counter++) {
                $result = $conn->query("SELECT * FROM LidlItems Where LidlItemID = {$LidlPastList3ItemIDs[$counter]}");
                $LidlPrices = mysqli_fetch_assoc($result);
                $LidlPastList3Total = $LidlPastList3Total + ($LidlPrices['LidlPrice']);
            }

        }

    if (isset($_POST['SwapToPast'])) { //deletes  current tesco items
        $NewListTesco = $conn->query("INSERT INTO Lists Values (0, CURRENT_TIMESTAMP, 'Tesco', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
        $NewTescoID = $conn->insert_id;
        $NewListLidl = $conn->query("INSERT INTO Lists Values (0, CURRENT_TIMESTAMP, 'Lidl', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
        $NewLidlID = $conn->insert_id;

        $ListToSwapTesco = $User['ListIDCurrentTesco'];
        $ListToSwapLidl = $User['ListIDCurrentLidl'];
        $ListToSwapTesco2 = $User['PastList1TescoID'];
        $ListToSwapLidl2 = $User['PastList1LidlID'];
        $ListToSwapTesco3 = $User['PastList2TescoID'];
        $ListToSwapLidl3 = $User['PastList2LidlID'];

        

        $conn->query("UPDATE UserID Set
            PastList3TescoID = " . (!empty($User['PastList2TescoID']) ? intval($User['PastList2TescoID']) : 'NULL') . ",
            PastList3LidlID = " . (!empty($User['PastList2LidlID']) ? intval($User['PastList2LidlID']) : 'NULL') . ",
            PastList2TescoID = " . (!empty($User['PastList1TescoID']) ? intval($User['PastList1TescoID']) : 'NULL') . ",
            PastList2LidlID = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . ",
            PastList1TescoID = " . intval($User['ListIDCurrentTesco']) . ",
            PastList1LidlID = " . intval($User['ListIDCurrentLidl']). ",
            ListIDCurrentTesco = $NewTescoID,
            ListIDCurrentLidl = $NewLidlID
            WHERE UserID = " . intval($UID));

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

        if (!$result) {
            die("UPDATE failed: " . $conn->error . "<br><br>Query: " . $updateSQL);
       }

    }

     if (isset($_POST['MakeCurrent'])) { //deletes  current tesco items

        $ChangeID = intval($_POST['ChangeID']);

        $ListToSwapTesco = $User['ListIDCurrentTesco'];
        $ListToSwapLidl = $User['ListIDCurrentLidl'];
        $ListToSwapTesco2 = $User['PastList1TescoID'];
        $ListToSwapLidl2 = $User['PastList1LidlID'];
        $ListToSwapTesco2 = $User['PastList2TescoID'];
        $ListToSwapLidl2 = $User['PastList2LidlID'];
        $ListToSwapTesco3 = $User['PastList3TescoID'];
        $ListToSwapLidl3 = $User['PastList3LidlID'];

        if ($ChangeID == 1) {
            $conn->query("UPDATE UserID Set

                PastList1TescoID = " . intval($User['ListIDCurrentTesco']) . ",
                PastList1LidlID = " . intval($User['ListIDCurrentLidl']). ",
                ListIDCurrentTesco = " . (!empty($User['PastList1TescoID']) ? intval($User['PastList1TescoID']) : 'NULL') . ",
                ListIDCurrentLidl = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . "
                WHERE UserID = " . intval($UID));

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

            if (!$result) {
                die("UPDATE failed: " . $conn->error . "<br><br>Query: " . $updateSQL);
            }
        }
        
        if ($ChangeID == 2) {
            $conn->query("UPDATE UserID Set

                PastList3TescoID = " . (!empty($User['PastList2TescoID']) ? intval($User['PastList2TescoID']) : 'NULL') . ",
                PastList3LidlID = " . (!empty($User['PastList2LidlID']) ? intval($User['PastList2LidlID']) : 'NULL') . ",
                PastList2TescoID = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . ",
                PastList2LidlID = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . " ,
                PastList1TescoID = " . intval($User['ListIDCurrentTesco']) . ",
                PastList1LidlID = " . intval($User['ListIDCurrentLidl']). ",
                ListIDCurrentTesco = " . (!empty($User['PastList3TescoID']) ? intval($User['PastList3TescoID']) : 'NULL') . ",
                ListIDCurrentLidl = " . (!empty($User['PastList3LidlID']) ? intval($User['PastList3LidlID']) : 'NULL') . "
                WHERE UserID = " . intval($UID));

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

            if (!$result) {
                die("UPDATE failed: " . $conn->error . "<br><br>Query: " . $updateSQL);
            }
        }

        if ($ChangeID == 2) {
            $conn->query("UPDATE UserID Set

                PastList2TescoID = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . ",
                PastList2LidlID = " . (!empty($User['PastList1LidlID']) ? intval($User['PastList1LidlID']) : 'NULL') . " ,
                PastList1TescoID = " . intval($User['ListIDCurrentTesco']) . ",
                PastList1LidlID = " . intval($User['ListIDCurrentLidl']). ",
                ListIDCurrentTesco = " . (!empty($User['PastList2TescoID']) ? intval($User['PastList2TescoID']) : 'NULL') . ",
                ListIDCurrentLidl = " . (!empty($User['PastList2LidlID']) ? intval($User['PastList2LidlID']) : 'NULL') . "
                WHERE UserID = " . intval($UID));

            header("Location: " . $_SERVER['PHP_SELF']);
            exit();

            if (!$result) {
                die("UPDATE failed: " . $conn->error . "<br><br>Query: " . $updateSQL);
            }
        }

    }

    
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
                            if (count($TescoListIDs) == 0) {
                                echo '<h1>No items in list</h1>';
                            }
                            else {

                            

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

                                    if (count($LidlListIDs) == 0) {
                                        echo '<h1>No items in list</h1>';
                                    }
                                    else {

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
            <div class="listCount"><?php echo $pastListCount ?>/3</div>
        </div>
        


        
            <?php
                if ($pastListCount == 0) {
                    
                    echo '
                    <div class="mainLists">
                        <h1>No list found</h1>
                    </div>';
                }
                else {
                   // echo "pastListCount {$pastListCount}";
                    if ($pastListCount >= 1) {
                       // echo "counter {$counter}";
                        echo '
                        <div class="mainLists">
                            <div class="list">

                                <div class="tesco"></div>

                                    <div class="listContent">

                                        <div class="items">';
                                        echo 'test';

                                                for ($counterPastTesco1 = 0; $counterPastTesco1 < count($TescoPastList1ItemIDs); $counterPastTesco1++) {
                                                    $TescoPastList1ID = $TescoPastList1ItemIDs[$counterPastTesco1];
                                                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$TescoPastList1ID}");
                                                    $TescoPastList1Items = mysqli_fetch_assoc($result);
                                                    $TescoPastList1ItemID = $TescoPastList1Items['TescoItemID'];


                                                    echo 
                                                        '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                            <img src="' . $TescoPastList1Items['TescoImageURL'] . '" alt="placeholder">

                                                            <aside>
                                                                <p class="name">
                                                                    ' . $TescoPastList1Items['TescoItemName'] . '
                                                                </p>

                                                                <div class="LowerListItem">

                                                                    <div class="price">

                                                                        <h1>£' . $TescoPastList1Items['TescoPrice'] . '</h1>
                                                                        
                                                                    </div>

                                                                    <div class="Buttons">

                                                                        <i class="fa fa-close" style="font-size:36px"></i>
                                                                        <i class="fa fa-check" style="font-size:36px"></i>
                                                                        <a href="Product.php?id=' . $TescoPastList1ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                        <form method="POST">
                                                                            <button  type="submit" name="binItemTesco" class="Bin" value="' . ($counterPastTesco1 + 1) . '">
                                                                                <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                            </button>
                                                                        </form>

                                                                    </div>

                                                                </div>
                                                                    
                                                            </aside>
                                                        </div>
                                                    ';
                                                }
                                        echo '

                                                    

                                        </div>

                                        <div class="prices">
                                            <h2>Total: £';
                                                echo number_format($TescoPastList1Total, 2);

                                            echo '</h2>
                                            </div>
                                    </div>
                                </div>

                                <div class="list">
                                    <div class="lidl"></div>
                                    <div class="listContent">
                                        <div class="items">';


                                                        for ($counterPastLidl1 = 0; $counterPastLidl1 < count($LidlPastList1ItemIDs); $counterPastLidl1++) {
                                                            $LidlPastList1ID = $LidlPastList1ItemIDs[$counterPastLidl1];
                                                            $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$LidlPastList1ID}");
                                                            $LidlPastList1Items = mysqli_fetch_assoc($result);
                                                            $LidlPastList1ItemID = $LidlPastList1Items['LidlItemID'];


                                                        echo 
                                                            '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                                <img src="' . $LidlPastList1Items['LidlImageURL'] . '" alt="placeholder">

                                                                <aside>
                                                                    <p class="name">
                                                                        ' . $LidlPastList1Items['LidlItemName'] . '
                                                                    </p>

                                                                    <div class="LowerListItem">

                                                                        <div class="price">

                                                                            <h1>£' . $LidlPastList1Items['LidlPrice'] . '</h1>
                                                                            
                                                                        </div>

                                                                        <div class="Buttons">

                                                                            <i class="fa fa-close" style="font-size:36px"></i>
                                                                            <i class="fa fa-check" style="font-size:36px"></i>
                                                                            <a href="Product.php?id=' . $LidlPastList1ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                            <form method="POST">
                                                                                <button  type="submit" name="binItemLidl" class="Bin" value="' . ($counterPastLidl1 + 1) . '">
                                                                                    <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                                </button>
                                                                            </form>

                                                                        </div>

                                                                    </div>
                                                                        
                                                                </aside>
                                                            </div>';

                                                        }

                                                echo '
                                        </div>
                                        <div class="prices">
                                            <h2>Total: £';
                                                echo number_format($LidlPastList1Total, 2);

                                            echo '</h2>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                <form method="POST">
                                <input type="hidden" name="ChangeID" value="3">
                                    <button type="submit" name="MakeCurrent" id="" value=""  class="ChangeID">Make Current List</button>
                                    <button type="submit" name="DeleteList" id="" value=""  class="ChangeID">Delete List</button>
                                </form>
                            </div>
                        </div>';
                                    }
                    if ($pastListCount >= 2) {
                       // echo "counter {$counter}";
                        echo '
                        <div class="mainLists">
                        
                            <div class="list">

                                <div class="tesco"></div>

                                    <div class="listContent">

                                        <div class="items">';
                                        echo 'test';

                                                for ($counterPastTesco2 = 0; $counterPastTesco2 < count($TescoPastList2ItemIDs); $counterPastTesco2++) {
                                                    $TescoPastList2ID = $TescoPastList2ItemIDs[$counterPastTesco2];
                                                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$TescoPastList2ID}");
                                                    $TescoPastList2Items = mysqli_fetch_assoc($result);
                                                    $TescoPastList2ItemID = $TescoPastList2Items['TescoItemID'];


                                                    echo 
                                                        '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                            <img src="' . $TescoPastList2Items['TescoImageURL'] . '" alt="placeholder">

                                                            <aside>
                                                                <p class="name">
                                                                    ' . $TescoPastList2Items['TescoItemName'] . '
                                                                </p>

                                                                <div class="LowerListItem">

                                                                    <div class="price">

                                                                        <h1>£' . $TescoPastList2Items['TescoPrice'] . '</h1>
                                                                        
                                                                    </div>

                                                                    <div class="Buttons">

                                                                        <i class="fa fa-close" style="font-size:36px"></i>
                                                                        <i class="fa fa-check" style="font-size:36px"></i>
                                                                        <a href="Product.php?id=' . $TescoPastList2ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                        <form method="POST">
                                                                            <button  type="submit" name="binItemTesco" class="Bin" value="' . ($counterPastTesco2 + 1) . '">
                                                                                <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                            </button>
                                                                        </form>

                                                                    </div>

                                                                </div>
                                                                    
                                                            </aside>
                                                        </div>
                                                    ';
                                                }
                                        echo '

                                                    

                                        </div>

                                        <div class="prices">
                                            <h2>Total: £';
                                              echo number_format($TescoPastList2Total, 2);

                                            echo '</h2>
                                            </div>
                                    </div>
                                </div>

                                <div class="list">
                                    <div class="lidl"></div>
                                    <div class="listContent">
                                        <div class="items">';


                                                        for ($counterPastLidl2 = 0; $counterPastLidl2 < count($LidlPastList2ItemIDs); $counterPastLidl2++) {
                                                            $LidlPastList2ID = $LidlPastList2ItemIDs[$counterPastLidl2];
                                                            $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$LidlPastList2ID}");
                                                            $LidlPastList2Items = mysqli_fetch_assoc($result);
                                                            $LidlPastList2ItemID = $LidlPastList2Items['LidlItemID'];


                                                        echo 
                                                            '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                                <img src="' . $LidlPastList2Items['LidlImageURL'] . '" alt="placeholder">

                                                                <aside>
                                                                    <p class="name">
                                                                        ' . $LidlPastList2Items['LidlItemName'] . '
                                                                    </p>

                                                                    <div class="LowerListItem">

                                                                        <div class="price">

                                                                            <h1>£' . $LidlPastList2Items['LidlPrice'] . '</h1>
                                                                            
                                                                        </div>

                                                                        <div class="Buttons">

                                                                            <i class="fa fa-close" style="font-size:36px"></i>
                                                                            <i class="fa fa-check" style="font-size:36px"></i>
                                                                            <a href="Product.php?id=' . $LidlPastList2ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                            <form method="POST">
                                                                                <button  type="submit" name="binItemLidl" class="Bin" value="' . ($counterPastLidl2 + 1) . '">
                                                                                    <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                                </button>
                                                                            </form>

                                                                        </div>

                                                                    </div>
                                                                        
                                                                </aside>
                                                            </div>';

                                                        }

                                                echo '
                                        </div>
                                        <div class="prices">
                                            <h2>Total: £';
                                                echo number_format($LidlPastList2Total, 2);

                                            echo '</h2>
                                        </div>
                                    </div>
                                </div>
                                
                                <div>
                                <form method="POST">
                                <input type="hidden" name="ChangeID" value="3">
                                    <button type="submit" name="MakeCurrent" id="" value=""  class="ChangeID">Make Current List</button>
                                    <button type="submit" name="DeleteList" id="" value=""  class="ChangeID">Delete List</button>
                                </form>
                            </div>
                            </div>
                        </div>';
                                    }

                    if ($pastListCount == 3) {
                       // echo "counter {$counter}";
                        echo '
                        <div class="mainLists">
                            
                            <div class="list">

                                <div class="tesco"></div>

                                    <div class="listContent">

                                        <div class="items">';
                                        echo 'test';

                                                for ($counterPastTesco3 = 0; $counterPastTesco3 < count($TescoPastList3ItemIDs); $counterPastTesco3++) {
                                                    $TescoPastList3ID = $TescoPastList3ItemIDs[$counterPastTesco3];
                                                    $result = $conn->query("SELECT * FROM TescoItems WHERE TescoItemID = {$TescoPastList3ID}");
                                                    $TescoPastList3Items = mysqli_fetch_assoc($result);
                                                    $TescoPastList3ItemID = $TescoPastList3Items['TescoItemID'];


                                                    echo 
                                                        '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                            <img src="' . $TescoPastList3Items['TescoImageURL'] . '" alt="placeholder">

                                                            <aside>
                                                                <p class="name">
                                                                    ' . $TescoPastList3Items['TescoItemName'] . '
                                                                </p>

                                                                <div class="LowerListItem">

                                                                    <div class="price">

                                                                        <h1>£' . $TescoPastList3Items['TescoPrice'] . '</h1>
                                                                        
                                                                    </div>

                                                                    <div class="Buttons">

                                                                        <i class="fa fa-close" style="font-size:36px"></i>
                                                                        <i class="fa fa-check" style="font-size:36px"></i>
                                                                        <a href="Product.php?id=' . $TescoPastList3ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                        <form method="POST">
                                                                            <button  type="submit" name="binItemTesco" class="Bin" value="' . ($counterPastTesco3 + 1) . '">
                                                                                <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                            </button>
                                                                        </form>

                                                                    </div>

                                                                </div>
                                                                    
                                                            </aside>
                                                        </div>
                                                    ';
                                                }
                                        echo '

                                                    

                                        </div>

                                        <div class="prices">
                                            <h2>Total: £';
                                                echo number_format($TescoPastList3Total, 2);

                                            echo '</h2>
                                            </div>
                                    </div>
                                </div>

                                <div class="list">
                                    <div class="lidl"></div>
                                    <div class="listContent">
                                        <div class="items">';


                                                        for ($counterPastLidl3 = 0; $counterPastLidl3 < count($LidlPastList3ItemIDs); $counterPastLidl3++) {
                                                            $LidlPastList3ID = $LidlPastList3ItemIDs[$counterPastLidl3];
                                                            $result = $conn->query("SELECT * FROM LidlItems WHERE LidlItemID = {$LidlPastList3ID}");
                                                            $LidlPastList3Items = mysqli_fetch_assoc($result);
                                                            $LidlPastList3ItemID = $LidlPastList3Items['LidlItemID'];


                                                        echo
                                                            '<div class="itemAlt"> <!--item placeholder/ base design-->

                                                                <img src="' . $LidlPastList3Items['LidlImageURL'] . '" alt="placeholder">

                                                                <aside>
                                                                    <p class="name">
                                                                        ' . $LidlPastList3Items['LidlItemName'] . '
                                                                    </p>

                                                                    <div class="LowerListItem">

                                                                        <div class="price">

                                                                            <h1>£' . $LidlPastList3Items['LidlPrice'] . '</h1>
                                                                            
                                                                        </div>

                                                                        <div class="Buttons">

                                                                            <i class="fa fa-close" style="font-size:36px"></i>
                                                                            <i class="fa fa-check" style="font-size:36px"></i>
                                                                            <a href="Product.php?id=' . $LidlPastList3ItemID . '"><i class="fa fa-info-circle" style="font-size:36px"></i></a>
                                                                            <form method="POST">
                                                                                <button  type="submit" name="binItemLidl" class="Bin" value="' . ($counterPastLidl3 + 1) . '">
                                                                                    <i class="fa fa-trash-o" style="font-size:36px"></i>
                                                                                </button>
                                                                            </form>

                                                                        </div>

                                                                    </div>
                                                                        
                                                                </aside>
                                                            </div>';

                                                        }

                                                echo '
                                        </div>
                                        <div class="prices">
                                            <h2>Total: £';
                                                echo number_format($LidlPastList3Total, 2);

                                            echo '</h2>
                                        </div>
                                    </div>
                                </div>
                                
                            <div>
                                <form method="POST">
                                <input type="hidden" name="ChangeID" value="3">
                                    <button type="submit" name="MakeCurrent" id="" value=""  class="ChangeID">Make Current List</button>
                                    <button type="submit" name="DeleteList" id="" value=""  class="ChangeID">Delete List</button>
                                </form>
                            </div>
                            
                            </div>

                        </div>';
                                    }
                    }
                if ($pastListCount == 3) {
                        echo '
                        <div class="mainLists">
                            <h1>Maximum past lists reached (3). Please delete a past list to add a new one.</h1>
                        </div>';
                    }
                    else {
                        echo '
                        <div class="mainLists">
                            <form method="POST">
                                <button type="submit" name="SwapToPast" class="SaveCurrentToPastButton">
                                    <h1>Create new List as current</h1>
                                </button>
                            </form>
                        </div>';
                    }
                 ?>

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
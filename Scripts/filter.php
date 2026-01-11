<?php
    $conn = mysqli_connect('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }
    //connect to database

    $columns = mysqli_fetch_assoc($conn->query(
            "SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = 'Sort'"
    ));
    unset($columns[array_find($columns, "ItemID")]);
    //gets all possible sorting variables

    $_GET["l"] = $_GET["l"] ?? 0;
    $_GET["h"] = $_GET["h"] ?? 99999;
    //unsure how to write infinite in SQL so 99999 used

    $rows = mysqli_fetch_assoc($conn->query(
        "SELECT COUNT(*) AS [rows]
        FROM Sort S INNER JOIN JointItems J
        ON S.ItemID = J.ItemID
        LEFT JOIN TescoItems T
        ON J.ItemName = T.TescoItemName
        LEFT JOIN LidlItems L
        ON J.ItemName = L.LidlItemName
        WHERE J.ItemName LIKE '%{$_GET["s"]}%'
        AND (
            (T.TescoPrice >= {$_GET["l"]} AND T.TescoPrice <= {$_GET["h"]}) 
            OR
            (L.LidlPrice >= {$_GET["l"]} AND L.LidlPrice <= {$_GET["h"]}) 
        );
    "))["rows"];
    //gets maximum number of rows possible for settings


    function getData($offset, $sorts){
        global $conn;

        $data = [];
        foreach($sorts as $order){
            $query = 
            "SELECT S.ItemID, S.{$order}
            FROM Sort S INNER JOIN JointItems J
            ON S.ItemID = J.ItemID
            LEFT JOIN TescoItems T
            ON J.ItemName = T.TescoItemName
            LEFT JOIN LidlItems L
            ON J.ItemName = L.LidlItemName
            WHERE J.ItemName LIKE '%{$_GET["s"]}%'
            AND (
                (T.Price NOT NULL AND T.Price >= {$_GET["l"]} AND T.Price <= {$_GET["h"]}) 
                OR
                (L.Price NOT NULL AND L.Price >= {$_GET["l"]} AND L.Price <= {$_GET["h"]})
            )
            ORDER BY S.{$order} DESC
            OFFSET {$offset} ROWS
            LIMIT 30;";
            //gets the next 30 items in descending order when sorted using $order and filtered using search and price thresholds
            //will get array of 30 for each order type

            $data = array_merge(mysqli_fetch_assoc($conn->query($query)), $data);
        }

        structure($data);

        return $data;
    }



    function structure(&$data){
        $tableValues = array_keys($data);
        unset($tableValues[array_search("ItemID", $tableValues)]);
        foreach($tableValues as $value){
            $data[$value] = array_combine(
                array_splice($data["ItemID"], 0, count($data[$value])),
                $data[$value]
            );
        }
        unset($data["ItemID"]);
    }
    //restructures data into format that can be used for customsort [Sort1 -> [ID1 -> 0.5, ID2 -> 0], Sort2 -> [ID3 -> 1, ID4 -> 0.75]]
    //previously will be [itemID -> [ID1, ID2, ID3, ID4], Sort1 -> [0.5, 0], Sort2 -> [1, 0.75]] from getData()


    function customSort($values, &$previous, $goal=30){
        global $columns;
        global $rows;

        $sortsum = array_sum($values);
        foreach ($values as $key => $val){
            if($val < 0.02*$sortsum || !in_array($key, $columns)){
                unset(
                    $values[$key], 
                    $previous[$key]
                );
            }
        }
        unset($sortsum, $possibleValues);
        //removes all sorts that wont influence much at all to save on processing 

        $check = false;
        
        while(!$check){
            $newData = getData(count($previous), array_keys($values));
            $previous = array_merge_recursive($previous, $newData);
            //updates previous by adding 

            $intersections = array_keys(array_intersect_key(...array_values($previous)));

            if(count($intersections) >= $goal || count(array_first($previous)) >= $rows){
                $sortArr = [];
                foreach(array_keys($values) as $sort){
                    $sortArr[$sort] = [];
                    for($i=0; $i<$goal; $i++){
                        $cur = $intersections[$i];
                        if(!is_null($cur)) {$sortArr[$sort][$cur] = $previous[$sort][$cur];}
                    }
                }
                $check = true;
            }
            //gets first 30 items that appear on all selected sorts
        }

        $scores = array_fill_keys(array_keys(array_first($sortArr)), 0);
        foreach (array_keys($values) as $sort){
            $power = $values[$sort];
            foreach($sortArr[$sort] as $key => $val){
                $scores[$key] = $scores[$key]+($val*$power);
            }
        }

        //applies sort power to each sort type and then adds them together

        uasort($scores, fn($f, $s) => $s <=> $f);
        //descending

        return array_keys($scores);
    }
    //keeps getting individual arrays of the top 30 items when sorted using the keys in $values
    //until every array has 30 matching items
    //each item was returned with its "sort" power for each array
    //all powers in array are multiplied by value associared to the key in $values
    //all sort powers assigned to an item are added together, then ordered descendingly to get
    //an array of items in the correct order



    function array_first($array){
        $array = array_slice($array, 0, 1);
        return (array)array_shift($array);
    }
    //implimentation of array_first as it wasnt in this php version apparently
    //differs from original in that it converts to array as that would be faster in this program specifically

    $previous = json_decode($_GET["p"], true);
    $data = customSort(["Cost" => $_GET["c"], "Ratings" => $_GET["r"], "NumRatings" => $_GET["nr"]], $previous);
    //gets sort values, and previous loaded data to save on processing
    

    $finalData = [];
    foreach($data as $id){
        $query = 
        "SELECT J.ItemName, T.TescoPrice, T.TescoImage, L.LidlPrice, L.LidlImage
        FROM JointItems J LEFT JOIN TescoItems T
        ON J.ItemName = T.TescoItemName
        LEFT JOIN LidlItems L
        ON J.ItemName = L.LidlItemName
        WHERE J.ItemID = '{$id}'";

        $query = $conn->query($query);
        $query = mysqli_fetch_assoc($query)[0];

        $newData = [];
        $newData["Tesco"] = [];
        $newData["Lidl"] = [];
        $newData["Tesco"]["price"] = $query["TescoPrice"][$i];
        $newData["Tesco"]["image"] = $query["TescoImage"][$i];
        $newData["Lidl"]["price"] = $query["LidlPrice"][$i];
        $newData["Lidl"]["price"] = $query["LidlPrice"][$i];
        $newData["Tesco"]["name"] = $newData["Lidl"]["name"] = $query["ItemName"][$i];

        array_push($finalData, $newData);
    }

    echo json_encode(["real" => $finalData, "previous" => $previous]);
    //get the price, name and picture for each item, plus the "prev" array
    //outputs json encoded data puts in the format:
    /*
        {
            "real": [
                {
                    "Store": {
                        "name": "ItemName1",
                        "image": "ImageLink1",
                        "price": ItemPrice1
                    },
                    "Store2": {
                        "name": "ItemName1",
                        "image": "ImageLink2",
                        "price": ItemPrice2
                    },
                },
                {
                    "Store": {
                        "name": "ItemName2",
                        "image": "ImageLink3",
                        "price": ItemPrice3
                    },
                    "Store2": {
                        "name": "ItemName2",
                        "image": "ImageLink4",
                        "price": ItemPrice4
                    },
                }
            ],
            "previous": {
                "sort1": [
                    { "itemID1": sortscoreNum1 },
                    { "itemID2": sortscoreNum2 }
                ],
                "sort1": [
                    { "itemID3": sortscoreNum3 },
                    { "itemID4": sortscoreNum4 }
                ] 
            }
        }
    */

    close($conn);
?>
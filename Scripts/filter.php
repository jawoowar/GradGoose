<?php
    $conn = new mysqli('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }
    //connect to database

    $result = $conn->query(
            "SELECT COLUMN_NAME
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_NAME = 'Sort';"
    );

    $columns = mysqli_fetch_all($result);
    for($i=0, $size = count($columns); $i < $size; $i++){$columns[$i] = $columns[$i][0];}
    unset($columns[array_search( "ItemID", $columns)], $columns[array_search("SortID", $columns)]);
    //gets all possible sorting variables

    foreach($_GET as $key => $val){
        if(strlen($val) < 1 || $val == "undefined"){$_GET[$key] = null;}
        if(is_numeric($val)){$_GET[$key] = floatval($val);}
    }

    $_GET["l"] = $_GET["l"] ?? 0;
    $_GET["h"] = $_GET["h"] ?? 99999;
    $_GET["s"] = $_GET["s"] ?? "";
    $_GET["p"] = $_GET["p"] ?? "";
    $_GET["e"] = $_GET["e"] ?? "";
    $_GET["c"] = $_GET["c"] ?? 0.3;
    $_GET["r"] = $_GET["r"] ?? 0.3;
    $_GET["nr"] = $_GET["nr"] ?? 0.4;
    //default paramater values
    //unsure how to write infinite in SQL so 99999 used

    $rows = mysqli_fetch_assoc($conn->query(
        "SELECT COUNT(*) AS Nrows
        FROM Sort S INNER JOIN JointItems J
        ON S.ItemID = J.ItemID
        LEFT JOIN TescoItems T
        ON J.ItemName = T.TescoItemName
        LEFT JOIN LidlItems L
        ON J.ItemName = L.LidlItemName
        WHERE J.ItemName LIKE '%{$_GET["s"]}%'
        AND (
            (T.TescoPrice IS NOT NULL AND (T.TescoPrice >= {$_GET["l"]} AND T.TescoPrice <= {$_GET["h"]})) 
            OR
            (L.LidlPrice IS NOT NULL AND (L.LidlPrice >= {$_GET["l"]} AND L.LidlPrice <= {$_GET["h"]}))
        );
    "))["Nrows"];
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
                (T.TescoPrice IS NOT NULL AND (T.TescoPrice >= {$_GET["l"]} AND T.TescoPrice <= {$_GET["h"]})) 
                OR
                (L.LidlPrice IS NOT NULL AND (L.LidlPrice >= {$_GET["l"]} AND L.LidlPrice <= {$_GET["h"]}))
            )
            ORDER BY S.{$order} DESC, J.ItemID
            LIMIT {$offset}, 5;";
            //gets the next 30 items in descending order when sorted using $order and filtered using search and price thresholds
            //will get array of 30 for each order type

            $result = mysqli_fetch_all($conn->query($query));
            $data[$order] = [];
            foreach($result as $row){
                $data[$order] += [$row[0] => floatval($row[1])];
            }

            //$data = array_merge(mysqli_fetch_all($conn->query($query)), $data);
        }

        /*
        echo count($data["PriceScore"])." ";

        foreach($data["PriceScore"] as $key => $val){
            echo $val." ";
        }
        */

        //structure($data);

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


    function customSort($values, &$previous, &$exists){
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
        $test = array_fill(0, 99, 1);
        
        while(!$check && next($test)){
            $newData = getData(count(array_first($previous)), array_keys($values));
            //$previous = array_merge_recursive($previous, $newData);
            //updates previous by adding 

            foreach($newData as $key => $val){
                $previous[$key] += $newData[$key];
            }

            $intersections = array_keys(call_user_func_array("array_intersect_key", array_values($previous)));
            $intersections = array_values(array_diff($intersections, $exists));

            //echo in_array(1, array_keys(array_first($previous)));

            if(count($intersections) >= 5 || count(array_first($previous)) >= $rows){
                $sortArr = [];
                foreach(array_slice($intersections, 0, 5) as $intersection){
                     array_push($exists, $intersection);
                }
                foreach(array_keys($values) as $sort){
                    $sortArr[$sort] = [];
                    for($i=0, $size=min(5, count($intersections)); $i<$size; $i++){
                        $cur = $intersections[$i];
                        if(!is_null($cur)) {$sortArr[$sort][$cur] = $previous[$sort][$cur];}
                    }
                }
                $check = true;
            }
            //gets first 30 items that appear on all selected sorts
        }

        /*foreach($sortArr["PriceScore"] as $key => $val){
            echo $key." ";
        }
        echo "^";*/

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

        /*foreach($scores as $key => $val){
            echo $key." ";
        }
        echo "^";*/

        return array_keys($scores);
    }
    //keeps getting individual arrays of the top 30 items when sorted using the keys in $values
    //until every array has 30 matching items
    //each item was returned with its "sort" power for each array
    //all powers in array are multiplied by value associared to the key in $values
    //all sort powers assigned to an item are added together, then ordered descendingly to get
    //an array of items in the correct order



    function array_first($array): array{
        $array = array_slice($array, 0, 1);
        return (array)array_shift($array);
    }
    //implimentation of array_first as it wasnt in this php version apparently
    //differs from original in that it converts to array as that would be faster in this program specifically

    $previous = json_decode($_GET["p"], true);
    $previous = $previous ?? ["PriceScore" => [], "RatingScore" => [], "NumRatingScore" => []];
    $exists = json_decode($_GET["e"], true);
    $exists =  $exists ?? [];
    $data = customSort(
        ["PriceScore" => intval($_GET["c"]), "RatingScore" => intval($_GET["r"]), "NumRatingScore" => intval($_GET["nr"])],
        $previous,
        $exists
    );
    //gets sort values, and previous loaded data to save on processing
    

    $finalData = [];
    foreach($data as $id){
        $query = 
        "SELECT J.ItemName, T.TescoPrice, T.TescoImageURL, L.LidlPrice, L.LidlImageURL, J.ItemID
        FROM JointItems J LEFT JOIN TescoItems T
        ON J.ItemName = T.TescoItemName
        LEFT JOIN LidlItems L
        ON J.ItemName = L.LidlItemName
        WHERE J.ItemID = '{$id}'";

        $query = $conn->query($query);
        $query = mysqli_fetch_all($query)[0];

        $newData = [];
        $newData["tesco"] = [];
        $newData["lidl"] = [];
        $newData["tesco"]["price"] = $query[1];
        $newData["tesco"]["image"] = $query[2];
        $newData["lidl"]["price"] = $query[3];
        $newData["lidl"]["image"] = $query[4];
        $newData["tesco"]["name"] = $newData["lidl"]["name"] = $query[0];
        $newData["tesco"]["id"] = $newData["lidl"]["id"] = $query[5];

        array_push($finalData, $newData);
    }

    echo json_encode(["real" => $finalData, "previous" => $previous, "exists" => $exists]);
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
                "sort2": [
                    { "itemID3": sortscoreNum3 },
                    { "itemID4": sortscoreNum4 }
                ] 
            }
        }
    */

    mysqli_close($conn);
?>
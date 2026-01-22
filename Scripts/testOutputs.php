<?php
    $conn = mysqli_connect('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    $rowsT = 99;
    $columnsT = ["Sort1", "Sort2", "Sort3"];
    $testData = [
        "Sort1" => [
            'ID1' => 0.99,
            'ID2' => 0.98,
            'ID3' => 0.97,
            'ID4' => 0.96,
            'ID5' => 0.95,
            'ID6' => 0.94,
            'ID7' => 0.93,
            'ID8' => 0.92,
            'ID9' => 0.91,
            'ID10' => 0.9,
            'ID11' => 0.89,
            'ID12' => 0.88,
            'ID13' => 0.87,
            'ID14' => 0.86,
            'ID15' => 0.85,
            'ID16' => 0.84,
            'ID17' => 0.83,
            'ID18' => 0.82,
            'ID19' => 0.81,
            'ID20' => 0.8,
            'ID21' => 0.79,
            'ID22' => 0.78,
            'ID23' => 0.77,
            'ID24' => 0.76,
            'ID25' => 0.75,
            'ID26' => 0.74,
            'ID27' => 0.73,
            'ID28' => 0.72,
            'ID29' => 0.71,
            'ID30' => 0.7,
            'ID31' => 0.69,
            'ID32' => 0.68,
            'ID33' => 0.67,
            'ID34' => 0.66,
            'ID35' => 0.65,
            'ID36' => 0.64,
            'ID37' => 0.63,
            'ID38' => 0.62,
            'ID39' => 0.61,
            'ID40' => 0.6,
            'ID41' => 0.59,
            'ID42' => 0.58,
            'ID43' => 0.57,
            'ID44' => 0.56,
            'ID45' => 0.55,
            'ID46' => 0.54,
            'ID47' => 0.53,
            'ID48' => 0.52,
            'ID49' => 0.51,
            'ID50' => 0.5,
            'ID51' => 0.49,
            'ID52' => 0.48,
            'ID53' => 0.47,
            'ID54' => 0.46,
            'ID55' => 0.45,
            'ID56' => 0.44,
            'ID57' => 0.43,
            'ID58' => 0.42,
            'ID59' => 0.41,
            'ID60' => 0.4,
            'ID61' => 0.39,
            'ID62' => 0.38,
            'ID63' => 0.37,
            'ID64' => 0.36,
            'ID65' => 0.35,
            'ID66' => 0.34,
            'ID67' => 0.33,
            'ID68' => 0.32,
            'ID69' => 0.31,
            'ID70' => 0.3,
            'ID71' => 0.29,
            'ID72' => 0.28,
            'ID73' => 0.27,
            'ID74' => 0.26,
            'ID75' => 0.25,
            'ID76' => 0.24,
            'ID77' => 0.23,
            'ID78' => 0.22,
            'ID79' => 0.21,
            'ID80' => 0.2,
            'ID81' => 0.19,
            'ID82' => 0.18,
            'ID83' => 0.17,
            'ID84' => 0.16,
            'ID85' => 0.15,
            'ID86' => 0.14,
            'ID87' => 0.13,
            'ID88' => 0.12,
            'ID89' => 0.11,
            'ID90' => 0.1,
            'ID91' => 0.09,
            'ID92' => 0.08,
            'ID93' => 0.07,
            'ID94' => 0.06,
            'ID95' => 0.05,
            'ID96' => 0.04,
            'ID97' => 0.03,
            'ID98' => 0.02,
            'ID99' => 0.01
        ],
        "Sort2" => [
            'ID99' => 0.99,
            'ID98' => 0.98,
            'ID97' => 0.97,
            'ID96' => 0.96,
            'ID95' => 0.95,
            'ID94' => 0.94,
            'ID93' => 0.93,
            'ID92' => 0.92,
            'ID91' => 0.91,
            'ID90' => 0.9,
            'ID89' => 0.89,
            'ID88' => 0.88,
            'ID87' => 0.87,
            'ID86' => 0.86,
            'ID85' => 0.85,
            'ID84' => 0.84,
            'ID83' => 0.83,
            'ID82' => 0.82,
            'ID81' => 0.81,
            'ID80' => 0.8,
            'ID79' => 0.79,
            'ID78' => 0.78,
            'ID77' => 0.77,
            'ID76' => 0.76,
            'ID75' => 0.75,
            'ID74' => 0.74,
            'ID73' => 0.73,
            'ID72' => 0.72,
            'ID71' => 0.71,
            'ID70' => 0.7,
            'ID69' => 0.69,
            'ID68' => 0.68,
            'ID67' => 0.67,
            'ID66' => 0.66,
            'ID65' => 0.65,
            'ID64' => 0.64,
            'ID63' => 0.63,
            'ID62' => 0.62,
            'ID61' => 0.61,
            'ID60' => 0.6,
            'ID59' => 0.59,
            'ID58' => 0.58,
            'ID57' => 0.57,
            'ID56' => 0.56,
            'ID55' => 0.55,
            'ID54' => 0.54,
            'ID53' => 0.53,
            'ID52' => 0.52,
            'ID51' => 0.51,
            'ID50' => 0.5,
            'ID49' => 0.49,
            'ID48' => 0.48,
            'ID47' => 0.47,
            'ID46' => 0.46,
            'ID45' => 0.45,
            'ID44' => 0.44,
            'ID43' => 0.43,
            'ID42' => 0.42,
            'ID41' => 0.41,
            'ID40' => 0.4,
            'ID39' => 0.39,
            'ID38' => 0.38,
            'ID37' => 0.37,
            'ID36' => 0.36,
            'ID35' => 0.35,
            'ID34' => 0.34,
            'ID33' => 0.33,
            'ID32' => 0.32,
            'ID31' => 0.31,
            'ID30' => 0.3,
            'ID29' => 0.29,
            'ID28' => 0.28,
            'ID27' => 0.27,
            'ID26' => 0.26,
            'ID25' => 0.25,
            'ID24' => 0.24,
            'ID23' => 0.23,
            'ID22' => 0.22,
            'ID21' => 0.21,
            'ID20' => 0.2,
            'ID19' => 0.19,
            'ID18' => 0.18,
            'ID17' => 0.17,
            'ID16' => 0.16,
            'ID15' => 0.15,
            'ID14' => 0.14,
            'ID13' => 0.13,
            'ID12' => 0.12,
            'ID11' => 0.11,
            'ID10' => 0.1,
            'ID9' => 0.09,
            'ID8' => 0.08,
            'ID7' => 0.07,
            'ID6' => 0.06,
            'ID5' => 0.05,
            'ID4' => 0.04,
            'ID3' => 0.03,
            'ID2' => 0.02,
            'ID1' => 0.01
        ],
        "Sort3" => [
            'ID84' => 0.99,
            'ID54' => 0.98,
            'ID26' => 0.97,
            'ID6' => 0.96,
            'ID82' => 0.95,
            'ID51' => 0.94,
            'ID10' => 0.93,
            'ID80' => 0.92,
            'ID34' => 0.91,
            'ID7' => 0.9,
            'ID18' => 0.89,
            'ID75' => 0.88,
            'ID68' => 0.87,
            'ID62' => 0.86,
            'ID3' => 0.85,
            'ID53' => 0.84,
            'ID41' => 0.83,
            'ID98' => 0.82,
            'ID15' => 0.81,
            'ID30' => 0.8,
            'ID57' => 0.79,
            'ID40' => 0.78,
            'ID24' => 0.77,
            'ID73' => 0.76,
            'ID61' => 0.75,
            'ID33' => 0.74,
            'ID13' => 0.73,
            'ID71' => 0.72,
            'ID31' => 0.71,
            'ID43' => 0.7,
            'ID23' => 0.69,
            'ID44' => 0.68,
            'ID81' => 0.67,
            'ID83' => 0.66,
            'ID35' => 0.65,
            'ID72' => 0.64,
            'ID2' => 0.63,
            'ID56' => 0.62,
            'ID70' => 0.61,
            'ID52' => 0.6,
            'ID28' => 0.59,
            'ID21' => 0.58,
            'ID27' => 0.57,
            'ID19' => 0.56,
            'ID77' => 0.55,
            'ID25' => 0.54,
            'ID92' => 0.53,
            'ID11' => 0.52,
            'ID4' => 0.51,
            'ID17' => 0.5,
            'ID46' => 0.49,
            'ID38' => 0.48,
            'ID22' => 0.47,
            'ID67' => 0.46,
            'ID65' => 0.45,
            'ID42' => 0.44,
            'ID93' => 0.43,
            'ID95' => 0.42,
            'ID55' => 0.41,
            'ID29' => 0.4,
            'ID49' => 0.39,
            'ID36' => 0.38,
            'ID97' => 0.37,
            'ID60' => 0.36,
            'ID66' => 0.35,
            'ID74' => 0.34,
            'ID20' => 0.33,
            'ID90' => 0.32,
            'ID69' => 0.31,
            'ID14' => 0.3,
            'ID9' => 0.29,
            'ID39' => 0.28,
            'ID16' => 0.27,
            'ID76' => 0.26,
            'ID89' => 0.25,
            'ID59' => 0.24,
            'ID8' => 0.23,
            'ID85' => 0.22,
            'ID88' => 0.21,
            'ID37' => 0.2,
            'ID64' => 0.19,
            'ID99' => 0.18,
            'ID32' => 0.17,
            'ID12' => 0.16,
            'ID86' => 0.15,
            'ID5' => 0.14,
            'ID47' => 0.13,
            'ID50' => 0.12,
            'ID96' => 0.11,
            'ID45' => 0.1,
            'ID78' => 0.09,
            'ID48' => 0.08,
            'ID91' => 0.07,
            'ID1' => 0.06,
            'ID58' => 0.05,
            'ID94' => 0.04,
            'ID87' => 0.03,
            'ID79' => 0.02,
            'ID63' => 0.01
        ]
    ];

    function getDataT($previous, $validKeys) {
        global $testData;

        $offset = count(array_first($previous));

        $output = array_map(
            fn($val) => array_slice($val, $offset, 30),
            $testData
        );

        foreach (array_keys($output) as $key){
            if(!in_array($key, $validKeys)){
                unset($output[$key]);
            }
        }

        return $output;
    }



    $columns = mysqli_fetch_assoc($conn->query(
            "SELECT COLUMN_NAME
            FROM information_schema.columns
            WHERE TABLE_NAME = 'Sort'"
    ));
    unset($columns[array_find($columns, "ItemID")]);
    //gets all possible sorting variables

    $_GET["l"] = $_GET["l"] ?? 0;
    $_GET["h"] = $_GET["h"] ?? 99999;
    //unsure how to write infinite in SQL so 99999 used

    $rows = mysqli_fetch_assoc($conn->query(
        "SELECT COUNT(*) AS rows
        FROM Sort INNER JOIN JointItems J
        ON S.ItemID = J.ItemID
        LEFT JOIN TescoItems T
        ON S.ItemID = T.TescoItemID
        LEFT JOIN LidlItems L
        ON S.ItemID = L.LidlItemID 
        WHERE J.ItemName LIKE %{$_GET["s"]}%
        AND (
            (T.TescoPrice >= {$_GET["l"]} AND T.TescoPrice <= {$_GET["h"]}) 
            OR
            (L.LidlPrice >= {$_GET["l"]} AND L.LidlPrice <= {$_GET["h"]}) 
        );"
    ))["rows"];
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
                ON S.ItemID = T.ItemID
                LEFT JOIN LidlItems L
                ON S.ItemID = L.ItemID 
                WHERE J.ItemName LIKE %{$_GET["s"]}%
                AND (
                    (T.Price NOT NULL AND T.Price >= {$_GET["l"]} AND T.Price <= {$_GET["h"]}) 
                    OR
                    (L.Price NOT NULL AND L.Price >= {$_GET["l"]} AND L.Price <= {$_GET["h"]})
                )
                ORDER BY S.{$order} DESC
                OFFSET {$offset} ROWS
                LIMIT 30;
            ";
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
    //previously will be [itemID -> [ID1, ID2, ID3, ID4], Sort1 -> [0.5, 0], Sort2 -> [1, 0.75]]


    function customSort($values, &$previous, $goal=30){
        global $columnsT;
        global $rowsT;

        $sortsum = array_sum($values);
        foreach ($values as $key => $val){
            if($val < 0.02*$sortsum || !in_array($key, $columnsT)){
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
            $newData = getDataT(count($previous), array_keys($values));
            $previous = array_merge_recursive($previous, $newData);

            foreach($previous["Sort3"] as $i => $ii){
                echo $i." => ".$ii."   ";
            }
            echo " RESTART ";
            //gets previous+next 30 items

            $intersections = array_keys(array_intersect_key(...array_values($previous)));
            foreach($intersections as $i){
                echo $i." ";
            }
            if(count($intersections) >= $goal || count(array_first($previous)) >= $rowsT){
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

        foreach($scores as $i => $ii){
            echo $i." => ".$ii."   ";
        }

        return array_keys($scores);
    }



    function array_first($array){
        $array = array_slice($array, 0, 1);
        return (array)array_shift($array);
    }
    //implimentation of array_first as it wasnt in this php version apparently
    //differs from original in that it converts to array as that is the only needed implimentation in this program



    $previous = [];
    foreach(customSort(["Sort1" => 0, "Sort2" => 1, "Sort3" => 0.5], $previous) as $item){
        echo $item;
    }

    $testData = ["ItemID" => [1, 2, 3, 3, 2, 1, 2, 3, 1], "Sort1" => [1, 0.5, 0], "Sort2" => [1, 0.3, 0.1], "Sort3" => [0.75, 0.5, 0]];
    structure($testData);
    customSort(["Sort1" => 0, "Sort2" => 1, "Sort3" => 0.5], $testData);

    foreach($columns as $column){
        echo "+".$column."+";
    }
    echo "+".$rows."+";

    close($conn);
?>
<?php
    $conn = mysqli_connect('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    $columns = mysqli_fetch_assoc(
        $conn->query("SHOW COLUMNS FROM jenniferwoodward_GradGoose.Sort")
    );
    unset($columns[array_find($columns, "ItemID")]);

    $rows = mysqli_fetch_assoc($conn->query("
        SELECT COUNT(*) AS rows
        FROM Sort INNER JOIN JointItems J
        ON S.ItemID = J.ItemID
        INNER JOIN TescoItems T
        ON S.ItemID = T.ItemID
        INNER JOIN LidlItems L
        ON S.ItemID = L.ItemID 
        WHERE J.ItemName LIKE %{$_GET["s"]}%
        AND (
            (T.Price >= {$_GET["l"]} AND T.Price <= {$_GET["h"]}) 
            OR
            (L.Price >= {$_GET["l"]} AND L.Price <= {$_GET["h"]}) 
        );
    "))["rows"];


    function getData($offset){
        global $columns;
        global $conn;

        $data = [];
        foreach($columns as $order){
            $query = "
                SELECT S.ItemID, S.{$order}
                FROM Sort S INNER JOIN JointItems J
                ON S.ItemID = J.ItemID
                INNER JOIN TescoItems T
                ON S.ItemID = T.ItemID
                INNER JOIN LidlItems L
                ON S.ItemID = L.ItemID 
                WHERE J.ItemName LIKE %{$_GET["s"]}%
                AND (
                    (T.Price >= {$_GET["l"]} AND T.Price <= {$_GET["h"]}) 
                    OR
                    (L.Price >= {$_GET["l"]} AND L.Price <= {$_GET["h"]}) 
                )
                ORDER BY {$order} DESC
                OFFSET {$offset} ROWS
                LIMIT 30;
            ";

            $newData = $conn->query($query);
            $data = array_merge(mysqli_fetch_assoc($newData), $data);
        }

        return structure($data);
    }
        



    function structure(&$data){
        $tableValues = array_keys($data);
        unset($tableValues["ItemID"]);
        foreach($tableValues as $value){
            $data[$value] = array_combine($data["ItemID"], $data[$value]);
            array_splice($data["ItemID"], 0, count($data[$value]));
        }
        unset($data["ItemID"]);
    }
    //restructures data into format that can be used for customsort (values -> [itemID -> 1, itemID -> 0.5])


    //REMOVE $DATA
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

            $previous = array_merge($previous, getData(count(array_first($previous))));

            //gets previous+next 30 items

            $intersections = array_intersect_key(...$previous);
            if(count($intersections) >= $goal || count(array_first($previous)) >= $rows){
                $sortArr = [];
                foreach(array_keys($values) as $sort){
                    $sortArr[$sort] = [];
                    for($i=0; $i<$goal; $i++){
                        $cur = $intersections[$i];
                        $sortArr[$sort][$cur] = $previous[$sort][$cur];
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

        rsort($scores);

        return array_keys($scores);
    }
?>
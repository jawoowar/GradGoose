<?php

    $conn = mysqli_connect('localhost', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_commentTest');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

    $data = (array)$conn->query("SELECT ItemID, RatingScore, FROM WHERE");

    foreach(array_diff(array_keys($data), ["ItemID"]) as $sort){
        $data = array_combine($data["ItemID"], $data[$sort]);
    }


    function customSort($values, $data, $goal=30, $previous=[]){
        foreach ($values as $key => $val){
            if($val < 0.02){
                unset($values[$key]);
                unset($data[$key]);
                unset($previous[$key]);
            }
        }

        $check = false;
        
        while(!$check){
            $startFrom = count(array_first($previous));
            $previous = array_map(
                fn($val, $key) => $previous[$key]+array_slice($val, $startFrom, $startFrom+30),
            $data, array_keys($data));
            $intersections = array_intersect_key(...$previous);
            if(count($intersections) >= $goal || count(array_first($previous)) == count(array_first($data))){
                $sortArr = [];
                foreach(array_keys($values) as $sort){
                    $sortArr[$sort] = [];
                    for($i=0; $i<$goal; $i++){
                        $cur = $intersections[$i];
                        $sortArr[$sort][$cur] = $data[$sort][$cur];
                    }
                }
                $check = true;
            }
        }

        foreach (array_keys($values) as $sort){
            $power = $values[$sort];
            $sortArr[$sort] = array_map(fn($val) => $val*$power, $sortArr[$sort]);
        }

        rsort($sortArr);

        return array_keys($sortArr, $previous);
    }
?>
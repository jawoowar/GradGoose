<?php

    //get data
    function getValues($data){
        rsort($data);
        $high = array_first($data);
        $data = array_map(fn($val) => $val/$high, $data);
        return $data;
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
            $startFrom = count($previous);
            $previous = array_map(
                fn($val, $key) => $previous[$key]+array_slice($val, $startFrom, $startFrom+30),
            $data, array_keys($data));
            $intersections = array_intersect_key(...$previous);
            if(count($intersections) >= $goal){
                $sortArr = [];
                foreach(array_keys($values) as $sort){
                    $sortArrTemp = [];
                    for($i=0; $i<$goal; $i++){
                        $cur = $intersections[$i];
                        $sortArrTemp[$cur] = $data[$sort][$cur];
                    }
                    $sortArr[$sort] = $sortArrTemp;
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
<?php
    $data = [
        [
            "tesco" => [
                "id" => 0,
                "name" => "Item1",
                "price" => 12.99,
                "image" => "https://i.ytimg.com/vi/m7BHCMc50aE/sddefault.jpg"
            ],
            "lidl" => [
                "name" => "Item1",
                "price" => 13.99,
                "image" => "https://i.ytimg.com/vi/m7BHCMc50aE/sddefault.jpg"
            ],
            "asda" => [
                "name" => "Item1",
                "price" => 11.99,
                "image" => "https://i.ytimg.com/vi/m7BHCMc50aE/sddefault.jpg"
            ]
        ],
        [
            "tesco" => [
                "name" => "Item2",
                "price" => 11.99,
                "image" => "https://i.ebayimg.com/images/g/XFcAAOSwk0lllkKg/s-l1200.jpg"
            ],
            "lidl" => [
                "name" => "Item2",
                "price" => 10.99,
                "image" => "https://i.ebayimg.com/images/g/XFcAAOSwk0lllkKg/s-l1200.jpg"
            ],
            "asda" => [
                "name" => "Item2",
                "price" => 7.99,
                "image" => "https://i.ebayimg.com/images/g/XFcAAOSwk0lllkKg/s-l1200.jpg"
            ],
            "sainsburys" => [
                "name" => "Item2",
                "price" => 9.99,
                "image" => "https://i.ebayimg.com/images/g/XFcAAOSwk0lllkKg/s-l1200.jpg"
            ],
        ],
    ];
    
    echo json_encode($data);
?>
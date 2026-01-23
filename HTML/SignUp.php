<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $conn = mysqli_connect('ysjcs.net', 'jennifer.w', 'EHEXYUE8', 'jenniferwoodward_GradGoose');

    if (!$conn) {
        die("Connection failed: ".mysqli_connect_error());
    }

            if (isset($_POST['SignUp'])) {

                $uid = $_POST['username'];
                $email = $_POST['email'];
                $pwd = $_POST['password'];


                $sql = "SELECT * FROM UserID WHERE Email='$email'";
                $result = $conn->query($sql);
                //echo $result->num_rows;
                if ($result->num_rows == 0) {

                    if ($uid == '' or $email == '' or $pwd == '') {
                        echo "Please fill in all fields";
                    }
                    else {

                        $NewListTesco = $conn->query("INSERT INTO Lists Values (0, CURRENT_TIMESTAMP, 'Tesco', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
                        $NewTescoID = $conn->insert_id;
                        $NewListLidl = $conn->query("INSERT INTO Lists Values (0, CURRENT_TIMESTAMP, 'Lidl', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
                        $NewLidlID = $conn->insert_id;
                        $NewFavorite = $conn->query("INSERT INTO Favorites Values (0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL)");
                        $NewFavoriteID = $conn->insert_id;

                        $sql = "INSERT INTO UserID VALUES (0,'$uid', '$email', '$pwd', null, 0, 0, '$NewTescoID', '$NewLidlID', null, null, null, null, null, null, '$NewFavoriteID')";
                        $result = $conn->query($sql);

                        echo "signed up successfully";
                    }
                        //header("Location: Index.html");
                        //exit();
                    
                } else {
                    echo "not sorted";

                }
            }
    
?>

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
    <link rel="stylesheet" href="../Styles/Login.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

<body class="Website"> <!--Moves the whole website to the centre-->

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.php" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <form action=""><!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        </form>
        
        <!--Buttons to move to a different page-->
        <a href="Lists.php"><i class="fa fa-navicon"></i></a>
        <a href="Favs.php"><i class="material-icons" style="font-size:55px">star</i></a>
        <a href="Profile.php"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.php"><button class="SignUpButton"> Sign Up </button></a>

    </div>
    <body>
        <main>
            <form method="POST">
                <div class="LoginMain" >
                    <div class="TextFields">
                        <div class="TextField">
                            <label form="uname">Username:</label><br>
                            <input type="text" id="username" class="TextFields" name="username" placeholder="Username/Email"><br>
                        </div>
                        <div class="TextField">
                            <label for="email">Email:</label><br>
                            <input type="text" id="email" class="TextFields" name="email" placeholder="Password">
                        </div>
                        <div class="TextField">
                            <label for="pass">Password:</label><br>
                            <input type="text" id="password" class="TextFields" name="password" placeholder="Password">
                        </div>
                    </div>
                    <div class="Buttons">
                        <a href="Login.html"><button type="button">back</button></a>
                        <a><button type="submit" name="SignUp">Sign-up</button></a>
                    </div>
                </div>
            </form>
            
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
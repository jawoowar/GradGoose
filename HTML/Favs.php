<!DOCTYPE html>
<html>
    <head>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

        <title>GradGoose</title>
    </head>

    <link rel="stylesheet" href="../Styles/Root.css">
    <link rel="stylesheet" href="../Styles/Items.css">
    <link rel="stylesheet" href="../Styles/Index.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Chivo+Mono:ital,wght@0,100..900;1,100..900&display=swap');
    </style>

<body class="Website"> <!--Moves the whole website to the centre-->

    <div class="Header" style="margin: 5px;"> <!--Everything at the top of the page-->
        <a href="Index.html" style="width: 10%;"><img src="../Media/GradGooseLogo.svg" alt="" width="100%" height="100%" style="margin: 0;"></a>
        
        <form action=""><!--add the database in to the action-->
        <input type="text" id="Search" name="Search" placeholder="Search">
        </form>
        
        <!--Buttons to move to a different page-->
        <a href="Lists.html"><i class="fa fa-navicon"></i></a>
        <a href="Favs.html"><i class="material-icons" style="font-size:55px">star</i></a>
        <a href="Profile.html"><i class="fa fa-user-circle-o"></i></a>
        <a href="Login.html"><button class="SignUpButton"> Sign Up </button></a>

    </div>

    <div class="Items" >
        <div class="Products">
            <div id="items">
                <?
                echo '
                         <div class="${store}">
                            <div class="${cap+"Wrap"}">
                                <a></a>
                                <span class="${cap+"Text"}">${cap}</span>
                            </div>
                        </div>
                    <div class="item" href="${productLink}"> <!--item placeholder/ base design-->
                        <div class="store">
                            ${storestring}
                        </div>

                        <a href="${productLink}"><img src="${item[stores[0]].image}" alt="placeholder"></a>
                        <p class="name">${item[stores[0]].name}</p>

                        <div class="lower">
                            <div class="price">
                                <h1>£${price.toFixed(2)}</h1>
                                <p>£${lo} - £${hi}</p>
                            </div>

                            <div class="Buttons">
                                <a href=""><i class="fa fa-plus fa-3x" alt=""></i></a>
                                <a href=""><i class="fa fa-plus fa-3x" alt=""></i></a>
                            </div>
                        </div> 
                    </div>`
                    '
                    ?>
                
                
            </div>
        </div>
    </div>

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

<script src="../Scripts/index.js"></script>

</html>
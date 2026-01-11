function getCookie(value){
    let cookies = document.cookies;
    return !!cookies && cookies.substring(cookies.indexOf(value)+1) == "Comic Relief" ? "Comic Relief" : "Chivo Mono";
}

document.querySelector(":root").style.setProperty(
    "--font", getCookie("font")
);
//checks cookies to set accessable font or not
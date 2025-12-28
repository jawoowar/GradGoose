function getCookie(value){
    let cookies = document.cookies;
    return !!cookies && cookies.substring(cookies.indexOf(value)+1) == "false" ? "Comic Relief" : "Chivo Mono";
}

document.querySelector(":root").style.setProperty(
    "--font", getCookie("font")
);
function getCookie(value){
    cookies = document.cookies;
    return cookies.substring(cookies.indexof(value)+1);
}

document.querySelector(":root").style.setProperty(
    "font", getCookie("font")
);
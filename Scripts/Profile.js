// tabbed menu

function openSettings(evt, SettingsName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(SettingsName).style.display = "block";
  evt.currentTarget.className += " active";
}

//sets accessable font 
function accessFont(evt){
  checked = !checked;
  document.cookies = "font=".concat(checked, ";");
  let fontCheck = checked ? "Comic Relief" : "Chivo Mono";
  document.querySelector(":root").style.setProperty("--font", fontCheck);
  evt.currentTarget.checked = checked;
}
  
// Get the element with id="defaultOpen" and click on it

document.getElementById("defaultOpen").click();

//selects if accessable font is enabled
var checked = getCookie("font") == "true"
document.getElementById("font").checked = checked;
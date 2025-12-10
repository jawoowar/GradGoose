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
  fontCheck = evt.currentTarget.checked ? "Comic Sans" : "Chivo Mono";
  document.cookies = fontCheck;
  document.querySelector(":root").style.setProperty(fontCheck);
}
  
// Get the element with id="defaultOpen" and click on it

document.getElementById("defaultOpen").click();

//selects if accessable font is enabled
if(getCookie("font") == "Comic Sans"){
  document.getElementById("font").checked = true;
}
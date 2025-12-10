elem = document.getElementById("carousel");
numImages = length(elem.children);
size = elem.getBoundingClientRect();
scrollCount = Math.ciel((size.x/size.y)-numImages);

scrollInc = 0
setInterval(() => {
    elem.scrollTo(size.y*scrollInc, 0);
    scrollInc < scrollCount ? scrollInc++ : scrollInc = 0;
}, 5000);
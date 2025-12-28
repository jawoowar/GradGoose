var params = new URLSearchParams(window.location.search);
var offset = 0;

var wait = true;

function loadNew(){
    if(wait){
        wait = false;

        indata = {
            c: params.get("cost"),
            r: params.get("ratings"),
            nr: params.get("numRatings"),
            l: params.get("lo"),
            h: params.get("hi"),
            s: params.get("Search"),
            o: offset += 30
        };

        $.getJSON("../Scripts/filter.php", indata).done(
            (data) => {
                let newItems = [];

                for(item in Object.keys(data)){
                    item = data[item];

                    stores = Object.keys(item);
                    prices = stores.map((store) => {return item[store].price});
                    lo = Math.min(...prices).toFixed(2);
                    hi = Math.max(...prices).toFixed(2);

                    console.log(stores);

                    storestring = stores.map((store) => {
                        let cap = store[0].toUpperCase()+store.substring(1);
                        return `<div class="${store}">
                            <div class="${cap+"Wrap"}">
                                <a></a>
                                <span class="${cap+"Text"}">${cap}</span>
                            </div>
                        </div>`
                    });
                    storestring = storestring.join("\n");

                    productLink = "./product.html?id="+item[stores[0]].id;

                    newItems.push(`<div class="item" href="${productLink}"> <!--item placeholder/ base design-->
                        <div class="store">
                            ${storestring}
                        </div>

                        <a href="${productLink}"><img src="${item[stores[0]].image}" alt="placeholder"></a>
                        <p class="name">${item[stores[0]].name}</p>

                        <div class="lower">
                            <div class="price">
                                <h1>£${item[stores[0]].price.toFixed(2)}</h1>
                                <p>£${lo} - £${hi}</p>
                            </div>

                            <div class="Buttons">
                                <a href=""><img src="../Media/Plus.png" alt=""></a>
                                <a href=""><img src="../Media/Star.png" alt=""></a>
                            </div>
                        </div> 
                    </div>`);
                }

                products = document.getElementById("items");
                products.innerHTML = products.innerHTML+"\n\n"+newItems.join("\n\n");
            }
        );

        wait = true;
    }
}



function setSort(cost, numRatings, ratings){
    counter = 0;
    [cost, numRatings, ratings].forEach((sort) =>{
        sliders[counter].value = sort
        counter++
    })
}



document.getElementById("loadMore").addEventListener("click", () => {loadNew()});
var lo = document.getElementById("lo");
var hi = document.getElementById("hi");
var sliders = [document.getElementById("Cost"), document.getElementById("NumRatings"), document.getElementById("Ratings")];
lo.addEventListener("changed", () => {hi.min = lo.value});
hi.addEventListener("changed", () => {lo.max = hi.value});

var sorts = Array.from(document.getElementsByClassName("radial")).map((elem) => {
    return elem.children[0];
});
[[30, 40, 30], [100, 0, 0], [0, 100, 0], [0, 0, 100]].forEach((sort, index) => {
    sorts[index].addEventListener("click", (evt) => {evt.target.checked = true; setSort(...sort)});
});

function deselect(){
    sorts.forEach((sort) => {
        sort.checked = false;
    })
}

sliders.forEach((slider) => {
    console.log(sorts);
    slider.addEventListener("click", () => {deselect()})
});



function submit(search) {
    location.replace(location.origin+location.pathname+"?"+new URLSearchParams({
        lo: lo.value,
        hi: hi.value,
        cost: sliders[0].value,
        numRatings: sliders[1].value,
        ratings: sliders[2].value,
        search: search ? document.getElementById("Search").value : params.get("Search")
    }).toString());
}

document.getElementById("submit").addEventListener("click", () => {submit(false)});

loadNew();






elem = document.getElementsByClassName("Carasel")[0];
elem.scrollTo(0, 0);
elem.style.setProperty("scroll-behavior", "smooth");
//if the carousel starts with smooth behavior offset left doesnt work
children = Array.from(elem.children);
children = children.map((i) => {return i.offsetLeft});
numImages = children.length;
size = elem.getBoundingClientRect();
scrollCount = Math.floor(numImages-((size.width+(((size.width/size.height)*10)+10))/size.height));
//gets how many images that are in the container that arent currently fully shown


scrollInc = 0;
setInterval(() => {
    scrollInc < scrollCount ? scrollInc++ : scrollInc = 0;
    elem.scrollTo(children[scrollInc]-10, 0);
}, 5000);
//every 5 seconds, scrolls to 
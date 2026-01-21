var params = new URLSearchParams(window.location.search);
var offset = -5;

var previous;
var exists;

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
            o: offset += 5,
            p: JSON.stringify(previous),
            e: JSON.stringify(exists)
        };

        $.get("../Scripts/filter.php", indata,
            (data) => {
                let newItems = [];

                console.log(data);

                data = JSON.parse(data);

                console.log("test");

                for(item in Object.keys(data.real)){
                    item = data["real"][item];

                    stores = Object.keys(item);
                    prices = stores.map((store) => {return parseFloat(item[store].price)});
                    lo = Math.min(...prices).toFixed(2);
                    hi = Math.max(...prices).toFixed(2);
                    price = prices.reduce((out, cur) => {out??=0; return out+cur})/prices.length;

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

                    productLink = "./Product.php?id="+item[stores[0]].id;

                    newItems.push(`<div class="item" href="${productLink}"> <!--item placeholder/ base design-->
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
                    </div>`);
                }

                products = document.getElementById("items");
                products.innerHTML = products.innerHTML+"\n\n"+newItems.join("\n\n");

                previous = data.previous;
                exists = data.exists;
            });

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
var lo = document.querySelector("#lo");
var hi = document.querySelector("#hi");
var sliders = [document.getElementById("Cost"), document.getElementById("NumRatings"), document.getElementById("Ratings")];
lo.addEventListener("change", (e) => {document.querySelector("#hi").setAttribute("min", parseFloat(e.target.value));});
hi.addEventListener("change", (e) => {document.querySelector("#lo").setAttribute("max", parseFloat(e.target.value))});

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
        lo: document.querySelector("#lo").value,
        hi: document.querySelector("#hi").value,
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
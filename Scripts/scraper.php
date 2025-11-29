<?php

function outsideQuotes(string $haystack, string $needle, int $placement = 0):bool|int{
    
    while(true){
        $placement = min(
            strpos($haystack, "\"", $placement),
            strpos($haystack, "'", $placement),
            strpos($haystack, $needle, $placement)
        );

        $quoteType = $haystack[$placement];

        if(!in_array($quoteType, ["\"", "'"])){
            $placement = strpos($placement, $quoteType, $placement);
            if ($placement >= strlen($haystack) || $placement === false) {
                return false;
            }
        } else {
            return $placement;
        }
    }
}
//strpos, but skips all characters inside quotes to stop processing of strings containing html code
//as the html code needing to be read






class Element {

    readonly array $attributes;
    readonly string $id;
    readonly array $classes;
    public Element $parent;
    public array $children;

    public int $innerText { 
        get{
            $quer = outsideQuotes(self->full, "</".self->type.">");
            return substr(self->full, self->innerText, $quer ? $quer : 0);
        } 
        set {
            self->full = substr(self->full, 0, self->innerText)+$value+"</".self->type.">";
        }
    }
    //innertext gotten every time from substr of full element text

    public string $type {
        get;
        set {
            self->full = "<".$value.substr(self->full, strlen(self->type)+1);
            self->type = $value;
        }
    }

    public string $full { get; }


    public function __construct(string $elementText){
        self->full = $elementText;
        self->innerText = outsideQuotes($elementText, ">");
        self->type = substr($elementText, 1, min(
            strpos($elementText, " "),
            strpos($elementText, ">")
        )-1);

        $check = 0;
        $checkArray = [0];
        $attrArray = [];
        while($check != false){
            $check = strpos($elementText, "=", $check);
            array_push($checkArray, $check);
            try{
                $checkString = substr($elementText, $checkArray[0], $checkArray[1]);
                array_push($attrArray, 
                    substr($checkString, strlen($checkString)-strpos(strrev($checkString), " "))
                );
            }
            array_shift($checkArray);
            //gets names of all attributes by getting all strings between a space and = character
        }
        array_pop($attrArray);

        self->attributes = $attrArray;
        self->id = self.getAttribute("id");
        self->classes = explode(" ", self.getAttribute("class"));
    }


    public function getAttribute(string $attribute){
        if(in_array($attribute, self->attributes)){
            $offset = outsideQuotes(self->full, $attribute)+strlen($attribute)+1;
            return substr(self->full, $offset, outsideQuotes(self->full, " ", $offset));
        } else {
            return null;
        }
    }


    public function strval() {
        return self->full;
    }
}






class Html {

    static private array $voids = [
        "area", "base", "br", "col", "embed", 
        "hr", "img", "input", "link", "meta", 
        "param", "source", "track", "wbr", "!DOCTYPE",
        "!--"
    ];

    static private array $skips = [
        "script", "style"
    ];

    readonly array $elements;
    //elements stored with indexes reffering to their ids or classes, depending on the value of idSet
    private bool $idSet;



    private function compileElement(string $text, array $children=[]){
        $curElement = new Element($text);
        foreach($children as $elem){
            $elem->parent = $curElement;
        }
        $curElement->children = $children;
        $key = self->idSet ? $curElement->id : $curElement->classes;
        while (true){
            if(self->elements[$key] == null){
                break;
            } else {
                $key = $key."#";
            }
        }
        self->elements[self->idSet ? $curElement->id : $curElement->classes] = $curElement;
        return $curElement;
    }
    //creates an element object based on text and adds it to the array of all elements
    //adds children to the element and sets the parent of those children to the element



    public function __construct(string $url, bool $idSet=true, $context){
        $text = file_get_contents($url, false, $context);

        self->idSet = $idSet;

        $parentArray = [];
        $children = [];
        $offset = 0;
        $parentPossible = array_merge(self::skips, self::voids);
        while($offset != false){
            $end = false;
            $offset = outsideQuotes($text, "<", $offset)+$offset;
            //find next instance of <

            $type = substr($text, $offset+1, 
            min(strpos($text, " ", $offset), strpos($text, ">", $offset))-1
            );
            if($type[0] == "/"){
                $type = ltrim($type, "/");
                $end = true;
            }
            //get element type, if / is first character, remove it and signify its the ending of an element

            if($type == end($parentArray)[0] && $end){
                $offset = $offset + 1 + strlen($type);

                $len = end($parentArray)[1];
                $curElement = $this->compileElement(
                    substr($text, $len, $offset-$len),
                    end($children)
                );
                
                array_pop($children);
                array_push($children[count($children)-1], $curElement);
                array_pop($parentArray);

                //if starts with / and is the last addition to parentarray (heirarchy structure)
                //then copy whole string of the element and set it as element object
                //add contained children and parent, then remove from parents array
            } else if(in_array($type, $parentPossible)){
                $search = in_array($type, self::voids) ? ">" : "</$type>";
                $len = outsideQuotes($text, $search, $offset) + strlen($search) - $offset;
                unset($search);
                array_push(
                    $children[count($children)-1],
                    $this->compileElement(substr($text, $offset, $len))
                ); 
                $offset = $offset + $len;

                //if cant have parents, skip to end of element, depending on if innertext is possible or not
                //add the element
            } else {
                array_push($parentArray, [$type, $offset]);
                
                //if can have parents add to parent array
            }
        }
    }



    public function goTo($url): Html{
        $new = new Html($url, self->idSet);
        unset($this);
        return $new;
    }

}



function compileTescoItem($url){
    $link = new HTML($url, false);

    $dataCollected = [];
    /*
    name
    image
    price
    discount
    category
    ratings
    num ratings
    vegetarian/vegan
    weight (poundage)
    ingreedients
    */

    $curElement = $link->elements["ddsweb-heading o8cYgjDQW1wuuMi f-mPEa_heading f-mPEa_headlineXxxl"];
    $info = $curElement->innerText;
    $infoplace = strpos(strrev($info), " ")-strlen($info);
    $info = [
        substr($info, 0, $infoplace),
        substr($info, $infoplace)
    ];
    unset($infoplace);
    array_push(
        $dataCollected, 
        $info[0]
    );
    //name

    $curElement = $link->elements["m11ES32rZWLMMZ5"];
    $curElement = $curElement->children[0]->children[0]; //go 2 down
    array_push(
        $dataCollected, 
        $curElement->getAttribute("src")
    );
    //image

    $curElement = $link->elements["ddsweb-text wQ1gta_priceText wQ1gta_priceTextVertical MIOefW_text MIOefW_shortFormLg"];
    array_push(
        $dataCollected, 
        $curElement->innerText
    );
    //price

    $curElement = $link->elements["ddsweb-text ddsweb-value-bar__content-text AnNz0a_contentText AnNz0a_singleLineEllipse MIOefW_text MIOefW_shortFormMd"];
    array_push(
        $dataCollected, 
        $curElement->innerText
    );
    //discount

    $curElement = $link->elements["ddsweb-breadcrumb__list v8ppzq_list v8ppzq_tabletLargeDisplayNoneMax"];
    $curElement = $curElement->children[count($curElement->children)-2]->children[0]->children[0];
    //go to 2nd to last child, then go down 2
    array_push(
        $dataCollected, 
        $curElement->innerText
    );
    //category

    $curElement = $link->elements["ddsweb-text _5Hmf9W_hint ddsweb-rating__hint MIOefW_text MIOefW_shortFormSm"];
    $curElement = floatval($curElement->innerText);
    array_push(
        $dataCollected, 
        $curElement
    );
    //ratings

    $curElement = $link->elements["ddsweb-text _5Hmf9W_hint ddsweb-rating__hint MIOefW_text MIOefW_shortFormSm"];
    try{
        $curElement = $curElement->children[0]->innerText;
        $curElement = floatval($curElement);
    } catch(Exception $e) {
        unset($e);
        $curElement = 0;
    }
    array_push(
        $dataCollected, 
        $curElement
    );
    //num ratings

    $curElement = $link->elements["ddsweb-text HUQ7XYZAWa_Nd5d MIOefW_text MIOefW_shortFormSm"];
    if(!$curElement == null){
        $curElement = $curElement->innerText;
    }
    array_push(
        $dataCollected, 
        $curElement
    );
    //vegetarian / vegan

    $info = intval($info[1])*($info[1][strlen($info[1])-2] == "k" ? 1000 : 1);
    array_push(
        $dataCollected,
        $info
    );
    //weight (poundage)

    //ingreedients

    return $dataCollected;
}
//gets info on a tesco item via its url


function getTescoItems(){
    $checkNum = new HTML("https://www.tesco.com/groceries/en-GB/shop/food-cupboard/all?sortBy=relevance&page=1&count=48#top", false);
    $checkNum = $checkNum->elements["XipULG_unorderedList"];
    $checkNum = floatVal($checkNum->children[count($checkNum->children)-2]->children[0]->children[0]->innerText);

    $allItems = [];

    for ($pageNum = 1; $pageNum <= $checkNum; $pageNum++){
        $page = new HTML("https://www.tesco.com/groceries/en-GB/shop/food-cupboard/all?sortBy=relevance&page=".$pageNum."&count=48#top", false);
        $itemClasses = "_64Yvfa_titleLink ddsweb-link ddsweb-link__anchor ddsweb-link__inline ZEu1JW_inlineLink ZEu1JW_link";
        while(true){
            $item = $page->elements[$itemClasses];
            if ($item == null){
                break;
            } else {
                array_push($allItems, compileTescoItem($item->getAttribute("href")));
                $itemClasses = $itemClasses."#";
            }
        }
    }
}




function compileLidlItem($json, $category) {
    $dataCollected = [];
    //https://www.lidl.co.uk/q/api/category/h/snacks-confectionery/h10071044?assortment=GB&locale=en_GB&version=v2.0.0
    /*
    name
    image
    price
    discount
    category
    ratings
    num ratings
    vegetarian/vegan
    weight (poundage)
    ingreedients
    */

    array_push(
        $dataCollected, 
        $json["label"]
    );
    //name

    array_push(
        $dataCollected, 
        $json["gridbox"]["data"]["image"]
    );
    //image

    array_push(
        $dataCollected, 
        $json["gridbox"]["data"]["price"]["price"]
    );
    //price

    array_push(
        $dataCollected, 
        ""
    );
    //discount

    array_push(
        $dataCollected, 
        $category
    );
    //category

    array_push(
        $dataCollected, 
        0
    );
    //ratings

    array_push(
        $dataCollected, 
        0
    );
    //num ratings
    //lidl does not support ratings

    array_push(
        $dataCollected, 
        ""
    );
    //vegetarian/vegan
    //lidl does not support this label

    array_push(
        $dataCollected, 
        1000*$dataCollected[2]/floatval(ltrim($json["gridbox"]["data"]["price"]["basePrice"]["text"], "£"))
    );
    //weight (poundage)

    //ingreedients

    return $dataCollected;
}


function getLidlItems(){
    $lidlLink = "https://www.lidl.co.uk";

    $pages = new HTML("https://www.lidl.co.uk/h/fresh-fruit-vegetables/h10071012", false);
    $pages = $pages->elements["n-header__main-navigation n-header__main-navigation--sub"];
    array_shift($pages->children);
    $pages = array_map(fn($element) => $lidlLink.$element->children[0]->getAttribute("href"), $pages->children);
    //gets all catagories needed to look through

    $allItems = [];

    foreach($pages as $page){
        $urlSection = substr($page, strpos($page, "/h")+2);
        $page = new HTML($page, false);
        $items = intval($page->elements["s-products-count-sort__count"]->innerText);

        foreach(range(0, $items, 48) as $offset){
            $itemInfo = file_get_contents(
                "https://www.lidl.co.uk/q/api/category/".$urlSection."?offset=".$offset."&fetchsize=48&locale=en_GB&assortment=GB&version=2.1.0"
            );
            $itemInfo = json_decode($itemInfo);
            foreach($itemInfo["items"] as $item){
                array_push($allItems, compileLidlItem($item, $page));
            }
        }
    }

    return $allItems;
}




function getValues($data){
    rsort($data);
    $high = array_first($data);
    $data = array_map(fn($val) => $val/$high, $data);
    return $data;
}



/*
echo "Lidl:\n";

foreach(getLidlItems() as $item){
    echo $item;
}

echo "\n\nTesco:\n";

foreach(getTescoItems() as $item){
    echo $item;
}
*/

$testCode = new Html("/test.html");
echo $testCode->elements["fort"]->innerText;

?>
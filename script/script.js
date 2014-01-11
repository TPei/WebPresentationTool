/**
 * @author Thomas Peikert
 * jquery-esque $ function
 * @param selector expects #/. + id/classname to select elements or just a valid elementselector
 * @returns {*} the requested element
 */
function $(selector){
    if(selector == 'body')
    {
        return document.body;
    }
    else if(selector.substring(0, 1) == '#')
    {
        return document.getElementById(selector.substring(1));
    }
    else if(selector.substring(0, 1) == '.')
    {
        return document.getElementsByClassName(selector.substring(1));
    }
    else
    {
        console.log(document.getElementsByName(selector));
        return document.getElementsByName(selector);
    }

}

/*
function transformSlideSize()
{
    var baseWidth = 800;
    var baseHeight = 600;
    var width = window.innerWidth - 370 ;
    var height = window.innerHeight - 30; // 40px for navigation size

    var widthFactor = width / baseWidth;
    var heightFactor = height / baseHeight;

    console.log("width: "+width);
    console.log("height: "+height);
    console.log("widthFactor: "+widthFactor);
    console.log("heightFactor: "+heightFactor);

    var factor = Math.min(widthFactor, heightFactor);

    console.log("factor: "+factor);

    //$('.slide').style.transform = "scale("+factor+", "+factor+")";
    //$('.slide').style.webkitTransform = "scale("+factor+", "+factor+")";
    var slide = $('#activeSlideContainer');
    slide.style.transform = 'scale('+factor+')';
    slide.style.webkitTransform = 'scale('+factor+')';
    slide.parentNode.style.width = parseInt(slide.offsetWidth*factor) + 'px';
    slide.parentNode.style.height = parseInt(slide.offsetHeight*factor) + 'px';

}

window.onresize = function()
{
    transformSlideSize();
}*/
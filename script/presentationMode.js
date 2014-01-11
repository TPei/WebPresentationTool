window.onload = function () {
    document.onkeydown = function (event) {
        event.preventDefault();

        switch (event.keyCode) {
            case 13:
                endPresentation();
                break;
            case 37:
                changeSlide('previous');
                break;
            case 38:
                changeSlide('first');
                break;
            case 39:
                changeSlide('next');
                break;
            case 40:
                changeSlide('last');
                break;
        }

        return false;
    }

    //transformSlideSize();
}
/*
function transformSlideSize()
{
    var baseWidth = 800;
    var baseHeight = 600;
    var width = window.innerWidth;
    var height = window.innerHeight - $('#presentationNavigation').style.height; // 40px for navigation size

    var widthFactor = width / baseWidth;
    var heightFactor = height / baseHeight;

    console.log("width: "+width);
    console.log("height: "+height);
    console.log("widthFactor: "+widthFactor);
    console.log("heightFactor: "+heightFactor);

    var factor = Math.min(widthFactor, heightFactor);

    console.log("factor: "+factor);

    /*$('.slide').style.transform = "scale("+factor+", "+factor+")";
    $('.slide').style.webkitTransform = "scale("+factor+", "+factor+")";*/
    /*var slide = $('#activeSlideContainer');
    slide.style.transform = 'scale('+factor+')';
    slide.style.webkitTransform = 'scale('+factor+')';
    console.log(slide.offsetWidth + 'px');
    slide.parentNode.style.width = parseInt(slide.offsetWidth*factor) + 'px';
    slide.parentNode.style.height = parseInt(slide.offsetHeight*factor) + 'px';

}

window.onresize = function()
{
    transformSlideSize();
}*/
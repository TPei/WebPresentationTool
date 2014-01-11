/**
 * @author Thomas Peikert
 */

function changeView(view)
{
    var url = "ajax.php";
    var data = {};

    data.handler = 'view';
    data.action = 'changeView';
    data.view = view;

    console.log("changing template to "+view);

    Ajax.post(data, url, function(json){

        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            $('#mainView').innerHTML = json.html;
            if(view == 'show')
            {
                // change active slide on key press (arrow keys) and end presentation (enter)
                document.onkeydown = function(event) {
                    event.preventDefault();

                    switch (event.keyCode)
                    {
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
            }
            else if(view = 'spectate')
            {
                /*transformSlideSize();
                window.onresize = function()
                {
                    transformSlideSize();
                }
                console.log('transformed');*/
            }
        }
    });
}
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
     $('.slide').style.webkitTransform = "scale("+factor+", "+factor+")";
    $('#activeSlideContainer').style.transform = 'scale('+factor+')';
    $('#activeSlideContainer').style.webkitTransform = 'scale('+factor+')';*/

}

function refreshEditorSlide(id)
{
    console.log("refresh dat slide");
    var url = "ajax.php";
    var action = "changeSlide";


    console.log("slide id: "+id);

    var data = {};
    data.action = action;
    data.id = id;
    data.handler = 'slide';

    Ajax.post(data, url, function(json){

        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            // refresh active slide
            $('#activeSlideContainer').innerHTML = json.html;

            // refresh active slide thumbnail
            // .slidesContainer .slide[data-id=json.id]
            //$('.slidesContainer').
        }
    });

}
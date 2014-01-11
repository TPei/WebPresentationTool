/**
 * @author Thomas Peikert
 */

/**
 * triggered when new slide button is pressed
 * ajax request to add new slide to presentation
 * => update editor view
 * => to keep the active slide, the active slide value is also sent
 * @param presentation
 */
function addSlide(presentation){
    console.log("new slide for: "+presentation);

    var data = {};
    data.action = 'addSlide';
    data.presentationId = presentation;
    data.handler = 'presentation';

    var url = 'ajax.php';

    Ajax.post(data, url, function(json){
        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            // refresh slide thumbnail container
            var data = {};
            data.id = json.presentationId;

            var slide = json.slideId;
            data.view = "editor";
            data.action = 'changeView';

            data.handler = 'view';
            var url = "ajax.php";

            Ajax.post(data, url, function(json){
                $('#mainView').innerHTML = json.html;
                document.title = json.title;


                var url = "ajax.php";
                var action = "changeSlide";


                var data = {};
                data.action = action;
                data.id = slide;
                data.handler = 'slide';

                // go back to previously active slide
                Ajax.post(data, url, function(json){

                    if(json.error){
                        $('#activeSlideContainer').innerHTML = json.error;
                    }
                    else
                    {
                        $('#activeSlideContainer').innerHTML = json.html;
                    }
                });
            })
        }
    });
}

/**
 * shows link generator box
 */
function getLink()
{
    $('#linkInfo').style.display = 'block';
    $('#linkInfoWrapper').style.display = 'block';
}

function hideLink()
{
    $('#linkInfo').style.display = 'none';
    $('#linkInfoWrapper').style.display = 'none';
}

function hidePresentationDialog()
{
    $('#newPresentation').style.display = 'none';
}

function generateLink()
{
    // linkTitel
    // linkRef
    var title = $('#linkTitle').value;
    var link = $('#linkRef').value;

    $('#linkTitle').value = "";
    $('#linkRef').value = "";

    $('#linkInfo').style.display = 'none';
    $('#linkInfoWrapper').style.display = 'none';

    console.log(title);
    console.log(link);


    var data = {};
    data.action = 'addLinkToSlide';
    data.title = title;
    data.link = link;

    data.handler = 'presentation';
    var url = 'ajax.php';

    Ajax.post(data, url, function(json){
        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            // refresh acive slide container
            var url = "ajax.php";
            var action = "changeSlide";
            var slide = json.id;


            console.log("slide id: "+slide);

            var data = {};
            data.action = action;
            data.id = slide;
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
    });
}

function getList()
{
    $('#listInfo').style.display = 'block';
    $('#listInfoWrapper').style.display = 'block';
}

function hideList()
{
    $('#listInfo').style.display = 'none';
    $('#listInfoWrapper').style.display = 'none';
}

function generateList()
{
    console.log("generate List");
    // linkTitel
    // linkRef
    var element = $('#elements').value;
    var unordered = $('#unordered').checked;

    $('#elements').value = "";

    $('#listInfo').style.display = 'none';
    $('#listInfoWrapper').style.display = 'none';

    var elements = element.split("&");

    var data = {};
    data.action = 'addListToSlide';
    data.elements = elements;
    data.unordered = unordered;

    data.handler = 'presentation';
    var url = 'ajax.php';

    Ajax.post(data, url, function(json){
        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            // refresh active slide container
            refreshEditorSlide(json.id)
        }
    });
}


function addElement()
{
    var e = $("#newElement");
    var elementName = e.options[e.selectedIndex].value;
    addElementAction(elementName);
}

/**
 * add element to presentation
 * triggered through element sidebar
 * send ajax request to trigger element to active slide
 * @param elementName
 */
function addElementAction(elementName){
    if(elementName=='a')
    {
        getLink();
        return;
    }
    if(elementName == 'li')
    {
        getList();
        return;
    }

    // calculate needed div size in order to avoid rendering issues
    var text = "Text";

    var data = {};
    data.action = 'addElementToSlide';
    data.element = elementName;
    data.text = text;

    data.handler = 'presentation';
    console.log(elementName);
    var url = 'ajax.php';

    Ajax.post(data, url, function(json){
        if(json.error){
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else
        {
            // refresh active slide container
            refreshEditorSlide(json.id);

        }
    });

}

function calculateWidth(text)
{
    var div = $('#lengthCalculator');
    div.innerHTML = text;

    div.style.fontSize = 20;
    var width = (div.clientWidth + 1);

    return width;
}

function calculateHeight(text)
{
    var div = $('#lengthCalculator');
    div.innerHTML = text;

    div.style.fontSize = 20;
    var height = (div.clientHeight + 1);

    return height;
}
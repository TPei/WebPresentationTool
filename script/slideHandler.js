/**
 * @author Thomas Peikert
 */

var selectedElement = null;
var selectedSlide = null;

function changeSlide(command) {
    // gets next or previous
    var url = "ajax.php";
    var action = "changePresentationSlide";

    var data = {};
    data.action = action;
    data.command = command;
    data.handler = "slide";


    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            $('#activeSlideContainer').innerHTML = json.html;
        }
    });

}

/**
 * click a slide in slide thumbnails -> change slide
 * @param id
 */
function showSlide(id) {
    // changing slide -> reset selected element
    selectedElement = null;

    if (selectedSlide != null) {
        selectedSlide.classList.remove('uiSlideSelected');
    }

    selectedSlide = $('#' + id);
    selectedSlide.classList.add('uiSlideSelected');

    var action = "changeSlide";

    var data = {};
    data.action = action;
    data.id = id;
    data.handler = "slide";

    Ajax.post(data, "ajax.php", function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            $('#activeSlideContainer').innerHTML = json.html;
        }
    });

    data = {};

    data.handler = 'slide';
    data.action = 'getSlideIndex';

    var url = "ajax.php";
    console.log("get slide index");
    Ajax.post(data, url, function (json) {
        console.log("got slide index: " + json.index);
        $('#indexPicker').value = json.index;
    })
}

/**
 * select an element in a slide -> make editable
 * @param element
 */
function selectElement(element) {
    console.log('new element selected');
    // change class of selected (and unselected element) for styling
    // and make / unmake content editable
    // and save to database
    if (selectedElement != null) {
        selectedElement.classList.remove('uiSelected');
        selectedElement.classList.add('drag');

        // set selectedElement to uneditable, then saving the changed contents
        selectedElement.setAttribute("contentEdiatable", false);
        changeText(selectedElement);
    }

    selectedElement = element;

    element.classList.add("uiSelected");
    selectedElement.classList.remove('drag');
    element.setAttribute("contentEditable", true);
    console.log(element);

    //var elementText = element.getElementsByClassName('textnode')[0].innerHTML;
    var elementText = element.innerHTML;
    console.log(elementText);
    var elementId = element.dataset.id;
    console.log(elementId);

    $('#elementText').value = elementText;
    $('#elementId').value = elementId;


    // widthPicker, heightPicker, xPicker, yPicker
    $('#widthPicker').value = element.offsetWidth;
    $('#heightPicker').value = element.offsetHeight;

}

/**
 * triggered by a click on a slide
 * call function to update selected item
 * and unselect it
 */
function unselectElement() {
    console.log('element unselected!');
    if (selectedElement != null) {
        selectedElement.classList.remove('uiSelected');
        selectedElement.classList.add('drag');

        // set selectedElement to uneditable, then saving the changed contents
        selectedElement.setAttribute("contentEditable", false);
        changeText(selectedElement);
    }
    selectedElement = null;
}

/**
 * triggered by elementdimension number picker
 * ajax request to update dimensions in databse
 */
function editElementDimensions() {
    console.log("edit element dimensions");
    var elementId = $('#elementId').value;
    console.log(elementId);
    var element = $('#' + elementId);

    console.log(element);

    element.style.width = $('#widthPicker').value + "px";

    console.log(element);

    var data = {};
    data.action = "editElementDimensions";
    data.id = element.dataset.id;
    data.width = element.offsetWidth;
    data.handler = "slide";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            refreshEditorSlide(json.id);
        }
    });
}

function changeSlideIndex()
{
    var newIndex = $('#indexPicker').value;

    var data = {};
    data.action = "changeSlideIndex";
    data.index = newIndex;

    data.handler = "presentation";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.success){
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
 * triggered from mouseup after dragging an element
 * request element position update through ajax
 * @param selectedElement
 */
function editElementPosition(selectedElement) {
    var left = selectedElement.getBoundingClientRect().left;
    var top = selectedElement.getBoundingClientRect().top;


    var data = {};
    data.action = "editElementPosition";
    data.id = selectedElement.dataset.id;
    data.left = left;
    data.top = top;
    data.handler = "slide";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            // only updates database document, no callback action required
        }
    });
}

/**
 * request to update an elements' text in database
 * @param element
 */
function changeText(element) {
    //var text = element.getElementsByClassName('textnode')[0].innerHTML;
    var text = element.innerHTML;
    var elementId = element.dataset.id;

    console.log("neuer Text für Element " + elementId + " ist " + text);


    var div = $('#lengthCalculator');
    div.innerHTML = element;
    var width = calculateWidth(element);
    var height = calculateHeight(element);

    var data = {};
    data.action = "editElement";
    data.elementId = elementId;
    data.text = text;
    data.height = height;
    data.width = width;
    data.handler = "slide";
    var url = "ajax.php";


    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            // only updates database document, no callback action required
            console.log("Neuer Text: " + json.text);
        }
    });
}

/**
 * request the deletion of an element
 * refresh editor view
 * @param id
 */
function deleteElement(id) {
    var elementId = $('#' + id).value;

    var data = {};
    data.action = "deleteElement";
    data.elementId = elementId;

    data.handler = "slide";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            // update slide
            $('#activeSlideContainer').innerHTML = json.html;
        }
    });
}

/**
 * request deletion of active slide
 */
function deleteSlide() {
    var data = {};
    data.action = "deleteActiveSlide";
    data.handler = "slide";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            // update slide
            // reload updated presentation
            showPresentation(json.id);
            console.log(json.slideId);
        }
    });
}

function duplicateSlide() {
    var data = {};
    data.action = "duplicateActiveSlide";
    data.handler = "slide";
    var url = "ajax.php";

    Ajax.post(data, url, function (json) {

        if (json.error) {
            $('#activeSlideContainer').innerHTML = json.error;
        }
        else {
            // update slide
            // reload updated presentation
            showPresentation(json.id);
        }
    });
}
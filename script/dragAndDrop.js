/**
 * @author Thomas Peikert
 */

var _startX = 0;            // mouse starting positions
var _startY = 0;
var _offsetX = 0;           // current element offset
var _offsetY = 0;
var _dragElement;           // needs to be passed from onMouseDown to onMouseMove
var _oldZIndex = 0;


InitDragDrop();

/**
 *
 * @constructor
 */
function InitDragDrop()
{
    document.onmousedown = onMouseDown;
    document.onmouseup = OnMouseUp;
}

/**
 * register clicked element and it's positions
 * also show width and height in box made for changing those sizes
 * @param e
 * @returns {boolean}
 */
function onMouseDown(e)
{
    var target = e.target;

    // for Firefox, left click == 0
    if (e.button == 0 && target.classList.contains('drag'))
    {
        // grab the mouse position
        _startX = e.clientX;
        _startY = e.clientY;

        // grab the clicked element's position
        _offsetX = getNumber(target.style.left);
        _offsetY = getNumber(target.style.top);

        // show element width / height in size picker
        $('#widthPicker').value = target.offsetWidth;
        $('#elementId').value = target.dataset.id; // and add id in field for updating or deleting

        // show that the item is selected
        target.classList.add("uiSelected");

        // bring the clicked element to the front while it is being dragged
        _oldZIndex = target.style.zIndex;
        target.style.zIndex = 10000;

        // we need to access the element in onMouseMove
        _dragElement = target;

        // tell our code to start moving the element with the mouse
        document.onmousemove = onMouseMove;

        // cancel out any text selections
        //document.body.focus();

        // prevent text selection in IE
        document.onselectstart = function () { return false; };

        // prevent image drag actions
        target.ondragstart = function() { return false; };

        // prevent text selection (except IE)
        return false;
    }
}

/**
 * caluclate the element's new position
 * @param e
 */
function onMouseMove(e)
{
    e.preventDefault();
    if (e == null)
        var e = window.event;


    // keep element inside slide
    var elementWidth =  _dragElement.offsetWidth;
    var elementHeight = _dragElement.offsetHeight;
    // slide coordinates - elementsizes
    var minX = 0, minY = 55, maxX = 800 - elementWidth, maxY = 600 - elementHeight;
    var left = Math.min(maxX, Math.max(minX, (_offsetX + e.clientX - _startX)));
    var top = Math.min(maxY, Math.max(minY, (_offsetY + e.clientY - _startY)));

    // set styling on slide
    _dragElement.style.left =  left + 'px';
    _dragElement.style.top =  top + 'px';
}

/**
 * stop element move actions
 * trigger database update
 * @param e
 * @constructor
 */
function OnMouseUp(e)
{
    if (_dragElement != null)
    {
        e.preventDefault();
        // update element position in database
        editElementPosition(_dragElement);

        _dragElement.style.zIndex = _oldZIndex;

        // we're done with these events until the next onMouseDown
        document.onmousemove = null;
        document.onselectstart = null;
        _dragElement.ondragstart = null;

        // this is how we know we're not dragging
        _dragElement = null;

    }
}

/**
 * convert element style to int
 * @param value
 * @returns {*}
 */
function getNumber(value)
{
    var n = parseInt(value);

    if(n == null || isNaN(n))
    {
        return 0;
    }
    else
    {
        return n
    }
}

/**
 * @author Thomas Peikert
 */

/**
 * ajax request to display a selected presentation
 * @param id
 */
function showPresentation(id){
    var data = {};
    data.id = id;

    data.view = "editor";

    data.handler = 'view';
    data.action = 'changeView';

    var url = "ajax.php";
    console.log("show presentation request");
    Ajax.post(data, url, function(json){
        $('#mainView').innerHTML = json.html;
        document.title = json.title;
    })
}

/**
 * hide "no presentation div"
 * show new presentation dialog
 * add eventListener
 */
function addPresentationDialog(){
    $('#newPresentation').style.display = 'block';

    // fails if noPresentations div isn't included in current template
    try
    {
        $('#noPresentations').style.display = 'none';
    }
    catch(err)
    { }

    $('#newPresentationButton').addEventListener("click", function(event){
        event.preventDefault();
        createPresentation();
        return false;
    }, false);
}

function changePresentationDialog(id){
    $('#changePresentation').style.display = 'block';


    $('#changePesentationButton').addEventListener("click", function(event){
        event.preventDefault();
        changePresentation();
        uploadLogo();
        return false;
    }, false);

    var data = {};
    data.action = "getPresentationInfo";
    data.id = id;

    data.handler = 'presentation';

    var url = "ajax.php";
    Ajax.post(data, url, function(json){
        $('#newTitle').innerHTML = json.title;
        $('#newDescription').innerHTML = json.description;
        $('#newDescription').dataset.id = id;
    })


}

/**
 * post info from new presentation dialog and create a presentation accordingly
 */
function createPresentation(){
    console.log("new presentation");
    $('#newPresentation').style.display = 'none';

    var data = {};
    data.action = "createPresentation";
    data.title = $('#title').value;
    data.description = $('#description').value;


    data.handler = 'presentation';

    var url = "ajax.php";
    console.log('create presentation request');
    Ajax.post(data, url, function(json){
        console.log('callback');
        changeView('index');
    })
}

/**
 * change a presentation's base info
 */
function changePresentation(){
    console.log("new presentation");
    $('#changePresentation').style.display = 'none';

    var data = {};
    data.action = "changePresentation";
    data.title = $('#newTitle').value;
    data.description = $('#newDescription').value;
    data.id = $('#newDescription').dataset.id;

    data.handler = 'presentation';

    var url = "ajax.php";
    console.log('create presentation request');
    Ajax.post(data, url, function(json){
        console.log('callback');
        changeView('index');
    })
}

/**
 * delete a presentation by given id
 * @param id
 */
function deletePresentation(id)
{
    console.log("ID der zu löschenden Präsentation ist: "+id);

    var data = {};
    data.action = "deletePresentation";
    data.presentation = id;


    data.view = "editor";
    data.handler = 'presentation';

    var url = "ajax.php";
    console.log('delete presentation request');
    Ajax.post(data, url, function(json){
        if(json.success)
            changeView('index');
    })
}

/**
 * start the active presentation
 */
function givePresentation()
{
    var data = {};
    data.action = "startActivePresentation";
    data.handler = "presentation";
    var url = "ajax.php";
    console.log('give presentation request');
    Ajax.post(data, url, function(json){
        console.log("success: " + json.success);
        if(json.success)
            //changeView('show');
            window.open('presentationMode.php', '_blank');
    })
}

/**
 * start a presentation with given id
 * @param id
 */
function giveThisPresentation(id)
{
    var data = {};
    data.action = "startPresentation";
    data.id = id;
    data.handler = "presentation";
    var url = "ajax.php";
    console.log('give presentation request');
    Ajax.post(data, url, function(json){
        console.log("success: " + json.success);
        if(json.success)
            //changeView('show');
            window.open('presentationMode.php', '_blank');
    })
}

/**
 * close the presentation window
 */
function endPresentation()
{
    var data = {};
    data.action = "endPresentation";

    data.handler = "presentation";
    var url = "ajax.php";

    Ajax.post(data, url, function(json){
        if(json.success)
            //changeView('index');
            window.close();
    })
}

/**
 * spectate a presentation
 * requests update every second
 * -> only updates view if the slide was changed
 * ends spectating if presentation was stopped
 * @param id the spectated presentation's id
 * @param slideNo the previous slide number
 */
function spectatePresentation(id, slideNo)
{
    var data = {};
    data.action = "spectatePresentation";
    data.id = id;

    data.handler = "presentation";
    var url = "ajax.php";

    Ajax.post(data, url, function(json){
        if(json.active)
        {
            // only refresh if the new slide is not the current one
            if(json.slideNumber !== slideNo)
                $('#mainView').innerHTML = json.html.substring(22);

            // refresh every second
            setTimeout(function() {
                spectatePresentation(id, json.slideNumber);
            }, 1000);
        }
        else
        {
            $('#mainView').innerHTML = "<h1>Die Präsentation wurde beendet</h1>";
            setTimeout(function(){
                changeView('index');
            },1000);
        }
    })
}

function copyPresentation(id)
{
    var data = {};
    data.action = "copyPresentation";
    data.id = id;

    data.handler = "presentation";
    var url = "ajax.php";

    console.log("ID: "+id);
    Ajax.post(data, url, function(json){
        changeView('index');
    })
}

function hidePresentationChangeInfo()
{
    $('#changePresentation').style.display = 'none';
}
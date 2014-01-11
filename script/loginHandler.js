/**
 * @author Thomas Peikert
 */

/**
 * add event to login button
 */
window.onload = function () {
    $('#submit').addEventListener("click", function (event) {
        event.preventDefault();
        submitLogin();
        return false;
    }, false);

    $('#spectateButton').addEventListener("click", function(event) {
        event.preventDefault();
        changeView('spectate');
        return false;
    }, false);
};

/**
 * login / register function
 * triggered by login button
 */
function submitLogin()
{
    var register = $('#register').checked;
    var action;
    if(register){
        action = "register";
    }else{
        action = "login";
    }

    var data = {};
    data.action = action;
    data.username = $('#username').value;
    data.password = $('#password').value;
    data.handler = 'login';

    console.log("schicke request");

    var url = 'ajax.php';

    Ajax.post(data, url, function(json){

        if(json.error){
            $('#response').innerHTML = json.error;
        }
        else if(json.registered)
        {
            $('#response').innerHTML = json.registered;
        } else {
            $('#mainView').innerHTML = json.html;
        }
    });


}
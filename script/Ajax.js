/**
 * @author Thomas Peikert
 */

/**
 * @constructor
 */
function Ajax(){}

/**
 * ajax post request
 * @param data being send to php
 * @param url target php script
 * @param callback php response
 */
Ajax.post = function(data, url, callback){

    // starts ajax request
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = JSON.parse(xmlhttp.responseText);

            callback(response);
        }
    };
    var ajax = {};
    ajax.ajax = data;

    console.log("reuqest wird in ajax nun geschickt");
    console.log("url: "+url);
    console.log("data: "+JSON.stringify(data));

    // request
    xmlhttp.open("POST",url,true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("ajax="+JSON.stringify(data));
}

/**
 * Ajax get request
 * @param data
 * @param url
 * @param callback
 */
Ajax.get = function(data, url, callback){

    // starts ajax request
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            var response = JSON.parse(xmlhttp.responseText);

            callback(response);
        }
    };
    var queryString = url+"?"+generateQuerystring(data);

    // request
    xmlhttp.open("GET", queryString,true);
    xmlhttp.send();

}

/**
 * generate a queryString from a given key, value array
 * @param data given key value array
 * @returns {string} querystring
 */
function generateQuerystring(data)
{
    var queryString = "";
    for(var key in data)
    {
        queryString += key + "=" + data[key] + "&";
    }

    // remove last "&" sign
    return queryString.substring(0, queryString.length-1);
}
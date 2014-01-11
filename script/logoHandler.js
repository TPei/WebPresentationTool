/**
 * @author Thomas Peikert
 */
/**
 * @author Thomas Peikert
 */
// monitor changes to the dom subtree and
// try to add an event listener to the file upload field
document.addEventListener("DOMSubtreeModified", function(e) {
    try
    {
        $('#logoUploadForm').addEventListener('change', function (event) {
            file = event.target.files[0];
            console.log("file: "+file);
            handleFileSelect(event);
        }, false);
    }
    catch(exc)
    { }
}, false);

var file = null;


function handleFileSelect(evt) {
    var files = evt.target.files; // FileList object

    // Loop through the FileList and render image files as thumbnails.
    for (var i = 0, f; f = files[i]; i++) {

        // Only process image files.
        if (!f.type.match('image.*')) {
            continue;
        }

        var reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                // Render thumbnail
                $('#thumbnail').innerHTML = '<img class="thumbnail" src="'+ e.target.result +'">';
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}


function uploadLogo() {
    if(file == null){
        // no image added, don't update logo
        return;
    }
    var url = "uploadLogo.php";

    var fieldName = "logoUploadForm";
    var formData = new FormData();
    formData.append(fieldName, file);
    formData.append('id', $('#newDescription').dataset.id);

    var request = new XMLHttpRequest();

    request.onload = function (e) {
        console.log(e);
    };

    request.upload.onprogress = function (e) {
        console.log(e);
    };

    request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
            var json = JSON.parse(request.responseText);
            console.log("id: "+json.id);
            if(json.success){
                $('#activeSlideContainer').innerHTML = json.html
            }
            else
                console.log("FEHLER");
        }
    };

    request.open("POST", url);
    request.send(formData);
}
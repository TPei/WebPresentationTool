/**
 * @author Thomas Peikert
 */

// monitor changes to the dom subtree and
// try to add an event listener to the file upload field
document.addEventListener("DOMSubtreeModified", function(e) {
    try
    {
        $('#imageUploadForm').addEventListener('change', function (event) {
            file = event.target.files[0];
            console.log("file: "+file);
            handleFileSelectInSlide(event)
        }, false);
    }
    catch(exc)
    { }
}, false);

var file = null;

function handleFileSelectInSlide(evt) {
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
                $('#thumbnailInSlide').innerHTML = '<img class="thumbnail" src="'+ e.target.result +'">';
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    }
}

function uploadFile() {
    if(file == null){
        alert("Keine Datei ausgewählt!");
        return;
    }

    var url = "uploadImage.php";

    var fieldName = "imageUploadForm";
    var formData = new FormData();
    formData.append(fieldName, file);

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

            if(json.success){
                $('#activeSlideContainer').innerHTML = json.html;
            }
            else
                console.log("FEHLER");
        }
    };

    $('#thumbnailInSlide').innerHTML = "";
    request.open("POST", url);
    request.send(formData);
}
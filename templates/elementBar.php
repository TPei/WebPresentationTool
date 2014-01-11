<div class="elementBar">
    <div>
        <ul>
        <li>
            <a class="addSlide" onclick="addSlide(id)">Neue Folie</a>
        </li>
        </ul>
    </div>

    <div class="selectElement">
        <p>Neues Element:</p>

        <select id="newElement">
            <option value="h1">große Überschrift</option>
            <option value="h3">kleine Überschrift</option>
            <option value="p">Absatz</option>
            <option value="q">Zitat</option>
            <option value="a">Link</option>
            <option value="li">Liste</option>
            <option value="hr">Trennlinie</option>
        </select>

        <button onclick="addElement();">platzieren</button>
    </div>

    <div class="selectElement">
        <p>Bild einfügen</p>
        <input type="file" id="imageUploadForm" name="imageUploadForm"/>
        <button onclick="uploadFile()">Upload</button>
        <div id="thumbnailInSlide"></div>
    </div>

    <div class="selectElement">
        <!-- noch umarbeiten!!! -->
        <p>ausgewähltes Element</p>
        <input style="display: none" type="text" value="placeholder" id="elementText">
        <button style="display: none" id="elementId" onclick="changeText(this.id);">erneuern</button>
        <button id="elementId" onclick="deleteElement(this.id);">löschen</button>

        <br/>
        width: <input type="number" onchange="editElementDimensions(this)" id="widthPicker" value="0">
    </div>

    <div class="selectElement">
        <!-- noch umarbeiten!!! -->
        <p>ausgewählte Folie</p>
        <button id="slideId" onclick="deleteSlide();">löschen</button>
        <button id="slideId2" onclick="duplicateSlide();">duplizieren</button>
        <br />
        Foliennummer: <input type="number" onchange="changeSlideIndex(this)" id="indexPicker" value="0">
    </div>

</div>
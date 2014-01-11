<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:template match="Slide">
        <div id="activeSlide">
            <div class="slide">
                <xsl:attribute name="onclick">
                    unselectElement();
                </xsl:attribute>
                <div class="header">
                    <xsl:for-each select="logo">
                        <img>
                            <xsl:attribute name="src">
                                getimage.php?id=<xsl:value-of select="@imageRef"/>
                            </xsl:attribute>
                            <xsl:attribute name="style">
                                float: left;
                                width: 50px;
                                height: 50px;
                                -webkit-user-select: none;
                                -moz-user-select: none;
                                -ms-user-select: none;
                            </xsl:attribute>
                        </img>
                    </xsl:for-each>
                    <div style="float: left;">
                        <p class="headertitle"><xsl:value-of select="@title"/></p>
                        <p class="headerDescription"><xsl:value-of select="@description"/></p>
                    </div>
                    <div style="float: right">
                        <p class="headerauthor"><xsl:value-of select="@author"/></p>
                        <p class="headerdate"><xsl:value-of select="@timestamp"/></p>
                    </div>

                </div>


                <div class="content">

                <xsl:for-each select="headingelement">
                    <h1 class="headingelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>

                        <xsl:value-of select="@text" disable-output-escaping="yes"/>
                    </h1>
                </xsl:for-each>
                <xsl:for-each select="headingelementsmall">
                    <h3 class="headingelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>

                        <xsl:value-of select="@text" disable-output-escaping="yes"/>
                    </h3>
                </xsl:for-each>
                <xsl:for-each select="textelement">
                    <p class="textelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>
                        <xsl:value-of select="@text" disable-output-escaping="yes"/>
                    </p>
                </xsl:for-each>

                <xsl:for-each select="quoteelement">
                    <q class="quoteelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>

                        <xsl:value-of select="@text" disable-output-escaping="yes"/>

                    </q>
                </xsl:for-each>

                <xsl:for-each select="dividerelement">
                    <hr/>
                </xsl:for-each>

                <xsl:for-each select="linkelement">
                    <div class="linkelement drag">
                        <xsl:attribute name="onclick">
                            linkClick(event)
                        </xsl:attribute>
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>
                        <a>
                            <xsl:attribute name="href">
                                <xsl:value-of select="@link"/>
                            </xsl:attribute>
                            <xsl:attribute name="target">
                                _blank
                            </xsl:attribute>
                            <xsl:value-of select="@text" disable-output-escaping="yes"/>
                        </a>


                    </div>
                </xsl:for-each>

                <xsl:for-each select="ulistelement">
                    <ul class="listelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            padding: 5px;
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>
                        <xsl:for-each select="uli">
                            <li>
                                <xsl:value-of select="@listelement" disable-output-escaping="yes"/>
                            </li>
                        </xsl:for-each>

                    </ul>
                </xsl:for-each>

                <xsl:for-each select="olistelement">
                    <ol class="listelement drag">
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-role">
                            <xsl:value-of select="@type"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            padding: 5px;
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                        <xsl:attribute name="ondblclick">
                            selectElement(this);
                        </xsl:attribute>
                        <xsl:for-each select="oli">
                            <li>
                                <xsl:value-of select="@listelement" disable-output-escaping="yes"/>
                            </li>
                        </xsl:for-each>
                    </ol>
                </xsl:for-each>


                <xsl:for-each select="imageelement">
                    <img>
                        <xsl:attribute name="src">
                            getimage.php?id=<xsl:value-of select="@imageRef"/>
                        </xsl:attribute>
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="class">
                            drag
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: <xsl:value-of select="@width"/>px;
                            height: <xsl:value-of select="@height"/>px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                    </img>
                </xsl:for-each>

                <xsl:for-each select="horizontaldiverelemement">
                    <hr>
                        <xsl:attribute name="class">
                            drag
                        </xsl:attribute>
                        <xsl:attribute name="data-id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="id">
                            <xsl:value-of select="@_id"/>
                        </xsl:attribute>
                        <xsl:attribute name="style">
                            position: absolute;
                            left: <xsl:value-of select="@x"/>px;
                            top: <xsl:value-of select="@y"/>px;
                            z-index: <xsl:value-of select="@z"/>;
                            width: 200px;
                            height: 2px;
                            -webkit-user-select: none;
                            -moz-user-select: none;
                            -ms-user-select: none;
                        </xsl:attribute>
                    </hr>
                </xsl:for-each>
                </div>
                <div class="footer">
                    Page
                    <xsl:value-of select="@index"/>
                </div>
            </div>
        </div>
    </xsl:template>
</xsl:stylesheet>

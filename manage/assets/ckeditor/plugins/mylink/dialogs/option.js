/**
 * Copyright (c) 2014-2018, CKSource - Frederico Knabben. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * The abbr plugin dialog window definition.
 *
 * Created out of the CKEditor Plugin SDK:
 * http://docs.ckeditor.com/ckeditor4/docs/#!/guide/plugin_sdk_sample_1
 */

// Our dialog definition.
CKEDITOR.dialog.add('linkoption', function (editor) {
    var linkTitle, linkProtocol, linkUrl, DisplayText, linkRel, linkTarget, selectedText;
    var Jtxt, Jurl, Jtitle, Jrel, Jtarget;

    var mySelection = editor.getSelection();
    if (CKEDITOR.env.ie) {
        mySelection.unlock(true);
        selectedText = mySelection.getNative().createRange().text;
    } else {
        selectedText = mySelection.getNative();
    }

    return {
        // Basic properties of the dialog window: title, minimum size.
        title: 'Link Option',
        minWidth: 400,
        minHeight: 300,
        // Dialog window content definition.
        contents: [
            {
                // Definition of the Basic Settings dialog tab (page).
                id: 'tab-basic',
                label: 'Layout',
                // The tab content.
                elements: [
                    {
                        type: 'html',
                        html: "<style>.rr {overflow: hidden;padding: 2px 0px;}\n\
                        .rr .lbl {float: left;width: 120px;padding: 3px 0;}\n\
                        .rr .fld {float: left;width: calc(100% - 120px);box-sizing: border-box;}\n\
                        .rr .fld input.ckIn {padding: 2px;border: 1px solid #ddd;width: 100%;box-sizing: border-box;}</style><div class='LinkAddBody'>\n\
                                    <div class='rr'>\n\
                                        <div class='lbl'>Display Text</div>\n\
                                        <div class='fld'><input id='linkTxt' type='text' class='ckIn '></div>\n\
                                    </div>\n\
                                    <div class='rr'>\n\
                                        <div class='lbl'>Title</div>\n\
                                        <div class='fld'><input id='linkTitle' type='text' class='ckIn '></div>\n\
                                    </div>\n\
                                    <div class='rr'>\n\
                                        <div class='lbl'>URL</div>\n\
                                        <div class='fld'><input id='linkUrl' type='text' class='ckIn '></div>\n\
                                    </div>\n\
                                    <div class='rr'>\n\
                                        <div class='lbl'>Rel</div>\n\
                                        <div class='fld'>\n\
                                            <select id='linkRel' class='custom-select custom-select-sm'>\n\
                                                <option value='noopener'>Noopener</option>\n\
                                                <option value=''>None</option>\n\
                                                <option value='nofollow'>Nofollow</option>\n\
                                                <option value='alternate'>Alternate</option>\n\
                                                <option value='bookmark'>Bookmark</option>\n\
                                                <option value='noreferrer'>Noreferrer</option>\n\
                                            </select>\n\
                                        </div>\n\
                                    </div>\n\
                                    <div class='rr'>\n\
                                        <div class='lbl'></div>\n\
                                        <div class='fld'><input id='newWindow' value='true' type='checkbox'>&nbsp;&nbsp;<label for='newWindow'>New Window</label></div>\n\
                                    </div>\n\
                                    <div class='CKdevider'></div>\n\
                                    <input type='text' class=\"ckIn\" placeholder='Search' id='searchQ'>\n\
                                    <div id='searchData'></div>\n\
                               </div>",
                        onShow: function () {
                            var loader = "<span class='spinLoader'></span>";
                            Jtxt = $("#linkTxt");
                            Jtitle = $("#linkTitle");
                            Jurl = $("#linkUrl");
                            Jrel = $("#linkRel");
                            Jtarget = $("#newWindow");

                            $(Jtxt).val(selectedText);
                            //Search Page/post===

                            var timeoutId
                            $("#searchQ").keyup(function () {
                                clearTimeout(timeoutId);
                                timeoutId = setTimeout(function () {
                                    var sq = $("#searchQ").val();

                                    $("#searchData").html("<span class='Searching'>Searching..</span>");
                                    var data = { str: sq };
                                    jQuery.post(ADMIN_URL + '/editor/page-links/', data, function (response) {
                                        $("#searchData").html(response);
                                    });
                                }, 1000);
                            });
                        }
                    },
                ]
            }
        ],
        // This method is invoked once a user clicks the OK button, confirming the dialog.
        onOk: function () {

            DisplayText = $(Jtxt).val();
            linkTitle = $(Jtitle).val();
            linkUrl = $(Jurl).val();
            linkRel = $(Jrel).val();

            linkTarget = false;
            if ($(Jtarget).is(":checked")) {
                linkTarget = true;
            }


            var htm = '<a ';
            if (linkRel !== 'none') {
                htm += 'rel="' + linkRel + '" ';
            }

            htm += 'href="' + linkUrl + '" ';

            if (linkTarget == true) {
                htm += 'target="_blank" ';
            }
            if (linkTitle !== '') {
                htm += 'title="' + linkTitle + '" ';
            }
            htm += '>';
            htm += DisplayText;
            htm += '</a>';
            editor.insertHtml(htm);
            $("#searchData").html("");
            $("#searchQ").val("");
            $("#linkTitle").val("");
            $("#linkUrl").val("");
        }
    };
});

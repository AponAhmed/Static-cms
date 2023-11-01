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
CKEDITOR.dialog.add('optionCanned', function(editor) {

    var rw, col, align, mrgin, padIn, cls, widthstr;
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: 'Canned   Insert',
        minWidth: 250,
        minHeight: 200,
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
                        html: "<div class='cannedDataBody'>\n\
                                    <button type='button' style='padding: 2px 4px 0px 4px;font-size: 12px; background: #ededed; border: 1px solid #ddd;border-radius: 3px;position: absolute;top: 10px;right: 50px;' onclick='addNew()'>Add New</button>\n\
                                    <div id='cannedData_" + editor.name + "'></div>\n\
                                </div>",
                        onShow: function() {
                            loadData();

                        }
                    },
                ]
            },
        ],
        // This method is invoked once a user clicks the OK button, confirming the dialog.
        onOk: function() {

        }
    };
});

function addNew(editor) {

    //var curStr = "asdfsdf";
//    if (CKEDITOR.instances.PostEditor) {
//        var curIns = 'PostEditor';
//    } else {
//        var curIns = 'texoDesc';
//    }

    var curIns = currentEditorInstance();

    var curStr = CKEDITOR.instances[curIns].getData();
    //console.log(curStr);
    var ttle = "";
    if ($("#pageTitle").length > 0) {
        var ttle = $("#pageTitle").val();
    } else {
        if ($("#name").length > 0) {
            var ttle = $("#name").val();
        } else {
            var today = new Date();
            var dd = today.getDate();

            var mm = today.getMonth() + 1;
            var yyyy = today.getFullYear();
            today = dd + '/' + mm + '/' + yyyy + ", " + today.getHours() + ":" + today.getMinutes();
            ttle = today;
        }

    }
    var data = {ajx_action: "addCanned", str: curStr, ttl: ttle};
    jQuery.post('index.php', data, function(response) {
        var obj = jQuery.parseJSON(response);
        if (obj['error'] == "") {
            msg(obj['msg'], "G");
            loadData();
        } else {
            msg(obj['msg'], "R");
        }
    });
}

function loadData() {
    var curIns = currentEditorInstance();
    var loader = "<span class='spinLoader'></span>";
    $("#cannedData").html("<span class='Searching'>Searching..</span>");
    var data = {ajx_action: "cannedData"};
    jQuery.post('index.php', data, function(response) {
        $("#cannedData_" + curIns).html(response);
    });
}

function cndIns(_this) {

//    if (CKEDITOR.instances.PostEditor) {
//        var curIns = 'PostEditor';
//    } else {
//        var curIns = 'texoDesc';
//    }
    var curIns = currentEditorInstance();


    var str = $(_this).parent().find('textarea').val();
    CKEDITOR.instances[curIns].insertHtml(str);
    CKEDITOR.dialog.getCurrent().hide()
    //console.log(str);

}

function canndDelete(id) {
    var c = confirm("Are you sure to DELETE");
    if (c) {
        var data = {ajx_action: "canndDelete", id: id};
        jQuery.post('index.php', data, function(response) {
            var obj = jQuery.parseJSON(response);
            if (obj['error'] == "") {
                msg(obj['msg'], "G");
                loadData();
            } else {
                msg(obj['msg'], "R");
            }
        });
    }
}
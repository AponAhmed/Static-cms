/**
 * Copyright (c) 2018, siatex - Apon. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Basic sample plugin inserting current date and time into the CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add('acolumn', {
    // Register the icons. They must match command names.
    icons: 'Column,Media,Canned,More,NewCanned,ButtonL,CardAdd',
    // The plugin initialization logic goes inside this method.
    init: function (editor) {
        editor.addContentsCss(this.path + 'style/colEditor.css');
        editor.addCommand('insertColPop', new CKEDITOR.dialogCommand('option'));
        editor.addCommand('insertCanned', new CKEDITOR.dialogCommand('optionCanned'));
        editor.addCommand('insertButtonL', new CKEDITOR.dialogCommand('optionButton'));
        editor.addCommand('CardAdd', new CKEDITOR.dialogCommand('optionCardAdd'));
        var currentInstance = "PostEditor";
        for (var id in CKEDITOR.instances) {
            CKEDITOR.instances[id].on('focus', function (e) {
                // Fill some global var here
                currentInstance = e.editor.name;

            });
        }
        //console.log(currentInstance);
        // Define the editor command that inserts a timestamp.
        editor.addCommand('insertMedia', {
            // Define the function that will be fired when the command is executed.
            exec: function (editor) {
                // var r = $('h1').html();
                // alert(r);
                var data = { for: 'editor' };
                jQuery.post(ADMIN_URL + '/imagebrowse/', data, function (response) {
                    new DialogBox({
                        title: "Browse Media",
                        body: response,
                        actions: [
                            {
                                label: "Cancel",
                                class: "btn-primary",
                                callback: function (_this) {
                                    _this.close();
                                }
                            }
                        ]
                    });
                });
            }
        });


        editor.addCommand('insertMore', {
            exec: function (editor) {
                var selectedText;
                selectedText = editor.getSelection().getSelectedText();

                if (selectedText != "") {
                    if (selectedText.indexOf("[[") >= 0) {
                        var clnStr = selectedText.replace("[[", "");
                        clnStr = clnStr.replace("]]", "");
                        editor.insertHtml(clnStr);
                    } else {
                        editor.insertHtml("[[<span class='readMore'>" + selectedText + "</span>]]");
                    }
                } else {
                    msg("Select some content first !", 'R');
                }
            }
        });

        editor.addCommand('newCanned', {
            exec: function (editor) {
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
                var data = { ajx_action: "addCanned", str: curStr, ttl: ttle };
                jQuery.post('index.php', data, function (response) {
                    var obj = jQuery.parseJSON(response);
                    if (obj['error'] == "") {
                        msg(obj['msg'], "G");
                    } else {
                        msg(obj['msg'], "R");
                    }
                });
            }
        })


        // Create the toolbar button that executes the above command.
        editor.ui.addButton('Column', {
            label: 'Insert Column',
            command: 'insertColPop',
            toolbar: 'insert'
        });

        editor.ui.addButton('Media', {
            label: 'Insert Media',
            command: 'insertMedia',
            toolbar: 'insert'
        });

        // editor.ui.addButton('NewCanned', {
        //     label: 'New Canned',
        //     command: 'newCanned',
        //     toolbar: 'insert'
        // });
        // editor.ui.addButton('Canned', {
        //     label: 'Canned Insert',
        //     command: 'insertCanned',
        //     toolbar: 'insert'
        // });
        // editor.ui.addButton('ButtonL', {
        //     label: 'Insert A Button',
        //     command: 'insertButtonL',
        //     toolbar: 'insert'
        // });
        // editor.ui.addButton('CardAdd', {
        //     label: 'Insert Cards',
        //     command: 'CardAdd',
        //     toolbar: 'insert'
        // });
        // editor.ui.addButton('More', {
        //     label: 'Add in Read More',
        //     command: 'insertMore',
        //     toolbar: 'insert'
        // });

        //editor.addCommand('insertColPop', new CKEDITOR.dialogCommand('colordialog'));
        //CKEDITOR.dialog.add( 'colordialog', this.path + 'dialogs/colordialog.js' );
        CKEDITOR.dialog.add('option', this.path + 'dialogs/option.js');
        CKEDITOR.dialog.add('optionCanned', this.path + 'dialogs/optionCanned.js');
        CKEDITOR.dialog.add('optionButton', this.path + 'dialogs/optionButton.js');
        CKEDITOR.dialog.add('optionCardAdd', this.path + 'dialogs/optionCardAdd.js');
    }
});

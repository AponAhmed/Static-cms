/**
 * Copyright (c) 2018, siatex - Apon. All rights reserved.
 * Licensed under the terms of the MIT License (see LICENSE.md).
 *
 * Basic sample plugin inserting current date and time into the CKEditor editing area.
 *
 * Created out of the CKEditor Plugin SDK:
 */

// Register the plugin within the editor.
CKEDITOR.plugins.add('mylink', {
    // Register the icons. They must match command names.
    icons: 'mlink,munlink',
    // The plugin initialization logic goes inside this method.
    init: function(editor) {
        //editor.addContentsCss(this.path + '/style/mylink.css');
        editor.addCommand('insertlink', new CKEDITOR.dialogCommand('linkoption'));
        editor.ui.addButton('mlink', {
            label: 'Link',
            command: 'insertlink',
            toolbar: 'insert'
        });
        editor.ui.addButton('munlink', {
            label: 'Unlink',
            command: 'unlink',
            toolbar: 'insert'
        });

        CKEDITOR.dialog.add('linkoption', this.path + 'dialogs/option.js');

    }
});

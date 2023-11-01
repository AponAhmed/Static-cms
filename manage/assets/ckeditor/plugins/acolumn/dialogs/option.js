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
CKEDITOR.dialog.add('option', function (editor) {

    var rw, col, align, mrgin, padIn, cls, widthstr, colm, coltab;
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: 'Column Option',
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
                        type: 'select',
                        labelLayout: 'horizontal',
                        id: 'col',
                        controlStyle: 'width: 6em',
                        label: 'Column',
                        items: [['1'], ['2'], ['3'], ['4'], ['6']],
                        default: '3',
                        onChange: function (api) {
                            col = this.getValue();
                            var cw = Number(12 / col);
                            var i, str = '';
                            for (i = 1; i <= col; i++) {
                                str += cw;
                                if (col > i) {
                                    str += ',';
                                }
                            }
                            //$("#cke_177_textInput").val(str);
                            CKEDITOR.dialog.getCurrent().setValueOf('tab-basic', 'widthstr', str);

                            widthstr = str;
                        },
                        onShow: function () {
                            col = this.getValue();
                        }
                    },
                    {
                        type: 'select',
                        labelLayout: 'horizontal',
                        id: 'col-tab',
                        controlStyle: 'width: 6em',
                        label: 'Col. on Tab',
                        items: [['None'], ['1'], ['2'], ['3'], ['4'], ['6']],
                        default: 'None',
                        onChange: function (api) {
                            coltab = this.getValue();
                        },
                        onShow: function () {
                            coltab = this.getValue();
                        }
                    },
                    {
                        type: 'select',
                        labelLayout: 'horizontal',
                        id: 'col-mob',
                        controlStyle: 'width: 6em',
                        label: 'Col. on Mobile',
                        items: [['None'], ['1'], ['2'], ['3'], ['4'], ['6']],
                        default: 'None',
                        onChange: function (api) {
                            colm = this.getValue();
                        },
                        onShow: function () {
                            colm = this.getValue();
                        }
                    },
                    {
                        // Text input field for the abbreviation title (explanation).
                        type: 'text',
                        id: 'widthstr',
                        labelLayout: "horizontal",
                        label: "Column's Width",
                        onChange: function (api) {
                            widthstr = this.getValue();
                            var wArr = widthstr.split(',');
                            //console.log(wArr);
                            var total = 0;
                            for (var i = 0; i < wArr.length; i++) {
                                total += Number(wArr[i]);
                            }
                            if (total > 12) {
                                msg('Maximum 12 column allow, Entered ' + total, "R");
                            }

                        },
                        onShow: function () {
                            widthstr = this.getValue();
                        },
                        default: '4,4,4'

                    },
                    {
                        type: 'select',
                        labelLayout: 'horizontal',
                        id: 'align',
                        label: 'Align',
                        items: [['Left', 'left'], ['Center', 'center'], ['Right', 'right'], ['Justify', 'justify']],
                        default: 'justify',
                        onChange: function (api) {
                            align = this.getValue();
                        },
                        onShow: function () {
                            align = this.getValue();
                        }
                    },
                    {
                        // Text input field for the abbreviation title (explanation).
                        type: 'text',
                        id: 'mrgn',
                        labelLayout: 'horizontal',
                        label: 'Margin (px) ',
                        onChange: function (api) {
                            mrgin = this.getValue();
                        },
                        onShow: function () {
                            mrgin = this.getValue();
                        },
                        default: '0,0,0,0'

                    },
                    {
                        // Text input field for the abbreviation title (explanation).
                        type: 'text',
                        id: 'pdng',
                        labelLayout: 'horizontal',
                        label: 'Padding (px)',
                        //validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty." )
                        onChange: function (api) {
                            padIn = this.getValue();
                        },
                        onShow: function () {
                            padIn = this.getValue();
                        },
                        default: '0,0,0,0'
                    },
                    {
                        // Text input field for the abbreviation title (explanation).
                        type: 'text',
                        id: 'class',
                        //labelLayout: 'horizontal',
                        label: 'Custom CSS Class ',
                        //validate: CKEDITOR.dialog.validate.notEmpty( "Explanation field cannot be empty." )
                        onChange: function (api) {
                            cls = this.getValue();
                        },
                        onShow: function () {
                            cls = this.getValue();
                        }
                    },
                    {
                        type: 'checkbox',
                        id: 'row',
                        label: 'Include in Row',
                        default: 'checked',
                        onClick: function () {
                            //this = CKEDITOR.ui.dialog.checkbox
                            rw = this.getValue();
                        },
                        onShow: function () {
                            rw = this.getValue();
                        }
                    }
                ]
            },
            // Definition of the Advanced Settings dialog tab (page).

            //            {
            //                id: 'tab-a',
            //                label: 'Style Settings',
            //                elements: [
            //                    {
            //                        // Another text field for the abbr element id.
            //                        type: 'text',
            //                        id: 'color',
            //                        label: 'Id',
            //                        onClick: function() {
            //                            // this = CKEDITOR.ui.dialog.checkbox
            //                            //alert('Checked: ' + this.getValue());
            //                        }
            //                    }
            //                ]
            //            }
        ],
        // This method is invoked once a user clicks the OK button, confirming the dialog.
        onOk: function () {
            var htm = "";
            var styl = "";
            var classes_in = "";

            if (col == "") {
                col = 3;
            }
            var cw = 12 / col;
            var cl = cw;
            cw = " w" + cw;
            //alert(cw);
            classes_in += " " + align + " " + cls;

            if (padIn !== '0,0,0,0') {
                var padd = '';
                var paddArray = padIn.split(',');
                var arrayLength = paddArray.length;
                for (var i = 0; i < arrayLength; i++) {
                    padd += paddArray[i] + 'px';
                    //Do something
                }
                styl += 'padding:' + padd + ';';
            }
            if (mrgin !== '0,0,0,0') {
                var mar = '';
                var marginArray = mrgin.split(',');
                var arrayLength = marginArray.length;
                for (var i = 0; i < arrayLength; i++) {
                    mar += marginArray[i] + 'px';
                    //Do something
                }
                styl += 'margin:' + mar + ';';
            }

            var wArr = widthstr.split(',');
            var resW = "";
            if (colm != 'None') {
                colmw = 12 / Number(colm);
                resW += " m" + colmw;
            }
            if (coltab != 'None') {
                coltabW = 12 / Number(coltab);
                resW += " t" + coltabW;
            }
            for (var i = 1; i <= col; i++) {
                var indx = i - 1;
                cl = wArr[indx]; //Custom column Width 
                htm += '<div class="box box-' + cl + resW + '">\n\
                            <div class="innerColumn ' + classes_in + '" style="' + styl + '"><p>&nbsp;</p></div>\n\
                        </div>';
            }

            if (!rw) {
                htm = '<div class="box-row">' + htm + '</div>';
            }
            htm += "<p>&nbsp;</p>";
            //rw = true;
            //var rw, col, align, mrgn, pdng, cls;
            //            // The context of this function is the dialog object itself.
            //            // http://docs.ckeditor.com/ckeditor4/docs/#!/api/CKEDITOR.dialog
            //            var dialog = this;
            //
            //            // Create a new <abbr> element.
            //            var abbr = editor.document.createElement('abbr');
            //
            //            // Set element attribute and text by getting the defined field values.
            //            abbr.setAttribute('title', dialog.getValueOf('tab-basic', 'title'));
            //            abbr.setText(dialog.getValueOf('tab-basic', 'abbr'));
            //
            //            // Now get yet another field value from the Advanced Settings tab.
            //            var id = dialog.getValueOf('tab-adv', 'id');
            //            if (id)
            //                abbr.setAttribute('id', id);

            // Finally, insert the element into the editor at the caret position.
            //editor.insertElement(abbr);

            editor.insertHtml(htm);

        }
    };
});

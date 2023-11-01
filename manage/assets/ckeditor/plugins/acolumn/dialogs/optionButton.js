/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


CKEDITOR.dialog.add('optionButton', function(editor) {
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: 'Insert Button',
        minWidth: 450,
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
                        html: "<div id='BtnArea'>\n\
<a href='' class='Cbtn' contenteditable='true'>Text Here</a>\n\
</div>\n\
<div class='BtnOptions'>\n\
<input type='text' id='btnUrlPlace' placeholder='URL'>\n\
\n\<hr>\n\
<div class='mainWrap'><div class='btntemplate'><div><label class='lblBtnController'>Template</label><br>\n\
\n\<button class='EBtn Ebtn1' >Click</button>\n\
\n\<button class='EBtn Ebtn2' >Click</button>\n\
\n\<button class='EBtn Ebtn3' >Click</button><br>\n\
\n\<button class='EBtn Ebtn4' >Click</button>\n\
\n\<button class='EBtn Ebtn5' >Click</button>\n\
\n\<button class='EBtn Ebtn6' >Click</button><br>\n\
\n\<button class='EBtn Ebtn7' >Click</button>\n\
\n\<button class='EBtn Ebtn8' >Click</button>\n\
\n\<button class='EBtn Ebtn9' >Click</button><br>\n\
\n\<button class='EBtn Ebtn10' >Click</button>\n\
\n\<button class='EBtn Ebtn11' >Click</button>\n\
\n\<button class='EBtn Ebtn12' >Click</button><br>\n\
\n\<button class='EBtn Ebtn13' >Click</button>\n\
\n\<button class='EBtn Ebtn14' >Click</button>\n\
\n\<button class='EBtn Ebtn15' >Click</button>\n\
</div></div><div class='customise'><label class='lblBtnController'>Customize <a class='clearCustomize' href='javascript:void(0)'>Clear</a></label>\n\
<div class='optionF'>\n\
<label>Font-Size</label>\n\
<input placeholder='14px' class='fontSize cssProp keyUp' data-css-attr='font-size' type='text'>\n\
</div>\n\
\n\
<div class='optionF'>\n\
<label>Padding</label>\n\
<input placeholder='5px 15px' class='paddingInput cssProp keyUp' data-css-attr='padding' type='text'>\n\
</div>\n\
\n\
<div class='optionF'>\n\
<label>BG Color</label>\n\
<input value='#4da4c1' class='cPicker BGColor pl30 cssProp' data-css-attr='background' type='text'>\n\
</div>\n\
\n\
<div class='optionF'>\n\
<label>Border Color</label>\n\
<input value='#194c5e' class='cPicker BorderColor pl30 cssProp' data-css-attr='border-color' type='text'>\n\
</div>\n\
\n\
<div class='optionF'>\n\
<label>Text Color</label>\n\
<input value='#fff' class='cPicker txtColor pl30 cssProp' data-css-attr='color' type='text'>\n\
</div>\n\
\n\
<div class='optionF'>\n\
<label>Border Radius</label>\n\
<input value='4px' class='BRadius cssProp keyUp' data-css-attr='border-radius' type='text'>\n\
</div>\n\
\n\
</div></div></div>",
                        onShow: function() {
                            initBtnBuilder();
                        }
                    },
                ]
            },
        ],
        onOk: function() {
            $(".Cbtn").removeAttr('contenteditable');
            var btnHtm = $("#BtnArea").html();
            editor.insertHtml(btnHtm);
        }
    }

    function initBtnBuilder() {
        jQuery('.cPicker').each(function() {
            jQuery(this).minicolors({
                control: jQuery(this).attr('data-control') || 'hue',
                defaultValue: jQuery(this).attr('data-defaultValue') || '',
                format: jQuery(this).attr('data-format') || 'hex',
                keywords: jQuery(this).attr('data-keywords') || '',
                inline: jQuery(this).attr('data-inline') === 'true',
                letterCase: jQuery(this).attr('data-letterCase') || 'lowercase',
                opacity: jQuery(this).attr('data-opacity'),
                position: jQuery(this).attr('data-position') || 'bottom left',
                swatches: jQuery(this).attr('data-swatches') ? jQuery(this).attr('data-swatches').split('|') : [],
                change: function(value, opacity) {
                    if (!value)
                        return;
                    if (opacity)
                        value += ', ' + opacity;
                    if (typeof console === 'object') {
                        //	console.log(value);
                    }
                },
                theme: 'bootstrap'
            });

        });

        $(".EBtn").click(function() {
            var ob = $('.Cbtn');
            $('.Cbtn').removeAttr('class');
            var cls = $(this).attr('class');
            $(ob).addClass("Cbtn " + cls);
        });
        $(".clearCustomize").click(function() {
            $('.Cbtn').removeAttr('style');
        })

        $("#btnUrlPlace").keyup(function() {
            $('.Cbtn').attr('href', $(this).val());
        });
        $('.cssProp.keyUp').keyup(function() {
            $('.Cbtn').css($(this).attr('data-css-attr'), $(this).val());
        });
        $('.cssProp.cPicker').change(function() {
            $('.Cbtn').css($(this).attr('data-css-attr'), $(this).val());
        });


//        $(".paddingInput").keyup(function() {
//            $('.Cbtn').css('padding', $(this).val());
//        });
//        $(".BGColor").change(function() {
//            $('.Cbtn').css('background', $(this).val());
//        });
//        $(".BorderColor").change(function() {
//            $('.Cbtn').css('border-color', $(this).val());
//        });
//        $(".txtColor").change(function() {
//            $('.Cbtn').css('color', $(this).val());
//        });
//        $(".fontSize").keyup(function() {
//            $('.Cbtn').css('font-size', $(this).val());
//        });
//        $(".BRadius").keyup(function() {
//            $('.Cbtn').css('border-radius', $(this).val());
//        });
    }
});
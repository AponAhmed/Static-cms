/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


CKEDITOR.dialog.add('optionCardAdd', function(editor) {
    var CardContent;
    return {
        // Basic properties of the dialog window: title, minimum size.
        title: 'Insert Post Cards',
        minWidth: 900,
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
                        html: "<div class='columnCh'><label>Column </label><select id='columnCh' class='custom-select custom-select-sm'><option>4</option><option>3</option><option>5</option><option>2</option><option>6</option></select></div><div id='CardBuilderWrap'>Select Column</div>",
                        onShow: function() {
                            initBuilder();
                        }
                    },
                ]
            },
        ],
        onOk: function() {

            var htm = '<div class="card-blocks-wrap">';
            $('.card-wrap').each(function() {
                var link = $(this).find('.linksPlace').val();
                $("*").removeAttr('contenteditable');
                if (link != "") {
                    htm += "<a href='" + link + "'>";
                }
                if ($(this).find('.LinkChose').remove()) {
                    htm += '<div class="card-wrap">';
                    htm += $(this).html();
                    htm += "</div>";
                }
                if (link != "") {
                    htm += "</a>";
                }

            })

            htm += "</div>";
            //htm = $("#CardBuilderWrap").html();
            CardContent = htm.replaceAll("contenteditable='true'", "");
            editor.insertHtml(CardContent);
        }
    }
    function initBuilder() {
        $("#columnCh").change(function() {
            blockGenerator($(this).val());
        });
        blockGenerator($("#columnCh").val());
        $('.img-wrap').click(function() {
            //console.log(this);
            browse4image(this);
        });
    }
    function blockGenerator(col) {
        htm = '<div class="card-blocks-wrap">';
        dom = $('#domain').attr('href');
        for (var i = 1; i <= col; i++) {
            htm += '<div class="card-wrap"><div class="card-block-inner">';
            htm += '<span class="LinkChose"><input type="text" class="linksPlace collapse"><i class="fas fa-link"></i></span>';
            htm += "<div class='img-wrap'><img src='" + dom + "admin/images/imgIcon.jpg'></div>";
            htm += "<div class='text-wrap'><h2 contenteditable='true'>Heading Text Here</h2><p contenteditable='true'>Paragraph Here</p></div>";
            htm += "</div></div>";
        }
        htm += "</div>";
        CardContent = htm.replaceAll("contenteditable='true'", "");
        $("#CardBuilderWrap").html(htm);
        $(".LinkChose").click(function() {

            $('.linksPlace').addClass('collapse');
            $(this).find('.linksPlace').removeClass('collapse');
            console.log('clicked for insert Link');
        });
    }
    function browse4image(_this) {
        $(_this).addClass('browsing4Image');
        //console.log(_this);
        $.fancybox.open({
            src: 'index.php?w=850&h=500&c=forms&m=library&FieldId=mdInsSelectedCurrent&calback=addCardImage&fancybox=true&instanse=' + editor.name, // Source of the content
            type: 'ajax', // Content type: image|inline|ajax|iframe|html (optional)
            opts: {caption: ""}, // Object containing item options (optional)
            afterLoad: function() {
                //alert("loaded");
                var content = $(this.opts.$orig[0]).data('content');
                $(".fancybox-div").html(content);
            }
        })
    }

});
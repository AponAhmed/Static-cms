<?php admin_header(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.14/theme/base16-light.min.css">
<style type="text/css">
    .CodeMirror {
        min-height: calc(100vh - 130px);
    }

    /* Loading indicator */
    #loading-container {
        text-align: center;
        margin: 0 10px;
        margin-top: 4px;
    }

    .CodeMirror-scroll {
        border: 1px solid #ddd;
        padding: 10px 0;
        background: #fff;
    }

    .spinner {
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Response message */
    .response-message {
        display: inline-block;
        margin-left: 10px;
        padding: 5px 10px;
        border-radius: 3px;
        font-size: 14px;
    }
</style>
<div class="page-body">
    <div class="custom-css">

        <textarea id="css-editor" style="display: none;"><?php echo $css ?></textarea>
        <hr>
        <button onclick="saveCSS()" class="btn btn-primary">Save CSS</button>
        <div id="loading-container" style="display: none;">
            <div class="spinner"></div>
        </div>

    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.63.1/mode/css/css.min.js"></script>

<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("css-editor"), {
        mode: "css",
        lineNumbers: true,
        theme: "base16-light",
    });

    function saveCSS() {
        // Get the CSS content from the editor
        var cssContent = editor.getValue();

        // Show the loading indicator
        $("#loading-container").css('display', 'inline-block');

        // Send the CSS content to your PHP backend using AJAX
        $.ajax({
            url: '<?php page_url() ?>/store-css/',
            method: 'POST',
            data: {
                css: cssContent
            },
            success: function(response) {
                // Hide the loading indicator
                $("#loading-container").hide();

                // Display the response message to the right of the button
                var message = JSON.parse(response);
                var messageContainer = $("<div></div>").text(message.message).addClass("response-message");
                $(".custom-css").append(messageContainer);
                setTimeout(() => {
                    messageContainer.remove();
                }, 3000);
            },
            error: function(error) {
                // Hide the loading indicator
                $("#loading-container").hide();

                // Handle errors
                console.error(error);
            }
        });
    }
</script>
<?php admin_footer(); ?>
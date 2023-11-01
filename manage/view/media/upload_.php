<?php admin_header(); ?>
<div class="page-body">
    <h2>Upload Image</h2>
    <form id="imageUploadForm">
        <input type="file" name="image" id="imageInput" accept="image/*" multiple required>
    </form>

    <div class="image-preview" id="imagePreview"></div>

    <div class="progress-bar-container">
        <div class="progress-bar" id="progressBar" style="width: 0;"></div>
    </div>

    <script>
        // Function to preview selected images
        function previewImages() {
            var preview = document.getElementById('imagePreview');
            preview.innerHTML = '';

            var files = document.getElementById('imageInput').files;

            for (var i = 0; i < files.length; i++) {
                var file = files[i];
                var reader = new FileReader();

                reader.onload = function(e) {
                    var image = document.createElement('img');
                    image.src = e.target.result;
                    preview.appendChild(createUploadingDiv(image));
                };

                if (file) {
                    reader.readAsDataURL(file);
                    uploadImageViaAjax(file);
                }
            }
        }

        // Trigger the previewImages function when images are selected
        document.getElementById('imageInput').addEventListener('change', previewImages);

        // Function to create the uploading div with image and progress
        function createUploadingDiv(image) {
            var uploadingDiv = document.createElement('div');
            uploadingDiv.className = 'uploading';
            uploadingDiv.appendChild(image);

            var progressDiv = document.createElement('div');
            progressDiv.className = 'progress';
            uploadingDiv.appendChild(progressDiv);

            return uploadingDiv;
        }

        // Function to handle image upload via AJAX
        function uploadImageViaAjax(file) {
            var formData = new FormData();
            formData.append('image', file);

            // Add the 'dir' parameter to the FormData
            formData.append('dir', '<?php echo $upload2Dir ?>');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', ADMIN_URL + '/media/store/', true);

            // Track the upload progress
            xhr.upload.onprogress = function(event) {
                var progress = (event.loaded / event.total) * 100;
                var progressBar = document.getElementById('progressBar');
                progressBar.style.width = progress + '%';
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    // Handle the response and update the progress bar
                    var progressBar = document.getElementById('progressBar');
                    progressBar.style.width = '0%';
                } else {
                    console.error('Error:', xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error('Error:', xhr.statusText);
            };

            xhr.send(formData);
        }
    </script>
</div>
<?php admin_footer(); ?>
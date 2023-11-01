import Notification from "./Notification";

export default class ImageUploader {
    constructor(uploadUrl, maxImages = 10) {
        this.uploadUrl = uploadUrl;
        this.maxImages = maxImages;
        this.uploadedImages = [];
        this.previewArea = document.getElementById('previewArea');
    }

    uploadImages(files) {
        const totalFiles = files.length;
        const allowedFiles = this.maxImages - this.uploadedImages.length;

        if (totalFiles > allowedFiles) {
            new Notification({ message: `You can Select a maximum of ${this.maxImages} images at a Time.`, type: 'alert' });
            return;
        }


        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            if (!file.type.startsWith('image/')) {
                new Notification({ message: `File ${file.name} is not an image and will be skipped.`, type: 'alert' });
                continue;
            }

            // Create a blank image space
            const imageContainer = document.createElement('div');
            imageContainer.className = 'image-container';
            this.previewArea.appendChild(imageContainer);

            const progressBar = this.createProgressBar(file.name);
            this.removeBtn = document.createElement('span');
            this.removeBtn.innerHTML = "&times;";
            this.removeBtn.addEventListener('click', () => {
                imageContainer.remove();
            });
            this.removeBtn.classList.add('remove-attachment');
            imageContainer.appendChild(progressBar);
            imageContainer.appendChild(this.removeBtn);

            const formData = new FormData();
            formData.append('image', file);
            this.uploadFile(formData, progressBar, imageContainer);
        }
    }

    clearPriview() {
        this.previewArea.innerHTML = '';
    }

    createProgressBar(filename) {
        const progressBar = document.createElement('div');
        progressBar.className = 'progress-bar';
        progressBar.innerHTML = `<div class="progress-bar-fill"></div>`;//<div class="progress-bar-text">${filename}</div>
        return progressBar;
    }

    removeUpload(filename) {
        const xhr = new XMLHttpRequest();
        // Define the URL where you want to send the POST request
        const url = this.uploadUrl + "remove/"; // Replace with your server endpoint
        // Create a FormData object to send data
        const formData = new FormData();
        // Add the "file-name" parameter to the FormData object
        formData.append("filename", filename);
        // Configure the request
        xhr.open("POST", url, true);
        // Set a callback function to handle the response
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Request was successful
                    console.log(xhr.responseText);
                } else {
                    // Request encountered an error
                    console.error("Request failed with status:", xhr.status);
                }
            }
        };
        // Send the request with the FormData
        xhr.send(formData);
    }

    async uploadFile(formData, progressBar, imageContainer) {
        const xhr = new XMLHttpRequest();
        xhr.upload.addEventListener('progress', (e) => {
            if (e.lengthComputable) {
                const percent = (e.loaded / e.total) * 100;
                progressBar.querySelector('.progress-bar-fill').style.width = percent + '%';
            }
        });

        xhr.onreadystatechange = () => {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    // File was successfully uploaded
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        progressBar.classList.add('upload-success');
                        this.uploadedImages.push(response.name);
                        this.displayImage(response.url, imageContainer);
                        //<input type="hidden" name="attachments[]" value="${filename}">
                        let fileInput = document.createElement('input');
                        fileInput.type = "hidden";
                        fileInput.name = "attachments[]";
                        fileInput.value = response.name;
                        imageContainer.appendChild(fileInput);

                        this.removeBtn.addEventListener('click', (e) => {
                            this.removeUpload(response.name);
                        })
                    } else {
                        progressBar.classList.add('upload-error');
                        new Notification({ message: response.message, type: 'error' });
                        this.previewArea.removeChild(imageContainer);
                    }
                } else {
                    progressBar.classList.add('upload-error');
                    new Notification({ message: 'Error uploading file.', type: 'error' });
                    this.previewArea.removeChild(imageContainer);
                }
            }
        };

        xhr.open('POST', this.uploadUrl + 'upload/', true);
        xhr.send(formData);
    }

    displayImage(url, imageContainer) {
        const image = document.createElement('img');
        image.src = url;
        //imageContainer.innerHTML = ''; // Clear the progress bar
        imageContainer.appendChild(image);
    }
}


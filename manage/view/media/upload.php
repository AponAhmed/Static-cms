<?php admin_header(); ?>
<div class="page-body">
    <div class="attachment-area">
        <input style="display:none" multiple onchange="uploadFile(this)" type="file" id="attachment-select">
        <label for="attachment-select" class="tooltip browseTrigger" data-position='top' data-bg='#555' title="Attach Something"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112" />
            </svg></label>
        <div id="uploadCount" class="upload-counter"></div>
        <div class="attachments"></div>
    </div>

    <script>
        // Initialize a counter for successful uploads and total files
        let info = [0, 0];

        class ImageUploader {
            constructor(options) {
                this.route = options.route || '';
                this.items = options.items || false;
                this.name = options.name || 'file';
                this.callback = options.callback || false;
                this.onProcess = options.onProcess || false;
                this.removeCallback = options.removeCallback || function() {};
                this.onComplete = options.onComplete || false;
                this.response = false;
                this.formData = options.formData || false; // {'field': val}
            }

            onUploadProgress(event) {
                const percentCompleted = Math.round((event.loaded * 100) / event.total);
                if (percentCompleted === 100 && this.onComplete) {
                    this.onComplete(this);
                }
                if (this.items) {
                    this.item.querySelector('.up-progress').style.width = percentCompleted + '%';
                    if (this.onProcess) {
                        this.onProcess(this, event);
                    }
                }
            }

            removeAttachment() {
                this.item.remove();
            }

            createProgressbar() {
                this.item = document.createElement('div');
                this.item.classList.add('file-item');
                this.progressBar = document.createElement('span');
                this.progressBar.classList.add('up-progress');
                this.item.appendChild(this.progressBar);

                this.label = document.createElement('label');
                this.label.classList.add('file-name');
                this.label.innerHTML = this.file.name;

                this.cancelBtn = document.createElement('span');
                this.cancelBtn.classList.add('cancel-btn');
                this.cancelBtn.innerHTML = '&times;';
                this.cancelBtn.addEventListener('click', (e) => {
                    this.removeAttachment();
                });

                this.item.appendChild(this.cancelBtn);
                this.item.appendChild(this.label);
                this.items.appendChild(this.item);
                this.setInfo();
            }

            setInfo() {
                this.item.dataset.tooltip = this.humanFileSize(this.file.size);
                let iconHtml = document.createElement('span');
                iconHtml.classList.add('file-icon');
                iconHtml.classList.add(`file-${this.extension}`);
                this.item.appendChild(iconHtml);
            }

            async upload(file) {
                this.file = file;
                let re = /(?:\.([^.]+))?$/;
                this.extension = re.exec(this.file.name)[1];
                if (this.items) {
                    this.createProgressbar();
                }

                const data = new FormData();
                if (this.formData) {
                    for (const field in this.formData) {
                        data.append(field, this.formData[field]);
                    }
                }

                data.append(this.name, file);

                const xhr = new XMLHttpRequest();
                xhr.open('POST', this.route, true);

                xhr.upload.onprogress = (event) => {
                    const percentCompleted = Math.round((event.loaded * 100) / event.total);
                    this.onUploadProgress(event);
                };

                xhr.onload = () => {
                    if (xhr.status === 200) {
                        this.response = JSON.parse(xhr.responseText);
                    } else {
                        console.error('Error:', xhr.statusText);
                    }

                    if (this.callback) {
                        this.callback(this.response, this);
                    }
                };

                xhr.onerror = () => {
                    console.error('Error:', xhr.statusText);
                };

                xhr.send(data);
            }

            humanFileSize(bytes, si = true, dp = 1) {
                const thresh = si ? 1000 : 1024;
                if (Math.abs(bytes) < thresh) {
                    return bytes + ' B';
                }
                const units = si ? ['KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'] : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
                let u = -1;
                const r = 10 ** dp;
                do {
                    bytes /= thresh;
                    ++u;
                } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);
                return bytes.toFixed(dp) + ' ' + units[u];
            }
        }

        async function uploadFile(_this) {
            let files = _this.files;
            info[0] = files.length;
            let upwrap = document.querySelector(".attachments");

            // Function to set a file item's status to error (red color)
            function setErrorStatus(item) {
                item.classList.add('error'); // Add a CSS class for styling
                item.querySelector('.up-progress').style.width = '100%'; // Set progress to 100%
            }

            for (const element of files) {
                let fileUp = new ImageUploader({
                    route: ADMIN_URL + '/media/store/',
                    items: upwrap,
                    name: 'image',
                    onProcess: (obj, p) => {
                        //console.log(obj.file);
                    },
                    onComplete: (obj) => {
                        console.log('upload complete');
                    },
                    callback: function(res, obj) {
                        if (res.error) {
                            setErrorStatus(obj.item); // Set the item's status to error
                            console.error('Error:', res.msg);
                            obj.item.classList.add('uploaded-error');
                        } else {
                            info[1]++;
                            uploadCount.innerHTML = `Uploaded successfully ${info[1]} of ${info[0]}`;
                            obj.item.classList.add('uploaded-success');
                        }
                    },
                    formData: {
                        'dir': '<?php echo $upload2Dir ?>',
                    },
                });
                await fileUp.upload(element);
            }
        }
    </script>
</div>
<?php admin_footer(); ?>
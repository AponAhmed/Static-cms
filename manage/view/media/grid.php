<?php admin_header(); ?>
<div class="page-body">
    <div class="media-grid-wrapper">
        <?php
        foreach ($dirs as $dir) {
        ?>
            <div class="media-grid-item media-dir" data-subDir="<?php echo $dir->subDir ?>" data-type='dir' title="<?php echo $dir->name ?>">
                <a class="media-icon icon-dir" href="<?php echo $dir->url; ?>"><span class="count-media"><?php echo $dir->count ?></span></a>
                <div class="folder-name-area">
                    <div class='folder-name'><a href="<?php echo $dir->url; ?>"><?php echo $dir->name ?></a></div>
                    <div class="quick-action">
                        <a href="javascript:void(0)" onclick="renameItem(this)">Rename</a> | <a href="javascript:void(0)" onclick="quickDelete(this)">Delete</a>
                    </div>
                </div>
            </div>
        <?php
        } ?>
        <?php
        foreach ($files as $file) {
        ?>
            <div class="media-grid-item media-file" data-subDir="<?php echo $file->subDir ?>" data-url="<?php echo $file->src; ?>" data-type='file' title="<?php echo $file->name ?>">
                <a class="media-icon icon-image" target='_blank' href="<?php echo $file->url; ?>">
                    <img src="<?php echo $file->getSize(150); ?>" alt="<?php echo $file->name ?>">
                </a>
                <div class="folder-name-area">
                    <div class='folder-name' title="<?php echo $file->file ?>"><?php echo $file->file ?></div>
                    <div class="quick-action">
                        <a href="javascript:void(0)" onclick="renameItem(this)">Rename</a> | <a href="javascript:void(0)" onclick="quickDelete(this)">Delete</a> | <a href="javascript:void(0)" onclick="quickCopy(this)">Copy Url</a>
                    </div>
                </div>
            </div>

        <?php
        }
        ?>
    </div>
    <button id="gridToggleBtn" class="grid-toggle-btn"></button>
</div>

<script>
    // Function to toggle between list and grid view
    // Check local storage for user's view preference
    const grdIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><rect x="48" y="48" width="176" height="176" rx="20" ry="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><rect x="288" y="48" width="176" height="176" rx="20" ry="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><rect x="48" y="288" width="176" height="176" rx="20" ry="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><rect x="288" y="288" width="176" height="176" rx="20" ry="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>';
    const lstIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M160 144h288M160 256h288M160 368h288"/><circle cx="80" cy="144" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="256" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="80" cy="368" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>';
    // Add click event listener to the toggle button
    const gridToggleBtn = document.getElementById('gridToggleBtn');
    gridToggleBtn.addEventListener('click', toggleView);

    const viewPreference = localStorage.getItem('viewPreference');
    if (viewPreference != 'grid') {
        const mediaGridWrapper = document.querySelector('.media-grid-wrapper');
        mediaGridWrapper.classList.add('list');
        gridToggleBtn.innerHTML = grdIcon;
    } else {
        gridToggleBtn.innerHTML = lstIcon;
    }

    function toggleView() {
        const mediaGridWrapper = document.querySelector('.media-grid-wrapper');
        mediaGridWrapper.classList.toggle('list');
        // Store the current view preference in local storage
        const isListView = mediaGridWrapper.classList.contains('list');
        if (isListView) {
            gridToggleBtn.innerHTML = grdIcon;
        } else {
            gridToggleBtn.innerHTML = lstIcon;
        }
        localStorage.setItem('viewPreference', isListView ? 'list' : 'grid');

    }





    // JavaScript to handle the virtual context menu for multiple div.media-grid-item elements
    const mediaGridItems = document.querySelectorAll('.media-grid-item');
    let contextMenu = null;

    mediaGridItems.forEach(item => {
        item.addEventListener('contextmenu', function(event) {
            event.preventDefault(); // Prevent the default context menu from showing up
            // Create the virtual context menu
            // Remove the 'selected' class from all items
            mediaGridItems.forEach(otherItem => {
                otherItem.classList.remove('selected');
            });

            // Add the 'selected' class to the clicked item
            item.classList.add('selected');
            if (contextMenu) {
                document.body.removeChild(contextMenu);
            }
            contextMenu = document.createElement('ul');
            contextMenu.classList.add('context-menu');
            contextMenu.style.left = event.clientX + 'px';
            contextMenu.style.top = event.clientY + 'px';

            // Add context menu options dynamically based on the data-type attribute
            const type = item.getAttribute('data-type');
            const options = ['Rename', 'Delete'];
            if (type === 'file') {
                options.push('Copy Url');
            }

            options.forEach(option => {
                const li = document.createElement('li');
                li.textContent = option;
                li.style.listStyle = 'none';
                li.style.cursor = 'pointer';
                li.style.padding = '4px 16px';
                li.addEventListener('click', function() {
                    // Handle the action when an option is clicked based on the specific media item
                    const selectedItemText = item.textContent;
                    handleOptionClick(option, item, type);
                    document.body.removeChild(contextMenu); // Remove the virtual context menu
                    contextMenu = null;
                });
                contextMenu.appendChild(li);
            });

            // Append the virtual context menu to the document body
            document.body.appendChild(contextMenu);
        });
    });

    function renameItem(item) {
        item = item.closest('.media-grid-item')
        const folderName = item.querySelector('.folder-name');
        const originalName = folderName.textContent;
        const input = document.createElement('input');
        const type = item.getAttribute('data-type');
        input.type = 'text';
        input.value = originalName;
        folderName.textContent = '';
        folderName.appendChild(input);
        input.focus();

        // Add event listener to handle edit completion
        input.addEventListener('blur', async function() {
            const newName = input.value.trim();
            if (newName !== originalName) {
                const subDir = item.getAttribute('data-subdir');
                try {
                    const response = await renameFolder(type, originalName, newName, subDir);
                    if (response.status == 'error') {
                        alert(response.message);
                    } else {
                        // Handle success response
                        folderName.textContent = newName;
                        item.querySelector('a').setAttribute('href', response.url);
                        //alert(`Folder successfully renamed to "${newName}"`);
                    }

                } catch (error) {
                    // Handle error response
                    alert(`Error renaming folder: ${error.message}`);
                }
            } else {
                // If the name hasn't changed, revert back to the original name
                folderName.textContent = originalName;
            }
        });

        input.addEventListener('keypress', function(event) {
            // Handle "Enter" key press to trigger AJAX request on edit completion
            if (event.key === 'Enter') {
                input.blur();
            }
        });

    }

    function quickDelete(asdf, type) {
        let item = asdf.closest('.media-grid-item');
        if (confirm('Are you sure you want to delete')) {
            // Handle the delete action
            const type = item.getAttribute('data-type');
            const subDir = item.getAttribute('data-subdir');
            const folderName = item.querySelector('.folder-name');
            const fileName = folderName.textContent;
            deleteItem(type, subDir, fileName)
                .then(data => {
                    if (data.status == 'success') {
                        item.remove();
                    }
                    //console.log(data); // Handle the response from the server
                })
                .catch(error => {
                    console.error(error); // Handle errors
                });
        }
    }

    function quickCopy(a) {
        item = a.closest('.media-grid-item')
        // Handle the copy URL action (only for 'file' type)
        const url = item.getAttribute('data-url');
        copyToClipboard(url);

        // Change option to "Copied" for 2 seconds
        const originalText = a.textContent;
        a.textContent = 'Copied';
        setTimeout(function() {
            a.textContent = originalText;
        }, 2000);
    }

    // Callback function to handle the context menu options
    function handleOptionClick(option, item, type) {
        if (option === 'Rename') {
            // Handle the rename action
            const folderName = item.querySelector('.folder-name');
            const originalName = folderName.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = originalName;
            folderName.textContent = '';
            folderName.appendChild(input);
            input.focus();

            // Add event listener to handle edit completion
            input.addEventListener('blur', async function() {
                const newName = input.value.trim();
                if (newName !== originalName) {
                    const subDir = item.getAttribute('data-subdir');
                    try {
                        const response = await renameFolder(type, originalName, newName, subDir);
                        if (response.status == 'error') {
                            alert(response.message);
                        } else {
                            // Handle success response
                            folderName.textContent = newName;
                            item.querySelector('a').setAttribute('href', response.url);
                            //alert(`Folder successfully renamed to "${newName}"`);
                        }

                    } catch (error) {
                        // Handle error response
                        alert(`Error renaming folder: ${error.message}`);
                    }
                } else {
                    // If the name hasn't changed, revert back to the original name
                    folderName.textContent = originalName;
                }
            });

            input.addEventListener('keypress', function(event) {
                // Handle "Enter" key press to trigger AJAX request on edit completion
                if (event.key === 'Enter') {
                    input.blur();
                }
            });
        } else if (option === 'Delete') {
            if (confirm('Are you sure you want to delete')) {
                // Handle the delete action
                const subDir = item.getAttribute('data-subdir');
                const folderName = item.querySelector('.folder-name');
                const fileName = folderName.textContent;
                deleteItem(type, subDir, fileName)
                    .then(data => {
                        if (data.status == 'success') {
                            item.remove();
                        }
                        //console.log(data); // Handle the response from the server
                    })
                    .catch(error => {
                        console.error(error); // Handle errors
                    });
            }
        } else if (option === 'Copy Url') {
            // Handle the copy URL action (only for 'file' type)
            const url = item.getAttribute('data-url');
            copyToClipboard(url);

            // Change option to "Copied" for 2 seconds
            const originalText = option.textContent;
            option.textContent = 'Copied';
            setTimeout(function() {
                option.textContent = originalText;
            }, 2000);
        }
    }

    // Copy text to clipboard
    function copyToClipboard(text) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        document.body.appendChild(textarea);
        textarea.select();
        document.execCommand('copy');
        document.body.removeChild(textarea);
    }

    // Hide the virtual context menu when clicking outside
    document.addEventListener('click', function(event) {
        if (contextMenu && !contextMenu.contains(event.target)) {
            document.body.removeChild(contextMenu);
            contextMenu = null;
        }
    });


    // Function to perform the AJAX request to rename the folder
    async function renameFolder(type, fromName, toName, subDir) {
        const endpoint = ADMIN_URL + '/media/rename/';
        const data = {
            type: type,
            'from-name': fromName,
            'to-name': toName,
            subDir: subDir
        };

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Failed to rename folder');
            }

            return response.json();
        } catch (error) {
            throw new Error(error.message);
        }
    }

    // Function to perform the AJAX request to delete a directory or file
    async function deleteItem(type, subDir, fileName) {
        const endpoint = ADMIN_URL + '/media/delete/';
        const data = {
            type: type,
            subDir: subDir,
            fileName: fileName
        };

        // console.log(data);
        // return;
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Failed to delete');
            }

            const responseData = await response.json();
            return responseData;
        } catch (error) {
            throw new Error(error.message);
        }
    }


    // JavaScript
    function createNewDirectory(dir) {

        const wrapper = document.querySelector('.media-grid-wrapper');

        // Create the new media grid item (directory)
        const newMediaItem = document.createElement('div');
        newMediaItem.classList.add('media-grid-item', 'media-dir');
        newMediaItem.setAttribute('data-subdir', '');
        newMediaItem.setAttribute('data-type', 'dir');
        newMediaItem.setAttribute('title', 'mix 2');

        const iconLink = document.createElement('a');
        iconLink.classList.add('media-icon', 'icon-dir');
        iconLink.href = '#';

        const folderNameArea = document.createElement('div');
        folderNameArea.classList.add('folder-name-area');

        const folderName = document.createElement('div');
        folderName.classList.add('folder-name');

        const input = document.createElement('input');
        input.type = 'text';

        console.log(dir);
        // Add event listener to handle AJAX request on "Enter" key press or blur
        input.addEventListener('keydown', async (event) => {
            if (event.key === 'Enter') {
                const newName = input.value.trim();
                if (newName !== '') {
                    try {
                        const response = await newDir(newName, dir);
                        // Handle success response
                        if (response.status == 'success') {
                            folderName.textContent = response.name;
                            iconLink.href = response.url;
                        } else {
                            alert("Error Createing directory");
                        }
                    } catch (error) {
                        // Handle error response
                        console.error('Error renaming directory:', error);
                    }
                }
            }
        });

        input.addEventListener('blur', async () => {
            const newName = input.value.trim();
            if (newName !== '') {
                try {
                    const response = await newDir(newName, dir);
                    // Handle success response
                    if (response.status == 'success') {
                        folderName.textContent = response.name;
                        iconLink.href = response.url;
                    } else {
                        alert("Error Createing directory");
                    }
                } catch (error) {
                    // Handle error response
                    console.error('Error Createing directory:', error);
                }
            }
        });

        folderName.appendChild(input);
        folderNameArea.appendChild(folderName);
        newMediaItem.appendChild(iconLink);
        newMediaItem.appendChild(folderNameArea);

        // Append the new media grid item to the beginning of the wrapper
        wrapper.insertBefore(newMediaItem, wrapper.firstChild);

        input.focus();
    }

    async function newDir(newName, dir) {
        const endpoint = ADMIN_URL + '/media/new-dir/';
        const data = {
            name: newName,
            subDir: dir
        };

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error('Failed to Create directory');
            }
            return response.json();
        } catch (error) {
            throw new Error(error.message);
        }
    }
</script>
<?php admin_footer(); ?>
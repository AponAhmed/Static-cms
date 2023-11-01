<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="settings">
        <div class="tab-wrap settings-tab">
            <ul>
                <li class="active section-head" data-id="general">General Settings</li>
                <li class="section-head" data-id="pageSettings">Page Settings</li>
                <li class="section-head" data-id="media">Media Settings</li>
                <li class="section-head" data-id="headers">Response Headers</li>
                <li class="section-head" data-id="contacts">Contacts</li>
                <li class="section-head" data-id="system">System</li>
            </ul>
            <div class="tab-contents-wrap">
                <section id="general" class="tab-pan active">
                    <div class="input-group">
                        <label for="name">Logo:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('logo') ?>" type="text" id="logo" name="logo">
                    </div>
                    <div class="input-group">
                        <label for="name">Favicon:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('favicon') ?>" type="text" id="favicon" name="favicon">
                    </div>

                    <div class="input-group">
                        <label for="name">Site Name:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('name') ?>" type="text" id="name" name="name">
                    </div>


                    <div class="input-group">
                        <label for="tagline">Tag Line:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('tagline') ?>" type="text" id="tagline" name="tagline">
                    </div>

                    <div class="input-group">
                        <label for="siteurl">Site URL:</label>
                        <input class="text-input" type="text" value="<?php echo $settings->getSetting('siteurl') ?>" id="siteurl" name="siteurl">
                    </div>
                    <div class="input-group">
                        <label></label>

                        <label for="disable_search_engines" style="width:  250px !important;"> <input type="checkbox" <?php echo $settings->getSetting('disable_search_engines', false) ? 'checked' : '' ?> id="disable_search_engines" name="disable_search_engines">&nbsp;&nbsp; Disable From Search Engines </label>
                    </div>
                    <div class="input-group">
                        <label></label>
                        <input type="checkbox" <?php echo $settings->getSetting('debug', false) ? 'checked' : '' ?> id="debug" name="debug">&nbsp;&nbsp;
                        <label for="debug">Debug Mode:</label>
                    </div>
                </section>
                <section id="pageSettings" class="tab-pan">
                    <div class="input-group">
                        <label for="home_page">Home Page</label>
                        <input class="text-input" type="text" value="<?php echo $settings->getSetting('home_page','home') ?>" id="home_page" name="home_page">
                    </div>
                    <div class="input-group">
                        <label for="redirect_404">404 Page</label>
                        <div class="">
                            <input type="checkbox" <?php echo $settings->getSetting('redirect_404', false) ? 'checked' : '' ?> id="redirect_404" name="redirect_404">&nbsp;&nbsp;
                            <input class="text-input" style="max-width: 45px; margin-right: 15px;" type="text" value="<?php echo $settings->getSetting('redirect_404_code', '301') ?>" id="redirect_404_code" name="redirect_404_code">
                            <label for="redirect_404">Redirect to Home Page</label>
                        </div>
                    </div>

                </section>
                <section id="media" class="tab-pan">
                    <div class="input-group">
                        <label for="virtualimage">Virtual Image:</label>
                        <input type="checkbox" <?php echo $settings->getSetting('virtualimage', false) ? 'checked' : '' ?> id="virtualimage" name="virtualimage">&nbsp;&nbsp;
                        <label for="virtualimage">Enable</label>
                    </div>
                    <div class="input-group">
                        <label for="virtualwebp">Virtual Webp:</label>
                        <input type="checkbox" <?php echo $settings->getSetting('virtualwebp', false) ? 'checked' : '' ?> id="virtualwebp" name="virtualwebp">&nbsp;&nbsp;
                        <label for="virtualwebp">Enable</label>
                    </div>
                    <div class="input-group">
                        <label for="virtualdir">Virtual Image Base:</label>
                        <input class="text-input" type="text" value="<?php echo $settings->getSetting('virtualdir') ?>" id="virtualdir" name="virtualdir">
                    </div>

                    <div class="input-group">
                        <label for="imagebase">Attachment URL Base:</label>
                        <input class="text-input" type="text" value="<?php echo $settings->getSetting('imagebase') ?>" id="imagebase" name="imagebase">
                    </div>
                    <div class="input-group">
                        <label for="imagebase">Private Directories:</label>
                        <input class="text-input" type="text" value="<?php echo $settings->getSetting('privateDirs') ?>" id="privateDirs" name="privateDirs">
                        <span>&nbsp;&nbsp;For more then one then Seperated by comma(,)</span>
                    </div>
                </section>
                <div id="headers" class="tab-pan">
                    <!-- Content for Cache Policy tab -->
                    <div class="input-group">
                        <label>Response Headers</label>
                        <div>
                            <textarea style="width: 600px;min-height:120px;" id="responseHeaders" name="responseHeaders" placeholder="Cache-Control: max-age=3600, public"><?php echo $settings->getSetting('responseHeaders') ?></textarea>

                            <br><label style="font-weight: 300;font-style: italic;">Each Header should new line. shortcode : [time], [time] + 3600 , [siteurl]</label>
                        </div>
                        <span>
                            &nbsp; Cache-Control: max-age=31536000, public<br>
                            &nbsp; Expires: [time] + 31536000 GMT<br>
                            &nbsp; content-disposition: inline;<br>
                        </span>
                    </div>
                </div>
                <div id="contacts" class="tab-pan">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" class="text-input" placeholder="Email Address" value="<?php echo $settings->getSetting('email', 'sales@siatexltd.com') ?>" id="email" name="email">
                    </div>
                    <div class="input-group">
                        <label>Telephone</label>
                        <input type="text" class="text-input" placeholder="Telephone" value="<?php echo $settings->getSetting('telephone', '(02) 222-285-548') ?>" id="telephone" name="telephone">
                    </div>
                    <div class="input-group">
                        <label>WhatsApp</label>
                        <input type="text" class="text-input" placeholder="WhatsApp" value="<?php echo $settings->getSetting('whatsapp', '') ?>" id="whatsapp" name="whatsapp">
                    </div>

                </div>
                <div class="tab-pan" id="system">
                    <span>Click Here to update system with latest version</span>
                    <hr>
                    <button type="button" id="updateButton" class="btn btn-primary">Update System</button>
                </div>
            </div>
        </div>
        <button type="submit" id="saveBtn">Save Settings</button>
    </form>
</div>
<script>
    //Update system
    const updateButton = document.getElementById('updateButton');

    updateButton.addEventListener('click', () => {
        // Send an AJAX request to the server to download and unzip the update.zip file
        updateButton.innerHTML = 'Updating...';
        const xhr = new XMLHttpRequest();
        xhr.open('GET', ADMIN_URL + '/sysupdate/update/', true);

        xhr.onreadystatechange = function() {

            if (xhr.readyState === 4 && xhr.status === 200) {
                let serverResponse = xhr.responseText;
                if (serverResponse == 1) {
                    updateButton.innerHTML = 'Update Success';
                    updateButton.classList.remove('btn-primary');
                    updateButton.classList.add('btn-success');
                    setTimeout(function() {
                        updateButton.classList.remove('btn-success');
                        updateButton.classList.add('btn-primary');
                        updateButton.innerHTML = 'Update System';
                    }, 3000);
                } else {
                    updateButton.innerHTML = '----';
                    alert('System update failed (Server Error), Contact your server administrator');
                }
                // Handle a successful response from the server
            } else if (xhr.readyState === 4 && xhr.status !== 200) {
                // Handle errors or failures
                updateButton.innerHTML = 'Try Again';
                alert('System update failed (Network error).');
            }
        };

        xhr.send();
    });

    /*init All Tab*/
    // Find all elements with the class .tab-wrap
    const tabWraps = document.querySelectorAll('.tab-wrap');
    // Loop through the tab-wrap elements and create Tab instances
    tabWraps.forEach(tabWrap => {
        new Tab(tabWrap);
    });
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector(".settings");

        form.addEventListener("submit", function(event) {
            event.preventDefault();
            let btn = document.querySelector("#saveBtn");
            btn.innerHTML = "Updating...";
            var formData = {};
            var inputs = form.querySelectorAll("input, select, textarea");

            inputs.forEach(function(input) {
                var name = input.name;
                var value = input.type === 'checkbox' ? input.checked : input.value;
                formData[name] = value;
            });

            var xhr = new XMLHttpRequest();
            const endpoint = ADMIN_URL + '/settings/update/';
            xhr.open("POST", endpoint, true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status == 'success') {
                        btn.innerHTML = "Updated successfully";
                    } else {
                        btn.innerHTML = "Updated Error";
                    }
                    setTimeout(() => {
                        btn.innerHTML = "Save Settings";
                    }, 2000);
                    //console.log("Settings saved successfully:", response);
                } else {
                    console.error("Error saving settings:", xhr.statusText);
                }
            };

            xhr.onerror = function() {
                console.error("Network error occurred");
            };

            xhr.send(JSON.stringify(formData));
        });
    });
</script>

<?php admin_footer(); ?>
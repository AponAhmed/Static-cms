<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="settings">
        <div class="tab-wrap settings-tab">
            <ul>
                <li class="active section-head" data-id="apiSettings">Api Settings</li>
                <!-- <li class="section-head" data-id="ipApi">IP Api</li> -->
            </ul>
            <div class="tab-contents-wrap">
                <section id="apiSettings" class="tab-pan active">
                    <div class="input-group">
                        <label for="api_path">Api Path:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('contact_api_path') ?>" type="text" id="api_path" name="contact_api_path">
                    </div>
                    <div class="input-group">
                        <label for="api_key">Api Key:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('contact_api_key') ?>" type="text" id="api_key" name="contact_api_key">
                    </div>
                </section>
                <!-- <section id="ipApi" class="tab-pan">
                    <div class="input-group">
                        <label for="ip_api_path">Api Url Structure:</label>
                        <input class="text-input" value="<?php echo $settings->getSetting('ip_api_path') ?>" type="text" id="ip_api_path" name="ip_api_path">
                    </div>
                </section> -->
            </div>
        </div>
        <button type="submit" id="saveBtn">Save Settings</button>
    </form>
</div>
<script>
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
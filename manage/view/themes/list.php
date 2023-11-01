<?php admin_header(); ?>
<div class="page-body">
    <div class="theme-container">
        <?php
        foreach ($themes as $dir => $theme) {
        ?>
            <div class="theme-thumbnails">
                <?php if (isset($theme['thumb'])) {
                ?>
                    <img src="<?php echo $theme['thumb'] ?>" alt="<?php echo $theme['name'] ?>" />
                <?php
                } ?>
                <div class="theme-info">
                    <strong class="theme-name"><?php echo $theme['name'] ?></strong>
                    <label class="theme-vs-auth">
                        Version : <span><?php echo $theme['version'] ?></span>
                        <?php if (isset($theme['author']) && !empty($theme['author'])) { ?>
                            Author : <span><?php echo $theme['author'] ?></span>
                        <?php } ?>
                    </label>
                    <button type="button" class="active-theme" onclick="alert('Work in Progress...')" <?php echo $theme['current'] ? 'disabled' : "" ?>><?php echo $theme['current'] ? 'Current' : "Active" ?></button>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var form = document.querySelector(".settings");

        form.addEventListener("submit", function(event) {
            event.preventDefault();

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
                    console.log("Settings saved successfully:", response);
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
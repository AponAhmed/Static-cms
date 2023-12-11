<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="settings sitemap-generate">
        <div class="tab-wrap settings-tab">
            <ul>
                <li class="section-head active" data-id="generateNsettings">Generate & Settings</li>
                <li class="section-head" data-id="data">Data</li>
                <li class="section-head" data-id="exLinks">External Links</li>
            </ul>
            <div class="tab-contents-wrap p0">
                <section id="generateNsettings" class="tab-pan active">
                    <div class="sitemap-generate-section" style="padding: 25px;">
                        <div class="input-group">
                            <label for="sitemap_dir">Sitemaps Dir</label>
                            <input class="text-input" style="max-width:220px;" placeholder="sitemaps" type="text" value="<?php echo $settings->getSetting('sitemap_dir') ?>" id="sitemap_dir" name="sitemap_dir">
                        </div>
                        <div class="input-group">
                            <label for="sitemap_dir">Max Links</label>
                            <div>
                                <input class="text-input" style="max-width:120px;" placeholder="1000" type="text" value="<?php echo $settings->getSetting('sitemap_max_link') ?>" id="sitemap_max_link" name="sitemap_max_link">
                                <sapan>Maximum link in a single sitemap(xml) file</sapan>
                            </div>

                        </div>
                        <div class="input-group">
                            <label for="sitemap_file_name">File Name</label>
                            <input style="max-width: 180px;" class="text-input" type="text" placeholder="sitemap" value="<?php echo $settings->getSetting('sitemap_file_name') ?>" id="sitemap_file_name" name="sitemap_file_name">
                            <span style="padding:0 4px;">.xml</span>
                        </div>
                        <div class="input-group">
                            <label for="sitemap_mod_date">Modified Date</label>
                            <input style="max-width: 180px;" class="text-input" type="date" value="<?php echo $settings->getSetting('sitemap_mod_date') ?>" id="sitemap_mod_date" name="sitemap_mod_date">

                        </div>
                        <hr>

                        <div class="form-group">
                            <button type="button" onclick="generateSitemap(this)">Generate Sitemap</button>
                            <span id="gerenateResponse"></span>
                            <a id="viewUrl" target="_blank" href="<?php siteUrl() ?>/<?php echo $settings->getSetting('sitemap_file_name', 'sitemap') ?>.xml">View Sitemap</a>
                        </div>
                        <div id="reportContainer">

                        </div>

                    </div>
                </section>
                <section id="exLinks" class="tab-pan" style="padding: 25px;">
                    <div style="display: flex;flex-direction: column;">
                        <label>Each link put at new line</label>
                        <textarea rows="20" name="external_links"><?php 
						$links=$settings->getSetting('external_links');
						if(!empty($links)){
							echo $links;
						}else{
							echo 'https://www.siatex.com
https://www.siatex.co
https://siatexsl.com
https://siatexhk.com
https://siatexinc.com
https://topteeinc.com
https://huraira-fashion.com
https://paimexco.com
https://www.bgmea.com.bd
https://www.bkmea.com
https://www.siatexsourcing.com
https://www.pritomtex.com
https://www.idyasinc.com
https://shadanatex.com
https://www.siatexpromowear.com
https://www.amfori.org
https://www.oeko-tex.com
https://www.sedex.com
https://wrapcompliance.org
https://bangladeshaccord.org';
						}
						?></textarea>
                    </div>
                </section>
                <section id="data" class="tab-pan">
                    <!-- Content for Cache Policy tab -->
                    <div class="key-tab-item" id="csvFiles">
                        <div class="keyword-manage">
                            <div class="keyword-groups">
                                <input style="display: none" onchange="uploadCsvFile2Server(this)" type="file" id="uploadCsv">
                                <label class="uploadSvg" for="uploadCsv">
                                    <svg class="ionicon" style="width:20px" viewBox="0 0 512 512">
                                        <title>Cloud Upload</title>
                                        <path d="M320 367.79h76c55 0 100-29.21 100-83.6s-53-81.47-96-83.6c-8.89-85.06-71-136.8-144-136.8-69 0-113.44 45.79-128 91.2-60 5.7-112 43.88-112 106.4s54 106.4 120 106.4h56" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M320 255.79l-64-64-64 64M256 448.21V207.79" />
                                    </svg>
                                    Upload
                                    <span class="csv-progress"></span>
                                </label>
                                <ul class="svgFileList">
                                    <?php
                                    $files = $csvs->listCsvFiles();
                                    if ($files) {
                                        foreach ($files as $file) {
                                            echo "<li data-name='$file' class='csvList' onclick='loadCsv(this)'><span class='removeList' onclick='removeCsv(this)'>&times;</span>$file</li>";
                                        }
                                    }
                                    ?>
                                </ul>
                            </div>
                            <div class="csv-wrap">
                                <div class="svg-data"></div>
                                <button class="update-csvData" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                        <title>Update</title>
                                        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M416 128L192 384l-96-96" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>
                </section>
            </div>
        </div>
        <button type="submit" id="saveBtn" class="btn-primary">Save Settings</button>
    </form>
</div>
<script src="<?php echo admin_assets('/js/sitemap.js') ?>"></script>
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
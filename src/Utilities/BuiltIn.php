<?php

namespace Aponahmed\Cmsstatic\Utilities;

use Aponahmed\Cmsstatic\manage\Model\Taxonomy;
use Aponahmed\Cmsstatic\shortcode\StaticShortcode;

class BuiltIn
{

    static $controller;

    static function metabox($controller = false)
    {
        self::$controller = $controller;
        self::registerBoxs();
    }

    static function registerBoxs()
    {
        add_meta_box([
            'id' => 'shortcodeHint',
            'title' => 'Available Shortcode',
            'templates' => ['all'],
            'sidebar' => true,
            'priority' => 'high',
            'type' => ['all'],
            'callback' => [self::class, 'shortcodeHint'],
            'collapsed' => true
        ]);

        add_meta_box([
            'id' => 'feature_image',
            'title' => 'Feature Image',
            'templates' => ['all'],
            'sidebar' => true,
            'type' => ['all'],
            'callback' => [self::class, 'fetureImageCallback'],
        ]);


        add_meta_box([
            'id' => 'category_manager',
            'title' => 'Category',
            'templates' => ['all'],
            'sidebar' => true,
            'priority' => 'high',
            'type' => ['static'],
            'callback' => [self::class, 'categoryManager'],
        ]);

        add_meta_box([
            'id' => 'seo_meta_box',
            'title' => 'Seo Meta',
            'templates' => ['all'],
            'sidebar' => false,
            'type' => ['page'],
            'callback' => [self::class, 'seo_meta_box_content'],
        ]);

        add_meta_box([
            'id' => 'multipage_settings',
            'title' => 'Multipage Settings',
            'templates' => ['all'],
            'sidebar' => true,
            'type' => ['page'],
            'priority' => 'high',
            'callback' => [self::class, 'multipage_settings_callback'],
        ]);
    }


    static function shortcodeHint()
    {
?>

        {title}, {slug}, {h1}, {meta-title}, {meta-desc}, {meta-key}
        <hr>
        {segment-1},{segment-2}...
        <hr>
        [image dir="assets" col="4" title="hide/none" mcol="2" tcol="3" link="yes" rand="yes" limit="28" cart="yes" fromAll="yes"]
        <hr>
        [grid col="2"][/grid], [color color="#666"][/color], [bold][/bold], <br>[link], [inlink] &lt;text&gt; [/inlink],<br>[exlink], [texlink] &lt;text&gt; [/texlink]
        <hr>
        [innerLink number="yes"] Q:-- A:---[/innerLink],<br> [innerLink number="yes"]-Heading Basis-[/innerLink],<br> [innerLink number="yes" type="single"]-Heading Basis Single-[/innerLink]
        <hr>
        [randkey n="2" h="2" hl="2" pl="20" sl="6" col="2" show="false" link="false"]<br>
        [randphrase n="5"]<br>
        [randkey-list n="30"]
        <hr>
        [static cat="Services" col="3" icon="yes" icon-color="#f00" icon-bg="bg-e"]
        <pre>
        <?php print_r(StaticShortcode::$default) ?>
        </pre>
    <?php
    }

    static function categoryManager($page)
    {
        $taxonomy = new Taxonomy('static-category');
        $categories = $taxonomy->get();
    ?>
        <ul class="category-list">
            <?php
            if ($categories) {
                foreach ($categories as $name => $data) {
                    $checked = "";
                    //var_dump($data);
                    if (in_array($page->slug, $data)) {
                        $checked = "checked";
                    }
                    echo "<li><label><input class=\"termcheck\" name=\"taxo[static-category][]\" value=\"$name\" $checked type=\"checkbox\">&nbsp;$name</label><span onclick=\"removeterm('$name',this)\" class=\"remove-term\">&times;</span></li>";
                }
            }
            ?>
        </ul>
        <div class="inline-flex group-input-btn">
            <input type="text" id="newTaxonomy" value="">
            <button type="button" id="btnsaveCategory" class="btn btn-default">Save</button>
        </div>
        <script>
            // Assuming you have included jQuery in your HTML
            $("#btnsaveCategory").on("click", function() {
                // Define the data you want to send in the POST request
                var postData = {
                    taxonomy: 'static-category',
                    name: $("#newTaxonomy").val()
                };

                // Perform the AJAX POST request
                $.ajax({
                    type: 'POST', // HTTP method for the request
                    url: ADMIN_URL + '/taxonomy/add/', // Replace with the URL of your API endpoint
                    data: JSON.stringify(postData), // Convert the data to JSON format
                    contentType: 'application/json', // Set the content type to JSON
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.error) {
                            alert(response.message);
                        } else {
                            $("#newTaxonomy").val("");
                            $(".category-list").append(`<li><label><input name="taxo[static-category][]" value="${response.item}" checked type="checkbox">&nbsp;${response.item}</label><span class="remove-term" onclick="removeterm('${response.item}',this)">&times;</span></li>`);
                        }
                        // Handle the successful response from the server
                        console.log('Success:', response);

                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Handle any errors that occurred during the request
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            });

            // Listen for checkbox uncheck events
            $('input.termcheck').on('change', function() {
                if (!$(this).prop('checked')) {
                    console.log('termcheck uncheck');
                    var checkboxValue = $(this).val();
                    var existingSlug = $('#PageSlug').val();

                    // Create a hidden input field with the unchecked checkbox value and existing slug
                    var hiddenInput = '<input type="hidden" name="termuncheck[static-category][' + checkboxValue + ']" value="' + existingSlug + '">';

                    // Append the hidden input to the parent <li> element
                    $('.category-list').append(hiddenInput);
                }
            });

            function removeterm(name, _this) {
                if (!confirm('Are you sure you want to remove')) return
                var postData = {
                    taxonomy: 'static-category',
                    name: name,
                };

                // Perform the AJAX POST request
                $.ajax({
                    type: 'POST', // HTTP method for the request
                    url: ADMIN_URL + '/taxonomy/remove/', // Replace with the URL of your API endpoint
                    data: JSON.stringify(postData), // Convert the data to JSON format
                    contentType: 'application/json', // Set the content type to JSON
                    success: function(response) {
                        response = JSON.parse(response);
                        if (response.error) {
                            alert(response.message);
                        } else {
                            $(_this).closest('li').remove();
                        }
                        // Handle the successful response from the server
                        console.log('Success:', response);
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // Handle any errors that occurred during the request
                        console.error('Error:', textStatus, errorThrown);
                    }
                });
            }
        </script>
    <?php
    }

    static function multipage_settings_callback($page)
    {
        $dir = urlSlashFix(self::$controller::$contentDir . "/csvs/");
        $csvFileController = new CsvFileManager($dir);
        //what csv file will apply for this page
        //default segments with comma separated
        return self::$controller->view('sitemap.multiple-page-metabox', ['csvs' => $csvFileController, 'page' => $page]);
    }

    static function fetureImageCallback($page)
    {
        $images = $page->featureimages;
        $svg = $page->svg;

        $imgStr = $images;
        if (is_array($images)) {
            $imgStr = "";
            foreach ($images as $image) {
                $imgStr .= $image . "\n";
            }
        }

        echo "<textarea rows='5' name='data[featureimages]' class='text-input'>$imgStr</textarea>";
        echo "<label>SVG</label>";
        echo "<textarea rows='2' name='data[svg]' class='text-input'>$svg</textarea>";
        //$val = isset($images[0]) ? $images[0] : "";
        // echo "<input class=\"text-input\" name=\"data[featureimages][]\" value=\"" . $val . "\" type=\"text\">";
    }

    static function seo_meta_box_content($page)
    {
    ?>
        <div class="tab-wrap">
            <ul>
                <li class="active section-head" data-id="metaData" style="padding: 5px 10px;">Meta Data</li>
                <li class="section-head" data-id="keySuggest" style="padding: 5px 10px;">Keywords</li>
            </ul>
            <div class="tab-contents-wrap">
                <section id="metaData" class="tab-pan active">
                    <div class="input-container">
                        <label>Title</label>
                        <input type="text" class="text-input" value="<?php __e($page->meta_title) ?>" placeholder="Meta Title" title="Meta Title" name="data[meta_title]">
                    </div>
                    <hr>
                    <div class="input-container flex">
                        <label for="seo-options">Robots Option:</label>
                        <select id="seo-options" name="data[meta_robots]">
                            <option value="index, follow" <?php echo $page->meta_robots == 'index, follow' ? 'selected' : '' ?>>Index & Follow</option>
                            <option value="noindex, follow" <?php echo $page->meta_robots == 'noindex, follow' ? 'selected' : '' ?>>NoIndex & Follow</option>
                            <option value="noindex, nofollow" <?php echo $page->meta_robots == 'noindex, nofollow' ? 'selected' : '' ?>>NoIndex & NoFollow</option>
                            <option value="noarchive" <?php echo $page->meta_robots == 'noarchive' ? 'selected' : '' ?>>No Archive</option>
                            <option value="nosnippet" <?php echo $page->meta_robots == 'nosnippet' ? 'selected' : '' ?>>No Snippet</option>
                        </select>
                    </div>
                    <div class="input-container flex">
                        <label>Description</label>
                        <textarea class="text-input" placeholder="Meta Description" rows="5" title="Meta Description" name="data[meta_desc]"><?php __e($page->meta_desc) ?></textarea>
                    </div>
                    <div class="input-container flex">
                        <label>Keywords</label>
                        <textarea class="text-input" placeholder="Meta Keywords" rows="5" title="Meta Keyword" name="data[meta_key]"><?php __e($page->meta_key) ?></textarea>
                    </div>
                </section>
                <section class="tab-pan" id="keySuggest">
                    <div class='flex'>
                        <input type="text" id="mainkey" style="padding: 2px 10px;" name="data[main_key]" value="<?php __e($page->main_key) ?>">
                        <button type="button" onclick="generateKeyTemp()">Generate</button>
                    </div>
                    <hr>
                    <textarea id="outputText" style="display: none;" name="data[main_key_output]"><?php __e($page->main_key_output) ?></textarea>
                    <div id="output_container">

                    </div>
                </section>
            </div>
        </div>
        <script>
            generateKeyTemp();
            /*init All Tab*/
            // Find all elements with the class .tab-wrap
            const tabWraps = document.querySelectorAll('.tab-wrap');
            // Loop through the tab-wrap elements and create Tab instances
            tabWraps.forEach(tabWrap => {
                new Tab(tabWrap);
            });

            function generateKeyTemp() {
                let keyword = document.getElementById('mainkey').value;
                let str = `<h1>${keyword} Manufacturers in {segment-1}</h1>
<strong>${keyword} Manufacturers in {segment-1}</strong>

<h2>${keyword} Suppliers in {segment-1}</h2> 
<strong>${keyword} Suppliers in {segment-1}</strong>

<h2>${keyword} Exporters in {segment-1}</h2>
<strong>${keyword} Exporters in {segment-1}</strong>

<h2>Wholesale ${keyword} in {segment-1}</h2>
<strong>Wholesale ${keyword} in {segment-1}</strong>

<p>${keyword} Manufacturers in {segment-1}, ${keyword} Suppliers in {segment-1}, ${keyword} Exporters in {segment-1}, Wholesale ${keyword} in {segment-1}, Wholesaler ${keyword} in {segment-1}, ${keyword} in {segment-1}</p>`;

                document.getElementById("output_container").innerHTML = str;
            }
        </script>
<?php
    }
}

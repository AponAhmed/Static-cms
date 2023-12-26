<?php

class BlogRelatedTemplate
{
    public function __construct()
    {
        if (is_admin()) {
            $this->admin();
        } else {
            add_action('blog_banner', [$this, 'blog_banner'], 1);
            add_action('blog_content', [$this, 'blog_content'], 1);
        }
    }


    static function test_metaBoxCallback($page)
    {
        $templateData = $page->templateData;
?>
        <div class="box-row">
            <div class="box box-8">
                <div class="meta-input">
                    <label>Banner Left</label>
                    <textarea name="data[templateData][banner_left]" id="fa" class="bannerLeft" cols="30" rows="10"><?php $templateData ? __e($templateData['banner_left']) : "" ?></textarea>
                </div>
                <label><input type="checkbox" value="1" <?php echo isset($templateData['banner_image_fixed']) && $templateData['banner_image_fixed'] ? "checked" : "" ?> name="data[templateData][banner_image_fixed]"> <strong>Image Scroll Fixed</strong></label>&nbsp;&nbsp;|&nbsp;
                <label><input type="checkbox" value="1" <?php echo isset($templateData['banner_image_caption_d']) && $templateData['banner_image_caption_d'] ? "checked" : "" ?> name="data[templateData][banner_image_caption_d]"> <strong>Disable Image Caption</strong></label>&nbsp;&nbsp;|&nbsp;
                <label><input type="checkbox" value="1" <?php echo isset($templateData['location_disable']) && $templateData['location_disable'] ? "checked" : "" ?> name="data[templateData][location_disable]"> <strong>Disable location</strong></label>
            </div>
            <div class="box box-4">
                <label><input type="checkbox" value="1" name="data[templateData][sidebar]" <?php echo isset($templateData['sidebar']) && $templateData['sidebar'] ? "checked" : "" ?>>&nbsp;&nbsp;Enable Sidebar</label>
                <hr>
                <input class="text-input" type="text" name="data[templateData][sidebar_title]" value="<?php echo isset($templateData['sidebar_title']) ? $templateData['sidebar_title'] : "" ?>" placeholder="Sidebar Title">
                <div class="meta-input flex-row">
                    <label>Menu</label>
                    <?php menuSelect('data[templateData][sidebar_menu]', isset($templateData['sidebar_menu']) ? $templateData['sidebar_menu'] : ''); ?>
                    <!-- <select name='data[templateData][sidebar_menu]'>
                        <option value="">Select</option>
                    </select> -->
                </div>
                <div class="meta-input flex-row">
                    <label>Position</label>
                    <select name='data[templateData][sidebar_pos]'>
                        <option value="left" <?php echo isset($templateData['sidebar_pos']) && $templateData['sidebar_pos'] == 'left' ? 'selected' : "" ?>>Left</option>
                        <option value="right" <?php echo isset($templateData['sidebar_pos']) && $templateData['sidebar_pos'] == 'right' ? 'selected' : "" ?>>Right</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="box-row">
            <div class="box box-12">
                <label><input type="checkbox" value="1" <?php echo isset($templateData['sidebar_text_enable']) && $templateData['sidebar_text_enable'] == '1' ? 'checked' : "" ?> onchange="sidebarTextToggle(this)" name="data[templateData][sidebar_text_enable]"> Sidebar Text</label>
                <div class="sidebar-text-editor-container" style="<?php echo isset($templateData['sidebar_text_enable']) && $templateData['sidebar_text_enable'] == '1' ? 'display:block' : "display:none" ?>">
                    <textarea class="text-input" id="sidebarText" placeholder="Sidebar Bottom" rows="9" name="data[templateData][sidebar_bottom]"><?php echo isset($templateData['sidebar_bottom']) ? $templateData['sidebar_bottom'] : "" ?></textarea>
                </div>
            </div>
        </div>

        <div class="box-row">
            <div class="box box-12">
                <div class="meta-input">
                    <label>After Banner</label>
                    <div style="border: 1px solid #ddd;text-align: center;padding: 5px;">
                        <label>
                            <input type="radio" value="0" <?php echo isset($templateData['only_best']) && $templateData['only_best'] == '0' ? "checked" : "" ?> name="data[templateData][only_best]">
                            None
                        </label>
                        <label>
                            <input type="radio" value="1" <?php echo isset($templateData['only_best']) && $templateData['only_best'] == '1' ? "checked" : "" ?> name="data[templateData][only_best]">
                            Only Best Product
                        </label>
                        <label>
                            <input type="radio" value="2" <?php echo isset($templateData['only_best']) && $templateData['only_best'] == '2' ? "checked" : "" ?> name="data[templateData][only_best]">
                            Simply the Best
                        </label>
                    </div>
                    <textarea name="data[templateData][after_banner]" id="fa" class="afterBanner" cols="30" rows="10"><?php $templateData ? __e($templateData['after_banner']) : ""  ?></textarea>
                </div>
            </div>
        </div>
        <script>
            editor(document.querySelector('.bannerLeft'));
            editor(document.querySelector('.afterBanner'));
            editor(document.querySelector('#sidebarText'));

            function sidebarTextToggle(event) {
                var sidebarTextContainer = document.querySelector('.sidebar-text-editor-container');
                if (event.checked) {
                    sidebarTextContainer.style.display = 'block';
                } else {
                    sidebarTextContainer.style.display = 'none';
                }
            }
        </script>
    <?php
    }

    function admin()
    {
        add_meta_box([
            'id' => 'blog_related_template_meta',
            'title' => 'Template Meta Data',
            'templates' => ['template-blog-related'],
            'sidebar' => false,
            'priority' => 'high',
            'callback' => [self::class, 'test_metaBoxCallback'],
        ]);
    }

    function blog_banner($page)
    {
        $cls = 'template-banner';

        $templateData = $page->templateData;
        $desc = "";
        if (isset($templateData->banner_left) && $templateData->banner_left != '') {
            $cls .= " has_description";
            $desc = $templateData->banner_left;
        }
        $scrollFixed = false;
        if (property_exists($templateData, 'banner_image_fixed') && $templateData->banner_image_fixed == '1') {
            $scrollFixed = true;
            $cls .= " scrolling-fixed-wrapper";
        }

    ?>
        <div class="<?php echo $cls ?>">
            <div class="banner-left">
                <h1><?php echo h1_text() ?></h1>
                <?php echo $desc; ?>
            </div>
            <div class="banner-image">
                <div class="image-thumb scroll-fixed">
                    <?php
                    if (count(featureimages()) > 1) {
                        $caption = true;
                        if (property_exists($templateData, 'banner_image_caption_d') && $templateData->banner_image_caption_d == 1) {
                            $caption = $templateData->banner_image_caption_d == 1 ? false : true;
                        }
                        makeSlider($page->featureimages, [
                            'bullet' => true,
                            'caption' => $caption,
                            'random' => true,
                            'nav' => true,
                            'sliderArea' => ['d' => "2:1:21", 't' => '2:1', 'm' => '1:1:15'] //Acording area of window (window Width:Slider Widtg)
                        ]);
                    } else {
                        get_thumbnail([6, 6, 12], true, true);
                    }
                    ?>
                </div>
            </div>
        </div>
<?php
    }

    static function blog_content($page)
    {
        $templateData = (array) $page->templateData;
        if (isset($templateData['after_banner']) && !empty($templateData['after_banner'])) {
            echo "<section class=\"banner-bottom-section\">";

            if (isset($templateData['only_best'])) {
                if ($templateData['only_best'] == 1) {
                    echo '<div class="section_subtitle">ONLY THE BEST</div>
                    <div class="section_title">PRODUCT</div>';
                } else if ($templateData['only_best'] == 2) {
                    echo '<div class="section_subtitle">SIMPLY THE BEST</div>
                    <div class="section_title">PORTFOLIOS</div>';
                }
            }

            echo $templateData['after_banner'];
            echo "</section>";
        }
        //Sidebar options: sidebar_bottom, sidebar,sidebar_title,sidebar_menu,sidebar_pos
        $sidebar = isset($templateData['sidebar']) ? $templateData['sidebar'] : false;
        $sidebar_title = isset($templateData['sidebar_title']) ? $templateData['sidebar_title'] : "";
        $sidebar_menu = isset($templateData['sidebar_menu']) ? $templateData['sidebar_menu'] : "";
        $sidebar_pos = isset($templateData['sidebar_pos']) ? $templateData['sidebar_pos'] : "left";
        $sidebar_bottom = isset($templateData['sidebar_bottom']) ? $templateData['sidebar_bottom'] : "";

        $locDisable = isset($templateData['location_disable']) ? $templateData['location_disable'] : "";



        if ($sidebar) {
            $left = "sidebar-" . $sidebar_pos;

            echo '<section class="page-content-section">';
            echo '<div class="box-row ' . $left . '">';

            echo '<div class="box box-8">';
            the_content();
            echo "</div>";

            echo '<div class="box box-4"><aside class="blog-related-sidebar">';
            if (!empty($sidebar_title)) {
                echo '<h3 class="sidebar-title">' . $sidebar_title . '</h3>';
            }

            if ($locDisable != '1') {
                echo '[location]';
            }

            if (!empty($sidebar_menu)) {
                get_menu($sidebar_menu, ['class' => 'sidebar-menu']);
            }

            if (!empty($sidebar_bottom)) {
                echo "<div class='sidebar-bottom-text'>" . autop($sidebar_bottom) . "</div>";
            }

            echo "</aside></div>";

            echo "</div>";
            echo "</section>";
        } else {
            echo '<section class="page-content-section">';
            the_content();
            echo "</section>";
        }
    }
}


new BlogRelatedTemplate();

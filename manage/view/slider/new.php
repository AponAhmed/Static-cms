<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="page-composer-area" action="<?php page_url() ?>/store/">
        <div class="page-editor">
            <input type="text" id="PageTitle" class="text-input" placeholder="Slider Title" name="data[title]" require>
            <input type="hidden" id="PageSlugNew" class="text-input" placeholder="Slug" name="data[slug]" required>
            <div class="slider-container">

            </div>
            <button type="button" id="add-slide">+</button>
        </div>
        <div class="controller-section">
            <div class="meta-box controll-box">
                <div class="meta-box-header controll-box-title">
                    <span>Publish</span>
                </div>
                <div class="meta-box-container">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
            <div class="meta-box controll-box">
                <div class="meta-box-header controll-box-title">
                    <span>Settings</span>
                </div>
                <div class="meta-box-container">

                    <div class="meta-input flex-row">
                        <label>Interval</label>
                        <input type="text" class="text-input" name="data[interval]" value="5000">

                    </div>
                    <div class="flex-row">
                        <label></label>
                        <div style="width:100%">
                            <label><input value="true" name="data[nav]" type="checkbox">&nbsp;&nbsp;Enable Nav</label><br>
                            <label><input value="true" name="data[bullet]" type="checkbox">&nbsp;&nbsp;Bullet</label>
                        </div>
                    </div>
                    <hr>
                    <div class="meta-input flex-row">
                        <label>Max-Height</label>
                        <input type="text" class="text-input" name="data[height]" value="350">
                    </div>
                    <!-- <div class="meta-input flex-row">
                    <label>Max-Width</label>
                    <input type="text" class="text-input" name="data[width]" value="">
                </div> -->
                    <div class="meta-input flex-row">
                        <label>Type</label>
                        <div>
                            <select id="Slidertype" onchange="controlShortByType()" name="data[type]">
                                <option value="full-width">Full Width</option>
                                <option value="two-column-single">2 Column</option>
                                <option value="two-column">Full Slide 2 Column</option>
                            </select>
                            <label id="clickableControll" style="display: flex;margin: 8px 0;"><input style="width: 16px;margin-right: 4px;" type="checkbox" <?php echo $slider->clickable == '1' ? 'checked' : ''  ?> name="data[clickable]" value="1"> Gallery (Hoverable)</label>
                        </div>
                    </div>

                    <div class="meta-input flex-row tw-col-type">
                        <label>Image Position</label>

                        <select name="data[img_pos]">
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="meta-input flex-row full-width-type">
                        <label>Content Position</label>
                        <select name="data[content_pos]">
                            <option value="center">Center</option>
                            <option value="left">Left</option>
                            <option value="right">Right</option>
                        </select>
                    </div>
                    <div class="meta-input flex-row">
                        <label>H Tag</label>
                        <select name="data[title_tag]">
                            <option value="h1">H1</option>
                            <option value="h2">H2</option>
                            <option value="h3">H3</option>
                            <option value="h4">H4</option>
                            <option value="h5">H5</option>
                            <option value="strong">Strong</option>
                        </select>
                    </div>
                    <hr>
                    <div class="meta-input flex-row">
                        <label>Image Ratio</label>
                        <select name="data[image_ratio]">
                            <option value="r">Aspect Ratio</option>
                            <option value="s">Squre</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<script src="<?php echo admin_assets('/slider-manage.js') ?>"></script>
<script>
    controlShortByType();

    function controlShortByType() {
        let Slidertype = document.querySelector('#Slidertype').value;
        if (Slidertype == 'full-width') {
            let fullWidthCs = document.querySelectorAll('.full-width-type');
            fullWidthCs.forEach((elm) => {
                elm.style.display = 'flex';
            });
            let twClm = document.querySelectorAll('.tw-col-type');
            twClm.forEach((elm) => {
                elm.style.display = 'none';
            });

        } else {
            let fullWidthCs = document.querySelectorAll('.full-width-type');
            fullWidthCs.forEach((elm) => {
                elm.style.display = 'none';
            });
            let twClm = document.querySelectorAll('.tw-col-type');
            twClm.forEach((elm) => {
                elm.style.display = 'flex';
            });

        }
    }

    function textToSlug(text) {
        return text
            .toLowerCase() // Convert the text to lowercase
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/[^a-z0-9-]/g, '') // Remove any non-alphanumeric or hyphen characters
            .replace(/--+/g, '-') // Replace consecutive hyphens with a single hyphen
            .replace(/^-+|-+$/g, ''); // Remove leading and trailing hyphens
    }

    PageTitle.addEventListener('keyup', (e) => {
        PageSlugNew.value = textToSlug(e.target.value);
    });
</script>
<?php admin_footer(); ?>
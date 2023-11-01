<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="page-composer-area" action="<?php page_url() ?>/store/">
        <div class="page-editor">
            <input type="text" id="PageTitle" value="<?php __e($slider->title) ?>" class="text-input" placeholder="Slider Title" name="data[title]">
            <input type="hidden" value="<?php __e($slider->slug) ?>" class="text-input" name="data[existing-slug]" required>
            <input type="hidden" id="PageSlugNew" value="<?php __e($slider->slug) ?>" class="text-input" placeholder="Slug" name="data[slug]" required>

            <div class="slider-maker">
                <div class="image-select-area">
                    <div class="slider-container">
                        <?php foreach ($slider->image as $k => $image) {
                            $sliderlink = isset($slider->links[$k]) ? $slider->links[$k] : '';
                        ?>
                            <div class="slider-item">
                                <div class="slider-image">
                                    <?php echo $slider->getImage($k) ?>
                                    <label class="media-browser browser-slider slider-control-icon"><svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                            <path d="M64 192v-72a40 40 0 0140-40h75.89a40 40 0 0122.19 6.72l27.84 18.56a40 40 0 0022.19 6.72H408a40 40 0 0140 40v40" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                            <path d="M479.9 226.55L463.68 392a40 40 0 01-39.93 40H88.25a40 40 0 01-39.93-40L32.1 226.55A32 32 0 0164 192h384.1a32 32 0 0131.8 34.55z" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                        </svg></label>
                                    <div class="input-fields">
                                        <input type="text" name="data[image][]" value="<?php echo $image ?>">
                                        <input type="text" placeholder="Link" name="data[links][]" value="<?php echo $sliderlink ?>">
                                    </div>
                                </div>
                                <div class="slider-details">
                                    <input type="text" name="data[image_title][]" value="<?php __e($slider->image_title[$k]) ?>" placeholder="Title">
                                    <textarea name="data[image_description][]" placeholder="Description"><?php __e($slider->image_description[$k]) ?></textarea>
                                </div>
                                <span onclick="removeSliderItem(this)" class="remove-slider-item slider-control-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                                        <path d="M112 112l20 320c.95 18.49 14.4 32 32 32h184c17.67 0 30.87-13.51 32-32l20-320" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                        <path stroke="currentColor" stroke-linecap="round" stroke-miterlimit="10" stroke-width="32" d="M80 112h352" />
                                        <path d="M192 112V72h0a23.93 23.93 0 0124-24h80a23.93 23.93 0 0124 24h0v40M256 176v224M184 176l8 224M328 176l-8 224" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                                    </svg>
                                </span>
                            </div>
                        <?php
                        } ?>
                    </div>
                    <button type="button" id="add-slide">+</button>
                </div>
                <div class="single-content">
                    <textarea id="single-content" name="data[single_content]"><?php echo $slider->single_content ?></textarea>
                </div>
            </div>

        </div>
        <div class="controller-section">
            <div class="meta-box controll-box">
                <div class="meta-box-header controll-box-title">
                    <span>Publish</span>
                </div>
                <div class="meta-box-container">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            <div class="meta-box controll-box">
                <div class="meta-box-header controll-box-title">
                    <span>Publish</span>
                </div>
                <div class="meta-box-container">
                    <div class="meta-input flex-row">
                        <label>Interval</label>
                        <input type="text" class="text-input" name="data[interval]" value="<?php echo !empty($slider->interval) ? $slider->interval : 5000 ?>">

                    </div>
                    <div class="flex-row">
                        <label></label>
                        <div style="width:100%">
                            <label><input value="true" <?php echo $slider->nav ? 'checked' : '' ?> name="data[nav]" type="checkbox">&nbsp;&nbsp;Enable Nav</label><br>
                            <label><input value="true" <?php echo $slider->bullet ? 'checked' : '' ?> name="data[bullet]" type="checkbox">&nbsp;&nbsp;Bullet</label>
                        </div>
                    </div>
                    <hr>
                    <div class="meta-input flex-row">
                        <label>Max-Height</label>
                        <input type="text" class="text-input" name="data[height]" value="<?php echo $slider->height != "" ? $slider->height : 350 ?>">
                    </div>
                    <!-- <div class="meta-input flex-row">
                    <label>Max-Width</label>
                    <input type="text" class="text-input" name="data[width]" value="<?php //echo $slider->width != "" ? $slider->width : 350 
                                                                                    ?>">
                </div> -->
                    <div class="meta-input flex-row">
                        <label>Type</label>
                        <div>
                            <select id="Slidertype" onchange="controlShortByType()" name="data[type]">
                                <option <?php echo $slider->type == 'full-width' ?  'selected' : '' ?> value="full-width">Full Width</option>
                                <option <?php echo $slider->type == 'two-column-single' ?  'selected' : '' ?> value="two-column-single">2 Column</option>
                                <option <?php echo $slider->type == 'two-column' ?  'selected' : '' ?> value="two-column">Full Slide 2 Column</option>
                            </select>
                            <label id="clickableControll" style="display: flex;margin: 8px 0;"><input style="width: 16px;margin-right: 4px;" type="checkbox" <?php echo $slider->clickable == '1' ? 'checked' : ''  ?> name="data[clickable]" value="1"> Gallery (Hoverable)</label>
                        </div>

                    </div>
                    <div class="meta-input flex-row tw-col-type">
                        <label>Image Position</label>

                        <select name="data[img_pos]">
                            <option <?php echo $slider->img_pos == 'left' ?  'selected' : '' ?> value="left">Left</option>
                            <option <?php echo $slider->img_pos == 'right' ?  'selected' : '' ?> value="right">Right</option>
                        </select>
                    </div>
                    <div class="meta-input flex-row full-width-type">
                        <label>Content Position</label>
                        <select name="data[content_pos]">
                            <option <?php echo $slider->content_pos == 'center' ?  'selected' : '' ?> value="center">Center</option>
                            <option <?php echo $slider->content_pos == 'left' ?  'selected' : '' ?> value="left">Left</option>
                            <option <?php echo $slider->content_pos == 'right' ?  'selected' : '' ?> value="right">Right</option>
                        </select>
                    </div>
                    <div class="meta-input flex-row">
                        <label>H Tag</label>
                        <select name="data[title_tag]">
                            <option <?php echo $slider->title_tag == 'h1' ?  'selected' : '' ?> value="h1">H1</option>
                            <option <?php echo $slider->title_tag == 'h2' ?  'selected' : '' ?> value="h2">H2</option>
                            <option <?php echo $slider->title_tag == 'h3' ?  'selected' : '' ?> value="h3">H3</option>
                            <option <?php echo $slider->title_tag == 'h4' ?  'selected' : '' ?> value="h4">H4</option>
                            <option <?php echo $slider->title_tag == 'h5' ?  'selected' : '' ?> value="h5">H5</option>
                            <option <?php echo $slider->title_tag == 'strong' ?  'selected' : '' ?> value="strong">Strong</option>
                        </select>
                    </div>
                    <hr>
                    <div class="meta-input flex-row">
                        <label>Image Ratio</label>
                        <select name="data[image_ratio]">
                            <option <?php echo $slider->image_ratio == 'r' ?  'selected' : '' ?> value="r">Aspect Ratio</option>
                            <option <?php echo $slider->image_ratio == 's' ?  'selected' : '' ?> value="s">Squre</option>
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
    editor('single-content', 'simple', 245);

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

        if (Slidertype == 'two-column-single') {
            $(".slider-details").hide();
            $("#clickableControll").show();
            $(".single-content").show();
        } else {
            $(".slider-details").show();
            $("#clickableControll").hide();
            $(".single-content").hide();
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
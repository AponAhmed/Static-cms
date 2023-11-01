<?php admin_header(); ?>
<div class="page-body">
    <form method="post" class="page-composer-area" action="<?php page_url() ?>/store/">
        <div class="page-editor">
            <div class="page-meta-sec">
                <input type="text" id="PageTitle" class="text-input" placeholder="Page Title" name="data[title]">
                <div class="input-container inline-flex">
                    <label>Slug</label>
                    <input type="text" id="PageSlugNew" class="text-input" placeholder="Slug" name="data[slug]" required>
                </div>
                <div class="input-container inline-flex">
                    <label>H1 Text</label>
                    <input type="text" id="h1" class="text-input" placeholder="Heddin 1 Text" name="data[h1]">
                </div>
                <div class="input-container inline-flex">
                    <label>Image Title</label>
                    <input type="text" id="feature_image_title" class="text-input" placeholder="Feature Image Title" name="data[feature_image_title]">
                </div>
            </div>
            <div class="page-editor-sec">
                <textarea id="mainEditor" name="data[content]" style="visibility: hidden;">
                    <p>Initial content.</p>
                </textarea>
            </div>
            <div class="main-meta-box-container">
                <?php meta_box('main', $page); ?>
            </div>
        </div>
        <div class="controller-section">
            <div class="meta-box controll-box">
                <div class="meta-box-header controll-box-title">
                    <span>Publish</span>
                </div>
                <div class="meta-box-container">
                    <?php
                    if (is_array($templates) && count($templates) > 0) {
                    ?>
                        <div class="meta-input">
                            <label>Template</label>
                            <select class="custom-select" name="data[template]" id="template">
                                <option value="">Default</option>
                                <?php foreach ($templates as $file => $template) { ?>
                                    <option value="<?php echo $file; ?>"><?php echo $template['name']; ?></option>
                                <?php  } ?>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
            <?php meta_box('sidebar', $page); ?>
        </div>
    </form>
</div>
<script type="module" src="<?php echo admin_assets('/page-composer.js') ?>"></script>
<?php admin_footer(); ?>
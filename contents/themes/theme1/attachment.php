<?php get_header();
?>
<div class="page">
    <div class="container">
        <div class="template-banner has_description">
            <div class="banner-left">
                <h1><?php echo get_title() ?></h1>
                <p><strong><?php echo get_title() ?></strong>, [randphrase n="8"]</p>
                [write-us]
            </div>
            <div class="banner-image">
                <div class="image-thumb"><?php get_thumbnail([6, 6, 12], true, true) ?></div>
            </div>
        </div>
        <article class="page-content">
            <section class="banner-bottom-section">
                <div class="product-thumbs-area">
                    <div class="section_subtitle">ONLY THE BEST</div>
                    <div class="section_title">PRODUCT</div>
                    [image dir="" col="4" mcol="2" tcol="3" link="yes" rand="yes" limit="28" fromAll="yes"]
                </div>
                <div class="product-content-area">
                    [color]
                    <div class="box-row">
                        <div class="box box-12">
                            <h2><?php echo get_title() ?></h2>
                            <p> [randphrase n="15"] </p>
                        </div>
                    </div>
                    [randkey n="4" h="3" hl="1" pl="28" sl="3" col="1" show="false" link="2"]

                    [/color]
                </div>
            </section>
        </article>
    </div>
</div>
<?php get_footer(); ?>
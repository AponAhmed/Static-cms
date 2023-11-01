<?php
/*
Template Name : Contact Template 
Author        : Apon Ahmed
Description   : This is a sample Contact Template.
*/

get_header();
?>
<main>
    <div class="page">
        <div class="container">
            <div class="contact-map">
                <?php echo get_thumbnail([12, 12, 12], true); ?>
            </div>
            <article class="content-area">
                <div class="box-row">
                    <div class="box box-8">
                        [contact]
                    </div>
                    <div class="box box-4">
                        <?php echo get_content(); ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
</main>
<?php get_footer(); ?>
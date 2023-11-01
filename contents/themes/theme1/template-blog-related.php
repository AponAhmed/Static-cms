<?php
/*
Template Name : Blog Related Template 
Author        : John Doe
Description   : This is a sample template.
*/

get_header();
?>
<main>
    <div class="page">
        <div class="container">
            <?php do_action('blog_banner', $page) ?>
            <article class="page-content">
                <?php do_action('blog_content', $page) ?>
            </article>
        </div>
    </div>
</main>
<?php get_footer(); ?>
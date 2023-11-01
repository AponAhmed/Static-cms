<?php
/*
Template Name : Another Product 
Author        : John Doe
Description   : This is a sample template.
*/

get_header();
?>
<main>
    <div class="page">
        <div class="container">
            <div class="banner-simple">
                <h1><?php echo h1_text(); ?></h1>
            </div>
            <article class="content-area">
                <?php echo get_content(); ?>
            </article>
        </div>
    </div>
</main>
<?php get_footer(); ?>
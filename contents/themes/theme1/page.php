<?php get_header();
?>
<main>
    <div class="page">
        <div class="container">
            <?php if (!is_home()) { ?>
                <h1><?php echo h1_text() ?></h1>
            <?php } ?>
            <article class="content-area">
                <?php the_content() ?>
            </article>
        </div>
    </div>
</main>
<?php get_footer(); ?>
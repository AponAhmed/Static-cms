<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php seo_meta() ?>
    <?php site_icon() ?>
    <?php get_breadcrumb_json() ?>
    <?php
    inline_theme_asset('dist/bundle.css'); //theme_uri('dist/bundle.css'); 
    ?>
    <?php getCustomCss(); ?>
    <script>
        const AJAXURL = '<?php echo ajax_url() ?>';
    </script>
</head>

<body>
    <div class="header-bg">
        <header id="master-head">
            <div class="nav-bg">
                <div class="container">
                    <nav class="nav-menu">
                        <div class="nav-toggle">
                            <span class="bar"></span>
                            <span class="bar"></span>
                            <span class="bar"></span>
                        </div>
                        <div class="nav-wrapper" id="navbarelement">
                            <?php get_menu('header-menu', ['class' => 'nav header-nav']) ?>
                        </div>
                    </nav>
                </div>
            </div>
            <div class="container">
                <div class="header-wrapper">
                    <div class="site-branding">
                        <div class="logo">
                            <a href="<?php echo site_url() ?>"><?php echo site_logo(236, 236)  ?></a>
                        </div>
                    </div>
                    <div class="header-right">
                        <?php header_contact() ?>
                        <div class="cart-toggler cart-icon">
                            <svg viewBox="0 0 512 512"><circle cx="176" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><circle cx="400" cy="416" r="16" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M48 80h64l48 272h256"/><path d="M160 288h249.44a8 8 0 007.85-6.43l28.8-144a8 8 0 00-7.85-9.57H128" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32"/></svg>
                            <span class="item-counter"></span>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    </div>
    <?php if (!is_home()) {
    ?>
        <div class="container">
            <?php get_breadcrumb(); ?>
        </div>
    <?php
    } ?>
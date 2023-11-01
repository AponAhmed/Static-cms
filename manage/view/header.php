<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo admin_title() ?></title>
    <?php site_icon() ?>
    <link rel="stylesheet" href="<?php echo admin_assets('box-grid.css') ?>">
    <link rel="stylesheet" href="<?php echo admin_assets('admin.css') ?>">
    <link rel="stylesheet" href="<?php echo admin_assets('editor.css') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Rubik:wght@300;400&display=swap" rel="stylesheet">
    <!-- <script src="<?php // echo admin_assets('ckeditor5/build/ckeditor.js') 
                        ?>" await></script> -->
    <script src="<?php echo admin_assets('ckeditor/ckeditor.js') ?>" await></script>
    <script src="<?php echo admin_assets('admin.js') ?>" await></script>
    <script>
        //JS GLOBAL Variables
        const ADMIN_URL = '<?php echo admin_url() ?>';
    </script>
</head>

<body>
    <input type="hidden" id="editorInstance">
    <div class="wrapper">
        <?php getMessage() ?>
        <div class="left-side">
            <?php admin_sidebar() ?>
        </div>
        <div class="right-side">
            <?php admin_topbar() ?>
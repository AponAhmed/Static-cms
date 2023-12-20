    <footer class="footer-bg">
        <div class="container">
            <?php get_footer_top() ?>
            <div class="box-row">
                <div class="box box-12">
                    <div class="site-footer">
                        <?php get_menu('footer-top', ['class' => 'footer-top-menu']) ?>
                        <p class="copyright">&copy; All rights reserved <?php echo site_name() ?> Bangladesh, Canada, USA - 1987-<?php echo date('Y') ?> </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="<?php echo theme_uri('dist/jquery.js'); ?>" defer></script>
    <script src="<?php echo theme_uri('dist/bundle.js'); ?>"></script>
    </body>

    </html>
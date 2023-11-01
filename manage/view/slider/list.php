<?php admin_header(); ?>
<div class="page-body">
    <table class="table table-striped page">
        <thead>
            <tr>
                <th>name</th>
                <th>Shortcode</th>
                <th>Modified At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($sliders as $slider) {
                $url = urlSlashFix(siteUrl() . "/" . $slider->slug . "/");
            ?>
                <tr>
                    <td title="<?php echo $slider->title ?>"><?php echo $slider->title ?></td>
                    <td>[slider slug="<?php echo $slider->slug ?>"]</td>
                    <td><?php echo $slider->modified_at ?></td>
                    <td><a href="<?php echo page_url() ?>/edit/<?php echo $slider->slug ?>/">Edit</a> | <a onclick="return confirm('Sure to Delete ?')" href="<?php echo page_url() ?>/delete/<?php echo $slider->slug ?>/">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
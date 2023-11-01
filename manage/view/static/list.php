<?php admin_header(); ?>
<div class="page-body">
    <table class="table table-striped page">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Snippet</th>
                <th>Modified At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($pages as $file => $page) {
                $url = urlSlashFix(siteUrl() . "/" . $page->slug . "/");
            ?>
                <tr>
                    <td title="<?php echo $page->title ?>"><?php echo $page->title ?></td>
                    <td><?php echo  $page->taxonomy ? implode(", ", $page->taxonomy->{'static-category'}) : '' ?></td>
                    <td><?php echo $page->snippet ?></td>
                    <td><?php echo $page->modified_at ?></td>
                    <td><a href="<?php echo page_url() ?>/edit/<?php echo $file ?>/">Edit</a> | <a onclick="return confirm('Sure to Delete ?')" href="<?php echo page_url() ?>/delete/<?php echo $file ?>/">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
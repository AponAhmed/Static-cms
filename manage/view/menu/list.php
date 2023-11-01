<?php admin_header(); ?>
<div class="page-body">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>slug</th>
                <th>Modified At</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($menus as $file => $menu) {
            ?>
                <tr>
                    <td><?php echo $menu->name ?></td>
                    <td><?php echo $menu->slug ?></td>
                    <td><?php echo $menu->modified_at ?></td>
                    <td><a href="<?php echo page_url() ?>/edit/<?php echo $file ?>/">Edit</a> | <a data-confirm="Are You sure to Delete '<?php echo $menu->name ?>' Menu ?" class="confirm" href="<?php echo page_url() ?>/delete/<?php echo $file ?>/">Delete</a></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
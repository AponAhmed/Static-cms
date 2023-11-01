<?php admin_header(); ?>
<div class="page-body">
    <table class="table table-striped page">
        <thead>
            <tr>
                <th>Name</th>
                <th>Count</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($terms as $term => $objects) {
            ?>
                <tr>
                    <td title="<?php echo $term ?>"><?php echo $term ?></td>
                    <td><?php echo count($objects) ?></td>
                    <td></td>
                </tr>
            <?php
            }
            ?>
        </tbody>
    </table>
</div>
<?php admin_footer(); ?>
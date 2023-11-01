<?php admin_header(); ?>
<div class="page-body">
    <form method="post" action="<?php page_url() ?>/store/">
        <div class="box-row">
            <?php
            foreach ($c::$blocks as $k => $block) {
            ?>
                <div class="box box-<?php echo $block['col'] ?>">
                    <div class="col-title">
                        <input type="text" class="text-input" name="blocks[<?php echo $k ?>][title]" value="<?php echo isset($data[$k]['title']) ? $data[$k]['title'] : $block['title'] ?>">
                    </div>
                    <?php if ($block['type'] == 'text') {
                        $val = isset($data[$k]['value']) ? $data[$k]['value'] : "";
                        echo '<textarea class="editor text-input" rows="15" name="blocks[' . $k . '][value]">' . $val . '</textarea>';
                    } elseif ($block['type'] == 'menus') {
                        echo $menu->getSelect("blocks[$k][value]", isset($data[$k]['value']) ? $data[$k]['value'] : "");
                    } else {
                        echo '--';
                    } ?>
                </div>
            <?php
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
    <script>

    </script>
</div>
<?php admin_footer(); ?>
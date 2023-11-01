<?php admin_header(); ?>
<div class="page-body o-h">
    <div class="menu-container h-full">
        <div id="menuManager" class="h-full">
            <div class="menu-name-block">
                <input type="hidden" id="actionType" value="<?php echo $type ?>">
                <input type="hidden" id="fileName" value="<?php echo $data->getFileBase() ?>">
                <input type="hidden" id="oldFile" value="<?php echo $data->getFileBase() ?>">
                <label>Name</label>
                <input type="text" id="menuName" name="menuname" value="<?php echo $data->name ?>" placeholder="Menu Name">
                <button type="button" class="menu-update">Update</button>
            </div>
            <hr>
            <div class="box-row h-full">
                <div class="box box-4 h-full">
                    <div class="menu-data-groups">
                        <div class="menu-page-group h-full o-h">
                            <label class="menu-group-title">Pages</label>
                            <ul class="menu-group-items">
                                <?php foreach ($pages as $page) {
                                    $multipage = false;
                                    if ($page->multiple_page) {
                                        $multipage = true;
                                    }
                                ?>
                                    <li title="<?php echo $multipage ? 'Multiple Page : ' . $page->multiple_data_file : 'Reguler Page' ?>" class="<?php echo $multipage ? 'multi-page' : '' ?>">
                                        <label>
                                            <input data-multiple="<?php echo $multipage ? 'yes' : 'no' ?>" class="add2menu" type="checkbox" data-url="<?php echo $page->getLink() ?>" data-title="<?php echo $page->title ?>" data-slug="<?php echo $page->slug ?>">
                                            <?php echo $page->title ?>
                                        </label>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <button type="button" id="addpage2menu" class="btn-new-menu"><svg viewBox="0 0 512 512">
                            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" d="M256 112v288M400 256H112"></path>
                        </svg> Add to Menu</button>
                </div>
                <div class="box box-8 h-full relative">
                    <ul class="menu-list"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo admin_assets('/shortable.js') ?>"></script>
<script src="<?php echo admin_assets('/menu-manager.js') ?>"></script>
<script>
    const data = JSON.parse('<?php echo addslashes($data->getJson()) ?>');
    new MenuManager(data, document.getElementById('menuManager'));
</script>
<?php admin_footer(); ?>
<label>
    <input type="hidden" name="data[multiple_page]" value="0">
    <input type="checkbox" name="data[multiple_page]" <?php echo $page->multiple_page == '1' ? 'checked' : '' ?> value="1">&nbsp;Enable Multiple Page
</label><br><br>
<div class="meta-input">
    <label>Data File</label>
    <select name="data[multiple_data_file]">
        <?php
        $curr = $page->multiple_data_file;
        foreach ($csvs->listCsvFiles() as $file) {
            $sel = $curr == $file ? 'selected' : "";
            echo "<option value=\"$file\" $sel >$file</option>";
        } ?>
    </select>
</div>
<div class="meta-input">
    <label>Url Structure - Use {col1}, {col2}...</label>
    <?php
    $urlStruc = $page->url_structure;
    $slug = $page->slug;
    if (empty($urlStruc) && $slug != "") {
        $urlStruc = "/" . $slug . "/";
    }
    ?>
    <input type="text" name="data[url_structure]" value="<?php echo $urlStruc; ?>">
</div>
<div class="meta-input">
    <label>Default Values for segments comma(,) separated</label>
    <?php $defSeg = $page->default_segments ?>
    <input type="text" name="data[default_segments]" value="<?php echo !empty($defSeg) ? $defSeg : 'Bangladesh'; ?>">
</div>
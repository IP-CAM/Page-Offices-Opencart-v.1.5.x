<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a onclick="$('#act_mode').val('1');$('#form').submit();" class="button"><?php echo $button_save_and_close; ?></a>
                <a onclick="$('#act_mode').val('0');$('#form').submit();" class="button"><?php echo $button_save; ?></a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a>
            </div>
        </div>
        <div class="content">
            <div id="tabs" class="htabs">
                <a href="#tab-general"><?php echo $tab_general; ?></a>
                <a href="#tab-data"><?php echo $tab_data; ?></a>
            </div>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <div id="tab-general">
                    <div id="languages" class="htabs">
                        <?php foreach ($languages as $language) { ?>
                        <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                        <?php } ?>
                    </div>
                    <?php foreach ($languages as $language) { ?>
                    <div id="language<?php echo $language['language_id']; ?>">
                        <table class="form">
                            <tr>
                                <td><span class="required">*</span> <?php echo $entry_name; ?></td>

                                <td><input type="text" name="office_description[<?php echo $language['language_id']; ?>][name]" size="100" value="<?php echo isset($office_description[$language['language_id']]) ? $office_description[$language['language_id']]['name'] : ''; ?>" />
                                    <?php if (isset($error_name[$language['language_id']])) { ?>
                                    <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                                    <?php } ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_title; ?></td>

                                <td><input type="text" name="office_description[<?php echo $language['language_id']; ?>][title]" size="100" value="<?php echo isset($office_description[$language['language_id']]) ? $office_description[$language['language_id']]['title'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_address; ?></td>

                                <td><input type="text" name="office_description[<?php echo $language['language_id']; ?>][address]" size="100" value="<?php echo isset($office_description[$language['language_id']]) ? $office_description[$language['language_id']]['address'] : ''; ?>" /></td>
                            </tr>
                            <tr>
                                <td><?php echo $entry_description; ?></td>

                                <td><textarea name="office_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>" rows="3" cols="100"><?php echo isset($office_description[$language['language_id']]) ? $office_description[$language['language_id']]['description'] : ''; ?></textarea></td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                </div>
                <div id="tab-data">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_parent; ?></td>
                            <td>
                                <select name="parent_id">
                                    <option value="0"><?php echo $text_none; ?></option>
                                    <?php foreach ($offices as $office) { ?>
                                    <?php if ($office['office_id'] == $parent_id) { ?>
                                    <option value="<?php echo $office['office_id']; ?>" selected="selected"><?php echo $office['name']; ?></option>
                                    <?php } else { ?>
                                    <option value="<?php echo $office['office_id']; ?>"><?php echo $office['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_phone; ?></td>
                            <td><input type="text" name="phone" value="<?php echo $phone; ?>" size="50" /></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_fax; ?></td>
                            <td><input type="text" name="fax" value="<?php echo $fax; ?>" size="50" /></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_email; ?></td>
                            <td><input type="text" name="email" value="<?php echo $email; ?>" size="50" /></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_longitude; ?></td>
                            <td><input type="text" name="longitude" value="<?php echo $longitude; ?>" size="50" /></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span><?php echo $entry_latitude; ?></td>
                            <td><input type="text" name="latitude" value="<?php echo $latitude; ?>" size="50" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_sort_order; ?></td>
                            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_status; ?></td>
                            <td><select name="status">
                                    <?php if ($status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                    </table>
                </div>
                <input type="hidden" name="act_mode"  id ="act_mode" value="0"/>
            </form>
        </div>
    </div>
</div>
<?php
/*
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
    <?php foreach ($languages as $language) { ?>
        CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
            filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
            filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
        });
    <?php } ?>
    //--></script>
*/ ?>
<script type="text/javascript"><!--
    $('#tabs a').tabs();
    $('#languages a').tabs();
    //--></script>
<?php echo $footer; ?>
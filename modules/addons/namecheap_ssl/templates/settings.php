<?php if($view['message']):?>
<div class="infobox"><strong><span class="title"><?php echo $view['message']?></span></strong><br></div>
<?php endif;?>

<form method="POST">
<table width="100%" cellspacing="2" cellpadding="3" border="0" class="form">
    <tbody>
        <tr>
            <td class="fieldlabel"><?php echo $_LANG['ncssl_addon_setting_ssync_date_offset_1']?></td>
            <td class="fieldarea">
                <select name="settings[sync_date_offset]">
                    <?php foreach($view['control_options']['sync_date_offset'] as $k=>$v):?>
                        <option value="<?php echo $k?>"<?php if($k==$view['settings']['sync_date_offset']): ?>selected="selected"<?php endif; ?>><?php echo $v;?></option>
                    <?php endforeach;?>
                </select>    
                <?php echo $_LANG['ncssl_addon_setting_ssync_date_offset_2']?>
            </td>
        </tr>
    </tbody>
</table>
    
<p align="center">
    <input type="submit" class="button" value="Save Changes">
</p>

</form>

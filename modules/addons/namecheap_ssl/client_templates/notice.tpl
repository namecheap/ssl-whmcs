<div style="min-height: 100px">

    <div>
        <strong>{$notice}</strong>
    </div>
    <br />
    {if $back_to_service_id}
    <input type="button" onclick="window.location='clientarea.php?action=productdetails&id={$back_to_service_id}'" class="btn" value="{$LANG.ncssl_back_to_service}">
    {/if}
    
</div>

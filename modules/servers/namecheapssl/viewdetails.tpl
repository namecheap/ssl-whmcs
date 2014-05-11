{if $domain neq ''}
<h1>{$LANG.ncssl_certificate_details_for} {$domain}</h1>
{else}
<h1>{$LANG.ncssl_certificate_details}</h1>
{/if}
{if $status neq 'not_exists'}
<table>
    <tr><td><b>{$LANG.ncssl_domain}</b></td><td>{$domain}</td></tr>
    <tr><td><b>{$LANG.ncssl_status}</b></td><td>{$status}</td></tr>
    <tr><td><b>{$LANG.ncssl_type}</b></td><td>{$type}</td></tr>
    {if !$httpBasedValidation}<tr><td><b>{$LANG.ncssl_approver_email}</b></td><td>{$approverEmail}</td></tr>{/if}
    <tr><td><b>{$LANG.ncssl_admin_email}</b></td><td>{$adminEmail}</td></tr>    
    {if $sansCount}
        <tr><td><b>{$LANG.ncssl_admin_viewdetails_count_of_sans}</b></td><td>{$sansCount}</td></tr>
        <tr><td><b>{$LANG.ncssl_admin_viewdetails_sans}</b> </td><td>{$sans}</td></tr>
    {/if}
</table>
<br />
{/if}
{if $status eq 'newpurchase' or $status eq 'newrenewal' or $status eq 'not_exists' or ($status eq 'inprogress' and $renewal)}
<form action="{$configlink}" method="get">
<input type="hidden" name="cert" value="{$confighash}" />
{* <input type="submit" value="Configure certificate" /> *}
<input type="submit" value="{$LANG.ncssl_configure_certificate}" />
</form>
{elseif $status eq 'active'}
{*<form action="clientarea.php?action=productdetails" method="post">
<input type="hidden" name="id" value="{$serviceid}" />
<input type="hidden" name="modop" value="custom" />
<input type="hidden" name="a" value="resendcert" />
<input type="submit" value="{$LANG.ncssl_resend_certificate}" />
</form>*}
<form action="clientarea.php?action=productdetails" method="post">
<input type="hidden" name="id" value="{$serviceid}" />
<input type="hidden" name="modop" value="custom" />
<input type="hidden" name="a" value="download" />
<input type="submit" value="{$LANG.ncssl_download_certificate}" />
</form>
{* elseif $status neq 'active' and $status neq 'cancelled' *}
{elseif ($status eq 'inprogress' or $status eq 'purchased')}

{if empty($httpBasedValidation)}
    <form action="clientarea.php?action=productdetails" method="post">
    <input type="hidden" name="id" value="{$serviceid}" />
    <input type="hidden" name="modop" value="custom" />
    <input type="hidden" name="a" value="resendapprove" />
    <input type="submit" value="{$LANG.ncssl_resend_approver_email}" />
    </form>
    
{else}
    <form action="clientarea.php?action=productdetails" method="post">
    <input type="hidden" name="id" value="{$serviceid}" />
    <input type="hidden" name="modop" value="custom" />
    <input type="hidden" name="a" value="show_validation_file_contents" />
    <input type="submit" value="{$LANG.ncssl_show_validation_file_contents}" />
    </form>
{/if}

{/if}

{if $status eq 'active'}
    <div style="padding:10px 0px 5px">
        <form action="clientarea.php?action=productdetails" method="post">
        <input type="hidden" name="id" value="{$serviceid}" />
        <input type="hidden" name="modop" value="custom" />
        <input type="hidden" name="a" value="clientstartreissue" />
        <input type="submit" value="{$LANG.ncssl_reissue_certificate}" />
        </form>
    </div>
{/if}

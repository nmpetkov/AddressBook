{pagesetvar name='title' value=$templatetitle}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_ADD' assign='addAuth'}
{userloggedin assign="loggedin"}
{formutil_getpassedvalue key="func" assign="func"}

{insert name='getstatusmsg'}

{assign_concat name='prefkey' 1='abtitle_' 2=$lang}
<h2>{$preferences.$prefkey}</h2>
{if !empty($loggedin)}
<div class="z-menu">{strip}
    <ul class="z-menulinks">
        <li style="display: inline;"><a href="{modurl modname=AddressBook type=user func=main}">{gt text="Address list"}</a></li>

        {if $addAuth}
        <li style="display: inline;"><a href="{modurl modname=AddressBook type=user func=edit}">{gt text="Add an address"}</a></li>
        {/if}

        {if !empty($loggedin)}
        {if $ot=="favourite"}
        <li style="display: inline;"><a href="{modurl modname=AddressBook type=user func=view ot=address}">{gt text="Show all"}</a></li>
        {else}
        <li style="display: inline;"><a href="{modurl modname=AddressBook type=user func=view ot=favourite}">{gt text="Show favourites"}</a></li>
        {/if}
        {/if}

        {if $addAuth}
         <li style="display: inline;"><a href="{modurl modname=AddressBook type=admin func=main}">{gt text="Admin"}</a></li>
        {/if}

        {if isset($user_id) && $user_id && $func neq 'edit'}
        <li id="fav" style="display: inline;{if $isFavourite} display:none;{/if}">
            <a href="#" onclick="javascript:add_fav({$address.id|safehtml},{$user_id|safehtml});">{gt text="Add to favourites"}</a>
        </li>
        <li id="nofav" style="display: inline;{if !$isFavourite} display:none;{/if}">
            <a href="#" onclick="javascript:del_fav({$address.id|safehtml},{$user_id|safehtml});">{gt text="Remove favourite"}</a>
        </li>
        {/if}
    </ul>
{/strip}</div>

{if $templatetitle}
    {if $ot=="favourite"}
        <h3>{$templatetitle} - {gt text="Favourites"}</h3>
    {/if}
{/if}
{/if}
{img id="ajax_indicator" style="display: none;" modname='core' set='ajax' src="indicator_circle.gif" alt=""}

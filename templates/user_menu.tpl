{pagesetvar name='title' value=$templatetitle}
{securityutil_checkpermission component='AddressBook::' instance='::' level='ACCESS_ADD' assign='addAuth'}
{userloggedin assign="loggedin"}
{formutil_getpassedvalue key="func" assign="func"}
<h2>{modgetvar module="AddressBook" name="abtitle"}</h2>

<div class="z-menu">
    <span class="z-menuitem-title">
        [ <span><a href="{modurl modname=AddressBook type=user func=main}">{gt text="Address list"}</a></span>

        {if $addAuth}
        <span>| <a href="{modurl modname=AddressBook type=user func=edit}">{gt text="Add an address"}</a></span>
        {/if}

        {if !empty($loggedin)}
        {if $ot=="favourite"}
        <span>| <a href="{modurl modname=AddressBook type=user func=view ot=address}">{gt text="Show all"}</a></span>
        {else}
        <span>| <a href="{modurl modname=AddressBook type=user func=view ot=favourite}">{gt text="Show favourites"}</a></span>
        {/if}
        {/if}
        {if $addAuth}
         <span>| <a href="{modurl modname=AddressBook type=admin func=main}">{gt text="Admin"}</a></span>
        {/if}
        {if $user_id && $func neq 'edit'}
        <span id="fav" {if $isFavourite}style="display:none;"{/if}>
            | <a href="#" onclick="javascript:add_fav({$address.id|varprepfordisplay},{$user_id|varprepfordisplay});">{gt text="Add to favourites"}</a>
        </span>
        <span id="nofav" {if !$isFavourite}style="display:none;"{/if}>
            | <a href="#" onclick="javascript:del_fav({$address.id|varprepfordisplay},{$user_id|varprepfordisplay});">{gt text="Remove favourite"}</a>
        </span>
        {/if}

        ]
    </span>
</div>

{insert name='getstatusmsg'}

{if $$templatetitle}
<h3 style="line-height:25px;">
    {$templatetitle}{if $ot=="favourite"} - {gt text="Favourites"}{/if}
    {img id="ajax_indicator" style="display: none;" modname='core' set='ajax' src="indicator_circle.gif" alt=""}
</h3>
{/if}

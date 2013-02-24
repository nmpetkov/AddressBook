{gt text="Address list" assign=templatetitle}
{include file="user_menu.tpl"}
{formutil_getpassedvalue key="sort" assign="sort"}
{usergetvar name="uid" assign="user_id"}
{userloggedin assign="loggedin"}
{securityutil_checkpermission component='AddressBook::' instance='::' level='ACCESS_READ' assign='viewAuth'}
{securityutil_checkpermission component='AddressBook::' instance='::' level='ACCESS_EDIT' assign='editAuth'}
{securityutil_checkpermission component='AddressBook::' instance='::' level='ACCESS_DELETE' assign='delAuth'}

<form class="z-form" id="addressbook-search" action="{modurl modname="AddressBook" type="user" func="view"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <fieldset>
            {if !$globalprotect && !empty($loggedin)}
            <div class="z-formrow">
                <label for="private">{gt text="Show private contacts only"}</label>
                <input id="private" type="checkbox" name="private" value="1" {if $private}checked{/if} />
            </div>
            {/if}
            <div class="z-formrow">
                <label>{gt text="Category"}</label>
                {gt text='All' assign='lblDef'}
                {nocache}
                {foreach from=$catregistry key='property' item='catview'}
                <div class="z-formnote">{selector_category category=$catview name="category" field='id' selectedValue=$category defaultValue=0 defaultText=$lblDef editLink=false}</div>
                {/foreach}
                {/nocache}
            </div>
            <div class="z-formrow">
                <label for="search_letter">{gt text="Search"}</label>
                <input id="search_letter" type="text" name="search" value="" style="width:120px;" maxlength="50" />
            </div>
            <div class="z-formbuttons">
                <input type="submit" value="{gt text="Go search"}" />
            </div>
        </fieldset>
    </div>
    <div id="addressbook-alphafilter" class="z-center">
        <strong>[{pagerabc posvar="letter" forwardvars="sort,category,private,search,ot" printempty=true}]</strong>
    </div>
</form>

<div class="addressbook_itemlist">
    <table class="z-datatable">
        <thead>
            <tr>
                {if $sort=="lname ASC"}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="lname DESC"}">{gt text="Name"}</a>{if strpos($sort,'name')} {img modname='AddressBook' src="s_asc.png" alt="" }{/if}</th>
                {else}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="lname ASC"}">{gt text="Name"}</a>{if strpos($sort,'name')} {img modname='AddressBook' src="s_desc.png" alt="" }{/if}</th>
                {/if}
                {if $sort=="company ASC"}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="company DESC"}">{gt text="Company"}</a>{if strpos($sort,'ompany')} {img modname='AddressBook' src="s_asc.png" alt="" }{/if}</th>
                {else}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="company ASC"}">{gt text="Company"}</a>{if strpos($sort,'ompany')} {img modname='AddressBook' src="s_desc.png" alt="" }{/if}</th>
                {/if}
                <th>{gt text="Contact"}</th>
                <th>{gt text="Action"}</th>
            </tr>
        </thead>
        <tbody>
            {foreach item=object from=$objectArray}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>
                    {if $name_order == 1}
                    {if $object.fname && $object.lname}{$object.fname|varprepfordisplay} {$object.lname|varprepfordisplay}{else}{$object.fname|varprepfordisplay}{$object.lname|varprepfordisplay}{/if}
                    {else}
                    {if $object.fname && $object.lname}{$object.lname|varprepfordisplay}, {$object.fname|varprepfordisplay}{else}{$object.lname|varprepfordisplay}{$object.fname|varprepfordisplay}{/if}
                    {/if}
                </td>
                <td>{$object.company|varprepfordisplay}</td>
                <td>
                    {if $object.c_main == 0}
                    {$object.contact_1|contact}
                    {elseif $object.c_main == 1}
                    {$object.contact_2|contact}
                    {elseif $object.c_main == 2}
                    {$object.contact_3|contact}
                    {elseif $object.c_main == 3}
                    {$object.contact_4|contact}
                    {elseif $object.c_main == 4}
                    {$object.contact_5|contact}
                    {/if}
                </td>
                <td class="z-nowrap">
                    {if $viewAuth}
                    <a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="demo.gif" __alt="View" __title="View"}</a>
                    {/if}
                    {if $editAuth || $user_id == $object.user_id}
                    <a href="{modurl modname=AddressBook type=user func=edit id=$object.id formcall="edit" ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="xedit.gif" __alt="Edit" __title="Edit"}</a>
                    {/if}
                    {if $delAuth || $user_id == $object.user_id}
                    <a href="{modurl modname=AddressBook type=user func=delete id=$object.id ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="14_layer_deletelayer.gif" __alt="Delete" __title="Delete"}</a>
                    {/if}
                </td>
            </tr>
            {foreachelse}
            <tr class="z-datatableempty"><td colspan="6">{gt text="No address found."}</td></tr>
            {/foreach}
        </tbody>
    </table>
</div>

{pager show="page" rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum}

{include file='user_viewmap.tpl'}

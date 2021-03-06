{if $categorydata|is_array && $categorydata.display_name.$lang}
    {assign var='templatetitle' value=$categorydata.display_name.$lang}
{else}
    {assign_concat name='prefkey' 1='abmetatitle_' 2=$lang}
    {assign var='templatetitle' value=$preferences.$prefkey}
{/if}
{include file="user_menu.tpl"}
{if $categorydata|is_array && $categorydata.display_desc.$lang}
    {setmetatag name='description' value=$categorydata.display_desc.$lang|strip_tags|trim|truncate:500}
{else}
    {assign_concat name='prefkey' 1='abmetadescription_' 2=$lang}
    {setmetatag name='description' value=$preferences.$prefkey|strip_tags|trim|truncate:500}
{/if}
{assign_concat name='prefkey' 1='abmetakeyword_' 2=$lang}
{setmetatag name='keywords' value=$preferences.$prefkey|strip_tags|trim|truncate:500}

{formutil_getpassedvalue key="sort" assign="sort"}
{usergetvar name="uid" assign="user_id"}
{userloggedin assign="loggedin"}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_READ' assign='viewAuth'}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_EDIT' assign='editAuth'}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_DELETE' assign='delAuth'}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_ADMIN' assign='adminAuth'}
{if $adminAuth}
    {ajaxheader modname='AddressBook' filename='addressbook.js' nobehaviour=true noscriptaculous=true}
{/if}

{if $themeinfo.name|strpos:"Mobile" !== false || $themeinfo.name|strpos:"Bootstrap" !== false}{assign var='mobile_mode' value=1}{else}{assign var='mobile_mode' value=0}{/if}

<form id="addressbook-search" class="z-form z-linear" action="{modurl modname="AddressBook" type="user" func="view"}" method="post" enctype="application/x-www-form-urlencoded">
    <fieldset{if $mobile_mode} data-role="fieldcontain" class="ui-hide-label"{/if}>
        {if !$globalprotect && !empty($loggedin) && $allowprivate}
        <div>
            <label for="private">{gt text="Show private contacts only"}</label>
            <input id="private" type="checkbox" name="private" value="1" {if $private}checked{/if} />
        </div>
        {/if}
        <label for="category">{gt text="Category"}</label>
        {gt text='All' assign='lblDef'}
        {nocache}
        {foreach from=$catregistry key='property' item='catview'}
        {selector_category category=$catview name="category" field='id' selectedValue=$category defaultValue=0 defaultText=$lblDef editLink=false submit=true}
        {/foreach}
        {/nocache}
        {if !$mobile_mode}
        <label for="search_letter">{gt text="Search"}</label>
        <input id="search_letter" type="text" name="search" style="width:120px;" maxlength="50" value="" title="{gt text="Search"}" />
        {*<input type="submit" value="{gt text="Search"}" />*}
        <button class="z-button z-bt-small" type="submit" name="searchsubmit" value="0" title="{gt text="Search"}" style="margin-left:-2px;"><img src="images/icons/extrasmall/search.png" style="height:14px;width:14px" alt="" /></button>
        {/if}
    </fieldset>
    {if $preferences.showabcfilter}
    <div id="addressbook-alphafilter">
        {pagerabc posvar="letter" forwardvars="sort,category,private,search,ot" printempty=true}
    </div>
    {/if}
</form>

<div class="addressbook_itemlist">
    <table class="z-datatable">
        <thead>
            {if !$mobile_mode}
            <tr>
                {if $preferences.addressbooktype == 2}
                    <th>{gt text="Logo"}</th>
                {/if}
                {if $preferences.addressbooktype == 1}
                    {if $sort=="lname ASC"}
                    <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="lname DESC"}">{gt text="Name"}</a>{if strpos($sort,'name')} {img modname='AddressBook' src="s_asc.png" alt="" }{/if}</th>
                    {else}
                    <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="lname ASC"}">{gt text="Name"}</a>{if strpos($sort,'name')} {img modname='AddressBook' src="s_desc.png" alt="" }{/if}</th>
                    {/if}
                {/if}
                {if $sort=="company ASC"}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="company DESC"}">{gt text="Company"}</a>{if strpos($sort,'ompany')} {img modname='AddressBook' src="s_asc.png" alt="" }{/if}</th>
                {else}
                <th><a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter search=$search sort="company ASC"}">{gt text="Company"}</a>{if strpos($sort,'ompany')} {img modname='AddressBook' src="s_desc.png" alt="" }{/if}</th>
                {/if}
                {if $preferences.addressbooktype == 2}
                    <th>{gt text="City"}</th>
                    <th>{gt text="Activity"}</th>
                {/if}
                <th>{gt text="Contact"}</th>
                {if $adminAuth}
                    <th>{gt text="Status"}</th>
                    <th>{gt text="User owner"}</th>
                {/if}
                {if $editAuth}
                    <th>{gt text="Viewed"}</th>
                {/if}
                <th>{gt text="Action"}</th>
            </tr>
            {/if}
        </thead>
        <tbody>
            {foreach item=object from=$objectArray}
            <tr class="{cycle values=z-odd,z-even}">
            {if $mobile_mode}
                <td>
                    {if $preferences.addressbooktype == 2 && isset($object.img) && $object.img<>''}
                        <a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">
                            <img src="{$object.img}" alt="" style="float: left; margin-right: 5px;" />
                        </a>
                    {/if}
                    <a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">
                        <strong>{$object.company|safehtml}</strong>
                    </a>
                    {if isset($object.city)}{$object.city|safehtml}{/if}<br />
                    {if isset($object.custom_1)}{$object.custom_1|safehtml}{/if}<br />
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
            {else}
                {if $preferences.addressbooktype == 2}
                    <td style="text-align:center">
                        {if isset($object.img) && $object.img<>''}<a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}"><img src="{$object.img}" alt="" />{/if}</a>
                    </td>
                {/if}
                {if $preferences.addressbooktype == 1}
                    <td>
                        {if $object.fname && $object.lname}{$object.fname|safehtml} {$object.lname|safehtml}{else}{$object.fname|safehtml}{$object.lname|safehtml}{/if}
                    </td>
                {/if}
                <td>
                    <a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{$object.company|safehtml}</a>
                </td>
                {if $preferences.addressbooktype == 2}
                    <td>
                        {if isset($object.city)}{$object.city|safehtml}{/if}
                    </td>
                    <td>
                        {if isset($object.custom_1)}{$object.custom_1|safehtml}{/if}
                    </td>
                {/if}
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
                {if $adminAuth}
                    {gt text="Click to activate" assign="activate"}
                    {gt text="Click to deactivate" assign="deactivate"}
                    <td class="z-nowrap">
                        <div id="statusactive_{$object.id}" style="display: {if $object.status}block{else}none{/if};">
                            <a href="javascript:void(0);" onclick="setstatus({$object.id},0)">{img src="greenled.png" modname="core" set="icons/extrasmall" title=$deactivate alt=$deactivate}</a>
                            &nbsp;{gt text="Active"}
                        </div>
                        <div id="statusinactive_{$object.id}" style="display: {if $object.status}none{else}block{/if};">
                            <a href="javascript:void(0);" onclick="setstatus({$object.id},1)">{img src="redled.png" modname="core" set="icons/extrasmall" title=$activate alt=$activate}</a>
                            &nbsp;{gt text="Inactive"}
                        </div>
                        {img id="statusajaxind_"|cat:$object.id style="display: none;" modname=core set="ajax" src="indicator_circle.gif" alt=""}
                    </td>
                    <td class="z-nowrap">
                        {$object.user_id|profilelinkbyuid}
                    </td>
                {/if}
                {if $editAuth}
                    <td>{if isset($object.counter)}{$object.counter}{/if}</td>
                {/if}
            {/if}
                <td class="z-nowrap">
                    {if $viewAuth}
                    <a href="{modurl modname=AddressBook type=user func=display id=$object.id search=$search ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="demo.png" __alt="View" __title="View"}</a>
                    {/if}
                    {if $editAuth || $user_id == $object.user_id}
                    <a href="{modurl modname=AddressBook type=user func=edit id=$object.id formcall="edit" ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="xedit.png" __alt="Edit" __title="Edit"}</a>
                    {/if}
                    {if $delAuth || $user_id == $object.user_id}
                    <a href="{modurl modname=AddressBook type=user func=delete id=$object.id ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' set='icons/extrasmall' src="14_layer_deletelayer.png" __alt="Delete" __title="Delete"}</a>
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

{* $Id: addressbook_admin_labels_view.tpl 68 2010-04-01 13:07:05Z herr.vorragend $ *}
{insert name='generateauthkey' module='AddressBook' assign="authid"}
{gt text="Contact labels" assign="templatetitle"}
{include file="addressbook_admin_menu.tpl"}

<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='windowlist.gif' set='icons/large' alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <p><a id="addnewitem" title="{gt text="Add new contact label"}" href="{modurl modname="AddressBook" type="admin" func="edit" ot="labels"}">{gt text="Add new contact label"}</a></p>
    <table class="z-datatable">
        <thead>
            <tr class="{cycle values="z-odd,z-even"}">
                <th><a href="{modurl modname="AddressBook" type="admin" func="view" ot="labels" sort="id"}">{gt text="ID"}</a></th>
                <th><a href="{modurl modname="AddressBook" type="admin" func="view" ot="labels" sort="name"}">{gt text="Contact label"}</a></th>
                {securityutil_checkpermission_block component='AddressBook::' instance='::' level=ACCESS_ADMIN}
                <th>{gt text="Action"}</th>
                {/securityutil_checkpermission_block}
            </tr>
        </thead>
        <tbody>
            {foreach from=$objectArray item=obj}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$obj.id|varprepfordisplay}</td>
                <td>{$obj.name|varprepfordisplay}</td>
                {securityutil_checkpermission_block component='AddressBook::' instance='::' level=ACCESS_ADMIN}
                <td>
                    <a href="{modurl modname="AddressBook" type="admin" func="edit" ot="labels" id=$obj.id}">{img modname=core src=xedit.gif set=icons/extrasmall __alt="Edit" __title="Edit"}</a>
                    <a href="{modurl modname="AddressBook" type="admin" func="delete" ot="labels" id=$obj.id authid=$authid}">{img src='14_layer_deletelayer.gif' modname='core' set='icons/extrasmall' __alt="Delete" __title="Delete"}</a>
                </td>
                {/securityutil_checkpermission_block}
            </tr>
            {/foreach}
        </tbody>
    </table>
    {pager show=page rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1 img_prev=images/icons/extrasmall/previous.gif img_next=images/icons/extrasmall/next.gif}
</div>

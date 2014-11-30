{include file="admin_menu.tpl"}
<div class="z-admincontainer">
    {gt text="Contact labels" assign="templatetitle"}
    <div class="z-adminpageicon">{img modname='core' src='windowlist.png' set='icons/large' alt=$templatetitle}</div>
    <h2>{$templatetitle}</h2>
    <p><a id="addnewitem" title="{gt text="Add new contact label"}" href="{modurl modname="AddressBook" type="admin" func="edit" ot="labels"}">{gt text="Add new contact label"}</a></p>
    <table class="z-datatable">
        <thead>
            <tr class="{cycle values="z-odd,z-even"}">
                <th><a href="{modurl modname="AddressBook" type="admin" func="view" ot="labels" sort="id"}">{gt text="ID"}</a></th>
                <th><a href="{modurl modname="AddressBook" type="admin" func="view" ot="labels" sort="name"}">{gt text="Contact label"}</a></th>
                <th><a href="{modurl modname="AddressBook" type="admin" func="view" ot="labels" sort="name1"}">{gt text="Contact label locale"}</a></th>
                {checkpermissionblock component='AddressBook::' instance='::' level=ACCESS_ADMIN}
                <th>{gt text="Action"}</th>
                {/checkpermissionblock}
            </tr>
        </thead>
        <tbody>
            {foreach from=$objectArray item=obj}
            <tr class="{cycle values="z-odd,z-even"}">
                <td>{$obj.id|safehtml}</td>
                <td>{$obj.name|safehtml}</td>
                <td>{$obj.name1|safehtml}</td>
                {checkpermissionblock component='AddressBook::' instance='::' level=ACCESS_ADMIN}
                <td>
                    <a href="{modurl modname="AddressBook" type="admin" func="edit" ot="labels" id=$obj.id}">{img modname=core src=xedit.png set=icons/extrasmall __alt="Edit" __title="Edit"}</a>
                    <a href="{modurl modname="AddressBook" type="admin" func="delete" ot="labels" id=$obj.id }">{img src='14_layer_deletelayer.png' modname='core' set='icons/extrasmall' __alt="Delete" __title="Delete"}</a>
                </td>
                {/checkpermissionblock}
            </tr>
            {/foreach}
        </tbody>
    </table>
    {pager show=page rowcount=$pager.numitems limit=$pager.itemsperpage posvar=startnum shift=1 img_prev=images/icons/extrasmall/previous.png img_next=images/icons/extrasmall/next.png}
</div>

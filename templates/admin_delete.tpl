{include file="admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='editdelete.gif' set='icons/large' __alt='Delete' }</div>
    {if $ot=="categories"}<h2>{gt text="Category"}</h2>{/if}
    {if $ot=="labels"}<h2>{gt text="Contact label"}</h2>{/if}
    <p class="z-warningmsg">{gt text="Do you really want to delete this item?"}</p>

    <form class="z-form" action="{modurl modname="AddressBook" type="admin" func="delete"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="confirmation" value="1" />
            <input type="hidden" name="id" value="{$id|varprepfordisplay}" />
            <input type="hidden" name="ot" value="{$ot|varprepfordisplay}" />

            <fieldset>
                <legend>{gt text="Confirmation prompt"}</legend>
                <div class="z-formrow">
                    <label>{gt text="Name"}:</label>
                    <span>{$object.name|varprepfordisplay}</span>
                </div>
            </fieldset>

            <div class="z-formbuttons">
                {button src='button_ok.gif' set='icons/small' __alt="Delete" __title="Delete"}
                <a href="{modurl modname=AddressBook type=admin func=view ot=$ot}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>

        </div>
    </form>
</div>

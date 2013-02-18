{include file="admin_menu.tpl"}
<div class="z-admincontainer">
    {if ($labels.id)}
    <div class="z-adminpageicon">{img modname='core' src='xedit.gif' set='icons/large' alt=$templatetitle}</div>
    {else}
    <div class="z-adminpageicon">{img modname='core' src='filenew.gif' set='icons/large' alt=$templatetitle}</div>
    {/if}
    <h2>{gt text="Contact label"}</h2>

    <form class="z-form" action="{modurl modname="AddressBook" type="admin" func="update"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            {if ($labels.id)}
            <input id="labels" name="labels[id]" value="{$labels.id|varprepfordisplay}" type="hidden" />
            {/if}
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="ot" value="labels" />

            <fieldset>
                {if ($labels.id)}
                <legend>{gt text="Edit contact label"}</legend>
                {else}
                <legend>{gt text="Add contact label"}</legend>
                {/if}

                <div class="z-formrow">
                    <label for="labels_name">{gt text="Name"}</label>
                    <input id="labels_name" name="labels[name]" value="{$labels.name|varprepfordisplay}" type="text" size="60" maxlength="80" />
                </div>
            </fieldset>

            <div class="z-formbuttons">
                {button src='button_ok.gif' set='icons/small' __alt="Create" __title="Create"}
                <a href="{modurl modname=AddressBook type=admin func=view ot=labels}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
</div>

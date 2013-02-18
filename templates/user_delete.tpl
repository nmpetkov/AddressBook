{gt text="Delete contact" assign=templatetitle}
{include file="user_menu.tpl"}

<p class="z-warningmsg">{gt text="Do you really want to delete this address?"}</p>
<form class="z-form" action="{modurl modname="AddressBook" type="user" func="deleteProcess"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="{$ot|varprepfordisplay}" />
        <input type="hidden" name="id" value="{$id|varprepfordisplay}" />
        <input type="hidden" name="startnum" value="{$startnum|varprepfordisplay}" />
        <input type="hidden" name="letter" value="{$letter|varprepfordisplay}" />
        <input type="hidden" name="sort" value="{$sort|varprepfordisplay}" />
        <input type="hidden" name="private" value="{$private|varprepfordisplay}" />
        <input type="hidden" name="category" value="{$category|varprepfordisplay}" />
        <input type="hidden" name="search" value="{$search|varprepfordisplay}" />
        <fieldset>
            <legend>{gt text="Confirmation prompt"}</legend>
            <div class="z-formbuttons">
                {button src='button_ok.gif' set='icons/small' __alt="Delete" __title="Delete"}
                <a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </fieldset>
    </div>
</form>

{gt text="Company update" assign=templatetitle}
{include file="user_menu.tpl"}
<p class="z-informationmsg">
    {gt text="You have changed the address for a company contact."}
    {gt text="Should all other contacts for this company be updated automatically (company name, address, zip, city, state and country)?"}
</p>
<form class="z-form" action="{modurl modname="AddressBook" type="user" func="update_company"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="{$ot|safehtml}" />
        <input type="hidden" name="id" value="{$id|safehtml}" />
        <input type="hidden" name="startnum" value="{$startnum|safehtml}" />
        <input type="hidden" name="letter" value="{$letter|safehtml}" />
        <input type="hidden" name="sort" value="{$sort|safehtml}" />
        <input type="hidden" name="private" value="{$private|safehtml}" />
        <input type="hidden" name="category" value="{$category|safehtml}" />
        <input type="hidden" name="search" value="{$search|safehtml}" />
        <input type="hidden" name="oldvalue" value="{$oldvalue|safehtml}" />
        <fieldset>
            <legend>{gt text="Confirmation prompt"}</legend>
            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt="Update" __title="Update"}
                <a href="{modurl modname=AddressBook type=user func=view startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </fieldset>
    </div>
</form>

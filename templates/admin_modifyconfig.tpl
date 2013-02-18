{include file="admin_menu.tpl"}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.gif' set='icons/large' __alt='Settings' }</div>
    <form class="z-form" action="{modurl modname="AddressBook" type="admin" func="updateconfig"}" method="post">
		<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <h2>{gt text="Settings"}</h2>
        <div>
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="preferences_abtitle">{gt text="Title of this Address Book"}</label>
                    <input id="preferences_abtitle" type="text" name="preferences[abtitle]" size="30" maxlength="60" value="{$preferences.abtitle|varprepfordisplay}" />
                </div>
                <div class="z-formrow">
                    <label>{gt text="Special character (Umlauts) replacement for sort columns"}</label>
                    <div>
                        <input id="preferences_special_chars_1" type="text" name="preferences[special_chars_1]" size="12" maxlength="24" value="{$preferences.special_chars_1|varprepfordisplay}" />
                        =>
                        <input id="preferences_special_chars_2" type="text" name="preferences[special_chars_2]" size="12" maxlength="24" value="{$preferences.special_chars_2|varprepfordisplay}" />
                    </div>
                </div>
                <div class="z-formrow">
                    <label for="preferences_itemsperpage">{gt text="Contacts per page"}</label>
                    <input id="preferences_itemsperpage" type="text" name="preferences[itemsperpage]" size="4" maxlength="4" value="{$preferences.itemsperpage|varprepfordisplay}" />
                </div>
                <div class="z-formrow">
                    <label for="preferences_globalprotect">{gt text="Disable personal address book mode"}</label>
                    <input id="preferences_globalprotect" type="checkbox" name="preferences[globalprotect]" value="1" {if $preferences.globalprotect}checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="preferences_use_prefix">{gt text="Enable the use of the 'form of address' field"}</label>
                    <input id="preferences_use_prefix" type="checkbox" name="preferences[use_prefix]" value="1" {if $preferences.use_prefix}checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="preferences_use_img">{gt text="Enable embedding of mediashare images"}</label>
                    <input id="preferences_use_img" type="checkbox" name="preferences[use_img]" value="1" {if $preferences.use_img}checked="checked"{/if} />
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Google Maps integration"}</legend>
                <div class="z-formrow">
                    <label for="preferences_google_api_key">{gt text="Google API key"}</label>
                    <input id="preferences_google_api_key" type="text" name="preferences[google_api_key]" size="90" maxlength="120" value="{$preferences.google_api_key|varprepfordisplay}" />
                </div>
                <div class="z-formrow">
                    <label for="preferences_google_zoom">{gt text="Google zoom"}</label>
                    <input id="preferences_google_zoom" type="text" name="preferences[google_zoom]" size="4" maxlength="2" value="{$preferences.google_zoom|varprepfordisplay}" />
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Custom tab"}</legend>
                <div class="z-formrow">
                    <label for="preferences_custom_tab">{gt text="Tab name"}</label>
                    <input id="preferences_custom_tab" type="text" name="preferences[custom_tab]" size="30" maxlength="60" value="{$preferences.custom_tab|varprepfordisplay}" />
                    <em class="z-sub z-formnote">{gt text="If empty, no custom fields are displayed."}</em>
                </div>
            </fieldset>
            <div class="z-formbuttons">
                {button src='button_ok.gif' set='icons/small' __alt="Update Configuration" __title="Update Configuration"}
                <a href="{modurl modname=AddressBook type=admin func=main}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
</div>

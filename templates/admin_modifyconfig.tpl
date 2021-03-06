{adminheader}
<div class="z-admincontainer">
    <div class="z-adminpageicon">{img modname='core' src='configure.png' set='icons/large' __alt='Settings' }</div>
    <form class="z-form" action="{modurl modname="AddressBook" type="admin" func="updateconfig"}" method="post">
		<input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <h2>{gt text="Settings"}</h2>
        <div>
            <fieldset>
                <legend>{gt text="General settings"}</legend>
                <div class="z-formrow">
                    <label for="preferences_abtitle">{gt text="Title of this Address Book"}</label>
                    {foreach item=language from=$languages}
                    {assign_concat name='prefkey' 1='abtitle_' 2=$language}
                    <div class="z-formlist">
                        <input id="preferences_abtitle_{$language}" name="preferences[abtitle_{$language}]" value="{$preferences.$prefkey|safehtml}" type="text" size="50" maxlength="255" />
                        <label for="preferences_abtitle_{$language}">({$language})</label>
                    </div>
                    {/foreach}
                </div>
                <div class="z-formrow">
                    <label for="preferences_abmetatitle">{gt text="Meta title"}</label>
                    {foreach item=language from=$languages}
                    {assign_concat name='prefkey' 1='abmetatitle_' 2=$language}
                    <div class="z-formlist">
                        <input id="preferences_abmetatitle_{$language}" name="preferences[abmetatitle_{$language}]" value="{$preferences.$prefkey|safehtml}" type="text" size="50" maxlength="255" />
                        <label for="preferences_abmetatitle_{$language}">({$language})</label>
                    </div>
                    {/foreach}
                </div>
                <div class="z-formrow">
                    <label for="preferences_abmetadescription">{gt text="Meta description"}</label>
                    {foreach item=language from=$languages}
                    {assign_concat name='prefkey' 1='abmetadescription_' 2=$language}
                    <div class="z-formlist">
                        <textarea id="preferences_abmetadescription_{$language}" name="preferences[abmetadescription_{$language}]" cols="60" rows="3">{$preferences.$prefkey|safehtml}</textarea>
                        <label for="preferences_abmetadescription_{$language}">({$language})</label>
                    </div>
                    {/foreach}
                </div>
                <div class="z-formrow">
                    <label for="preferences_abmetakeyword">{gt text="Meta keywords"}</label>
                    {foreach item=language from=$languages}
                    {assign_concat name='prefkey' 1='abmetakeyword_' 2=$language}
                    <div class="z-formlist">
                        <textarea id="preferences_abmetakeyword_{$language}" name="preferences[abmetakeyword_{$language}]" cols="60" rows="3">{$preferences.$prefkey|safehtml}</textarea>
                        <label for="preferences_abmetakeyword_{$language}">({$language})</label>
                    </div>
                    {/foreach}
                </div>
                <div class="z-formrow">
                    <label for="preferences_addressbooktype">{gt text="Type of this Address Book"}</label>
                    <select id="preferences_addressbooktype" name="preferences[addressbooktype]" size="1">
                        <option value="1"{if $preferences.addressbooktype eq 1} selected="selected"{/if}>{gt text="People"}</option>
                        <option value="2"{if $preferences.addressbooktype eq 2} selected="selected"{/if}>{gt text="Companies"}</option>
                    </select>
                </div>
                <div class="z-formrow">
                    <label for="preferences_showabcfilter">{gt text="Show ABC filter"}</label>
                    <input id="preferences_showabcfilter" type="checkbox" name="preferences[showabcfilter]" value="1" {if $preferences.showabcfilter eq 1}checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="preferences_itemsperpage">{gt text="Contacts per page"}</label>
                    <input id="preferences_itemsperpage" type="text" name="preferences[itemsperpage]" size="4" maxlength="4" value="{$preferences.itemsperpage|safehtml}" />
                </div>
                <div class="z-formrow">
                    <label for="preferences_globalprotect">{gt text="Private Address Book mode"}</label>
                    <input id="preferences_globalprotect" type="checkbox" name="preferences[globalprotect]" value="1" {if $preferences.globalprotect}checked="checked"{/if} />
                    <em class="z-sub z-formnote">{gt text="For users only, users see only their own records (admin sees all)."}</em>
                </div>
                <div class="z-formrow">
                    <label for="preferences_allowprivate">{gt text="Allow private addresses"}</label>
                    <input id="preferences_allowprivate" type="checkbox" name="preferences[allowprivate]" value="1" {if $preferences.allowprivate}checked="checked"{/if} />
                    <em class="z-sub z-formnote">{gt text="If to use private addresses at all."}</em>
                </div>
                <div class="z-formrow">
                    <label for="preferences_use_prefix">{gt text="Enable the use of the 'form of address' field"}</label>
                    <input id="preferences_use_prefix" type="checkbox" name="preferences[use_prefix]" value="1" {if $preferences.use_prefix}checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="preferences_use_img">{gt text="Enable images"}</label>
                    <input id="preferences_use_img" type="checkbox" name="preferences[use_img]" value="1" {if $preferences.use_img}checked="checked"{/if} />
                </div>
                <div class="z-formrow">
                    <label for="preferences_images_dir">{gt text="Default images directory"}</label>
                    <input id="preferences_images_dir" type="text" name="preferences[images_dir]" value="{$preferences.images_dir|safetext}" />
                </div>
                <div class="z-formrow">
                    <label for="preferences_images_manager">{gt text='Images manager'}</label>
                    <select id="preferences_images_manager" name="preferences[images_manager]" size="1">
                        <option value=""{if $preferences.images_manager eq ''} selected="selected"{/if}>Not selected</option>
                        <option value="kcfinder"{if $preferences.images_manager eq 'kcfinder'} selected="selected"{/if}>Kcfinder Zikula plugin</option>
                    </select>
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Google Maps integration"}</legend>
                <div class="z-formrow">
                    <label for="preferences_google_api_key">{gt text="Google API key"}</label>
                    <input id="preferences_google_api_key" type="text" name="preferences[google_api_key]" size="90" maxlength="120" value="{$preferences.google_api_key|safehtml}" />
                </div>
                <div class="z-formrow">
                    <label for="preferences_google_zoom">{gt text="Google zoom"}</label>
                    <input id="preferences_google_zoom" type="text" name="preferences[google_zoom]" size="4" maxlength="2" value="{$preferences.google_zoom|safehtml}" />
                </div>
            </fieldset>
            <fieldset>
                <legend>{gt text="Custom tab"}</legend>
                <div class="z-formrow">
                    <label for="preferences_custom_tab">{gt text="Tab name"}</label>
                    {foreach item=language from=$languages}
                    {assign_concat name='prefkey' 1='custom_tab_' 2=$language}
                    <div class="z-formlist">
                        <input id="preferences_custom_tab_{$language}" name="preferences[custom_tab_{$language}]" value="{$preferences.$prefkey|safehtml}" type="text" size="50" maxlength="255" />
                        <label for="preferences_custom_tab_{$language}">({$language})</label>
                    </div>
                    {/foreach}
                    <em class="z-sub z-formnote">{gt text="If empty, no custom fields are displayed."}</em>
                </div>
            </fieldset>
            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt="Update Configuration" __title="Update Configuration"}
                <a href="{modurl modname=AddressBook type=admin func=main}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>
</div>

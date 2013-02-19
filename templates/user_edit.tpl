{ajaxheader modname=AddressBook filename=addressbook.js}
{if $preferences.use_img}
{ajaxheader lightbox=true}
{/if}

{if ($address.id)}
{gt text="Edit this address" assign="templatetitle"}
{else}
{gt text="Add an address" assign="templatetitle"}
{/if}

{include file="user_menu.tpl"}
{formutil_getvalidationerror objectType="address" field="company" assign="valerror"}
{if $valerror}<p class="z-warningmsg">{$valerror}</p>{/if}
<form id="addressform" class="z-form" action="{modurl modname="AddressBook" type="user" func="edititem"}" method="post" enctype="application/x-www-form-urlencoded">
    <div>
        {if ($address.id)}
        <input id="address_id" name="address[id]" value="{$address.id|varprepfordisplay}" type="hidden" />
        {/if}
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="{$ot|varprepfordisplay}" />
        <input type="hidden" name="address[user_id]" value="{$user_id|varprepfordisplay}" />
        <input type="hidden" name="startnum" value="{$startnum|varprepfordisplay}" />
        <input type="hidden" name="letter" value="{$letter|varprepfordisplay}" />
        <input type="hidden" name="sort" value="{$sort|varprepfordisplay}" />
        <input type="hidden" name="private" value="{$private|varprepfordisplay}" />
        <input type="hidden" name="category" value="{$category|varprepfordisplay}" />
        <input type="hidden" name="search" value="{$search|varprepfordisplay}" />
        <input type="hidden" name="returnid" value="{$returnid|varprepfordisplay}" />

        <fieldset>
            <legend>{gt text="Name"}</legend>
            {if $preferences.use_prefix==1}
            <div class="z-formrow">
                <label for="address_prefix_">{gt text="Form of address"}</label>
                <div>
                    {gt text="No Prefix/Title" assign="defaultPrfxText"}
                    {selector_category path="/__SYSTEM__/General/Form of address" name="address[prefix]" defaultValue="0" defaultText=$defaultPrfxText selectedValue=$address.prefix editLink=true}
                </div>
            </div>
            {else}
            <input type="hidden" name="address[prefix]" value="$address.prefix" />
            {/if}
            <div class="z-formrow">
                <label for="address_lname">{gt text="Last Name"}</label>
                <input class="required" id="address_lname" name="address[lname]" value="{$address.lname|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_fname">{gt text="First Name"}</label>
                <input id="address_fname" name="address[fname]" value="{$address.fname|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_title">{gt text="Title"}</label>
                <input id="address_title" name="address[title]" value="{$address.title|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_company">{gt text="Company"}</label>
                <input id="address_company" name="address[company]" value="{$address.company|varprepfordisplay}" type="text" size="60" maxlength="280" />
            </div>

            {if $preferences.use_img==1}
            <div class="z-formrow">
                <label for="address_img"><a href="#" onclick="mediashareFindItem('address_img', document.location.pnbaseURL + 'index.php?module=mediashare&amp;type=external&amp;func=finditem&amp;url=relative&amp;mode=url');">{img modname='core' set='icons/extrasmall' src="search.gif" alt=$lblView }</a></label>
                <input id="address_img" name="address[img]" value="{$address.img|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            {if $address.img}
            <div class="z-formnote">
                {*<a href="{$address.img|addressbook_img:org}" rel="lightbox"><img src="{$address.img|addressbook_img:tmb}" alt="{$templatetitle}" /></a>*}
                <a href="{$address.img}" rel="lightbox"><img src="{$address.img}" alt="{$templatetitle}" /></a>
            </div>
            {/if}
            {else}
            <input type="hidden" id="address_img" name="address[img]" value="{$address.img}" />
            {/if}
        </fieldset>

        <fieldset>
            <legend>{gt text="Address"}</legend>
            <div class="z-formrow">
                <label for="address_address1">{gt text="Address"}</label>
                <input id="address_address1" name="address[address1]" value="{$address.address1|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_address2">&nbsp;</label>
                <input id="address_address2" name="address[address2]" value="{$address.address2|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            {if $preferences.zipbeforecity}
            <div class="z-formrow">
                <label for="address_zip">{gt text="Zip"}</label>
                <input id="address_zip" name="address[zip]" value="{$address.zip|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_city">{gt text="City"}</label>
                <input id="address_city" name="address[city]" value="{$address.city|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            {else}
            <div class="z-formrow">
                <label for="address_city">{gt text="City"}</label>
                <input id="address_city" name="address[city]" value="{$address.city|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_zip">{gt text="Zip"}</label>
                <input id="address_zip" name="address[zip]" value="{$address.zip|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            {/if}
            <div class="z-formrow">
                <label for="address_state">{gt text="State"}</label>
                <input id="address_state" name="address[state]" value="{$address.state|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_country">{gt text="Country"}</label>
                <input id="address_country" name="address[country]" value="{$address.country|varprepfordisplay}" type="text" size="60" maxlength="80" />
            </div>
            {if $preferences.google_api_key}
            <div class="z-formrow">
                <label for="address_geodata">{gt text="Google Maps coordinates"}</label>
                <input id="address_geodata" name="address[geodata]" value="{$address.geodata|varprepfordisplay}" type="text" size="60" maxlength="280" />
                <em class="z-sub z-formnote"><a href="javascript:get_geodata();">{gt text="Get coordinates"}</a></em>
            </div>
            {/if}
        </fieldset>

        <fieldset>
            <legend>{gt text="Contact"}</legend>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main1" name="address[c_main]" type="radio" value="0" {if $address.c_main==0}checked="checked"{/if} />
                    {if $address.c_label_1}
                    {assign var="lbl_default" value=$address.c_label_1}
                    {else}
                    {assign var="lbl_default" value="1"}
                    {/if}
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_1]" field="name" assocKey="id"  selectedValue=$lbl_default}
                </div>
                <input id="address_contact_1" name="address[contact_1]" value="{$address.contact_1}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main2" name="address[c_main]" type="radio" value="1" {if $address.c_main==1}checked="checked"{/if} />
                    {if $address.c_label_2}
                    {assign var="lbl_default2" value=$address.c_label_2}
                    {else}
                    {assign var="lbl_default2" value="5"}
                    {/if}
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_2]" field="name" assocKey="id"  selectedValue=$lbl_default2}
                </div>
                <input id="address_contact_2" name="address[contact_2]" value="{$address.contact_2}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main3" name="address[c_main]" type="radio" value="2" {if $address.c_main==2}checked="checked"{/if} />
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_3]" field="name" assocKey="id"  selectedValue=$address.c_label_3}
                </div>
                <input id="address_contact_3" name="address[contact_3]" value="{$address.contact_3}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main4" name="address[c_main]" type="radio" value="3" {if $address.c_main==3}checked="checked"{/if} />
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_4]" field="name" assocKey="id"  selectedValue=$address.c_label_4}
                </div>
                <input id="address_contact_4" name="address[contact_4]" value="{$address.contact_4}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main5" name="address[c_main]" type="radio" value="4" {if $address.c_main==4}checked="checked"{/if} />
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_5]" field="name" assocKey="id"  selectedValue=$address.c_label_5}
                </div>
                <input id="address_contact_5" name="address[contact_5]" value="{$address.contact_5}" type="text" size="60" maxlength="80" />
            </div>
        </fieldset>

        {if $preferences.custom_tab}
        <fieldset class="z-linear">
            <legend>{$preferences.custom_tab}</legend>
            {foreach item=cusfield from=$customfields}
            {assign_concat 1="custom_" 2=$cusfield.id name="fieldname"}
            <div class="z-formrow">
                {if $cusfield.type=="varchar(60) default NULL"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay}" type="text" size="60" maxlength="80" />
                {elseif $cusfield.type=="varchar(120) default NULL"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <textarea id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" class="z_texpand" rows="2" cols="40">{$address.$fieldname|varprepfordisplay}</textarea>
                {elseif $cusfield.type=="varchar(240) default NULL"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <textarea id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" class="z_texpand" rows="4" cols="40">{$address.$fieldname|varprepfordisplay}</textarea>
                {elseif $cusfield.type=="text"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <textarea id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" class="z_texpand" rows="6" cols="40">{$address.$fieldname|varprepfordisplay}</textarea>
                {elseif $cusfield.type=="decimal(10,2) default NULL"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay|formatnumber}" type="text" size="12" maxlength="12" />
                {elseif $cusfield.type=="int default NULL"}
                <label for="address_custom_{$cusfield.id}">{$cusfield.name|varprepfordisplay}</label>
                <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id}]" value="{$address.$fieldname|varprepfordisplay}" type="text" size="9" maxlength="9" />
                {elseif $cusfield.type=="date default NULL"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <div>
                    {gt text='%Y-%m-%d' domain='zikula' comment='This is from the core domain' assign='adrdateformat'}
                    <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay|date_format:$adrdateformat}" type="text" size="10" maxlength="10" />
                    {calendarinput objectname='' htmlname="address_custom_`$cusfield.id`" dateformat=$adrdateformat ifformat='%Y-%m-%d' defaultdate=$value}
                </div>
                {elseif $cusfield.type=="dropdown" && $cusfield.option}
                {ab_getoptionsinarray option=$cusfield.option assign="dropdowns"}
                <label for="address_custom_{$cusfield.id|varprepfordisplay}">{$cusfield.name|varprepfordisplay}</label>
                <select id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]">
                    {foreach item=drop from=$dropdowns}
                    <option value="{$drop|varprepfordisplay}"{if ($drop|trim == $address.$fieldname|trim)}selected="selected"{/if}>{$drop|trim}</option>
                    {/foreach}
                </select>
                {elseif $cusfield.type=="tinyint default NULL"}
                <br />
                <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay}" type="hidden" />
                {elseif $cusfield.type=="smallint default NULL"}
                <hr />
                <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay}" type="hidden" />
                {/if}
            </div>
            {/foreach}
        </fieldset>
        {else}
        {foreach item=cusfield from=$customfields}
        <input id="address_custom_{$cusfield.id|varprepfordisplay}" name="address[custom_{$cusfield.id|varprepfordisplay}]" value="{$address.$fieldname|varprepfordisplay}" type="hidden" />
        {/foreach}
        {/if}

        <fieldset class="z-linear">
            <legend>{gt text="Note"}</legend>
            <div class="z-formrow">
                <label for="address_note">{gt text="Content"}</label>
                <textarea id="address_note" name="address[note]" class="z_texpand" rows="6" cols="40">{$address.note|varprephtmldisplay}</textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>{gt text="Category"}</legend>
            <div class="z-formrow">
                <label>{gt text='Category'}</label>
                {gt text='Choose a category' assign='lblDef'}
                {nocache}
                {foreach from=$catregistry key='property' item='categoryitem'}
                <div class="z-formnote">{selector_category category=$categoryitem name="address[cat_id]" field='id' selectedValue=$address.cat_id defaultValue=0 defaultText=$lblDef}</div>
                {/foreach}
                {/nocache}
            </div>
            {if $preferences.globalprotect}
            <input id="address_private" name="address[private]" type="hidden" value="" />
            {else}
            <div class="z-formrow">
                <label for="address_private">{gt text="Private"}</label>
                <input id="address_private" name="address[private]" type="checkbox" {if $address.private}checked="checked"{/if} />
            </div>
            {/if}
        </fieldset>

        {notifydisplayhooks eventname='addressbook.ui_hooks.items.form_edit' id=null}

        <div class="z-formbuttons">
            {button src='button_ok.gif' set='icons/small' __alt="Create" __title="Create" class="formbutton"}
            {if $address.id}
            {button id="btn_duplicate" value='1' name="btn_duplicate" src='add_group.gif' set='icons/small' __alt="Duplicate this contact" __title="Duplicate this contact" class="formbutton"}
            {else}
            {button id="btn_save_duplicate" value='1' name="btn_save_duplicate" src='add_user.gif' set='icons/small' class="formbutton" __alt="Save and duplicate this contact" __title="Save and duplicate this contact"}
            {/if}
            {if !empty($returnid)}
            <a href="{modurl modname=AddressBook type=user func=display id=$returnid ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            {else}
            <a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.gif' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            {/if}
        </div>
    </div>

</form>

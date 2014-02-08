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
        {if $address.id}
        <input id="address_id" name="address[id]" value="{$address.id|safehtml}" type="hidden" />
        {/if}
        <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
        <input type="hidden" name="ot" value="{$ot|safehtml}" />
        <input type="hidden" name="address[user_id]" value="{$user_id|safehtml}" />
        <input type="hidden" name="startnum" value="{$startnum|safehtml}" />
        <input type="hidden" name="letter" value="{$letter|safehtml}" />
        <input type="hidden" name="sort" value="{$sort|safehtml}" />
        <input type="hidden" name="private" value="{$private|safehtml}" />
        <input type="hidden" name="category" value="{$category|safehtml}" />
        <input type="hidden" name="search" value="{$search|safehtml}" />
        <input type="hidden" name="returnid" value="{$returnid|safehtml}" />

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
            <input type="hidden" name="address[prefix]" value="{if $address.id}$address.prefix{/if}" />
            {/if}
            <div class="z-formrow">
                <label for="address_lname">{gt text="Last Name"}</label>
                <input class="required" id="address_lname" name="address[lname]" value="{if $address.id}{$address.lname|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_fname">{gt text="First Name"}</label>
                <input id="address_fname" name="address[fname]" value="{if $address.id}{$address.fname|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_title">{gt text="Title"}</label>
                <input id="address_title" name="address[title]" value="{if $address.id}{$address.title|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_company">{gt text="Company"}</label>
                <input id="address_company" name="address[company]" value="{if $address.id}{$address.company|safehtml}{/if}" type="text" size="60" maxlength="280" />
            </div>

            {if $preferences.use_img==1}
            <div class="z-formrow">
                <label for="address_img">{gt text="Image/Logo"}</label>
                {if $preferences.images_manager=='kcfinder'}{gt text="Upload/select image" assign="lblImageManage"}
                    <a href="#" onclick="openKCFinder(document.getElementById('address_img'));">{img modname='core' set='icons/extrasmall' src="search.png" alt=$lblImageManage title=$lblImageManage}</a>
                    {kcfinderscript_window upload_dir=$preferences.images_dir}{kcfinderscript_iframe upload_dir=$preferences.images_dir}
                {/if}
                <input id="address_img" name="address[img]" value="{if $address.id}{$address.img|safehtml}{/if}" type="text" size="60" maxlength="80" /><div id="kcfinder_div"></div>
            </div>
            {if $address.id && $address.img}
            <div class="z-formnote">
                {*<a href="{$address.img|addressbook_img:org}" rel="lightbox"><img src="{$address.img|addressbook_img:tmb}" alt="{$templatetitle}" /></a>*}
                <a href="{$address.img}" rel="lightbox"><img src="{$address.img}" alt="{$templatetitle}" /></a>
            </div>
            {/if}
            {else}
            <input type="hidden" id="address_img" name="address[img]" value="{if $address.id}{$address.img}{/if}" />
            {/if}
        </fieldset>

        <fieldset>
            <legend>{gt text="Address"}</legend>
            <div class="z-formrow">
                <label for="address_address1">{gt text="Address"}</label>
                <input id="address_address1" name="address[address1]" value="{if $address.id}{$address.address1|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_address2">&nbsp;</label>
                <input id="address_address2" name="address[address2]" value="{if $address.id}{$address.address2|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            {if isset($preferences.zipbeforecity) && $preferences.zipbeforecity}
            <div class="z-formrow">
                <label for="address_zip">{gt text="Zip"}</label>
                <input id="address_zip" name="address[zip]" value="{if $address.id}{$address.zip|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_city">{gt text="City"}</label>
                <input id="address_city" name="address[city]" value="{if $address.id}{$address.city|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            {else}
            <div class="z-formrow">
                <label for="address_city">{gt text="City"}</label>
                <input id="address_city" name="address[city]" value="{if $address.id}{$address.city|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_zip">{gt text="Zip"}</label>
                <input id="address_zip" name="address[zip]" value="{if $address.id}{$address.zip|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            {/if}
            <div class="z-formrow">
                <label for="address_state">{gt text="State"}</label>
                <input id="address_state" name="address[state]" value="{if $address.id}{$address.state|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_country">{gt text="Country"}</label>
                <input id="address_country" name="address[country]" value="{if $address.id}{$address.country|safehtml}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <label for="address_geodata">{gt text="Google Maps coordinates"}</label>
                <input id="address_geodata" name="address[geodata]" value="{if $address.id}{$address.geodata|safehtml}{/if}" type="text" size="60" maxlength="280" />
                {if $address.id}
                <em class="z-sub z-formnote"><a href="javascript:get_geodata();">{gt text="Get coordinates"}</a>&nbsp;|&nbsp;<a href="{modurl modname='AddressBook' type='ajax' func='get_geodata' val_1=$address.address1 val_2=$address.zip val_3=$address.city val_4=$address.country plane=1}">{gt text="Get plane"}</a></em>
                {/if}
            </div>
        </fieldset>

        <fieldset>
            <legend>{gt text="Contact"}</legend>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main1" name="address[c_main]" type="radio" value="0" {if $address.id && $address.c_main==0}checked="checked"{/if} />
                    {if $address.id && $address.c_label_1}
                    {assign var="lbl_default" value=$address.c_label_1}
                    {else}
                    {assign var="lbl_default" value="1"}
                    {/if}
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_1]" field="name" assocKey="id"  selectedValue=$lbl_default}
                </div>
                <input id="address_contact_1" name="address[contact_1]" value="{if $address.id}{$address.contact_1}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    <input id="address_c_main2" name="address[c_main]" type="radio" value="1" {if $address.id && $address.c_main==1}checked="checked"{/if} />
                    {if $address.id && $address.c_label_2}
                    {assign var="lbl_default2" value=$address.c_label_2}
                    {else}
                    {assign var="lbl_default2" value="5"}
                    {/if}
                    {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_2]" field="name" assocKey="id"  selectedValue=$lbl_default2}
                </div>
                <input id="address_contact_2" name="address[contact_2]" value="{if $address.id}{$address.contact_2}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    {if $address.id}
                        <input id="address_c_main3" name="address[c_main]" type="radio" value="2" {if $address.c_main==2}checked="checked"{/if} />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_3]" field="name" assocKey="id"  selectedValue=$address.c_label_3}
                    {else}
                        <input id="address_c_main3" name="address[c_main]" type="radio" value="2" />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_3]" field="name" assocKey="id"}
                    {/if}
                </div>
                <input id="address_contact_3" name="address[contact_3]" value="{if $address.id}{$address.contact_3}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    {if $address.id}
                        <input id="address_c_main4" name="address[c_main]" type="radio" value="3" {if $address.c_main==3}checked="checked"{/if} />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_4]" field="name" assocKey="id"  selectedValue=$address.c_label_4}
                    {else}
                        <input id="address_c_main4" name="address[c_main]" type="radio" value="3" />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_4]" field="name" assocKey="id"}
                    {/if}
                </div>
                <input id="address_contact_4" name="address[contact_4]" value="{if $address.id}{$address.contact_4}{/if}" type="text" size="60" maxlength="80" />
            </div>
            <div class="z-formrow">
                <div class="z-label">
                    {if $address.id}
                        <input id="address_c_main5" name="address[c_main]" type="radio" value="4" {if $address.c_main==4}checked="checked"{/if} />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_5]" field="name" assocKey="id"  selectedValue=$address.c_label_5}
                    {else}
                        <input id="address_c_main5" name="address[c_main]" type="radio" value="4" />
                        {selector_field_array modname="AddressBook" table="addressbook_labels" name="address[c_label_5]" field="name" assocKey="id"}
                    {/if}
                </div>
                <input id="address_contact_5" name="address[contact_5]" value="{if $address.id}{$address.contact_5}{/if}" type="text" size="60" maxlength="80" />
            </div>
        </fieldset>

        {if $preferences.custom_tab}
        <fieldset class="z-linear">
            <legend>{$preferences.custom_tab}</legend>
            {foreach item=cusfield from=$customfields}
            {assign_concat 1="custom_" 2=$cusfield.id name="fieldname"}
            <div class="z-formrow">
                {if $cusfield.type=="varchar(60) default NULL"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml}{/if}" type="text" size="60" maxlength="80" />
                {elseif $cusfield.type=="varchar(120) default NULL"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <textarea id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" class="z_texpand" rows="2" cols="40">{if $address.id}{$address.$fieldname|safehtml}{/if}</textarea>
                {elseif $cusfield.type=="varchar(240) default NULL"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <textarea id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" class="z_texpand" rows="4" cols="40">{if $address.id}{$address.$fieldname|safehtml}{/if}</textarea>
                {elseif $cusfield.type=="text"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <textarea id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" class="z_texpand" rows="6" cols="40">{if $address.id}{$address.$fieldname|safehtml}{/if}</textarea>
                {elseif $cusfield.type=="decimal(10,2) default NULL"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml|formatnumber}{/if}" type="text" size="12" maxlength="12" />
                {elseif $cusfield.type=="int default NULL"}
                <label for="address_custom_{$cusfield.id}">{$cusfield.name|safehtml}</label>
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id}]" value="{if $address.id}{$address.$fieldname|safehtml}{/if}" type="text" size="9" maxlength="9" />
                {elseif $cusfield.type=="date default NULL"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <div>
                    {gt text='%Y-%m-%d' domain='zikula' comment='This is from the core domain' assign='adrdateformat'}
                    <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml|date_format:$adrdateformat}{/if}" type="text" size="10" maxlength="10" />
                    {calendarinput objectname='' htmlname="address_custom_`$cusfield.id`" dateformat=$adrdateformat ifformat='%Y-%m-%d' defaultdate=$value}
                </div>
                {elseif $cusfield.type=="dropdown" && $cusfield.option}
                {ab_getoptionsinarray option=$cusfield.option assign="dropdowns"}
                <label for="address_custom_{$cusfield.id|safehtml}">{$cusfield.name|safehtml}</label>
                <select id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]">
                    {foreach item=drop from=$dropdowns}
                        {if $address.id}
                            <option value="{$drop|safehtml}"{if ($drop|trim == $address.$fieldname|trim)}selected="selected"{/if}>{$drop|trim}</option>
                        {else}
                            <option value="{$drop|safehtml}">{$drop|trim}</option>
                        {/if}
                    {/foreach}
                </select>
                {elseif $cusfield.type=="tinyint default NULL"}
                <br />
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml}{/if}" type="hidden" />
                {elseif $cusfield.type=="smallint default NULL"}
                <hr />
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml}{/if}" type="hidden" />
                {/if}
            </div>
            {/foreach}
        </fieldset>
        {else}
            {foreach item=cusfield from=$customfields}
                {assign_concat 1="custom_" 2=$cusfield.id name="fieldname"}
                <input id="address_custom_{$cusfield.id|safehtml}" name="address[custom_{$cusfield.id|safehtml}]" value="{if $address.id}{$address.$fieldname|safehtml}{/if}" type="hidden" />
            {/foreach}
        {/if}

        <fieldset class="z-linear">
            <legend>{gt text="Note"}</legend>
            <div class="z-formrow">
                <label for="address_note">{gt text="Content"}</label>
                <textarea id="address_note" name="address[note]" class="z_texpand" rows="6" cols="40">{if $address.id}{$address.note|safehtml}{/if}</textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>{gt text="Category"}</legend>
            <div class="z-formrow">
                <label>{gt text='Category'}</label>
                {gt text='Choose a category' assign='lblDef'}
                {nocache}
                {foreach from=$catregistry key='property' item='categoryitem'}
                <div class="z-formnote">
                    {if $address.id}
                    {selector_category category=$categoryitem name="address[cat_id]" field='id' selectedValue=$address.cat_id defaultValue=0 defaultText=$lblDef}
                    {else}
                    {selector_category category=$categoryitem name="address[cat_id]" field='id' defaultValue=0 defaultText=$lblDef}
                    {/if}
                </div>
                {/foreach}
                {/nocache}
            </div>
            {if $preferences.globalprotect}
            <input id="address_private" name="address[private]" type="hidden" value="" />
            {else}
            <div class="z-formrow">
                <label for="address_private">{gt text="Private"}</label>
                <input id="address_private" name="address[private]" type="checkbox" {if $address.id && $address.private}checked="checked"{/if} />
            </div>
            {/if}
        </fieldset>

        {notifydisplayhooks eventname='addressbook.ui_hooks.items.form_edit' id=null}

        <div class="z-formbuttons">
            {button src='button_ok.png' set='icons/small' __alt="Create" __title="Create" class="formbutton"}
            {if $address.id}
            {button id="btn_duplicate" value='1' name="btn_duplicate" src='add_group.png' set='icons/small' __alt="Duplicate this contact" __title="Duplicate this contact" class="formbutton"}
            {else}
            {button id="btn_save_duplicate" value='1' name="btn_save_duplicate" src='add_user.png' set='icons/small' class="formbutton" __alt="Save and duplicate this contact" __title="Save and duplicate this contact"}
            {/if}
            {if !empty($returnid)}
            <a href="{modurl modname=AddressBook type=user func=display id=$returnid ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            {else}
            <a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            {/if}
        </div>
    </div>

</form>

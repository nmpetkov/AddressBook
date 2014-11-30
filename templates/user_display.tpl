{ajaxheader modname=AddressBook filename=addressbook.js}
{userloggedin assign="loggedin"}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_EDIT' assign='editAuth'}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_DELETE' assign='delAuth'}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_ADMIN' assign='adminAuth'}

{if $themeinfo.name == 'Mobile'}{assign var='mobile_mode' value=1}{else}{assign var='mobile_mode' value=0}{/if}

{if $preferences.use_img}
{ajaxheader lightbox=true}
{/if}

{capture assign=templatetitle}
{gt text="Address"}:
{if $address.fname && $address.lname}
{$address.fname|safehtml} {$address.lname|safehtml}
{elseif $address.lname}
{$address.fname|safehtml}{$address.lname|safehtml}
{/if}
{/capture}

{include file="user_menu.tpl"}

<div class="adr_display z-form">
    <input type="hidden" name="ot" value="{$ot|safehtml}" />
    <input type="hidden" name="startnum" value="{$startnum|safehtml}" />
    <input type="hidden" name="letter" value="{$letter|safehtml}" />
    <input type="hidden" name="sort" value="{$sort|safehtml}" />
    <input type="hidden" name="private" value="{$private|safehtml}" />
    <input type="hidden" name="category" value="{$category|safehtml}" />
    <input type="hidden" name="search" value="{$search|safehtml}" />
    <fieldset>
        {if $address.cat_id}
        {category_path id=$address.cat_id field="display_name" assign="category_name"}
        {/if}
        {if $preferences.use_prefix==1 && $address.prefix}
        {category_path id=$address.prefix field="display_name" assign="prefix_name"}
        {/if}
        <legend>{if isset($category_name)}{gt text="Category"}: {$category_name.$lang|safehtml}{/if}</legend>
        <div class="z-clearfix">
            {if $address.fname or $address.lname}
            <div class="z-formrow">
                {if $preferences.use_prefix==1 && $address.prefix && $prefix_name}
                <span class="z-formlist">{$prefix_name.$lang|safehtml}</span>
                {/if}
                <label>{gt text="Name"}:</label>
                <span><strong>
                    {if $address.fname && $address.lname}
                    {$address.fname|safehtml} {$address.lname|safehtml}
                    {elseif $address.lname}
                    {$address.fname|safehtml}{$address.lname|safehtml}
                    {/if}
                </strong></span>
            </div>
            {/if}

            {if $address.title}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Title"}:</label>
                    <span>{$address.title|safehtml}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            {if $address.company}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Company"}:</label>
                    <span>{$address.company|safehtml}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            <div class="z-formrow">
                <label>{gt text="Address"}:</label>
                {if $address.address1}<div class="z-formlist">{$address.address1|safehtml}</div>{/if}
                {if $address.address2}<div class="z-formlist">{$address.address2|safehtml}</div>{/if}
                {if $address.zip || $address.city}<div class="z-formlist">{$address.zip|safehtml} {$address.city|safehtml}</div>{/if}
                {if $address.state}<div class="z-formlist">{$address.state|safehtml}</div>{/if}
                {if $address.country}<div class="z-formlist">{$address.country|safehtml}</div>{/if}
            </div>
            {if $address.img && $preferences.use_img==1}
            <div class="z-formnote">
                {*<a href="{$address.img|addressbook_img:org}" rel="lightbox"><img src="{$address.img|addressbook_img:tmb}" alt="{$templatetitle}" /></a>*}
                <a href="{$address.img}" rel="lightbox"><img src="{$address.img}" alt="{$templatetitle}" style="max-height: 300px; max-width: 600px;" /></a>
            </div>
            {/if}
        </div>
    </fieldset>

    <fieldset>
        <legend>{gt text="Contact"}</legend>
        {if $address.contact_1}
        <div class="z-formrow">
            <label>{getvaluebyid table="addressbook_labels" field="name" id=$address.c_label_1}:{if $address.c_main==0}<span class="z-mandatorysym">*</span>{/if}</label>
            <span>{$address.contact_1|contact}</span>
        </div>
        {/if}
        {if $address.contact_2}
        <div class="z-formrow">
            <label>{getvaluebyid table="addressbook_labels" field="name" id=$address.c_label_2}:{if $address.c_main==1}<span class="z-mandatorysym">*</span>{/if}</label>
            <span>{$address.contact_2|contact}</span>
        </div>
        {/if}
        {if $address.contact_3}
        <div class="z-formrow">
            <label>{getvaluebyid table="addressbook_labels" field="name" id=$address.c_label_3}:{if $address.c_main==2}<span class="z-mandatorysym">*</span>{/if}</label>
            <span>{$address.contact_3|contact}</span>
        </div>
        {/if}
        {if $address.contact_4}
        <div class="z-formrow">
            <label>{getvaluebyid table="addressbook_labels" field="name" id=$address.c_label_4}:{if $address.c_main==3}<span class="z-mandatorysym">*</span>{/if}</label>
            <span>{$address.contact_4|contact}</span>
        </div>
        {/if}
        {if $address.contact_5}
        <div class="z-formrow">
            <label>{getvaluebyid table="addressbook_labels" field="name" id=$address.c_label_5}:{if $address.c_main==4}<span class="z-mandatorysym">*</span>{/if}</label>
            <span>{$address.contact_5|contact}</span>
        </div>
        {/if}
    </fieldset>

    {assign_concat name='prefkey' 1='custom_tab_' 2=$lang}
    {if $preferences.$prefkey}
    <fieldset>
        <legend>{$preferences.$prefkey}</legend>
        {foreach item=cusfield from=$customfields}
        {assign_concat 1="custom_" 2=$cusfield.id name="fieldname"}
        {if $cusfield.type=="tinyint default NULL"}
        <br />
        {elseif $cusfield.type=="smallint default NULL"}
        <hr />
        {elseif $cusfield.type=="date default NULL"}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span>{$address.$fieldname|safehtml|date_format}</span>
        </div>
        {elseif $cusfield.type=="decimal(10,2) default NULL"}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span>{$address.$fieldname|safehtml|formatnumber}</span>
        </div>
        {else}
        {if $address.$fieldname<>''}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span class="z-formlist">{$address.$fieldname|safehtml}</span>
        </div>
        {/if}
        {/if}
        {/foreach}
    </fieldset>
    {/if}

    {if $address.note && $adminAuth}
    <fieldset class="z-linear">
        <legend>{gt text="Note"}</legend>
        <div class="z-formrow">{$address.note|safehtml}</div>
    </fieldset>
    {/if}

    {if $address.geodata}
    <fieldset class="z-linear">
        <legend>{gt text="Map"}</legend>
        {include file='user_displaymap.tpl'}
    </fieldset>
    {/if}

    <p class="z-sub">{gt text="Last changed on %s" tag1=$address.date|safehtml|date_format}</p>

    <div {if $mobile_mode} data-role="controlgroup" data-type="horizontal"{else}class="z-formbuttons z-buttons"{/if}>
        <a{if $mobile_mode} data-role="button" data-icon="back"{/if} href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{if !$mobile_mode}{img modname='core' src='agt_back.png' set='icons/small'   __alt='Back' __title='Back'}{/if}{gt text="Back"}</a>
        {if !$mobile_mode}
            {browserhack condition="if IE"}
                <a href="#" onclick="javascript:window.clipboardData.setData('Text','{if $address.company|safehtml}{$address.company|safehtml}\n{/if}{if $address.fname|safehtml}{$address.fname|safehtml} {/if}{if $address.lname}{$address.lname}\n\n{/if}{if $address.address1}{$address.address1|safehtml}\n{/if}{if $address.address2}{$address.address2|safehtml}\n{/if}{if $address.zip}{$address.zip|safehtml} {/if}{if $address.city}{$address.city|safehtml}\n{/if}{if $address.country}{$address.country|safehtml}{/if}');">{img modname='core' src='editpaste.png' set='icons/small'   __alt="Copy" __title="Copy"}</a>
            {/browserhack}
        {/if}
        {if $editAuth || $user_id == $address.user_id}
        <a{if $mobile_mode} data-role="button" data-icon="edit"{/if} class="z-bt-edit" href="{modurl modname=AddressBook type=user func=edit id=$address.id formcall=edit ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search returnid=$address.id}">{gt text="Edit"}</a>
        {/if}
        {if $delAuth}
        <a{if $mobile_mode} data-role="button" data-icon="delete"{/if} class="z-bt-delete" href="{modurl modname=AddressBook type=user func=delete id=$address.id ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search returnid=$address.id}">{gt text="Delete"}</a>
        {/if}
    </div>
</div>

{notifydisplayhooks eventname='addressbook.ui_hooks.items.display_view' id=$address.id}

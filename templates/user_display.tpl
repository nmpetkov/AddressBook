{ajaxheader modname=AddressBook filename=addressbook.js}
{userloggedin assign="loggedin"}
{securityutil_checkpermission component='AddressBook::' instance='::' level='ACCESS_EDIT' assign='editAuth'}

{if $preferences.use_img}
{ajaxheader lightbox=true}
{/if}

{capture assign=templatetitle}
{gt text="Address"}:
{if $address.fname && $address.lname}
{$address.fname|varprepfordisplay} {$address.lname|varprepfordisplay}
{elseif $address.lname}
{$address.fname|varprepfordisplay}{$address.lname|varprepfordisplay}
{/if}
{/capture}

{include file="user_menu.tpl"}

<div class="adr_display z-form">
    <input type="hidden" name="ot" value="{$ot|varprepfordisplay}" />
    <input type="hidden" name="startnum" value="{$startnum|varprepfordisplay}" />
    <input type="hidden" name="letter" value="{$letter|varprepfordisplay}" />
    <input type="hidden" name="sort" value="{$sort|varprepfordisplay}" />
    <input type="hidden" name="private" value="{$private|varprepfordisplay}" />
    <input type="hidden" name="category" value="{$category|varprepfordisplay}" />
    <input type="hidden" name="search" value="{$search|varprepfordisplay}" />
    <fieldset>
        {if $address.cat_id}
        {category_path id=$address.cat_id field="display_name" assign="category_name"}
        {/if}
        {if $address.prefix && $preferences.use_prefix==1}
        {category_path id=$address.prefix field="display_name" assign="prefix_name"}
        {/if}
        <legend>{if isset($category_name)}{gt text="Category"}: {$category_name.$lang|varprepfordisplay}{/if}</legend>
        <div class="z-clearfix">
            <div class="z-formrow">
                {if $prefix_name && $preferences.use_prefix==1}
                <span class="z-formlist">{$prefix_name.$lang|varprepfordisplay}</span>
                {/if}
                <label>{gt text="Name"}:</label>
                <span><strong>
                    {if $address.fname && $address.lname}
                    {$address.fname|varprepfordisplay} {$address.lname|varprepfordisplay}
                    {elseif $address.lname}
                    {$address.fname|varprepfordisplay}{$address.lname|varprepfordisplay}
                    {/if}
                </strong></span>
            </div>

            {if $address.title}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Title"}:</label>
                    <span>{$address.title|varprepfordisplay}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            {if $address.company}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Company"}:</label>
                    <span>{$address.company|varprepfordisplay}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            <div class="z-formrow">
                <label>{gt text="Address"}:</label>
                {if $address.address1}<div class="z-formlist">{$address.address1|varprepfordisplay}</div>{/if}
                {if $address.address2}<div class="z-formlist">{$address.address2|varprepfordisplay}</div>{/if}
                {if $address.zip || $address.city}<div class="z-formlist">{$address.zip|varprepfordisplay} {$address.city|varprepfordisplay}</div>{/if}
                {if $address.state}<div class="z-formlist">{$address.state|varprepfordisplay}</div>{/if}
                {if $address.country}<div class="z-formlist">{$address.country|varprepfordisplay}</div>{/if}
            </div>
            {if $address.img && $preferences.use_img==1}
            <div class="z-formnote">
                {*<a href="{$address.img|addressbook_img:org}" rel="lightbox"><img src="{$address.img|addressbook_img:tmb}" alt="{$templatetitle}" /></a>*}
                <a href="{$address.img}" rel="lightbox"><img src="{$address.img}" alt="{$templatetitle}" width="100" /></a>
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

    {if $preferences.custom_tab}
    <fieldset>
        <legend>{$preferences.custom_tab}</legend>
        {foreach item=cusfield from=$customfields}
        {assign_concat 1="custom_" 2=$cusfield.id name="fieldname"}
        {if $cusfield.type=="tinyint default NULL"}
        <br />
        {elseif $cusfield.type=="smallint default NULL"}
        <hr />
        {elseif $cusfield.type=="date default NULL"}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span>{$address.$fieldname|varprepfordisplay|date_format}</span>
        </div>
        {elseif $cusfield.type=="decimal(10,2) default NULL"}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span>{$address.$fieldname|varprepfordisplay|formatnumber}</span>
        </div>
        {else}
        <div class="z-formrow">
            <label>{$cusfield.name}:</label>
            <span class="z-formlist">{$address.$fieldname|varprephtmldisplay}</span>
        </div>
        {/if}
        {/foreach}
    </fieldset>
    {/if}

    {if $address.note}
    <fieldset class="z-linear">
        <legend>{gt text="Note"}</legend>
        <div class="z-formrow">{$address.note|varprephtmldisplay}</div>
    </fieldset>
    {/if}

    {if $address.geodata}
    <fieldset class="z-linear">
        <legend>{gt text="Map"}</legend>
        {include file='user_displaymap.tpl'}
    </fieldset>
    {/if}

    <p class="z-sub">{gt text="Last changed on %s" tag1=$address.date|varprepfordisplay|date_format}</p>

    <div class="z-formbuttons">
        <a href="{modurl modname=AddressBook type=user func=view ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search}">{img modname='core' src='agt_back.gif' set='icons/small'   __alt='Back' __title='Back'}</a>

        {browserhack condition="if IE"}
        &nbsp;&nbsp;<a href="#" onclick="javascript:window.clipboardData.setData('Text','{if $address.company|varprepfordisplay}{$address.company|varprepfordisplay}\n{/if}{if $address.fname|varprepfordisplay}{$address.fname|varprepfordisplay} {/if}{if $address.lname}{$address.lname}\n\n{/if}{if $address.address1}{$address.address1|varprepfordisplay}\n{/if}{if $address.address2}{$address.address2|varprepfordisplay}\n{/if}{if $address.zip}{$address.zip|varprepfordisplay} {/if}{if $address.city}{$address.city|varprepfordisplay}\n{/if}{if $address.country}{$address.country|varprepfordisplay}{/if}');">{img modname='core' src='editpaste.gif' set='icons/small'   __alt="Copy" __title="Copy"}</a>

        {/browserhack}
        {if $editAuth || $user_id == $address.user_id}
        &nbsp;&nbsp;<a href="{modurl modname=AddressBook type=user func=edit id=$address.id formcall="edit" ot=$ot startnum=$startnum private=$private category=$category letter=$letter sort=$sort search=$search returnid=$address.id}">{img modname='core' set='icons/small' src="edit.gif" __alt='Edit' __title='Edit'}</a>
        {/if}
    </div>
</div>

{notifydisplayhooks eventname='addressbook.ui_hooks.items.display_view' id=$quote.qid}

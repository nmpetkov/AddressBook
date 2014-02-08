{ajaxheader modname=AddressBook filename=addressbook.js}

{if $preferences.use_img}
{ajaxheader lightbox=true}
{/if}

<div class="adr_display z-form">
    <input type="hidden" name="ot" value="{$ot|safehtml}" />
    <input type="hidden" name="private" value="{$private|safehtml}" />
    <input type="hidden" name="category" value="{$category|safehtml}" />
    <fieldset>
        {if $address.cat_id}
        {category_path id=$address.cat_id field="display_name" assign="category_name"}
        {/if}
        {if $address.prefix && $preferences.use_prefix==1}
        {category_path id=$address.prefix field="display_name" assign="prefix_name"}
        {/if}
        <legend>{if isset($category_name)}{gt text="Category" domain="module_addressbook"}: {$category_name.$lang|safehtml}{/if}</legend>
        <div class="z-clearfix">
            <div class="z-formrow">
                {if $preferences.use_prefix==1 && $prefix_name}
                <span class="z-formlist">{$prefix_name.$lang|safehtml}</span>
                {/if}
                <label>{gt text="Name" domain="module_addressbook"}:</label>
                <span><strong>
                    {if $address.fname && $address.lname}
                    {$address.fname|safehtml} {$address.lname|safehtml}
                    {elseif $address.lname}
                    {$address.fname|safehtml}{$address.lname|safehtml}
                    {/if}
                </strong></span>
            </div>

            {if $address.title|safehtml}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Title" domain="module_addressbook"}:</label>
                    <span>{$address.title|safehtml}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            {if $address.company|safehtml}
            <div class="z-formrow">
                {if !$address.lname && !$address.fname}<strong>{/if}
                    <label>{gt text="Company" domain="module_addressbook"}:</label>
                    <span>{$address.company|safehtml}</span>
                {if !$address.lname && !$address.fname}</strong>{/if}
            </div>
            {/if}

            <div class="z-formrow">
                <label>{gt text="Address" domain="module_addressbook"}:</label>
                {if $address.address1}<div class="z-formlist">{$address.address1|safehtml}</div>{/if}
                {if $address.address2}<div class="z-formlist">{$address.address2|safehtml}</div>{/if}
                {if $address.zip || $address.city}<div class="z-formlist">{$address.zip|safehtml} {$address.city|safehtml}</div>{/if}
                {if $address.state}<div class="z-formlist">{$address.state|safehtml}</div>{/if}
                {if $address.country}<div class="z-formlist">{$address.country|safehtml}</div>{/if}
            </div>
            {if $address.img && $preferences.use_img==1}
            <div class="z-formnote">
                {*<a href="{$address.img|addressbook_img:org}" rel="lightbox"><img src="{$address.img|addressbook_img:tmb}" alt="" /></a>*}
                <a href="{$address.img}" rel="lightbox"><img src="{$address.img}" alt="" /></a>
            </div>
            {/if}
        </div>
    </fieldset>

    <fieldset>
        <legend>{gt text="Contact" domain="module_addressbook"}</legend>
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
            <label>{$cusfield.name|safehtml}:</label>
            <span>{$address.$fieldname|safehtml|date_format}</span>
        </div>
        {elseif $cusfield.type=="decimal(10,2) default NULL"}
        <div class="z-formrow">
            <label>{$cusfield.name|safehtml}:</label>
            <span>{$address.$fieldname|safehtml|formatnumber}</span>
        </div>
        {else}
        <div class="z-formrow">
            <label>{$cusfield.name|safehtml}:</label>
            <span class="z-formlist">{$address.$fieldname|safehtml}</span>
        </div>
        {/if}
        {/foreach}
    </fieldset>
    {/if}

    {if $address.note}
    <fieldset class="z-linear">
        <legend>{gt text="Note" domain="module_addressbook"}</legend>
        <div class="z-formrow">{$address.note|safehtml}</div>
    </fieldset>
    {/if}

    {if $address.geodata}
    <fieldset class="z-linear">
        <legend>{gt text="Map" domain="module_addressbook"}</legend>
        <div id="googlemap{$address.id|safehtml}" class="map" style="width: 100%; height: 200px; resize:both;"></div>
        {include file='user_displaymap.tpl'}
    </fieldset>
    {/if}

</div>
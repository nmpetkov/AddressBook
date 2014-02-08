{ajaxheader modname="AddressBook" filename="addressbook.js"}
{include file="admin_menu.tpl"}
<div class="z-admincontainer">
    {if $customfield.id}
    <div class="z-adminpageicon">{img modname='core' src='xedit.png' set='icons/large' alt=''}</div>
    {else}
    <div class="z-adminpageicon">{img modname='core' src='filenew.png' set='icons/large' alt=''}</div>
    {/if}
    <h2>{gt text="Custom fields"}</h2>

    <form class="z-form" action="{modurl modname="AddressBook" type="admin" func="update"}" method="post" enctype="application/x-www-form-urlencoded">
        <div>
            {if $customfield.id}
            <input id="customfield_id" name="customfield[id]" value="{$customfield.id|safehtml}" type="hidden" />
            {/if}
            <input type="hidden" name="csrftoken" value="{insert name='csrftoken'}" />
            <input type="hidden" name="ot" value="customfield" />
            {if $customfield.id}
            <input type="hidden" id="customfield_position" name="customfield[position]" value="{$customfield.position|safehtml}" />
            {else}
            <input type="hidden" id="customfield_position" name="customfield[position]" value="{$new_position|safehtml}" />
            {/if}
            <fieldset>
                {if $customfield.id}
                <legend>{gt text="Edit field"}</legend>
                {else}
                <legend>{gt text="Add field"}</legend>
                {/if}
                <div class="z-formrow">
                    <label for="customfield_name">{gt text="Field name"}</label>
                    <input id="customfield_name" name="customfield[name]" value="{if $customfield.id}{$customfield.name|safehtml}{/if}" type="text" size="60" maxlength="80" />
                </div>
                <div class="z-formrow">
                    {ab_getdatatypes assign="ab_datatype"}
                    <label for="customfield_type">{gt text="Data Type"}</label>
                    <select id="customfield_type" name="customfield[type]" onchange="toggleoption();">
                        {foreach from=$ab_datatype item=data}
                        <option value="{$data.type|safehtml}" {if $customfield.id && $data.type==$customfield.type}selected="selected"{/if}>{$data.dspname|safehtml}</option>
                        {/foreach}
                    </select>
                </div>
                <div id="custom_option" style="display:none;">
                    <div class="z-formrow">
                        <label for="customfield_option">{gt text="Items (comma-separated values)"}</label>
                        <textarea id="customfield_option" name="customfield[option]" cols="40" rows="4">{if $customfield.id}{$customfield.option|safehtml}{/if}</textarea>
                    </div>
                </div>
            </fieldset>
            <div class="z-formbuttons">
                {button src='button_ok.png' set='icons/small' __alt="Create" __title="Create"}
                <a href="{modurl modname=AddressBook type=admin func=view ot=customfield}">{img modname='core' src='button_cancel.png' set='icons/small'   __alt="Cancel" __title="Cancel"}</a>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        Event.observe(window, 'load', dropdown_check_init, false);

        function dropdown_check_init()
        {
            Event.observe('customfield_type', 'change', dropdown_onchange, false);
            if ( $('customfield_type').value == "dropdown") {
                $('custom_option').show();
            }
        }

        function dropdown_onchange()
        {
            if ( $('customfield_type').value == "dropdown") {
                Effect.BlindDown('custom_option');
            } else {
                Effect.BlindUp('custom_option');
            }
        }
    </script>
</div>

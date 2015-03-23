{gt text='Address book' assign=templatetitle}
{pagesetvar name='title' value=$templatetitle}
{insert name='getstatusmsg'}

{if $modvars.AddressBook.enablecategorization}
<h2>{$templatetitle}</h2>
<p>{gt text='Categories:'}</p>
{foreach from=$propertiesdata item='property'}
<ul>
    {foreach from=$property.subcategories item='category'}
    {array_field assign='categoryname' array=$category.display_name field=$modvars.ZConfig.language_i18n}
    {if $categoryname eq ''}{assign var='categoryname' value=$category.name}{/if}
    {array_field assign="categorydesc" array=$category.display_desc field=$modvars.ZConfig.language_i18n}
    <li><a href="{modurl modname='AddressBook' type='user' func='view' prop=$property.name category=$category.id}" title="{$categorydesc}">{$categoryname}</a></li>
    {/foreach}
</ul>
{/foreach}
{else}
{modfunc modname='AddressBook' type='user' func='view'}
{/if}
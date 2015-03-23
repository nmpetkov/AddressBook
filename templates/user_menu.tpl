{pagesetvar name='title' value=$templatetitle}
{checkpermission component='AddressBook::' instance='::' level='ACCESS_ADD' assign='addAuth'}
{assign_concat name='prefkey' 1='abtitle_' 2=$lang}
{formutil_getpassedvalue key="func" assign="func"}

{moduleheader modname='AddressBook' type='user' title=$preferences.$prefkey setpagetitle=true insertstatusmsg=true}

{if isset($user_id) && $user_id && $func neq 'edit'}
    <script type="text/javascript">
        //<![CDATA[
        // store favorite state (used to toggle state)
        var AddressBook_favState = {{$isFavourite}};
        attrOnClick = "javascript: AddressBook_toggleFavourite({{$address.id|safehtml}},{{$user_id|safehtml}});";
        aLiAdd = document.getElementById("adr_fav_add");
        aElementAdd = aLiAdd.getElementsByTagName("*");
        aElementAdd[0].setAttribute('onclick', attrOnClick);
        aLiRemove = document.getElementById("adr_fav_remove");
        aElementRemove = aLiRemove.getElementsByTagName("*");
        aElementRemove[0].setAttribute('onclick', attrOnClick);
        if (AddressBook_favState) {
            aLiAdd.style.display = 'none';
        } else {
            aLiRemove.style.display = 'none';
        }
        //]]>
    </script>
    {if $templatetitle}
        {if $ot=="favourite"}
            <h3>{$templatetitle} - {gt text="Favourites"}</h3>
        {/if}
    {/if}
{/if}

{img id="ajax_indicator" style="display: none;" modname='core' set='ajax' src="indicator_circle.gif" alt=""}

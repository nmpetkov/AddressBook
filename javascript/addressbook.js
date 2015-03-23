Event.observe(window, 'load', addressbook_texpand_init, false);

function addressbook_texpand_init()
{
    $$('.z_texpand').each(function(el){
      new Texpand(el, {autoShrink: true, shrinkOnBlur:false, expandOnFocus: false, expandOnLoad: false });
    });
}

function get_geodata() {
    var params = '';
    params += '&val_1=' + encodeURIComponent(document.getElementById('address_address1').value);
    params += '&val_2=' + encodeURIComponent(document.getElementById('address_zip').value);
    params += '&val_3=' + encodeURIComponent(document.getElementById('address_city').value);
    params += '&val_4=' + encodeURIComponent(document.getElementById('address_country').value);
    var pars = "module=AddressBook&type=ajax&func=get_geodata" + params;
    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php", {
        method : 'post',
        parameters : pars,
        onComplete : get_geodata_response
    });
}

function get_geodata_response(req) {

    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();

    if (data.lat_lon) {
        document.getElementById('address_geodata').value = data.lat_lon;
    }
}

function AddressBook_toggleFavourite(objectid, userid) {
    if (AddressBook_favState) {
        var pars = "module=AddressBook&type=ajax&func=deletefavourite&objectid=" + objectid + "&userid=" + userid;
    } else {
        var pars = "module=AddressBook&type=ajax&func=addfavourite&objectid=" + objectid + "&userid=" + userid;
    }
    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php", {
        method : 'post',
        parameters : pars,
        onComplete : AddressBook_toggleFavourite_response
    });
}

function AddressBook_toggleFavourite_response(req) {
    if (req.status != 200) {
        showajaxerror(req.responseText);
        return;
    }
    aElement = document.getElementById("adr_fav");
    if (AddressBook_favState) {
        AddressBook_favState = 0;
        document.getElementById("adr_fav_add").style.display = 'inline';
        document.getElementById("adr_fav_remove").style.display = 'none';
    } else {
        AddressBook_favState = 1;
        document.getElementById("adr_fav_add").style.display = 'none';
        document.getElementById("adr_fav_remove").style.display = 'inline';
    }
}

function customfieldinit() {

    Sortable.create("cf_list", {
        only : 'z-sortable',
        tree : true,
        constraint : false,
        onUpdate : cforderchanged
    });

    $A(document.getElementsByClassName('z-sortable')).each(function(node) {
        node.setStyle( {
            'cursor' : 'move'
        });
    });
}

function cforderchanged() {
    var pars = "module=AddressBook&type=ajax&func=change_cf_order&"
            + Sortable.serialize("cf_list");
    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php", {
        method : 'post',
        parameters : pars,
        onComplete : cforderchanged_response
    });
}

function cforderchanged_response(req) {
    if (req.status != 200) {
        showajaxerror(req.responseText);
        return;
    }

    pnrecolor('cf_list', 'cf_list_header');
}

function toggleoption() {
    if ($('customfield[type]').value == "dropdown")
        $('custom_option').appear();
    else
        $('custom_option').fade();
}

/**
 * Toggle active/inactive status
 */
function setstatus(id, status)
{
    ajaxindicator = document.getElementById("statusajaxind_"+id);
    ajaxindicator.style.display = "inline";

    var pars = {id: id, status: status};
    new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php?module=AddressBook&type=ajax&func=setstatus",
        {parameters: pars, onComplete: setstatus_response});
}
function setstatus_response(req)
{
    if (!req.isSuccess()) {
        Zikula.showajaxerror(req.getMessage());
        return;
    }
    var data = req.getData();
    
    if (data.alert) {
        alert(data.alert);
    }

    ajaxindicator = document.getElementById("statusajaxind_"+data.id);
    ajaxindicator.style.display = "none";

    elementActive = document.getElementById("statusactive_"+data.id);
    elementInactive = document.getElementById("statusinactive_"+data.id);
    if (elementActive && elementInactive) {
        if (data.status == 1) {
            elementActive.style.display = "block";
            elementInactive.style.display = "none";
        } else {
            elementActive.style.display = "none";
            elementInactive.style.display = "block";
        }
    }
}
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

    if (req.status != 200) {
        showajaxerror(req.responseText);
        return;
    }
    var json = Zikula.dejsonize(req.responseText);
    document.getElementById('address_geodata').value = json.data;
}

function add_fav(objectid, userid) {
    var pars = "module=AddressBook&type=ajax&func=addfavourite&objectid=" + objectid
            + "&userid=" + userid;
    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php", {
        method : 'post',
        parameters : pars,
        onComplete : add_fav_response
    });
}

function del_fav(objectid, userid) {
    var pars = "module=AddressBook&type=ajax&func=deletefavourite&objectid=" + objectid
            + "&userid=" + userid;
    var myAjax = new Zikula.Ajax.Request(Zikula.Config.baseURL+"ajax.php", {
        method : 'post',
        parameters : pars,
        onComplete : del_fav_response
    });
}

function add_fav_response(req) {
    if (req.status != 200) {
        showajaxerror(req.responseText);
        return;
    }
    Element.hide('fav', 'inline');
    Element.show('nofav', 'inline');
}

function del_fav_response(req) {
    if (req.status != 200) {
        showajaxerror(req.responseText);
        return;
    }
    Element.show('fav', 'inline');
    Element.hide('nofav', 'inline');
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

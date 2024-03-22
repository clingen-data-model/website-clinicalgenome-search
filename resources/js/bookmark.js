/**
**
**		Globals
**
*/

var $table = $('#table');
var selected_name = "";
var selected_uuid = "";

var originalurl = window.location.search;

$(function() {
    /**
     * Update table with  parameters
     */
    window.update_addr = function ()
    {
        var current = originalurl;

        const parms = new URLSearchParams(current);

        // set page size
        //var size = parms.get('size');

        // set Search
        var search = parms.get('search');
        if (parms.get('col_search') == "" || (parms.get('col_search') != "" && search != ""))
        {
            // because of where the dosage search is located, inputs collide
            var tok = $('#dosage-search-form input[name="_token"]').val();
            var typ = $('#dosage-search-form input[name="type"]').val();
            var teg = $('#dosage-search-form input[name="region"]').val();
            $table.bootstrapTable('resetSearch', search);
            $('#dosage-search-form input[name="_token"]').val(tok);
            $('#dosage-search-form input[name="type"]').val(typ);
            $('#dosage-search-form input[name="region"]').val(teg);
        }

        // set column sort and order
        var sort = parms.get('sort');
        var order = parms.get('order');

        // once for asc
        $("th[data-field='" + sort + "'] .sortable").click();

        // again for desc
        if (order == "desc")
            $("th[data-field='" + sort + "'] .sortable").click();

        // set page-list
        //var target = $('.page-list').find('a:contains("25")');
        //var target = $('.page-list').find('button').first();
        // set page
        var page = parms.get('page');
        $table.bootstrapTable('selectPage', parseInt(page))

    }


    /**
     * Update individual query parameter
     *
     * @param {*} field
     * @param {*} value
     */
    function set_addr(field, value)
    {
        var current = window.location.search;

        var parms = new URLSearchParams(current);

        parms.set(field, value);

        //mostly used by the search results page to preserve terms across reloads
        var a = $('.action-get-type').val();
        if (a !== undefined && a != "")
            parms.set('type', a);
        var b = $('.action-get-region').val();
        if (b !== undefined && b != "")
            parms.set('region', b);

        var newurl = parms.toString();

        window.history.replaceState('', 'ClinGen Curated Genes',
            window.location.origin + window.location.pathname + '?'
             + newurl);
    }


    /*
    **  Track the selected preference
    */
    $('#modalBookmark').on('click', '.bookmark-select-preference', function() {

        selected_uuid = $(this).attr('data-uuid');

        selected_name = $(this).attr('data-name');

        $('#bookmark-selected-preference').val(selected_name);
    });


    /*
    **  Trigger a menu reload on login
    */
    $('#preferences-menu').on('login', function() {

        uuid = 0;

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + uuid;

        //submits to the form's action URL
        $.ajax({
            type: "PUT",
            url: url,
            data: {_method: 'put', _token : window.token, ident: uuid, screen: window.scrid }
        }).done(function(response)
        {

            $('#preferences-menu').empty();

            Object.entries(response.list).forEach(item =>
            {
                const element = item[1];
                if (element.default == 1)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk"></i>  ' + element.name + '</a></li>');
                }
                else
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk fa-blank"></i>  ' + element.name + '</a></li>');
                }
            });
        });
    });


    /*
    **  Choose an action
    */
    $('.bookmark-modal-select').on('click', function() {

        var action = $(this).attr('data-action');

        if (action == null)
        {
            swal({
                title: "Error",
                text: "Please select an action first.",
                icon: "error",
            });

            return;
        }

        switch (action)
        {
            case 'remove':
                $('#button-selected-action').html('Delete');
                //$('.action-remove-bookmark').trigger('go');
                return;
            case 'select':
                $('#button-selected-action').html('Select');
                return;
            case 'default':
                $('#button-selected-action').html('Make Default');
                return;
            case 'update':
                $('#button-selected-action').html('Update');
                return;

        }
    });


    /*
    **  Perform Action
    */
    $('.bookmark-action-go').on('click', function() {

        var action = $('#button-selected-action').html();

        switch (action)
        {
            case 'Delete':
                $('.action-remove-bookmark').trigger('go');
                return;
            case 'Select':
                $('.action-restore-bookmark').trigger('go');
                return;
            case 'Make Default':
                $('.action-default-bookmark').trigger('go');
                return;
            case 'Update':
                $('.action-update-bookmark').trigger('go');
                return;
            default:
                swal({
                    title: "Error",
                    text: "Please select an action first.",
                    icon: "error",
                });

        }
    });


    /*
    **  Remove a boookmark
    */
    $('.action-remove-bookmark').on('go', function() {

        if (selected_uuid == "")
        {
            swal({
                title: "Error",
                text: "Please select a preference first.",
                icon: "error",
            });

            return;
        }

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + selected_uuid;

        //submits to the form's action URL
        $.ajax({
            type: "DELETE",
            url: url,
            data: {_method: 'delete', _token : window.token, ident: selected_uuid }
        }).done(function(response)
        {
            var current = $('#modal-current-bookmark').html();

            if (selected_name == current)
                $('#modal-current-bookmark').html('');

            selected_uuid = "";

            $('#preferences-menu').empty();

            Object.entries(response.list).forEach(item =>
            {
                const element = item[1];
                if (element.default == 1)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk"></i>  ' + element.name + '</a></li>');
                }
                else if (element.ident == response.new)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-check"></i>  ' + element.name + '</a></li>');
                }
                else
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk fa-blank"></i>  ' + element.name + '</a></li>');
                }
            });

            $('#bookmark-selected-preference').val('');

            $('#button-selected-action').html('Action');

            $('#modal-bookmark-status').html('Preference "' + selected_name + '" removed.');

            selected_name = "";

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while deleting the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });

    });


    /*
    **  Set bookmark as page default
    */
    $('.action-default-bookmark').on('go', function() {

        if (selected_uuid == "")
        {
            swal({
                title: "Error",
                text: "Please select a preference first.",
                icon: "error",
            });

            return;
        }

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + selected_uuid;

        //submits to the form's action URL
        $.ajax({
        type: "PUT",
        url: url,
        data: {_method: 'put', _token : window.token, ident: selected_uuid, name: selected_name, screen: window.scrid, default: 1 }
        }).done(function(response)
        {
            var current = $('#modal-current-bookmark').html();

            selected_uuid = "";

            $('#preferences-menu').empty();

            Object.entries(response.list).forEach(item =>
            {
                const element = item[1];
                if (element.default == 1)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk"></i>  ' + element.name + '</a></li>');
                }
                else if (element.name == current)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-check"></i>  ' + element.name + '</a></li>');
                }
                else
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk fa-blank"></i>  ' + element.name + '</a></li>');
                }
            });

            $('#bookmark-selected-preference').val('');

            $('#button-selected-action').html('Action');

            $('#modal-bookmark-status').html('Preference "' + selected_name + '" is now the default.');

            selected_name = "";

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /*
    **  Save a new bookmark
    */
    $('.action-save-bookmark').on('click', function() {

        var uuid = 0;

        var name = $('#modal-new-bookmark').val();

        name = name.trim();

        if (name == "")
        {
            swal({
                title: "Error",
                text: "Please enter a name",
                icon: "error",
            });

            return;
        }

        // check if name already used
        $('#preferences-menu li').each(function(i)
        {
            var t = $(this).find('a').attr('data-name');
            if (t == name)
            {
                swal({
                    title: "Error",
                    text: "Duplicate name, please choose a unique name.",
                    icon: "error",
                });

                return;
            }
        });

        var settings = window.location.href;

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + uuid;

        //submits to the form's action URL
        $.ajax({
            type: "PUT",
            url: url,
            data: {_method: 'put', _token : window.token, ident: uuid, name: name, screen: window.scrid, settings: settings }
        }).done(function(response)
        {
            // make this the current bookmark
            $('#modal-current-bookmark').html(name);

            $('#preferences-menu').empty();

            Object.entries(response.list).forEach(item =>
            {
                const element = item[1];
                if (element.default == 1)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk"></i>  ' + element.name + '</a></li>');
                }
                else if (element.ident == response.new)
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-check"></i>  ' + element.name + '</a></li>');
                }
                else
                {
                    $("#preferences-menu").append('<li ><a href="#" data-uuid="' + element.ident + '" data-name="' + element.name + '" class="bookmark-select-preference"><i class="fas fa-asterisk fa-blank"></i>  ' + element.name + '</a></li>');
                }
            });

            $('#modal-new-bookmark').val('');

            $('#modal-bookmark-status').html('Preference "' + name  + '" is now the current selected preference.');

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /*
    **  Update an existing bookmark
    */
    $('.action-update-bookmark').on('go', function() {

        if (selected_uuid == "")
        {
            swal({
                title: "Error",
                text: "Please select a preference first.",
                icon: "error",
            });

            return;
        }

        var settings = window.location.href;

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + selected_uuid;

        //submits to the form's action URL
        $.ajax({
            type: "PUT",
            url: url,
            data: {_method: 'put', _token : window.token, ident: selected_uuid, name: selected_name, screen: window.scrid, settings: settings }
        }).done(function(response)
        {
            selected_uuid = "";

            $('#bookmark-selected-preference').val('');

            $('#button-selected-action').html('Action');

            $('#modal-bookmark-status').html('Preference "' + selected_name + '" has been updated.');

            selected_name = "";

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /*
    **  Retrieve and apply a bookmark
    */
    $('.action-restore-bookmark').on('go', function() {

        if (selected_uuid == "")
        {
            swal({
                title: "Error",
                text: "Please select a preference first.",
                icon: "error",
            });

            return;
        }

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('clingen_dash_token')
            }
        });

        var url = "/api/filters/" + selected_uuid;

        //submits to the form's action URL
        $.ajax({
        type: "GET",
        url: url,
        data: {_method: 'get', _token : window.token, ident: selected_uuid }
        }).done(function(response)
        {
            selected_uuid = "";
            selected_name = "";

            var url = window.location.origin + window.location.pathname + '?';

            for (const property in response.data.settings)
            {
                url = url + property + '=' + response.data.settings[property] + '&';
            }

            window.location.href = url;

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while updating the bookmark.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /**
     * Event handler when the user changes the page size or page number
     */
    $table.on('page-change.bs.table', function (e, pagenum, pagesize) {
        set_addr("page", pagenum);
        set_addr("size", pagesize);
    })


    /**
     * Event handler when user selects new column or changes sort order
     */
    $table.on('sort.bs.table', function (e, name, order) {
        set_addr("sort", name);
        set_addr("order", order);
    })


    /**
     * Even handler any time the search field is changed
     */
    $table.on('search.bs.table', function (e, text) {
        set_addr("search", text);
    })

});

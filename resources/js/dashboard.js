$(function() {

    var $table = $('#follow-table');
    var $reporttable = $('#table');
    var $gencontable = $('#gencon-table');

    /**
     * Choose a date for report start
     */
    $('#startdate').datepicker({
        uiLibrary: 'bootstrap',
        disableDates:  function (date) {
            // nothing older that 04-07-2021
            const mindate = new Date(2021, 3, 7);
            return date > mindate ? true : false;
        }
    });


    /**
     * Choose a date for report stop
     */
    $('#stopdate').datepicker({
        uiLibrary: 'bootstrap',
        disableDates:  function (date) {
            // nothing older that 04-07-2021
            const mindate = new Date(2021, 3, 7);
            return date > mindate ? true : false;
        }
    });


    /**
     * On logout from dashboard page, send to dead dashboard view.
     */
    $( "#dashboard-logout" ).on( "login", function( event, param1, param2 ) {

          window.location.reload();

    });


    /**
     * Show screen to follow a new gene
     */
    $('.action-new-region').on('click', function() {

        $('#search_region_form')[0].reset();
        $('#modalSearchRegion').modal('show');

    });


    /**
     * Show screen to follow a new gene
     */
     $('.action-new-gene').on('click', function() {

        $('#search_form')[0].reset();
        $('#modalSearchGene').modal('show');

    });


    /**
     * Chow screen to create a new report
     */
    $('.action-new-report').on('click', function() {

        $('#report-form')[0].reset();
        $('.action-select-text').html('GRCh37');
        $('#select-gchr').val('GRCh38');

        // deal with hidden fields
        $("#report_form input[name=ident]").val('');

        // clear the gene selector
        myselect.tagsinput('removeAll');

        $('#modalReport').modal('show');

    });


    /**
     * Edit an existing report
     */
    $('#report-view').on('click', '.action-edit-report', function() {

        var uuid = $(this).attr('data-uuid');

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

        var url = "/api/reports/" + uuid;

        $('#report-form')[0].reset();

        // deal with hidden fields
        $("#report_form input[name=ident]").val('');

        // clear the gene selector
        myselect.tagsinput('removeAll');

        //submits to the form's action URL
        $.get(url, function(response)
        {
            $('#edit-report-title').html('Edit User Report');
            $('#report-form').find("[name='title']").val(response.fields.title);
            $('#report-form').find("[name='description']").val(response.fields.description);
            $('#report-form').find("[name='startdate']").val(response.fields.startdate);
            $('#report-form').find("[name='stopdate']").val(response.fields.stopdate);
            $('#report-form').find("[name='ident']").val(uuid);
            $('#report-form').find("[name='regions']").val(response.fields.regions);
            $('#report-form').find("[name='type']").val(response.fields.type);
            $('.action-select-text').html(response.fields.type);

            //console.log(response.fields.genes);
            response.fields.genes.forEach(function(element) {
                myselect.tagsinput('add', { "hgncid": element.hgnc_id , "short": element.name });
            });

            $('#modalReport').modal('show');


        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while retrieving report.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /**
     * Region selector for new user report
     */
    $('#report_modal').on('click', '.action-select-grch', function () {

        var uuid = $(this).attr('data-uuid');

        $('.action-select-text').html(uuid);
        $('#select-gchr').val(uuid);
    });


    /**
     * Share a report (future feature)
     */
    $('#report-view').on('click', '.action-share-report', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').attr('data-index');

        var obj = $(this);

        swal("The ability to share a report with others is scheduled to be released later this year.  Thank-you for your patience.")

    });


    /**
     * Unlock a report
     */
    $('#report-view').on('click', '.action-unlock-report', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').attr('data-index');

        var obj = $(this);

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

        var url = "/api/reports/unlock";

        //submits to the form's action URL
        $.post(url, { id: uuid, _token: "{{ csrf_token() }}" }, function(response)
        {
            obj.removeClass('action-unlock-report').addClass('action-lock-report').html('<i class="fas fa-unlock" style="color:lightgray"></i>');
            obj.attr('title', "Lock Report");


        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while unlocking the report.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });



    /**
     * Lock a report
     */
    $('#report-view').on('click', '.action-lock-report', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').attr('data-index');

        var obj = $(this);

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

        var url = "/api/reports/lock";

        //submits to the form's action URL
        $.post(url, { id: uuid, _token: "{{ csrf_token() }}" }, function(response)
        {
            obj.removeClass('action-lock-report').addClass('action-unlock-report').html('<i class="fas fa-lock" style="color:red"></i>');
            obj.attr('title', "Unlock Report");

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while locking the report.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /**
     * Delete a report
     */
    $('#report-view').on('click', '.action-remove-report', function() {

        var uuid = $(this).attr('data-uuid');

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

        swal({
            title: "Are you sure?",
            text: "You will not be able to restore.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((yes) => {
                if (yes) {

                    var url = "/api/reports/remove";

                    //submits to the form's action URL
                    $.post(url, { id: uuid, _token: "{{ csrf_token() }}" }, function(response)
                    {
                        $reporttable.bootstrapTable('remove', {
                            field: 'ident',
                            values: uuid
                        });

                        var len = $reporttable.bootstrapTable('getData').length;

                        $('#custom-report-count').html(len);
                    }).fail(function(response)
                    {
                        swal({
                            title: "Error",
                            text: "An error occurred while unlocking the report.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                            icon: "error",
                        });
                    });
                }
        });

    });


    /**
     * Toggle global notifications
     */
    $('.action-toggle-notifications').on('click', function() {

        var tog;

        if ($(this).hasClass('fa-toggle-off'))
        {
            $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
            $('.action-toggle-notifications-text').html('On');
            $('.action-light-notification').addClass('fa-lightbulb');
            tog = 1;
        }
        else
        {
            $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
            $('.action-toggle-notifications-text').html('Off');
            $('.action-light-notification').removeClass('fa-lightbulb')
            tog = 0;
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

        var url = "/api/home/toggle";

        //submits to the form's action URL
        $.post(url, { value: tog, _token: "{{ csrf_token() }}" }, function(response)
        {

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while setting notifications.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });

    });


    /**
     * Toggle global notifications
     */
    $('.action-toggle-pause').on('click', function() {

        var tog;

        if ($(this).hasClass('fa-toggle-off'))
        {
            $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
            //$('.action-toggle-notifications-text').html('On');
            $('.action-pause-notification').addClass('fa-pause');
            tog = 1;
        }
        else
        {
            $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
            //$('.action-toggle-notifications-text').html('Off');
            $('.action-pause-notification').removeClass('fa-pause')
            tog = 0;
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

        var url = "/api/home/toggle-pause";

        //submits to the form's action URL
        $.post(url, { value: tog, _token: "{{ csrf_token() }}" }, function(response)
        {

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while pausing notifications.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });

    });


    /**
     * Show the profile modal
     */
    $('.action-edit-profile').on('click', function() {

        $('#modalProfile').modal('show');

    });


    /**
     * Select a report folder
     */
    $('.action-select-folder').on('click', function() {

        // what folder did they click on?
        var type = $(this).find('.caption').attr('data-type');

        // deselect previous
        $('.action-select-folder').removeClass('border');

        // show current selection
        $(this).addClass('border');

        //reload the table
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

        var url = "/api/home/reports/" + type;

        //submits to the form's action URL
        $.get(url, function(response)
        {
            //console.log(response.data);
            $('#table').bootstrapTable('load', response.data);
            $('#table').bootstrapTable("resetSearch","");

            if (type == 1)
            {
                $('#report-toolbar').html('Unless locked <i class="fas fa-lock" style="color:red"></i>, Notifications are deleted after 30 days');
            }
            else
            {
                $('#report-toolbar').html('');
            }

        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while selecting a folder.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    });


    /**
     * Show the settings profile from the indicator
     */
    $('.action-edit-settings').on('click', function() {

        $('#settings-tabs-global').trigger('click');
        $('#modalSettings').modal('show');

    });


    /**
     * Expand the Follow section
     */
    $('#collapseFollow').on('shown.bs.collapse', function () {
        $('#collapseFollowIcon').addClass('fa-minus-square').removeClass('fa-plus-square');
    });


    /**
     * Collapse the Follow sectio
     */
    $('#collapseFollow').on('hidden.bs.collapse', function () {
        $('#collapseFollowIcon').addClass('fa-plus-square').removeClass('fa-minus-square');
    });


    /**
     * Expand the reports section
     */
    $('#collapseReports').on('shown.bs.collapse', function () {
        $('#collapseReportsIcon').addClass('fa-minus-square').removeClass('fa-plus-square');
    });


    /**
     * Collapse the reports section
     */
    $('#collapseReports').on('hidden.bs.collapse', function () {
        $('#collapseReportsIcon').addClass('fa-plus-square').removeClass('fa-minus-square');
    });


    /**
     * Unfollow a gene
     */
    $table.on('click', '.action-follow-gene', function(element) {
        swal({
            title: "Are you sure?",
            text: "Unfollowed genes or groups can always be refollowed later.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((yes) => {
                if (yes) {
                    var hgnc  = $(this).closest('tr').data('hgnc');
                    $('#unfollow-gene-field').val(hgnc);
                    $('#unfollow_form').submit();
                    var row = $(this).closest('tr').find('td:first-child').html();
                    $table.bootstrapTable('remove', {
                        field: 'symbol',
                        values: row
                    });
                    $(":checkbox[value=" + hgnc.substring(1) + "]").prop("checked", false);

                }
        });
    });


    /**
     * Change the notification frequency for a gene or group
     */
    $("body").on('click', '.dropdown-menu li a', function(){
        var parent = $(this).parents("ul").attr('data-parent');

        if (typeof parent != 'undefined')
        {
            var btngrp = $('[data-attachedUl=' + parent + ']');
            var original = btngrp.find('.selection').text();
            btngrp.find('.selection').text($(this).text());

            //var gene = btngrp.closest('tr').find('td:first-child').attr('data-value');
            var gene = btngrp.closest('tr').attr('data-hgnc');

            // save the change
            server_update(gene, original, $(this).text());

        }
        else
        {
            $(this).parents(".btn-group").find('.selection').text($(this).attr('data-value'));
        }
    });


    /**
     * Close the dropdonw adter selectio
     */
    $(document).click(function (event) {
        //hide all our dropdowns
        $('.dropdown-menu[data-parent]').hide();

    });


    /**
     *
     * Send otification changes to the server
     */
    function server_update(gene, oldtype, newtype)
    {
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

        var url = "/api/home/notify";

        //submits to the form's action URL
        $.post(url, { gene: gene, old: oldtype, new: newtype, _token: "{{ csrf_token() }}" }, function(response)
        {
            //alert("OK");
        }).fail(function(response)
        {
            swal({
                title: "Error",
                text: "An error occurred while changing notifications.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                icon: "error",
            });
        });
    }


    $( '#unfollow_form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/api/genes/unfollow";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                var url = "/api/home/follow/reload";

                var gene = response.gene;

                //submits to the form's action URL
                $.get(url, function(response)
                {
                    //console.log(response.data);
                    $('#follow-table').bootstrapTable('load', response.data);
                    $('#follow-table').bootstrapTable("resetSearch","");

                    switch (gene)
                    {
                        case '@AllActionability':
                            $('#modalSettings').find('input[name="actionability_notify"]').prop('checked', false);
                            break;
                        case '@AllValidity':
                            $('#modalSettings').find('input[name="validity_notify"]').prop('checked', false);
                            break;
                        case '@AllDosage':
                            $('#modalSettings').find('input[name="dosage_notify"]').prop('checked', false);
                            break;
                        case '*':
                            $('#modalSettings').find('input[name="allgenes_notify"]').prop('checked', false);
                    }

                    $('#modalSearchGene').modal('hide');

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });
            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while unfollowing the item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

            $('#modalUnFollowGene').modal('hide');
        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });


    $( '#follow_form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/api/genes/follow";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                var url = "/api/home/follow/reload";

                var gene = response.gene;

                //submits to the form's action URL
                $.get(url, function(response)
                {
                    //console.log(response.data);
                    $('#follow-table').bootstrapTable('load', response.data);
                    $('#follow-table').bootstrapTable("resetSearch","");

                    switch (gene)
                    {
                        case '@AllActionability':
                            $('#modalSettings').find('input[name="actionability_notify"]').prop('checked', true);
                            break;
                        case '@AllValidity':
                            $('#modalSettings').find('input[name="validity_notify"]').prop('checked', true);
                            break;
                        case '@AllDosage':
                            $('#modalSettings').find('input[name="dosage_notify"]').prop('checked', true);
                            break;
                        case '*':
                            $('#modalSettings').find('input[name="allgenes_notify"]').prop('checked', true);
                    }

                    $('#modalSearchGene').modal('hide');

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });

            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while following a item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });


    $( '#search_region_form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/api/genes/follow";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                var url = "/api/home/follow/reload";

                var gene = response.gene;

                //submits to the form's action URL
                $.get(url, function(response)
                {
                    //console.log(response.data);
                    $('#follow-table').bootstrapTable('load', response.data);
                    $('#follow-table').bootstrapTable("resetSearch","");

                    switch (gene)
                    {
                        case '@AllActionability':
                            $('#modalSettings').find('input[name="actionability_notify"]').prop('checked', true);
                            break;
                        case '@AllValidity':
                            $('#modalSettings').find('input[name="validity_notify"]').prop('checked', true);
                            break;
                        case '@AllDosage':
                            $('#modalSettings').find('input[name="dosage_notify"]').prop('checked', true);
                            break;
                        case '*':
                            $('#modalSettings').find('input[name="allgenes_notify"]').prop('checked', true);
                    }

                    $('#modalSearchRegion').modal('hide');

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });

            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while following a item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });



    $( '#profile-form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/dashboard/profile";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                ;
            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while changing an item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

            $('#modalProfile').modal('hide');
        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });

    $( '#settings-form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/dashboard/preferences";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                ;
            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while changing an item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

            $('#modalSettings').modal('hide');
        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });


    $( '#report-form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/dashboard/reports";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                // for now, only user folders can be edited
                var url = "/api/home/reports/10";

                //submits to the form's action URL
                $.get(url, function(response)
                {
                    $('#table').bootstrapTable('load', response.data);
                    $('#table').bootstrapTable("resetSearch","");

                    // reset folder count
                    $('#custom-report-count').html(response.data.length);

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });

            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while updating the report.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

            $('#modalReport').modal('hide');
        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });


    // fix for bootstrap 3 limitation of dropdowns within a constrained area
    $(document).on('click', '.native-table [data-toggle="dropdown"]', function () {
        $buttonGroup = $(this).parent();
        if (!$buttonGroup.attr('data-attachedUl')) {
            var ts = +new Date;
            $ul = $(this).siblings('ul');
            $ul.attr('data-parent', ts);
            $buttonGroup.attr('data-attachedUl', ts);
            $(window).resize(function () {
                $ul.css('display', 'none').data('top');
            });
        } else {
            $ul = $('[data-parent=' + $buttonGroup.attr('data-attachedUl') + ']');
        }
        if (!$buttonGroup.hasClass('open')) {
            $ul.css('display', 'none');
            return;
        }

        dropDownFixPosition($(this).parent(), $ul);

        function dropDownFixPosition(button, dropdown) {
            var dropDownTop = button.offset().top + button.outerHeight();
            dropdown.css('top', dropDownTop + "px");
            dropdown.css('left', button.offset().left + "px");
            dropdown.css('position', "absolute");

            dropdown.css('width', dropdown.width());
            dropdown.css('heigt', dropdown.height());
            dropdown.css('display', 'block');
            dropdown.appendTo('body');
        }
    });


    var followterm = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: window.burl,
        wildcard: '%QUERY'
        }
    });

    var followtermGene = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
        url: window.burl,
        wildcard: '%QUERY'
        }
    });

    $('.queryFindGene').typeahead(null,
    {
        name: 'followtermGene',
        display: 'label',
        source: followtermGene,
        limit: Infinity,
        highlight: true,
        hint: false,
        autoselect:true,
    }).bind('typeahead:selected',function(evt,item){
        // here is where we can set the follow and refresh the screen.

        $('#follow-gene-field').val(item.hgncid);
        $('#follow_form').submit();

    });

    var myselect = $('#selected-genes');

    myselect.tagsinput({

        tagClass: function(item) {
            //console.log(item)
            switch (item.curated) {
                case true   : return 'label label-primary';
                case false  : return 'label label-default';
                case 2: return 'label label-danger';
                default: return 'label label-primary';
            }
        },
        itemValue: 'hgncid',
        itemText: 'short',
        typeaheadjs: {
            name: 'followtermGene',
            displayKey: 'short',
            limit: Infinity,
            //valueKey: 'short',
            //value: 'hgncid',
            source: followtermGene
        }
    });


    /*
    **  GeneConnect 
    */

    /**
     * Show screen to add a new gene
     */
    $('.action-gc-gene').on('click', function() {

        $('#gc_search_form')[0].reset();
        $('#modalSearchGenomeConnect').modal('show');

    });


    // gene lookup selector specifically for genomeconnect
    $('.queryFindGenomeConnect').typeahead(null,
        {
            name: 'followtermGene',
            display: 'label',
            source: followtermGene,
            limit: Infinity,
            highlight: true,
            hint: false,
            autoselect:true,
        }).bind('typeahead:selected',function(evt,item){
            // here is where we can set the follow and refresh the screen.
    
            $('#follow-gencon-field').val(item.hgncid);
            $('#follow_gencon_form').submit();
    
        });


        $( '#follow_gencon_form' ).validate( {
            submitHandler: function(form) {
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
    
                var url = "/api/gc/follow";
    
                var formData = $(form).serialize();
    
                //submits to the form's action URL
                $.post(url, formData, function(response)
                {
                    var url = "/api/home/gc/reload";
    
                    var gene = response.gene;
    
                    //submits to the form's action URL
                    $.get(url, function(response)
                    {
                        //console.log(response.data);
                        $('#gencon-table').bootstrapTable('load', response.data);
                        $('#gencon-table').bootstrapTable("resetSearch","");
                        
                        $('#modalSearchGenomeConnect').modal('hide');
    
                    }).fail(function(response)
                    {
                        alert("Error reloading table");
                    });
    
                }).fail(function(response)
                {
                    swal({
                        title: "Error",
                        text: "An error occurred while following a item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                        icon: "error",
                    });
                });
    
            },
            rules: {
                email: {
                    required: true,
                    email: true,
                    maxlength: 80
                }
            },
            messages: {
                email:  {
                    required: "Please enter your email address",
                    email: "Please enter a valid email address",
                    maxlength: "Section names must be less than 80 characters"
                },
            },
            errorElement: 'em',
            errorClass: 'invalid-feedback',
            errorPlacement: function ( error, element ) {
                // Add the `help-block` class to the error element
                error.addClass( "invalid-feedback" );
    
                if ( element.prop( "type" ) === "checkbox" ) {
                    error.insertAfter( element.parent( "label" ) );
                } else {
                    error.insertAfter( element );
                }
            },
            highlight: function ( element, errorClass, validClass ) {
                $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
            },
            unhighlight: function (element, errorClass, validClass) {
                $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
            }
        });


    $( '#unfollow_gencon_form' ).validate( {
        submitHandler: function(form) {
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

            var url = "/api/gc/remove";

            var formData = $(form).serialize();

            //submits to the form's action URL
            $.post(url, formData, function(response)
            {
                /*
                var url = "/api/home/follow/reload";

                var gene = response.gene;

                //submits to the form's action URL
                $.get(url, function(response)
                {
                    //console.log(response.data);
                    $('#follow-table').bootstrapTable('load', response.data);
                    $('#follow-table').bootstrapTable("resetSearch","");

                    switch (gene)
                    {
                        case '@AllActionability':
                            $('#modalSettings').find('input[name="actionability_notify"]').prop('checked', false);
                            break;
                        case '@AllValidity':
                            $('#modalSettings').find('input[name="validity_notify"]').prop('checked', false);
                            break;
                        case '@AllDosage':
                            $('#modalSettings').find('input[name="dosage_notify"]').prop('checked', false);
                            break;
                        case '*':
                            $('#modalSettings').find('input[name="allgenes_notify"]').prop('checked', false);
                    }

                    $('#modalSearchGene').modal('hide');
                    

                }).fail(function(response)
                {
                    alert("Error reloading table");
                });
                */
            }).fail(function(response)
            {
                swal({
                    title: "Error",
                    text: "An error occurred while unfollowing the item.  Please refresh the screen and try again.  If the error persists, contact Supprt.",
                    icon: "error",
                });
            });

            $('#modalUnFollowGenCon').modal('hide');
        },
        rules: {
            email: {
                required: true,
                email: true,
                maxlength: 80
            }
        },
        messages: {
            email:  {
                required: "Please enter your email address",
                email: "Please enter a valid email address",
                maxlength: "Section names must be less than 80 characters"
            },
        },
        errorElement: 'em',
        errorClass: 'invalid-feedback',
        errorPlacement: function ( error, element ) {
            // Add the `help-block` class to the error element
            error.addClass( "invalid-feedback" );

            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.parent( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    });


    /**
     * Remove geneconnect gene
     */
    $gencontable.on('click', '.action-remove-gc', function(element) {
        swal({
            title: "Are you sure?",
            text: "Removed genes can always be readded later.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((yes) => {
                if (yes) {
                    var ident  = $(this).closest('tr').data('uniqueid');
                    $('#unfollow-gencon-field').val(ident);
                    $('#unfollow_gencon_form').submit();
                    var row = $(this).closest('tr').find('td:first-child').html();
                    $gencontable.bootstrapTable('remove', {
                        field: 'symbol',
                        values: row
                    });
                    //$(":checkbox[value=" + hgnc.substring(1) + "]").prop("checked", false);

                }
        });
    });


});

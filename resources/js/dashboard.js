$(function() {
    var $table = $('#follow-table');
    var $reporttable = $('#table');

    $('#startdate').datepicker({
        uiLibrary: 'bootstrap'
    });

    $('#stopdate').datepicker({
        uiLibrary: 'bootstrap'
    });
    
    /* 
    ** If a user logs out on this page, we want them to see the dead view
    */
    $( "#dashboard-logout" ).on( "login", function( event, param1, param2 ) {

          window.location.reload();

    });

    $('.action-new-gene').on('click', function() {

        $('#search_form')[0].reset();
        $('#modalSearchGene').modal('show');

    });

    $('.action-new-report').on('click', function() {

        $('#report-form')[0].reset();

        // deal with hidden fields
        $("#report_form input[name=ident]").val('');

        // clear the gene selector
        myselect.tagsinput('removeAll');

        $('#modalReport').modal('show');

    });


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

        //submits to the form's action URL
        $.get(url, function(response)
        {
            $('#edit-report-title').html('Edit User Report');
            $('#report-form').find("[name='title']").val(response.fields.title);
            $('#report-form').find("[name='description']").val(response.fields.description);
            $('#report-form').find("[name='startdate']").val(response.fields.startdate);
            $('#report-form').find("[name='stopdate']").val(response.fields.stopdate);
            $('#report-form').find("[name='ident']").val(uuid);

            //console.log(response.fields.genes);
            response.fields.genes.forEach(function(element) {
                myselect.tagsinput('add', { "hgncid": element.hgnc_id , "short": element.name });
            });

            $('#modalReport').modal('show');

            
        }).fail(function(response)
        {
            alert("Error following gene");
        });
    });

    
    $('#report-view').on('click', '.action-share-report', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').attr('data-index');

        var obj = $(this);

        swal("The ability to share a report with others is scheduled to be released later this year.  Thank-you for your patience.")

    });

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

            
        }).fail(function(response)
        {
            alert("Error following gene");
        });
    });


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

            
        }).fail(function(response)
        {
            alert("Error following gene");
        });
    });


    $('#report-view').on('click', '.action-remove-report', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').attr('data-index');

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
                        //alert("OK");
                        $reporttable.bootstrapTable('remove', {
                            field: '$index',
                            values: row
                        });

                        var len = $reporttable.bootstrapTable('getData').length;

                        $('#custom-report-count').html(len);
                    }).fail(function(response)
                    {
                        alert("Error following gene");
                    });
                } 
        });

    });


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
            //alert("OK");
        }).fail(function(response)
        {
            alert("Error following gene");
        });

    });


    $('.action-edit-profile').on('click', function() {

        $('#modalProfile').modal('show');

    });


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
        }).fail(function(response)
        {
            alert("Error reloading table");
        });
        //var data = [{ title: "New Title", type: "Notification", display_created: "today", display_last: "yester_day", remove: 1, ident: "12345"}]
        
        //$('#table').bootstrapTable({ data: data });
        //$('#table').bootstrapTable('load', data);
        //clear the search
    });


    $('.action-edit-settings').on('click', function() {

        $('#modalSettings').modal('show');

    });

    $('#collapseFollow').on('shown.bs.collapse', function () {
        $('#collapseFollowIcon').addClass('fa-minus-square').removeClass('fa-plus-square');
    });

    $('#collapseFollow').on('hidden.bs.collapse', function () {
        $('#collapseFollowIcon').addClass('fa-plus-square').removeClass('fa-minus-square');
    });

    $('#collapseReports').on('shown.bs.collapse', function () {
        $('#collapseReportsIcon').addClass('fa-minus-square').removeClass('fa-plus-square');
    });

    $('#collapseReports').on('hidden.bs.collapse', function () {
        $('#collapseReportsIcon').addClass('fa-plus-square').removeClass('fa-minus-square');
    });

    $table.on('click', '.action-follow-gene', function(element) {
        swal({
            title: "Are you sure?",
            text: "You can alway follow again.",
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
                } 
        });
    });


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

    $(document).click(function (event) {
        //hide all our dropdowns
        $('.dropdown-menu[data-parent]').hide();

    });

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
            alert("Error following gene");
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
            //handle failed validation
            alert("Error following gene");
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
            //handle failed validation
            alert("Error following gene");
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
            //alert(JSON.stringify(response));
    
            /*if (response['message'])
            {
                swal("Done!", response['message'], "success")
                    .then((answer2) => {
                        if (answer2){*/
                            //$('.action-follow-gene').find('.fa-star').css('color', 'lightgray');
                        /*}
                });
            }*/
        }).fail(function(response)
        {
            //handle failed validation
            alert("Error following gene");
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
            //alert(JSON.stringify(response));
    
            /*if (response['message'])
            {
                swal("Done!", response['message'], "success")
                    .then((answer2) => {
                        if (answer2){*/
                            //$('.action-follow-gene').find('.fa-star').css('color', 'lightgray');
                        /*}
                });
            }*/
        }).fail(function(response)
        {
            //handle failed validation
            alert("Error following gene");
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
            //$('#report-').bootstrapTable("load", myData);
    
            /*if (response['message'])
            {
                swal("Done!", response['message'], "success")
                    .then((answer2) => {
                        if (answer2){*/
                            //$('.action-follow-gene').find('.fa-star').css('color', 'lightgray');
                        /*}
                });
            }*/

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
        //var data = [{ title: "New Title", type: "Notification", display_created: "today", display_last: "yester_day", remove: 1, ident: "12345"}]
        
        //$('#table').bootstrapTable({ data: data });
        //$('#table').bootstrapTable('load', data);
        //clear the search
        }).fail(function(response)
        {
            //handle failed validation
            alert("Error following gene");
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


    

    // make some mods to the search input field
    var search = $('.fixed-table-toolbar .search input');
    search.attr('placeholder', 'Search in table');

    $( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    //$("button[name='filterControlSwitch']").attr('title', 'Column Search');
    //$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');
});
</script>

<script src="/js/typeahead.js"></script>
<script>
    // suggest query stuff
 /* $( ".typeQueryGene" ).click(function() {
      alert("a");
    $("#navSearchBar").attr("action", "{{ route('genes.find') }}");
    $( ".inputQueryGene" ).show();
    $( ".inputQueryGene .queryGene" ).show();
    $( ".typeQueryLabel").text("Gene");
  });*/

 // $("#navSearchBar").attr("action", "{{ route('gene-search') }}");

  var followterm = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: '{{  url('api/genes/find/%QUERY') }}',
      wildcard: '%QUERY'
    }
  });

  var followtermGene = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: '{{  url('api/genes/find/%QUERY') }}',
      wildcard: '%QUERY'
    }
  });

  $('.queryFindGene').typeahead(null,
  {
    name: 'followtermGene',
    display: 'label',
    source: followtermGene,
    limit: 10,
    highlight: true,
    hint: false,
    autoselect:true,
  }).bind('typeahead:selected',function(evt,item){
    // here is where we can set the follow and refresh the screen.

    $('#follow-gene-field').val(item.hgncid);
    $('#follow_form').submit();
    
  });

  var myselect = $('#selected-genes');

  $(function() {
      //console.log(myselect);
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

    //myselect.tagsinput('add', { "hgncid": 1 , "short": "Amsterdam"   });
  });

  function symbolClass(value, row, index)
  {
      return {
          classes: 'table-symbol'
      }
  }

  function rowAttributes(row, index)
  {
    return {
        'data-hgnc': row.hgnc
    }
  }

  function formatSymbol(value, row, index)
  {	
      return '<a href="/kb/genes/' + row.hgnc + '">' + value + '</a></td>';
  }

@extends('layouts.app')


@section('content-heading')

	<div class="row mb-1">
		@include('dashboard.includes.header', ['active' => 'more'])
	</div>

@endsection

@section('content')
<div class="container">

    <div id="dashboard-logout" class="row justify-content-center">

        <div class="col-md-9 mt-3 pl-0 pr-0 border">
			<div class="mb-2">
                <a class="float-right m-2 collapsed" data-toggle="collapse" href="#collapseReports" role="button" aria-expanded="false" aria-controls="collapseReports">
                    <i class="far fa-plus-square fa-lg" style="color:#ffffff" id="collapseReportsIcon"></i></a>
                <h4 class="m-0 p-2 text-white" style="background:#3c79b6">Reports</h4>
            </div>
        
			@include('dashboard.includes.reports')
			
            <div>
                <a class="float-right m-2" data-toggle="collapse" href="#collapseFollow" role="button" aria-expanded="true" aria-controls="collapseFollow">
					<i class="far fa-minus-square fa-lg" style="color:#ffffff" id="collapseFollowIcon"></i></a>
				<a class="float-right mt-2 mr-4 action-edit-settings" data-toggle="tooltip" title="Global Notifications: On">
					<i class="far {{ $notification->frequency['global'] == "on" ? "fa-lightbulb" : '' }} fa-lg action-light-notification" style="color:#ffffff"></i></a>	
				<h4 class="m-0 p-2 text-white" style="background:#55aa7f">Followed Genes</h4>
            </div>
            
            @include('dashboard.includes.follow')
            
        </div>
        <div class="col-md-3 mt-3">

            @include('dashboard.includes.profile')

        </div>
    </div>
</div>
@endsection

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('modals')

	@include('modals.unfollowgene', ['gene' => ''])
	@include('modals.followgene', ['gene' => ''])
    @include('modals.profile')
    @include('modals.search')
	@include('modals.settings')
	@include('modals.report')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
	<link href="/css/gijgo.min.css" rel="stylesheet">
	<link href="/css/bootstrap-tagsinput.css" rel="stylesheet">

    <style>
        .profile-background
        {
          position: relative;
          top: 0;
          left: 0;
        }
        .avatar
        {
          position: absolute;
          top: 0px;
          left: 12px;
        }
        .avatar-name
        {
          position: absolute;
          top: 148px;
          left: 30px;
          font-size: 18px;
        }
        .avatar-title
        {
          position: absolute;
          top: 172px;
          left: 30px;
          font-size: 14px;
        }
		.size {
			height: 100px;
			width: 100px;
			display: block;
			margin-left: auto;
			margin-right: auto;
			}

		.caption {
			font-size: 14px;
			color: black; 
			text-align: center;
			width: 200px;
		}
		.folder-effects:hover .size {
			opacity: 0.7;
		}
		.selector {
			width: 100%;
		}
		.bootstrap-tagsinput {
 			 width: 100% !important;
		}
    </style>
    
@endsection

@section('script_js')

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/gijgo.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-tagsinput.min.js"></script>
<script src="/js/genetable.js"></script>
<script src="/js/edit.js"></script>

<script>
	
    $(function() {
		var $table = $('#follow-table');
		var $reporttable = $('#table');
    
    
        /*$('.action-logout').on('click', function() {

            $('#frm-logout').submit();

		});*/

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
					'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
					'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
					'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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

                var gene = btngrp.closest('tr').find('td:first-child').attr('data-value');

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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
   				}
			});

			var url = "/api/genes/unfollow";
			
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
   				}
			});

			var url = "/api/genes/follow";
			
			var formData = $(form).serialize();

			//submits to the form's action URL
			$.post(url, formData, function(response)
			{
				window.location.reload(true);
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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
    				'Authorization':'Bearer ' + Cookies.get('laravel_token')
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

        limit: 20,
        minLength: 3,
        highlight: true,
        hint: false,
        autoselect:true,
      }).bind('typeahead:selected',function(evt,item){
		// here is where we can set the follow and refresh the screen.
		console.log(item.hgncid);

		$('#follow-gene-field').val(item.hgncid);
		$('#follow_form').submit();
		
      });

	  var myselect = $('#selected-genes');

	  $(function() {
		  console.log(myselect);
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
    		//valueKey: 'short',
			//value: 'hgncid',
    		source: followtermGene
		  }
		});

		//myselect.tagsinput('add', { "hgncid": 1 , "short": "Amsterdam"   });
	  });

	  function formatSymbol(value, row, index)
	  {
		  //console.log(row._data.hgnc);

		  return '<a href="/kb/genes/' + row._data.hgnc + '">' + value + '</a></td>';
	  }

</script>

@endsection

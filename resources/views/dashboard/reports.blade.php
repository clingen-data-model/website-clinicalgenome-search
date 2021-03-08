@extends('layouts.app')

@section('content-heading')

    @include('dashboard.includes.header', ['active' => 'reports'])

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-3">
            <div class="row">
                <div class="col-sm-12">
                    <p>This is a list of all your reports.</p>
                </div>
            </div>
            <div id="toolbar" class="text-right">
                <button class="btn btn-block action-new-report">Create New Report</button>  
            </div>
            <div class="row mb-3">  
                <div class="col-md-12 native-table">
                    <table class="table" id="table" data-toggle="table"
                                    data-sort-name="symbol"
                                    data-sort-order="asc"
                                    data-locale="en-US"
                                    data-classes="table table-hover"
                                    data-toolbar="#toolbar"
                                    data-toolbar-align="right"
                                    data-addrbar="false"
                                    data-sortable="true"
                                    data-search="true"
                                    data-header-style="background: white;"
                                    data-filter-control="false"
                                    data-filter-control-visible="false"
                                    data-id-table="advancedTable"
                                    data-search-align="left"
                                    data-trim-on-search="false"
                                    data-show-search-clear-button="true"
                                    data-buttons="table_buttons"
                                    data-show-align="left"
                                    data-show-fullscreen="false"
                                    data-show-columns="false"
                                    data-show-columns-toggle-all="false"
                                    data-search-formatter="false"
                                    data-pagination="true"
                                    data-id-field="id"
                                    data-page-list="[10, 25, 50, 100, 250, all]"
                                    data-page-size="25"
                                    data-show-footer="true"
                                    data-side-pagination="client"
                                    data-pagination-v-align="bottom"
                                    data-show-extended-pagination="false"
                                    data-response-handler="responseHandler"
                                    data-header-style="headerStyle"
                                    data-show-filter-control-switch="false"
                                    >
                    <thead>
                        <tr>
                            <th class="col-sm-2" data-field="symbol" data-sortable="true">Title</th>
                            <th class="col-sm-2" data-sortable="true">Type</th>
                            <th class="col-sm-2" data-sortable="true">Created</th>
                            <th class="col-sm-2" data-sortable="true">Last Run</th>
                            <th class="col-sm-3" data-align="center">Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reports as $report)
                        <tr>
                            <td scope="row" class="table-symbol"><a href="{{ route('dashboard-show-report', ['id' => $report->ident]) }}" >{{ $report->title }}</a></td>
                            <td>{{ $report->type }}</td>
                            <td>{{ $report->display_date }}</td>
                            <td>{{ $report->display_last }}</td>
                            <td>
                                <span class="action-remove-report"><i class="fas fa-trash" style="color:red"></i></span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modals')

    @include('modals.unfollowgene', ['gene' => ''])
    @include('modals.search')

@endsection

@section('script_css')
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
    <link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
    
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

<script src="/js/bootstrap-table-filter-control.js"></script>

<script src="/js/genetable.js"></script>

<script>
	
    $(function() {
        var $table = $('#table')
    
        $('.action-logout').on('click', function() {

            $('#frm-logout').submit();

        });

        /*$('.action-new-gene').on('click', function() {

            $('#modalSearchGene').modal('show');

        });
*/
        $table.on('click', '.action-remove-report', function() {

            swal({
                title: "Are you sure?",
                text: "You will not be able to rrestore after deleting.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
                })
                .then((yes) => {
                    if (yes) {
                        var hgnc  = $(this).closest('tr').find('td:nth-child(2)').text();
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

                var gene = btngrp.closest('tr').find('td:first-child').html();

                // save the change
                server_update(gene, original, $(this).text());

                console.log(row);
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
                alert("OK");
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
								$('.action-follow-gene').find('.fa-star').css('color', 'lightgray');
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

</script>

@endsection

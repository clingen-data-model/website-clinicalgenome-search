@extends('layouts.app')

@section('content-heading')

    @include('dashboard.includes.header', ['active' => 'following'])

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mt-3">
            <div class="row">
                <div class="col-sm-12">
                    <p>This section allows you to follow a variety of elements within the ClinGen environmant.
                        You may also override the notification defaults on a per element basis, or even unfollow an element from this screen .</p>
                </div>
            </div>
            <div id="toolbar" class="text-right">
                <button class="btn btn-primary btn-block action-new-gene">Add New Gene To Follow</button>  
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
                            <th class="col-sm-2" data-field="symbol" data-sortable="true">Name</th>
                            <th class="col-sm-2" data-sortable="true">ID</th>
                            <th class="col-sm-3">Curation Status</th>
                            <th class="col-sm-2" data-sortable="true">Last Updated</th>
                            <th class="col-sm-3">Notify</th>
                            <th class="col-sm-3" data-align="center">Unfollow</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($genes as $gene)
                        <tr>
                            <td scope="row" class="table-symbol">{{ $gene->name }}</td>
                            <td>{{ $gene->hgnc_id }}</td>
                            <td>
                                <img src="/images/clinicalValidity-{{ $gene->hasActivity('validity') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/dosageSensitivity-{{ $gene->hasActivity('dosage') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/clinicalActionability-{{ $gene->hasActivity('actionability') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/variantPathogenicity-{{ $gene->hasActivity('varpath') ? 'on' : 'off' }}.png" width="22" height="22">
                                <img src="/images/Pharmacogenomics-{{ $gene->hasActivity('pharma') ? 'on' : 'off' }}.png" width="22" height="22">
                            </td>
                            <td>{{ $gene->displayDate($gene->date_last_curated) }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="text-left btn btn-block btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span class="selection">Daily</span><span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a data-value="Daily">Daily</a></li>
                                        <li><a data-value="Weekly">Weekly</a></li>
                                        <li><a data-value="Monthly">Monthly</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a data-value="Default">Default</a></li>
                                        <li role="separator" class="divider"></li>
                                        <li><a data-value="Pause">Pause</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td>
                                <span class="action-follow-gene"><i class="fas fa-star" style="color:green"></i></span>
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

        $('.action-new-gene').on('click', function() {

            $('#modalSearchGene').modal('show');

        });

        $table.on('click', '.action-follow-gene', function() {

            swal({
                title: "Are you sure?",
                text: "You can alway follow again.",
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
                btngrp.find('.selection').text($(this).text());
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

        $("button[name='filterControlSwitch']").attr('title', 'Column Search');
	    $("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');
    });
    </script>

    <script src="/js/typeahead.js"></script>
    <script>
      $( ".typeQueryGene" ).click(function() {
        $("#navSearchBar").attr("action", "{{ route('genes.find') }}");
        $( ".inputQueryGene" ).show();
        $( ".inputQueryGene .queryGene" ).show();
        $( ".typeQueryLabel").text("Gene");
      });

      var term = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/genes/find/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      var termGene = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/genes/find/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      $('.queryGene').typeahead(null,
      {
        name: 'termGene',
        display: 'label',
        source: termGene,

        limit: 20,
        minLength: 3,
        highlight: true,
        hint: false,
        autoselect:true,
      }).bind('typeahead:selected',function(evt,item){
        // here is where we can set the follow and refresh the screen.
      });

</script>

@endsection

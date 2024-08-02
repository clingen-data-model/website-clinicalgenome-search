@extends('layouts.app')

@section('content-heading')

	@include('gene.includes.follow')

	<div class="row mb-1 mt-1">

		@include('gene.includes.facts')

		<div class="col-md-9 col-xs-3 col-sm-4 mt-2 stats-banner">
			<div class="pb-0 mb-0 small float-right">
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countCurations text-18px">{{ $record->nvalid }}</span><br />Gene-Disease Validity Classifications</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countGenes text-18px">{{ $record->ndosage }}</span><br />Dosage Sensitivity Classifications</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->naction }}</span><br />Clinical Actionability Assertions</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->nvariant }}</span><br />Variant Pathogenicity Assertions</div>
				<div class="text-stats line-tight col-md-2 hidden-sm hidden-xs text-center px-1"><span class="countEps text-18px">{{ $record->ncpc }} / {{ $record->npharmgkb }}</span><br />CPIC / PharmGKB High Level Records</div>
				@if ($follow)
				<div class="text-stats line-tight col-md-2 text-center px-1"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:green"></i></span><br /> Follow Gene</div>
				@else
				<div class="text-stats line-tight col-md-2 text-center px-1"><span class="countEps text-18px action-follow-gene"><i class="fas fa-star" style="color:lightgray"></i></span><br /> Follow Gene</div>
				@endif
			</div>
		</div>

		@include("_partials.facts.gene-panel")

		@if ($show_clingen_comment)
		<div class="col-md-12">
			<h4 class="border-bottom-1">ClinGen Variant Classification Guidance  
				<!--<i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="ClinGen comments are comments from ClinGen."></i>-->
			</h4>
				<p>
					{{ $record->notes }}
				</p>
		</div>
		@endif

	</div>

	<!-- tab headers -->
	<ul class="nav nav-tabs mt-1" style="">
		<li class="active" style="">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class="">
              <span class='hidden-sm hidden-xs'>Curation </span>Summaries
            </a>
        </li>
          <li class="" style="">
            <a href="{{ route('gene-groups', $record->hgnc_id) }}" class="">Status and Future Work <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $total_panels }}</span></a>
          </li>
		  @if ($gc !== null && $gc->variant_count > 0)
		<li class="" style="">
			<a href="{{ route('gene-genomeconnect', $record->hgnc_id) }}" class="">GenomeConnect <span class="border-1 bg-white badge border-primary text-primary px-1 py-1/2 text-10px ">{{ $gc->variant_count }}</span></a>
		</li>
		@endif
		<li class="" style="">
			<a href="{{ route('gene-external', $record->hgnc_id) }}" class=""><span class='hidden-sm hidden-xs'>External Genomic </span>Resources </a>
		</li>
		<li class="" style="">
			<a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D"  class="" target="clinvar">ClinVar <span class='hidden-sm hidden-xs'>Variants  </span><i class="glyphicon glyphicon-new-window small" id="external_clinvar_gene_variants"></i></a>
		</li>
	</ul>

@endsection

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">

			@if((!empty($record->dosage_curation ) && !empty($record->dosage_curation_map)) OR (!empty($record->genetic_conditions))  OR (!empty($record->pharma)) OR (!empty($record->pharmagkb)))
				<div class="btn-group  btn-group-xs float-right" role="group" aria-label="...">
					<a  href="{{ route('gene-show', $record->hgnc_id) }}" class="btn btn-primary active">Group By Activity</a>
					<!-- only show the disease switch if there is a disease related activity -->
					@if((!empty($record->dosage_curation ) && !empty($record->dosage_curation_map)) || (!empty($record->genetic_conditions)) )
					<a  href="{{ route('gene-by-disease', $record->hgnc_id) }}" class="btn btn-default">Group By Gene-Disease Pair</a>
					@endif
				</div>
			@endif

			@php global $currations_set; $currations_set = false; @endphp

			@include('gene.includes.validity')

			@include('gene.includes.dosage')

			@include('gene.includes.actionability')

			@include('gene.includes.variant')

			@include('gene.includes.pharma')

			@include('gene.includes.pharmagkb')


			{{-- Check to see if curations are showing --}}
			@if($currations_set == false)

                @include('gene.includes.not_curated')

                <!--<br clear="both" />
					<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet curated {{ $record->hgnc_id }}.</strong> <br />View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.</div>
                -->

            @endif

@endsection

@section('heading')
<div class="content ">
	<div class="section-heading-content">
	</div>
</div>
@endsection

@section('modals')

	@include('modals.followgene', ['gene' => $record->hgnc_id])
	@include('modals.unfollowgene', ['gene' => $record->hgnc_id])

@endsection

@section('script_js')
<script>
	window.token = "{{ csrf_token() }}";
	window.bearer_token = Cookies.get('clingen_dash_token');
</script>

<script src="/js/jquery.validate.min.js" ></script>
<script src="/js/additional-methods.min.js" ></script>

<script>

$(function() {
	window.auth = {{ Auth::guard('api')->check() ? 1 : 0 }};
	var context = false;
	var gene = "{{ $record->hgnc_id ?? ''}}";

	$('.action-follow-gene').on('click', function() {

		var color = $(this).find('.fa-star').css('color');

		if (color == "rgb(0, 128, 0)"){
			if (window.auth)
			{
				// TODO:  create fake form and post it
				$('#unfollow_form').submit();
				$(this).find('.fa-star').css('color', 'lightgray');
				return;
			}
			$(this).find('.fa-star').css('color', 'lightgray');
		}
		else
		{
			if (window.auth)
			{
				// TODO:  create fake form and post it
				$('#follow_form').submit();
				$(this).find('.fa-star').css('color', 'green');
				return;
			}
			context = true;

			$('#login-context-value').val(gene);
			$('#register-context-value').val(gene);
			$('#follow-gene-id').collapse("show");
		}
	});


	$('.action-follow-cancel').on('click', function() {
		context = false;
		$('#follow-gene-email').val('');
		$('#login-context-value').val('');
		$('#register-context-value').val('');
		$('#follow-gene-id').collapse("hide");
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
				//alert(JSON.stringify(response));

				/*if (response['message'])
				{
					swal("Done!", response['message'], "success")
						.then((answer2) => {
							if (answer2){*/
								$('#follow-gene-id').collapse("hide");
								$('#follow-gene-email').val('');
								$('.action-follow-gene').find('.fa-star').css('color', 'green');

							/*}
					});
				}*/
			}).fail(function(response)
			{
				//handle failed validation
				alert("Error following gene.  Bad email address?");
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

    $('.action-expand-curation').on('click', function() {

        var uuid = $(this).attr('data-uuid');

        var row = $(this).closest('tr').next('tr');
        row.toggle();

        var chk = $(this).find('small');
        if (chk.html() == "show more  ")
            chk.html('show less  ');
        else
            chk.html('show more  ');

        chk = $(this).find('i.fas');
        if (chk.hasClass('fa-caret-down'))
            chk.removeClass('fa-caret-down').addClass('fa-caret-up');
        else
        chk.removeClass('fa-caret-up').addClass('fa-caret-down');
	});

});
</script>

@endsection

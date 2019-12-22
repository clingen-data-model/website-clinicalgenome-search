@extends('layouts.app')

@section('content')
<div id="gene_validity_show" class="container">
	<h2>Gene Validity Classification Summary</h2>
	<div class="row geneValidityScoresWrapper">
		<div class="col-sm-12">
			<div class="content-space content-border">
				{{ $animalmode ?? '' }}
				@if($score_sop == "SOP7")
					@include('validity.sop7')
				@elseif ($score_sop == "SOP6")
					@include('validity.sop6')
				@elseif ($score_sop == "SOP5")
					@include('validity.sop5')
				@elseif ($score_sop == "SOP4")
					@include('validity.sop4')
				@else
					ERROR - NO SOP SET
				@endif

				{{-- @if (!empty($score_string))
					@if ($assertion->jsonMessageVersion == "GCI.6")
						@include('validity.gci6')
					@else
						@include('validity.gci')
					@endif
				@elseif (!empty($score_string_sop5))
					@include('validity.sop5')
				@else
					@include('validity.sop4')
				@endif --}}
			</div>
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

@section('script_js')

@endsection
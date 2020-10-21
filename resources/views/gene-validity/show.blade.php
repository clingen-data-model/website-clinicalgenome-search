@extends('layouts.app')

@section('content')
<div id="gene_validity_show" class="container">
	<h2 class="mb-3">Gene-Disease Validity Classification Summary</h2>
	<div class="row geneValidityScoresWrapper">
		<div class="col-sm-12">
			<div class="content-space content-border">
				{{ $record->animalmode ?? '' }}
				@if(strpos($record->specified_by->label,"SOP7"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop7')
				@elseif (strpos($record->specified_by->label,"SOP6"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop6')
				@elseif (strpos($record->specified_by->label,"SOP5"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop5')
				@elseif (strpos($record->specified_by->label,"SOP4"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop4-legacy')
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

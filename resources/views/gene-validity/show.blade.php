@extends('layouts.app')

@section('content')
<div id="gene_validity_show" class="container">

<div class="row">
	    <div class="col-md-8">
      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/clinicalValidity-on.png" width="40" height="40"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0">  Gene-Disease Validity Classification</h1>
          </td>
        </tr>
      </table>
    </div>

    <div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
            <li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('validity-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Listing</a></li>
					</ul>
				</div>
			</div>
    </div>
</div>


	<div class="row geneValidityScoresWrapper">
		<div class="col-sm-12">
			<div class="content-space content-border">
				{{ $record->animalmode ?? '' }}
				@if($record->json_message_version == "GCI.8.1")
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop8-1')
				@elseif(strpos($record->specified_by->label,"SOP8"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop7')
				@elseif(strpos($record->specified_by->label,"SOP7"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop7')
				@elseif (strpos($record->specified_by->label,"SOP6"))
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop6')
				@elseif (strpos($record->specified_by->label,"SOP5") && $record->origin == true)
					@include('gene-validity.partial.report-heading')
					@include('gene-validity.partial.sop5-legacy')
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

@include('gene-validity.partial.rich_data_table')

@section('heading')
<div class="content ">
    <div class="section-heading-content">
    </div>
</div>
@endsection

@section('script_js')

@endsection

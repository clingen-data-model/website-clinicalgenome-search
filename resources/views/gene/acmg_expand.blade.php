<div class="row mt-2 mb-2">
	<div class="col-md-12">
		@if (empty($gene->notes))
		<span class="text-muted text-center font-italic ml-3">There are no guidance details at this time</span>
		@else
		{{ $gene->notes }}
		@endif
	</div>
</div>
<div class="row mt-0 mb-2">
	<div class="col-md-3">
		<b>Last Curated Date:  </b>{{ $gene->displayDate($gene->date_last_curated) }}
	</div>
	<div class="col-md-9 text-right">
		<span class="text-dark">
			Click on the disease name, or one of the activity classifications, to view more detailed information about the curation.
		</span>
	</div>
</div>
<div class="row border-bottom border-top mt-1 equal">
	<div class="col-md-5 border-right text-center text-bold pb-2 pt-2">ClinGen Curated Disease</div>
	<div class="col-md-1 border-right text-center text-bold pb-2 pt-2">MOI</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/clinicalValidity-on.png" title="Gene-Disease Validity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/dosageSensitivity-on.png" title="Dosage Sensitivity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/clinicalActionability-on.png" title="Clinical Actionability" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/variantPathogenicity-on.png" title="Variant Pathogenicity" style="width:25px">
	</div>
	<div class="col-md-2 text-center text-bold pb-2 pt-2">Reportable as SF</div>
</div>
@foreach ($scores as $score)
	<div class="row border-bottom equal">
		<!-- disease -->
		<div class="col-md-5 border-right pt-2 pb-2"><span onclick="event.stopPropagation();"><a href="/kb/conditions/{{ $score['mondo'] }}" class="text-primary">{{ $score['disease'] }}</a></span><div class="small text-muted">{{ $score['mondo'] }}</div></div>
		<!-- moi -->
		<div class="col-md-1 border-right pt-2 pb-2 text-center">
			<div class="pt-2">{{ $score['validity_moi'] }}
				@if (!empty($score['validity_moi']))
				<span class="cursor-pointer ml-1 mt-4" data-toggle="tooltip" data-placement="top" title="{{ $score['validity_moi'] }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
				@else
				<span class="ml-1 mt-4">&nbsp;</span>
				@endif
			</div>
		</div>
		<!-- Validity -->
		<div class="col-md-1 border-right p-2 text-center">
			@if ($score['validity_score'] !== null)
			<span class="small badge cg-{{ $score['validity_score'] }} mt-2 w-100" data-toggle="tooltip" data-placement="top" title="{{ $score['validity_tooltip'] }}"><a class="text-white" href="{{ $score['validity_link'] }}" target="_gt">{{ $score['validity_score'] }}</a></span>
			@else 
			<span class="small mt-2">&nbsp;</span>
			@endif
		</div>
		<!-- Dosage -->
		<div class="col-md-1 pt-1 pr-2 pl-2 pb-1 text-center">
			@if($score['dosage_haplo_score'] !== null)
				<span class="small badge cg-{{ $score['dosage_haplo_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="{{ $score['dosage_haplo_tooltip'] }}"><a class="text-white" href="{{ $score['dosage_link'] }}" target="_gt">{{ $score['dosage_haplo_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
			<hr class="mt-1 mb-1" />
			@if($score['dosage_triplo_score'] !== null)
				<span class="small badge cg-{{ $score['dosage_triplo_score'] }} w-100"  data-toggle="tooltip" data-placement="top" title="{{ $score['dosage_triplo_tooltip'] }}"><a class="text-white" href="{{ $score['dosage_link'] }}" target="_gt">{{ $score['dosage_triplo_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
		</div>
		<!-- Actionability -->
		<div class="col-md-1 border-right  border-left pt-1 pb-1 pr-2 pl-2 text-center">
			@if($score['has_actionability'])
			@if ($score['actionability_adult_score'] !== null)
			<span class="small badge cg-{{ $score['actionability_adult_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="{{ $score['actionability_adult_tooltip'] }}"><a class="text-white" href="{{ $score['actionability_adult_link'] }}" target="_akb">{{ $score['actionability_adult_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
			<hr class="mt-1 mb-1" />
			@if ($score['actionability_pediatric_score'] !== null)
			<span class="small badge cg-{{ $score['actionability_pediatric_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="{{ $score['actionability_ped_tooltip'] }}"><a class="text-white" href="{{ $score['actionability_pediatric_link'] }}" target="_akb">{{ $score['actionability_pediatric_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
			@else 
			<span class="small">&nbsp;</span>
			<div class="small text-muted">&nbsp;</div>
			@endif
		</div>
		<!-- variant -->
		<div class="col-md-1 border-right pt-1 pb-1 text-center">
			@if($score['variant_link'] !== null)
			<span class=""><a href="{{ $score['variant_link'] }}" target="_erepo">ERepo <i class="fas fa-external-link-alt"></i></a></span>
			<!-- <div class="small text-muted">Report</div> -->
			<span class=" mt-2"><a href="https://cspec.genome.network/cspec/ui/svi/?search={{ $gene->name }}" target="_erepo">CSpec <i class="fas fa-external-link-alt"></i></a></span>
			@else 
			<span class="small">&nbsp;</span>
			<div class="small text-muted">&nbsp;</div>
			@endif
		</div>
		<!-- reportable -->
		<div class="col-md-2 pt-2 pb-2 text-center">
			@switch($score['reportable'])
			@case('Yes')
			<div class="text-success mt-1 pt-2">
				<strong>{{ $score['reportable'] }}</strong>
			</div>
			@break
			@case('No')
			<div class="text-danger mt-1 pt-2">
				<strong>{{ $score['reportable'] }}</strong>
			</div>
			@break
			@case('NA')
			<div class="text-muted mt-1 pt-2">
				<strong>{{ $score['reportable'] }}</strong>
			</div>
			@break
			@case('Pending')
			<div class="text-warning mt-1 pt-2" data-toggle="tooltip" data-placement="top" title="Decision to report this gene-disease relationship as SF under review">
				<strong>{{ $score['reportable'] }}</strong>
			</div>
			@break
			@default
			<div class="mt-1 pt-2">
				<strong>{{ $score['reportable'] }}</strong>
			</div>
			@endswitch
		</div>
	</div>
@endforeach
@if (isset($removed))
<div class="row mt-3 mb-1">
	<div class="col-md-12">
		<span class="text-danger font-weight-bold font-italic mr-2">NOTE: 
			"Reportable as SF" annotations are only provided for gene-disease-MOI triads curated for gene-disease validity. 
			To see a full list of ClinGen curations for this gene, click <a href="/kb/genes/{{ $gene->hgnc_id }}">here</a>
		</span>
	</div>
</div>
@endif 

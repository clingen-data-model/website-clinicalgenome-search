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
	<div class="col-md-6 border-right text-center text-bold pb-2 pt-2">ClinGen Curated Disease</div>
	<div class="col-md-1 border-right text-center text-bold pb-2 pt-2">MOI</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/clinicalValidity-on.png" title="Gene-Disease Validity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2" data-toggle="tooltip" data-placement="top" title="Dosage Sensitivity displays Haploinsufficiency scores in the upper half of the cell, and Triplosensitivity scores in the lower half">
		<img class="" src="/images/dosageSensitivity-on.png" title="Dosage Sensitivity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2" data-toggle="tooltip" data-placement="top" title="Clinical Actionability displays Adult scores in the upper half of the cell, and Pediatric scores in the lower half">
		<img class="" src="/images/clinicalActionability-on.png" title="Clinical Actionability" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/variantPathogenicity-on.png" title="Variant Pathogenicity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1 pt-2">
		<img class="" src="/images/Pharmacogenomics-on.png" title="Pharmacogenomics" style="width:25px">
	</div>
</div>
@foreach ($diseases as $disease)
 @if (!isset($scores[$disease->id]))
	@continue
 @endif
	<div class="row border-bottom equal">
		<!-- disease -->
		<div class="col-md-6 border-right pt-2 pb-2"><span onclick="event.stopPropagation();"><a href="/kb/conditions/{{ $disease->curie }}" class="text-primary">{{ $disease->label }}</a></span><div class="small text-muted">{{ $disease->curie }}</div></div>
		<!-- moi -->
		<div class="col-md-1 border-right pt-2 pb-2 text-center">
			<div class=" pt-2">{{ $scores[$disease->id]['validity_moi'] }}
				@if (!empty($scores[$disease->id]['validity_moi']))
				<span class="cursor-pointer ml-1 mt-4" data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['validity_moi'] }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
				@else
				<span class="ml-1 mt-4">&nbsp;</span>
				@endif
			</div>
		</div>
		<!-- Validity -->
		<div class="col-md-1 border-right p-2 text-center">
			@if ($scores[$disease->id]['validity_score'] !== null)
			<span class="small badge cg-{{ $scores[$disease->id]['validity_score'] }} mt-2 w-100" data-toggle="tooltip" data-placement="top" title="Classification {{ $scores[$disease->id]['validity_tooltip'] }} for Gene-Disease Validity"><a class="text-white" href="{{ $scores[$disease->id]['validity_link'] }}" target="_gt">{{ $scores[$disease->id]['validity_score'] }}</a></span>
			@else 
			<span class="small mt-2">&nbsp;</span>
			@endif
		</div>
		<!-- Dosage -->
		<div class="col-md-1 pt-1 pr-2 pl-2 pb-1 text-center">
			@if($scores[$disease->id]['dosage_haplo_score'] !== null)
				<span class="small badge cg-{{ $scores[$disease->id]['dosage_haplo_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['dosage_haplo_tooltip'] }}"><a class="text-white" href="{{ $scores[$disease->id]['dosage_link'] }}" target="_gt">{{ $scores[$disease->id]['dosage_haplo_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
			<hr class="mt-1 mb-1" />
			@if($scores[$disease->id]['dosage_triplo_score'] !== null)
				<span class="small badge cg-{{ $scores[$disease->id]['dosage_triplo_score'] }} w-100"  data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['dosage_triplo_tooltip'] }}"><a class="text-white" href="{{ $scores[$disease->id]['dosage_link'] }}" target="_gt">{{ $scores[$disease->id]['dosage_triplo_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
		</div>
		<!-- Actionability -->
		<div class="col-md-1 border-right  border-left pt-1 pb-1 pr-2 pl-2 text-center">
			@if ($scores[$disease->id]['actionability_adult_score'] !== null)
			<span class="small badge cg-{{ $scores[$disease->id]['actionability_adult_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="Adult Actionability Assertion of {{ $scores[$disease->id]['actionability_adult_tooltip'] }}"><a class="text-white" href="{{ $scores[$disease->id]['actionability_adult_link'] }}" target="_akb">{{ $scores[$disease->id]['actionability_adult_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
			<hr class="mt-1 mb-1">
			@if ($scores[$disease->id]['actionability_pediatric_score'] !== null)
			<span class="small badge cg-{{ $scores[$disease->id]['actionability_pediatric_score'] }} w-100" data-toggle="tooltip" data-placement="top" title="Pediatric Actionability Assertion of {{ $scores[$disease->id]['actionability_ped_tooltip'] }}"><a class="text-white" href="{{ $scores[$disease->id]['actionability_pediatric_link'] }}" target="_akb">{{ $scores[$disease->id]['actionability_pediatric_score'] }}</a></span>
			@else
				<div class="small">&nbsp;</div>
			@endif
		</div>
		<!-- variant -->
		<div class="col-md-1 border-right pt-1 pb-1 text-center">
			@if($scores[$disease->id]['variant_link'] !== null)
			<span class=""><a href="{{ $scores[$disease->id]['variant_link'] }}" target="_erepo">ERepo <i class="fas fa-external-link-alt"></i></a></span>
			<!-- <div class="small text-muted">Report</div> -->
			<span class=" mt-2"><a href="https://cspec.genome.network/cspec/ui/svi/?search={{ $gene->name }}%20{{ $disease->curie }}" target="_erepo">CSpec <i class="fas fa-external-link-alt"></i></a></span>
			@else 
			<span class="small">&nbsp;</span>
			<div class="small text-muted">&nbsp;</div>
			@endif
		</div>
		<!-- pharma -->
		<div class="col-md-1 border-right pt-1 pb-1 text-center bg-secondary">
			<div class="mt-3" data-toggle="tooltip" data-placement="top" title="Pharmacogenomics does not curate to a disease">
				<span class="text-secondary">&nbsp;</span>
			</div>
		</div>
	</div>
@endforeach
@if (isset($scores[0]))
<div class="row mt-3 mb-1">
	<div class="col-md-12">
		<span class="text-danger font-weight-bold font-italic mr-2">NOTE: </span>
		Dosage Sensitivity also has a non-disease specific score for this gene of 
		<span class="font-weight-bold">
		@if ($scores[0]['dosage_haplo_gene_score'] !== null)
			<span class="small ml-1 mr-1 badge cg-{{ $scores[0]['dosage_haplo_gene_score'] }}" data-toggle="tooltip" data-placement="top" title="{{ $scores[0]['dosage_haplo_gene_tooltip'] }}"><a class="text-white" href="{{ $scores[0]['dosage_link'] }}" target="_gt">{{ $scores[0]['dosage_haplo_gene_tooltip'] }}</a></span>
		@endif
		</span>
		@if ($scores[0]['dosage_triplo_gene_score'] !== null)
			@if ($scores[0]['dosage_haplo_gene_score'] !== null)
				and
			@endif
			<span class="font-weight-bold">
				<span class="small ml-1 badge cg-{{ $scores[0]['dosage_triplo_gene_score'] }}"  data-toggle="tooltip" data-placement="top" title="{{ $scores[0]['dosage_triplo_gene_tooltip'] }}"><a class="text-white" href="{{ $scores[0]['dosage_link'] }}" target="_gt">{{ $scores[0]['dosage_triplo_gene_tooltip'] }}</a></span>
			</span>
		@endif
	</div>
</div>
@endif 

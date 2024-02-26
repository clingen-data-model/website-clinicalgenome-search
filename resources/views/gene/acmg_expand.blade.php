<div class="mt-0 mb-2">
	Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna 
	aliqua. Odio eu feugiat pretium nibh ipsum consequat nisl vel pretium. Natoque penatibus et magnis dis parturient.
</div>
<div class="row border-bottom border-top pt-2">
	<div class="col-md-5 border-right text-center text-bold pb-2">Disease</div>
	<div class="col-md-1 border-right text-center text-bold pb-2">MOI</div>
	<div class="col-md-1 border-right text-center pb-1">
		<img class="" src="/images/clinicalValidity-on.png" title="Gene-Disease Validity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1">
		<img class="" src="/images/dosageSensitivity-on.png" title="Dosage Sensitivity" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1">
		<img class="" src="/images/clinicalActionability-on.png" title="Clinical Actionability" style="width:25px">
	</div>
	<div class="col-md-1 border-right text-center pb-1">
		<img class="" src="/images/variantPathogenicity-on.png" title="Variant Pathogenicity" style="width:25px">
	</div>
	<div class="col-md-2 text-center text-bold pb-2">Reportable as SF</div>
</div>
@foreach ($diseases as $disease)
	<div class="row border-bottom">
		<div class="col-md-5 border-right pt-2 pb-2"><span class="small" onclick="event.stopPropagation();"><a href="/kb/conditions/{{ $disease->curie }}">{{ $disease->label }}</a></span><div class="small text-muted">{{ $disease->curie }}</div></div>
		<div class="col-md-1 border-right pt-2 pb-2 text-center">
			<div class="mt-1 pt-2">{{ $scores[$disease->id]['validity_moi'] }}
				@if (!empty($scores[$disease->id]['validity_tooltip']))
				<span class="cursor-pointer ml-1 mt-4" data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['validity_tooltip'] }} Mode Of Inheritance"><i class="fas fa-info-circle text-muted"></i></span>
				@endif
			</div>
		</div>
		<div class="col-md-1 border-right pt-2 pb-2 text-center">
			<span class="small badge cg-{{ $scores[$disease->id]['validity_score'] }} pt-2 pb-2 mt-2">{{ $scores[$disease->id]['validity_score'] }}</span>
		</div>
		<div class="col-md-1 pt-2 pb-2 text-center">
			<span class="small badge cg-haplo-{{ $scores[$disease->id]['dosage_haplo_score'] }} pt-2 pb-2 mt-2" data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['dosage_haplo_tooltip'] }}">{{ $scores[$disease->id]['dosage_haplo_score'] }}</span>
			<span class="small badge cg-triplo-{{ $scores[$disease->id]['dosage_triplo_score'] }} pt-2 pb-2 mt-2"  data-toggle="tooltip" data-placement="top" title="{{ $scores[$disease->id]['dosage_triplo_tooltip'] }}">{{ $scores[$disease->id]['dosage_triplo_score'] }}</span>
		</div>
		<div class="col-md-1 border-right  border-left pt-2 pb-2 text-center">
			@if($disease->has_actionability)
			<span class="small"><a href="{{ $scores[$disease->id]['actionability_link'] }}" target="_akb"><i class="fas fa-external-link-alt"></i></a></span>
			<div class="small text-muted">Report</div>
			@else 
			<span class="small">&nbsp;</span>
			<div class="small text-muted">&nbsp;</div>
			@endif
		</div>
		<div class="col-md-1 border-right pt-2 pb-2 text-center">
			@if($disease->has_variant)
			<span class="small"><a href="{{ $scores[$disease->id]['variant_link'] }}" target="_erepo"><i class="fas fa-external-link-alt"></i></a></span>
			<div class="small text-muted">Report</div>
			@else 
			<span class="small">&nbsp;</span>
			<div class="small text-muted">&nbsp;</div>
			@endif
		</div>
		<div class="col-md-2 pt-2 pb-2 text-center">
			@if (rand(0,1))
			<span class="text-success mt-3"><i class="fas fa-clipboard-check fa-2x"></i></span>
			@else 
			<span class="text-success mt-3">&nbsp;</span>
			@endif
		</div>
	</div>
@endforeach

<br clear="both" />

<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet published curations forr {{ $record->hgnc_id }}.</strong>
<br />
@if (count($pregceps) > 0)
<p>{{ count($pregceps) }} ClinGen Expert Panel and Working Group(s) are currently reviewing this gene.</p>
<p>Please click on the "Expert Panels & Group" tab above to view additional information</p>
@endif
<br />
View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.
</div>

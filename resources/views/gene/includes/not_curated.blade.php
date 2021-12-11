<br clear="both" />

<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet published curations for {{ $record->label  }} ({{ $record->hgnc_id }}).</strong>
<br />
@if (count($pregceps) > 0)
<br />
<p>One or more ClinGen Expert Panel(s) and Working Group(s) have preliminary activity related to this gene.</p>
<p>Please click on the <a href="{{ route('gene-groups', $record->hgnc_id) }}">"Other Relevant Expert Panels & Groups"</a> tab above to view additional information</p>
@endif
<br />
View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.
</div>

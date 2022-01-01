<br clear="both" />

<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet published curations for {{ $record->label  }} ({{ $record->hgnc_id }}).</strong>
<br />
@if (count($pregceps) > 0)
<br />
<p><b><i>{{ $record->label  }}</i></b> is in scope or under active curation for one or more Expert Panels or Groups.
    Please see the <a href="{{ route('gene-groups', $record->hgnc_id) }}">"Status and Future Work"</a> tab for more information</p>
@endif
<br />
View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.
</div>

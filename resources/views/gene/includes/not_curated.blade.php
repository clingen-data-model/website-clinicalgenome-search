<br clear="both" />

<div class="mt-3 alert alert-info text-center" role="alert"><strong>ClinGen has not yet published curations forr {{ $record->hgnc_id }}.</strong>
<br />

        @if ($record->curation_status !== null)
        @forelse ($record->curation_status as $item)
        <span class="badge badge-secondary">{{ $item['group'] }}</span>
        @empty
        @endforelse
        @endif
<br />
View <a href="{{ route('gene-external', $record->hgnc_id) }}">external genomic resources</a> or <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->label }}%5Bgene%5D">ClinVar</a>.
</div>

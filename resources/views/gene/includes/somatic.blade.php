{{-- resources/views/gene/partials/civic-assertions-demo.blade.php --}}
{{-- DEMO: CIViC Assertions split into Somatic + Predictive with summary counts + “working group” look --}}

@php
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| DEMO DATA (REMOVE AFTER DEMO)
|--------------------------------------------------------------------------
| This is fake CIViC-like data shaped to render BOTH tables + summary counts.
| Each row links out to CIViC in a new tab.
*/

$civic_predictive = collect([
  [
    'aid' => 'AID130',
    'gene' => 'EGFR',
    'variant' => 'T790M',
    'disease' => 'Lung Non-small Cell…',
    'drug' => 'Osimertinib',
    'evidence_level' => 'IA',
    'score' => 10,
    'civic_url' => 'https://civicdb.org/links/assertions/130',
  ],
  [
    'aid' => 'AID5',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Lung Non-small Cell…',
    'drug' => 'Erlotinib',
    'evidence_level' => 'IA',
    'score' => 8,
    'civic_url' => 'https://civicdb.org/links/assertions/5',
  ],
  [
    'aid' => 'AID105',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Lung Non-small Cell…',
    'drug' => 'Gefitinib',
    'evidence_level' => 'IA',
    'score' => 7,
    'civic_url' => 'https://civicdb.org/links/assertions/105',
  ],
  [
    'aid' => 'AID6',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Lung Non-small Cell…',
    'drug' => 'Afatinib',
    'evidence_level' => 'IA',
    'score' => 6,
    'civic_url' => 'https://civicdb.org/links/assertions/6',
  ],
  // a few extra rows like your sample list (fine for demo)
  [
    'aid' => 'AID199',
    'gene' => 'FGFR3',
    'variant' => 'N540K',
    'disease' => 'Urothelial Carcinoma',
    'drug' => 'Erdafitinib',
    'evidence_level' => 'IIC',
    'score' => 4,
    'civic_url' => 'https://civicdb.org/links/assertions/199',
  ],
  [
    'aid' => 'AID203',
    'gene' => 'FGFR1',
    'variant' => 'Amplification',
    'disease' => 'Breast Cancer',
    'drug' => null,
    'evidence_level' => 'IIC',
    'score' => 4,
    'civic_url' => 'https://civicdb.org/links/assertions/203',
  ],
  [
    'aid' => 'AID47',
    'gene' => 'FGFR3',
    'variant' => 'S249C',
    'disease' => 'Bladder Urothelial…',
    'drug' => 'Erdafitinib',
    'evidence_level' => 'IA',
    'score' => 1,
    'civic_url' => 'https://civicdb.org/links/assertions/47',
  ],
]);

$civic_somatic = collect([
  [
    'aid' => 'AID9001',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Lung Non-small Cell…',
    'significance' => 'Oncogenic',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/9001',
  ],
  [
    'aid' => 'AID9002',
    'gene' => 'EGFR',
    'variant' => 'T790M',
    'disease' => 'Lung Non-small Cell…',
    'significance' => 'Likely Oncogenic',
    'evidence_level' => 'B',
    'civic_url' => 'https://civicdb.org/links/assertions/9002',
  ],
  [
    'aid' => 'AID9003',
    'gene' => 'FGFR3',
    'variant' => 'S249C',
    'disease' => 'Bladder Urothelial…',
    'significance' => 'Oncogenic',
    'evidence_level' => 'B',
    'civic_url' => 'https://civicdb.org/links/assertions/9003',
  ],
]);

/*
|--------------------------------------------------------------------------
| SUMMARY COUNTS (NUMBERS FOR EACH “ACTIVITY”)
|--------------------------------------------------------------------------
| Predictive: group by Evidence Level (IA, IIC, etc.)
| Somatic: group by Significance (Oncogenic, Likely Oncogenic, etc.)
*/

$predictive_by_level = $civic_predictive
  ->groupBy(fn($r) => strtoupper($r['evidence_level'] ?? 'NA'))
  ->map(fn($g) => $g->count())
  ->sortKeys();

$predictive_by_drug = $civic_predictive
  ->groupBy(fn($r) => $r['drug'] ?: 'N/A')
  ->map(fn($g) => $g->count())
  ->sortDesc()
  ->take(6);

$somatic_by_sig = $civic_somatic
  ->groupBy(fn($r) => $r['significance'] ?: 'Unspecified')
  ->map(fn($g) => $g->count())
  ->sortDesc();

/*
|--------------------------------------------------------------------------
| INTERNAL “WORKING GROUP / EXPERT PANEL” LINKS
|--------------------------------------------------------------------------
| Keep the old look. If $record / hgnc_id isn’t available in this demo, we
| fall back to "#" so it still renders.
*/
$wgUrl = function () use ($record ?? null) {
  try {
    if (!empty($record) && !empty($record->hgnc_id)) {
      return route('gene-groups', $record->hgnc_id);
    }
  } catch (\Throwable $e) {}
  return '#';
};
@endphp

{{-- ===========================================================
     PREDICTIVE SUMMARY COUNTS (numbers for each activity)
=========================================================== --}}
@if ($civic_predictive->isNotEmpty())
  <div class="p-2 text-muted small bg-light">
    <strong>Predictive Assertions</strong> (Drug Response) — by Evidence Level:
    @foreach($predictive_by_level as $level => $count)
      <a target="_civic"
         href="https://civicdb.org/assertions?evidence_level={{ urlencode($level) }}"
         class="border-1 bg-white badge border-primary text-primary px-1 ml-1">
        {{ $level }}: {{ $count }} <i class="fas fa-external-link-alt ml-1"></i>
      </a>
    @endforeach

    @if($predictive_by_drug->isNotEmpty())
      <span class="ml-2">Top drugs:</span>
      @foreach($predictive_by_drug as $drug => $count)
        <a target="_civic"
           href="https://civicdb.org/assertions?drug={{ urlencode($drug) }}"
           class="border-1 bg-white badge border-secondary text-secondary px-1 ml-1">
          {{ $drug }}: {{ $count }} <i class="fas fa-external-link-alt ml-1"></i>
        </a>
      @endforeach
    @endif
  </div>
@endif

{{-- ===========================================================
     PREDICTIVE TABLE
=========================================================== --}}
@if ($civic_predictive->isNotEmpty())
<h3 id="link-civic-predictive" class="mt-6 mb-0 rounded-top" style="background:#FFFFFF;
background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 24%, rgba(20,118,142,1) 100%);">
  <img src="/images/clingen-somatic-icon.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs">
  Predictive (Drug Response) – CIViC Assertions
</h3>

<div class="card mb-5">
  <div class="card-body p-0 m-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th class="col-sm-2 th-curation-group text-left">Variant</th>
          <th class="col-sm-4">Disease</th>
          <th class="col-sm-2">Expert Panel</th>
          <th class="col-sm-1 text-center">Level</th>
          <th class="col-sm-2 text-center">Drug</th>
          <th class="col-sm-1 text-center">Score</th>
        </tr>
      </thead>
      <tbody>
        @foreach($civic_predictive as $row)
        <tr>
          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['gene'] }} {{ $row['variant'] }}
            </a>
            <div class="text-muted small">{{ $row['aid'] }}</div>
          </td>

          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['disease'] }}
            </a>
          </td>

          {{-- Maintain old “working group” look + link-away opens new tab --}}
          <td class="border-0 pb-1">
            <a href="{{ $wgUrl() }}"
               class="border-1 bg-white badge border-primary text-primary px-1">
              Somatic Expert Panel
            </a>
            <a target="_blank" href="{{ $wgUrl() }}">
              <i class="fas fa-external-link-alt ml-1"></i>
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-default btn-block text-left pt-1 btn-classification"
               target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['evidence_level'] ?? '—' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['drug'] ?? 'N/A' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <span class="badge badge-light">{{ $row['score'] ?? '—' }}</span>
          </td>
        </tr>
        @endforeach

        <tr><td colspan="6" class="border-0"></td></tr>
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- ===========================================================
     SOMATIC SUMMARY COUNTS (numbers for each activity)
=========================================================== --}}
@if ($civic_somatic->isNotEmpty())
  <div class="p-2 text-muted small bg-light">
    <strong>Somatic Oncogenicity</strong> — by Significance:
    @foreach($somatic_by_sig as $sig => $count)
      <a target="_civic"
         href="https://civicdb.org/assertions?significance={{ urlencode($sig) }}"
         class="border-1 bg-white badge border-primary text-primary px-1 ml-1">
        {{ $sig }}: {{ $count }} <i class="fas fa-external-link-alt ml-1"></i>
      </a>
    @endforeach
  </div>
@endif

{{-- ===========================================================
     SOMATIC TABLE
=========================================================== --}}
@if ($civic_somatic->isNotEmpty())
<h3 id="link-civic-somatic" class="mt-6 mb-0 rounded-top" style="background:#FFFFFF;
background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 24%, rgba(142,20,118,1) 100%);">
  <img src="/images/clingen-somatic-icon.png" width="40" height="40" style="margin-top:-4px" class="hidden-sm hidden-xs">
  Somatic Oncogenicity – CIViC Assertions
</h3>

<div class="card mb-5">
  <div class="card-body p-0 m-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th class="col-sm-2 text-left">Variant</th>
          <th class="col-sm-4">Disease</th>
          <th class="col-sm-2">Expert Panel</th>
          <th class="col-sm-2 text-center">Level</th>
          <th class="col-sm-2 text-center">Significance</th>
        </tr>
      </thead>
      <tbody>
        @foreach($civic_somatic as $row)
        <tr>
          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['gene'] }} {{ $row['variant'] }}
            </a>
            <div class="text-muted small">{{ $row['aid'] }}</div>
          </td>

          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['disease'] }}
            </a>
          </td>

          {{-- Maintain old “working group” look + link-away opens new tab --}}
          <td class="border-0 pb-1">
            <a href="{{ $wgUrl() }}"
               class="border-1 bg-white badge border-primary text-primary px-1">
              Somatic Expert Panel
            </a>
            <a target="_blank" href="{{ $wgUrl() }}">
              <i class="fas fa-external-link-alt ml-1"></i>
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-default btn-block text-left pt-1 btn-classification"
               target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['evidence_level'] ?? '—' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['significance'] ?? '—' }}
            </a>
          </td>
        </tr>
        @endforeach

        <tr><td colspan="5" class="border-0"></td></tr>
      </tbody>
    </table>
  </div>
</div>
@endif

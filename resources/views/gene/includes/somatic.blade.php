{{-- resources/views/gene/partials/civic-assertions-demo.blade.php --}}

@php
/*
|--------------------------------------------------------------------------
| DEMO DATA – REMOVE AFTER DEMO
|--------------------------------------------------------------------------
*/

$civic_predictive = collect(array(
  array(
    'aid' => 'AID130',
    'gene' => 'EGFR',
    'variant' => 'T790M',
    'drug' => 'Erlotinib',
    'disease' => 'Cancer',
    'classification' => 'Oncogenic',
    'score' => 10,
    'civic_url' => 'https://civicdb.org/links/assertions/130',
  ),
  array(
    'aid' => 'AID5',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'drug' => 'Erlotinib',
    'disease' => 'Cancer',
    'evidence_level' => 'IA',
    'classification' => 'Oncogenic',
    'score' => 8,
    'civic_url' => 'https://civicdb.org/links/assertions/5',
  ),

  array(
    'aid' => 'AID5',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Lung Non-small Cell Carcinoma',
    'drug' => 'Erlotinib',
    'evidence_level' => 'IA',
    'score' => 8,
    'classification' => 'Oncogenic',
    'civic_url' => 'https://civicdb.org/links/assertions/5',
  ),

  array(
    'aid' => 'AID105',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Urothelial Carcinoma',
    'drug' => 'Erdafitinib',
    'evidence_level' => 'IA',
    'score' => 7,
    'classification' => 'Oncogenic',
    'civic_url' => 'https://civicdb.org/links/assertions/105',
  ),
));

$civic_somatic = collect(array(
  array(
    'aid' => 'AID9001',
    'gene' => 'EGFR',
    'variant' => 'L858R',
    'disease' => 'Cancer',
    'drug' => 'Erlotinib',
    'significance' => 'Oncogenic',
    'count' => 2,
    'classification' => 'Oncogenic',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/9001',
  ),

  array(
    'aid' => 'AID9002',
    'gene' => 'EGFR',
    'variant' => 'T790M',
    'drug' => 'Erlotinib',
    'disease' => 'Breast Cancer',
    'significance' => 'Progenic',
    'count' => 3,
    'classification' => 'Oncogenic',
    'evidence_level' => 'B',
    'civic_url' => 'https://civicdb.org/links/assertions/9003',
  ),
));

/*
|--------------------------------------------------------------------------
| SUMMARY COUNTS
|--------------------------------------------------------------------------
*/

// Predictive by Evidence Level
$predictive_by_level = array();
foreach ($civic_predictive as $row) {
  $lvl = isset($row['evidence_level']) ? strtoupper($row['evidence_level']) : 'NA';
  if (!isset($predictive_by_level[$lvl])) {
    $predictive_by_level[$lvl] = 0;
  }
  $predictive_by_level[$lvl]++;
}
ksort($predictive_by_level);

// Somatic by Significance
$somatic_by_sig = array();
foreach ($civic_somatic as $row) {
  $sig = isset($row['significance']) ? $row['significance'] : 'Unspecified';
  if (!isset($somatic_by_sig[$sig])) {
    $somatic_by_sig[$sig] = 0;
  }
  $somatic_by_sig[$sig]++;
}
arsort($somatic_by_sig);

// Working Group URL
$wg_url = '#';
if (isset($record) && isset($record->hgnc_id)) {
  $wg_url = route('gene-groups', $record->hgnc_id);
}
@endphp

@if ($civic_somatic->count())
<div class="p-2 text-muted small bg-light">
  <strong>Somatic Oncogenicity</strong> — by Significance:
  @foreach ($somatic_by_sig as $sig => $count)
    <a target="_civic"
       href="https://civicdb.org/assertions?significance={{ urlencode($sig) }}"
       class="border-1 bg-white badge border-primary text-primary px-1 ml-1">
      {{ $sig }}: {{ $count }}
      <i class="fas fa-external-link-alt ml-1"></i>
    </a>
  @endforeach
</div>
@endif

{{-- ===========================================================
     SOMATIC TABLE
=========================================================== --}}
@if ($civic_somatic->count())
<h3 class="mt-6 mb-0 rounded-top"
    style="background:linear-gradient(90deg,#fff 0%,#fff 24%,#8e1476 100%);">
  <img src="/images/clingen-somatic-icon.png"
       width="40" height="40"
       style="margin-top:-4px"
       class="hidden-sm hidden-xs">
  Somatic Oncogenicity
</h3>

<div class="card mb-5">
  <div class="card-body p-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th>Variant</th>
          <th>Disease</th>
          <th>Expert Panel</th>
            <th> Count </th>
          <th class="text-center">Classification</th>
          <th class="text-center"></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($civic_somatic as $row)
        <tr>
          <td>
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['gene'] }}
            </a>
{{--            <div class="text-muted small">{{ $row['aid'] }}</div>--}}
          </td>
          <td>
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['disease'] }}
            </a>
          </td>
          <td>
            <a href="{{ $wg_url }}"
               class="badge border-primary text-primary bg-white">
              Somatic Expert Panel
            </a>
            <a target="_blank" href="{{ $wg_url }}">
              <i class="fas fa-external-link-alt ml-1"></i>
            </a>
          </td>
            <td> {{ $row['count'] }} </td>
          <td class="text-center">{{ $row['classification'] }}</td>
          <td class="text-center"> <a target="_civic" href="https://civicdb.org">
              CIViC <i class="fas fa-external-link-alt ml-1"></i>
            </a></td>
        </tr>
        @endforeach

        {{-- Reference row --}}
        <tr class="bg-light">
          <td colspan="5" class="text-center small">
            View complete oncogenicity evidence and context on
            <a target="_civic" href="https://civicdb.org">
              CIViC <i class="fas fa-external-link-alt ml-1"></i>
            </a>
          </td>
        </tr>

      </tbody>
    </table>
  </div>
</div>
@endif

{{-- ===========================================================
     PREDICTIVE SUMMARY
=========================================================== --}}
@if ($civic_predictive->count())
<div class="p-2 text-muted small bg-light">
  <strong>Predictive Assertions</strong>:
  @foreach ($predictive_by_level as $level => $count)
    <a target="_civic"
       href="https://civicdb.org/assertions?evidence_level={{ urlencode($level) }}"
       class="border-1 bg-white badge border-primary text-primary px-1 ml-1">
      {{ $level }}: {{ $count }}
      <i class="fas fa-external-link-alt ml-1"></i>
    </a>
  @endforeach
</div>
@endif

{{-- ===========================================================
     PREDICTIVE TABLE
=========================================================== --}}
@if ($civic_predictive->count())
<h3 class="mt-6 mb-0 rounded-top"
    style="background:linear-gradient(90deg,#fff 0%,#fff 24%,#14768e 100%);">
  <img src="/images/clingen-somatic-icon.png"
       width="40" height="40"
       style="margin-top:-4px"
       class="hidden-sm hidden-xs">
  Predictive (Drug Response)
</h3>

<div class="card mb-5">
  <div class="card-body p-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th>Variant</th>
          <th>Disease</th>
          <th>Expert Panel</th>
          <th class="text-center">Drug</th>
          <th class="text-center">Score</th>
            <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach ($civic_predictive as $row)
        <tr>
          <td>
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['gene'] }}
            </a>
{{--            <div class="text-muted small">{{ $row['aid'] }}</div>--}}
          </td>
          <td>
            <a target="_civic" href="{{ $row['civic_url'] }}">
              {{ $row['disease'] }}
            </a>
          </td>
          <td>
            <a href="{{ $wg_url }}"
               class="badge border-primary text-primary bg-white">
              Somatic Expert Panel
            </a>
            <td class="border-0 pb-1 "><a href="https://clinicalgenome.org/affiliation/}">{{ $wg_url }} <i class="fas fa-external-link-alt ml-1"></i></a></td>
          </td>
          <td class="text-center">{{ $row['drug'] }}</td>
          <td class="text-center">{{ $row['score'] }}</td>
            <td>  <a target="_civic" href="https://civicdb.org">
              CIViC <i class="fas fa-external-link-alt ml-1"></i>
            </a></td>
        </tr>
        @endforeach


      </tbody>
    </table>
  </div>
</div>
@endif



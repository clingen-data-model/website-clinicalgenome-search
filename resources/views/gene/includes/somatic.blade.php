@php
use Illuminate\Support\Collection;

/*
|--------------------------------------------------------------------------
| DEMO DATA – REMOVE AFTER DEMO
|--------------------------------------------------------------------------
*/

$civic_assertions = collect([

  // ---------------------------
  // Somatic / Oncogenicity
  // ---------------------------
  [
    'gene' => 'NTRK1',
    'variant' => 'p.Gly595Arg',
    'disease' => 'Lung adenocarcinoma',
    'category' => 'oncogenic',
    'significance' => 'Oncogenic',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/12345'
  ],
  [
    'gene' => 'NTRK1',
    'variant' => 'p.Arg580Gln',
    'disease' => 'Glioblastoma',
    'category' => 'oncogenic',
    'significance' => 'Likely Oncogenic',
    'evidence_level' => 'B',
    'civic_url' => 'https://civicdb.org/links/assertions/12346'
  ],
  [
    'gene' => 'EGFR',
    'variant' => 'p.Leu858Arg',
    'disease' => 'Non-small cell lung carcinoma',
    'category' => 'oncogenic',
    'significance' => 'Oncogenic',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/12347'
  ],

  // ---------------------------
  // Predictive / Drug response
  // ---------------------------
  [
    'gene' => 'EGFR',
    'variant' => 'p.Leu858Arg',
    'disease' => 'Non-small cell lung carcinoma',
    'category' => 'predictive',
    'drug' => 'Erlotinib',
    'clinical_significance' => 'Sensitivity',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/22345'
  ],
  [
    'gene' => 'EGFR',
    'variant' => 'p.Thr790Met',
    'disease' => 'Non-small cell lung carcinoma',
    'category' => 'predictive',
    'drug' => 'Gefitinib',
    'clinical_significance' => 'Resistance',
    'evidence_level' => 'A',
    'civic_url' => 'https://civicdb.org/links/assertions/22346'
  ],
  [
    'gene' => 'NTRK1',
    'variant' => 'p.Gly595Arg',
    'disease' => 'Solid tumors',
    'category' => 'predictive',
    'drug' => 'Larotrectinib',
    'clinical_significance' => 'Sensitivity',
    'evidence_level' => 'B',
    'civic_url' => 'https://civicdb.org/links/assertions/22347'
  ],

]);

/*
|--------------------------------------------------------------------------
| SPLIT INTO TABLE COLLECTIONS
|--------------------------------------------------------------------------
*/

$somatic_assertions = $civic_assertions->filter(fn ($a) =>
    in_array(strtolower($a['category']), ['oncogenic', 'oncogenicity'])
);

$predictive_assertions = $civic_assertions->filter(fn ($a) =>
    strtolower($a['category']) === 'predictive'
);
@endphp


@if ($somatic_assertions->isNotEmpty())
<h3 id="link-civic-somatic" class="mt-6 mb-0 rounded-top"
    style="background:#FFFFFF;
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 24%, rgba(142,20,118,1) 100%);">

    <img src="/images/clingen-somatic-icon.png" width="40" height="40"
         style="margin-top:-4px" class="hidden-sm hidden-xs">
    Somatic Oncogenicity – CIViC Assertions
</h3>

<div class="card mb-5">
  <div class="card-body p-0 m-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th class="col-sm-1 text-left">Gene</th>
          <th class="col-sm-4">Disease</th>
          <th class="col-sm-2">Expert Panel</th>
          <th class="col-sm-2 text-center">Evidence Level</th>
          <th class="col-sm-1 text-center">Type</th>
          <th class="col-sm-2 text-center">Significance</th>
        </tr>
      </thead>

      <tbody>
        @foreach($somatic_assertions as $a)
        <tr>
          <td class="border-0 pb-1">{{ $a['gene'] ?? '—' }}</td>

          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['disease'] ?? '—' }}
            </a>
            @if (!empty($a['variant']))
              <div class="text-muted small">Variant: {{ $a['variant'] }}</div>
            @endif
          </td>

          <td class="border-0 pb-1">
            <span class="badge badge-info">Somatic Expert Panel</span>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-default btn-block text-left pt-1 btn-classification"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['evidence_level'] ?? '—' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              Oncogenicity
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['significance'] ?? '—' }}
            </a>
          </td>
        </tr>
        @endforeach

        <tr><td colspan="6" class="border-0"></td></tr>
      </tbody>
    </table>
  </div>
</div>
@endif

@if ($predictive_assertions->isNotEmpty())
<h3 id="link-civic-predictive" class="mt-6 mb-0 rounded-top"
    style="background:#FFFFFF;
    background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,1) 24%, rgba(20,118,142,1) 100%);">

    <img src="/images/clingen-somatic-icon.png" width="40" height="40"
         style="margin-top:-4px" class="hidden-sm hidden-xs">
    Predictive (Drug Response) – CIViC Assertions
</h3>

<div class="card mb-5">
  <div class="card-body p-0 m-0">
    <table class="panel-body table mb-0">
      <thead class="thead-labels">
        <tr>
          <th class="col-sm-1 text-left">Gene</th>
          <th class="col-sm-4">Disease</th>
          <th class="col-sm-2">Expert Panel</th>
          <th class="col-sm-2 text-center">Evidence Level</th>
          <th class="col-sm-1 text-center">Drug</th>
          <th class="col-sm-2 text-center">Clinical Significance</th>
        </tr>
      </thead>

      <tbody>
        @foreach($predictive_assertions as $a)
        <tr>
          <td class="border-0 pb-1">{{ $a['gene'] ?? '—' }}</td>

          <td class="border-0 pb-1">
            <a target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['disease'] ?? '—' }}
            </a>
            @if (!empty($a['variant']))
              <div class="text-muted small">Variant: {{ $a['variant'] }}</div>
            @endif
          </td>

          <td class="border-0 pb-1">
            <span class="badge badge-info">Somatic Expert Panel</span>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-default btn-block text-left pt-1 btn-classification"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['evidence_level'] ?? '—' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['drug'] ?? '—' }}
            </a>
          </td>

          <td class="text-center border-0 pb-1">
            <a class="btn btn-xs btn-success btn-block"
               target="_civic" href="{{ $a['civic_url'] ?? '#' }}">
              {{ $a['clinical_significance'] ?? ($a['significance'] ?? '—') }}
            </a>
          </td>
        </tr>
        @endforeach

        <tr><td colspan="6" class="border-0"></td></tr>
      </tbody>
    </table>
  </div>
</div>
@endif


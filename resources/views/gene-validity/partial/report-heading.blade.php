<h3>{{ $record->gene->label }} - {{ displayMondoLabel($record->disease->label) }} {!! displayMondoObsolete($record->disease->label) !!}</h3>
{{ $record->interface }}
<div class="form-group">
<table class='table table-striped text-left' style="width:100%;" >
<tr style="font-size:14px">
    <td style="border-top-width:6px" nowrap class="text-left">Gene:</td>
    <td  style="border-top-width:6px">{{ $record->gene->label }} ({{ $record->gene->hgnc_id }})</td>
    <td colspan="2" rowspan="3"  style="border-top-width:6px; border-left-width:6px; border-left-color:rgb(221, 221, 221); border-left-style:solid; text-align: center;">
    <div class='badge badge-primary' style="font-size: 20px; padding:15px;">
      <a tabindex="0" class="text-white" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://www.clinicalgenome.org/site/assets/files/5967/gene-validity_classification.pdf" data-content="Gene-Disease Validity classification and scoring information">{{ App\GeneLib::validityClassificationString($record->classification->label ?? null) }}  <i class="glyphicon glyphicon-info-sign text-white"></i></a>
    </div>
    <div>Classification - {{ displayDate($record->report_date ?? null) }}</div></td>
  </tr>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Disease:</td>
    <td  style="">{{ displayMondoLabel($record->disease->label) }} <div> ({{ $record->disease->curie }}) {!! displayMondoObsolete($record->disease->label) !!} </div></td>
  </tr>
  @if($record->mode_of_inheritance)
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Mode of Inheritance:</td>
    <td style="">
      {{ $record->displayMoi($record->mode_of_inheritance->label, true) }}
      &nbsp;({{ $record->mode_of_inheritance->curie }})
      </td>
  </tr>
  @endif

  <tr style="font-size:14px">
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Replication over time:</td>
    <td style="width:40%;  border-top-width:6px">
      {{ $record->sop7_replication_over_time }}
    </td>
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Contradictory Evidence:</td>
    <td style="width:40%;  border-top-width:6px">
      {{ $record->sop7_valid_contradictory_evidence }}
    </td>
  </tr>
  <tr style="font-size:14px">
    <td style="vertical-align:top; border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">Expert Panel:</td>
    <td style="vertical-align:top; border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid">
      {{ $record->sop7_affiliation_name }}
    </td>
    <td style="vertical-align:top; border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">
    @if ($record->sop7_contributors ?? null)
      Contributors:
    @endif
    </td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid">
     @if ($record->sop7_contributors ?? null)
      @foreach ($record->sop7_contributors as $contributor)
          <div>{{ $contributor->name ?? null }} <span style="color:#999; text-transform: capitalize;">({{ $contributor->role ?? null }})</span></div>
      @endforeach
    @endif
    </td>
  </tr>

  <tr style="font-size:14px">
    <td style="vertical-align:top" nowrap class="text-left">Evidence Summary:</td>
    <td colspan="3" style="">
      {{ $record->sop7_final_classification_notes ?? null }}
      <div><a style="color:#000" href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">
        Gene Clinical Validity Standard Operating Procedures (SOP) -
        {{ App\GeneLib::validityCriteriaString($record->specified_by->label ?? null) }}
      </a></div>
    </td>
  </tr>
  </table>
</div>

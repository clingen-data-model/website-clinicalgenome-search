<h3>{{ $record->genes[0]['symbol'] }} - {{ $record->diseases[0]['label'] }}</h3>
<div class="form-group">
<table class='table table-striped text-left' style="width:100%;" >
<tr style="font-size:14px">
    <td style="border-top-width:6px" nowrap class="text-left">Gene:</td>
    <td  style="border-top-width:6px">{{ $record->genes[0]['symbol'] }} ({{ $record->genes[0]['hgnc_id'] }})</td>
    <td colspan="2" rowspan="3"  style="border-top-width:6px; border-left-width:6px; border-left-color:rgb(221, 221, 221); border-left-style:solid; text-align: center;">
    <div class='badge badge-primary' style="font-size: 20px; padding:15px;">
      <a tabindex="0" class="text-white" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://www.clinicalgenome.org/site/assets/files/5967/gene-validity_classification.pdf" data-content="Gene-Disease Validity classification and scoring information">{{ $record->score_data->summary->FinalClassification ?? null }}  <i class="glyphicon glyphicon-info-sign text-white"></i></a>
    </div>
    <div>Classification - {{ $record->score_data->summary->FinalClassificationDate ?? null }}</div></td>
  </tr>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Disease:</td>
    <td  style="">{{ $record->diseases[0]['label'] }} ({{ $record->diseases[0]['curie'] }})</td>
  </tr>
  @if($record->moi)
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Mode of Inheritance:</td>
    <td style="">
      {{ $record->displayMoi($record->moi, 'long') }}
      </td>
  </tr>
  @endif

  <tr style="font-size:14px">
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Replication over time:</td>
    <td style="width:40%;  border-top-width:6px">
      @if (($record->score_data->ReplicationOverTime ?? null) == "YES")
					YES
				@else
					NO
				@endif

    </td>
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Contradictory Evidence:</td>
    <td style="width:40%;  border-top-width:6px">
      @if (($record->score_data->ValidContradictoryEvidence->Value ?? null) == "YES")
					YES
				@else
					NO
				@endif
    </td>
  </tr>
  <tr style="font-size:14px">
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">Expert Panel:</td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid">
      {{ $record->attributions['label'] ?? null }}
    </td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">
    @if ($record->score_data->summary->contributors ?? null)
      Contributors:
    @endif
    </td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid">

     @if ($record->score_data->summary->contributors ?? null)
      @foreach ($record->score_data->summary->contributors as $contributor)
          {{ $contributor["name"] }}
      @endforeach
    @endif
    </td>
  </tr>

  <tr style="font-size:14px">
    <td style="vertical-align:top" nowrap class="text-left">Evidence Summary:</td>
    <td colspan="3" style="">
      {{ $record->score_data->summary->FinalClassificationNotes ?? null }}
      <div><a style="color:#000" href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">
        Gene Clinical Validity Standard Operating Procedures (SOP) -
        {{ $record->sop ?? null }}
      </a></div>
    </td>
  </tr>
  </table>
</div>

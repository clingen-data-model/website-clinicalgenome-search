<div class="form-group">
<table style="width:100%;" >
  <tr style="">
    <td style="width:15%; padding-top:5px; padding-bottom:5px; padding-right:1%;" valign="top"  nowrap class="text-right text-muted">Gene/Disease Pair:</td>
    <td style="width:85%; padding-bottom:5px"><h3 style="padding:0; margin:0"><strong style="color:#000"><i>
      {{ $record->genes[0]['symbol'] }}
      </i>:
      {{ $record->diseases[0]['label'] }}
      </strong></h3></td>
  </tr>
  <tr style="">
    <td style="width:15%; padding-right:1%;" nowrap class="text-right text-muted"></td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      @foreach ($record->diseases as $disease)
					 {{ $disease['curie'] }}
			@endforeach
      </strong></td>
  </tr>
      @if (isset($record->score_data->ModeOfInheritance))
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">Mode of Inheritance:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $record->score_data->ModeOfInheritance ?? null }}
      </strong></td>
  </tr>
  @endif
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">SOP:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
		<a href="{{ env('CG_URL_VALIDITY_SOP', 'http://www.clinicalgenome.org') }}">Gene Clinical Validity Standard Operating Procedures (SOP), Version 4</a>
      </strong></td>
  </tr>
</table>
</div>
<hr />
<table class="table table-compact table-bordered table-border-normal">
<tbody>
  <tr>
    <td rowspan="17" class="table-heading-line-thick table-title table-title-vertical"><div class="table-title-text">
      <div class="table-title-text-inner ">Genetic Evidence</div>
      </div></td>
    <td rowspan="12" class="table-heading-line-normal table-title table-title-vertical"><div class="table-title-text">
      <div class="table-title-text-inner ">Case-Level Data</div>
      </div></td>
    <td colspan="2" rowspan="2" class="table-heading-bg table-heading">Evidence Type</td>
    <td colspan="3" rowspan="2" class="table-heading-bg table-heading">Case Information Type</td>
    <td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines</td>
    <td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Scores</td>
    <td rowspan="2" style="width:40%" class="table-heading-bg table-heading">PMIDs/Notes</td>
  </tr>
  <tr>
    <td class="table-heading-bg table-heading table-heading-tight">Default</td>
    <td class="table-heading-bg table-heading table-heading-tight">Range</td>
    <td class="table-heading-bg table-heading table-heading-tight">Max</td>
    <td class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
    <td class="table-heading-bg table-heading table-heading-tight points-tally-bg">Tally</td>
  </tr>
  <tr>
    <td rowspan="5" class="table-title table-title-vertical table-border-thin"><div class="table-title-text">
      <div class="table-title-text-inner">Variant Evidence</div>
      </div></td>
    <td rowspan="3" class="table-title table-border-thin">Autosomal Dominant or X-linked Disorder</td>
    <td colspan="3">Variant is de novo</td>
    <td>2</td>
    <td>0-3</td>
    <td id="GeneticEvidence1Max">12</td>
    <td class="input-width-numbers points-given-bg"><div class="form-group">



      {{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithLOF']['value'] ?? null }}


      </div></td>
    <td class="points-tally-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithLOF']['tally'] ?? null }}</td>
    <td class="input-width-pmid">

      {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithLOF']['pmid'] ?? null) }}


      </td>
  </tr>
  <tr>
    <td colspan="3">Proband with predicted or proven null variant</td>
    <td>1.5</td>
    <td>0-2</td>
    <td id="GeneticEvidence2Max">10</td>
    <td class="input-width-numbers points-given-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['VariantIsDeNovo']['value'] ?? null }}</td>
    <td class=" points-tally-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['VariantIsDeNovo']['tally'] ?? null }}</td>
    <td class="input-width-pmid">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['VariantIsDeNovo']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td colspan="3" class='table-border-thin'>Proband with other variant type with some evidence of gene impact</td>
    <td class='table-border-thin'>0.5</td>
    <td class='table-border-thin'>0-1.5</td>
    <td id="GeneticEvidence3Max" class='table-border-thin'>7</td>
    <td class="input-width-numbers points-given-bg table-border-thin">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithNon-LOF']['value'] ?? null }}</td>
    <td class=" points-tally-bg table-border-thin"><span class="points-tally-bg">
      {{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithNon-LOF']['tally'] ?? null }}
      </span></td>
    <td class="input-width-pmid table-border-thin"><span class="input-width-pmid">
      {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['ProbandWithNon-LOF']['pmid'] ?? null) }}

      </span></td>
  </tr>
  <tr>
    <td rowspan="2" class="table-title table-border-thin">Autosomal Recessive Disease</td>
    <td colspan="3">Two variants in trans and at least one de novo or a predicted/proven null variant</td>
    <td>2</td>
    <td>0-3</td>
    <td rowspan="2" id="GeneticEvidence4Max" class=' table-border-thin'>12</td>
    <td class="input-width-numbers  points-given-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['TwoNariantsInTransAndAtLeastOneIsLOFOrDeNovo']['value'] ?? null }}</td>
    <td rowspan="2" class=" points-tally-bg table-border-thin">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['TwoNon-LOFVariantsInTrans']['tally'] ?? null }}</td>
    <td rowspan="2" class="input-width-pmid  table-border-thin">
    	{{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['TwoNon-LOFVariantsInTrans']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td colspan="3" class='table-border-thin'>Two variants (not predicted/proven null) with some evidence of gene impact in trans</td>
    <td class='table-border-thin'>1</td>
    <td class='table-border-thin'>0-1.5</td>
    <td class=' input-width-numbers  points-given-bg table-border-thin'>{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['VariantEvidence']['AutosomalDominantDisease']['TwoNon-LOFVariantsInTrans']['value'] ?? null }}</td>
  </tr>
  <tr>
    <td colspan="2" rowspan="5" class="table-heading-line-normal table-title">Segregation Evidence</td>
    <td rowspan="5" class="table-heading-line-normal">Evidence of segregation in one or more families</td>
    <td rowspan="5" class="table-heading-line-normal">LOD Score Examples</td>
    <td>3</td>
    <td>5</td>
    <td rowspan="5" class="table-heading-line-normal">0-7</td>
    <td rowspan="5" id="GeneticEvidence5Max" class="table-heading-line-normal">7</td>
    <td rowspan="5" class="table-heading-line-normal input-width-numbers  points-given-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['SegregationEvidence']['EvidenceOfSegregationInOneOrMoreFamilies']['value'] ?? null }}</td>
    <td rowspan="5" class="table-heading-line-normal points-tally-bg">{{ $record['score_data_array']['GeneticEvidence']['CaseLevelData']['SegregationEvidence']['EvidenceOfSegregationInOneOrMoreFamilies']['tally'] ?? null }}</td>
    <td rowspan="5" class="table-heading-line-normal input-width-pmid">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['CaseLevelData']['SegregationEvidence']['EvidenceOfSegregationInOneOrMoreFamilies']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td>2</td>
    <td>4</td>
  </tr>
  <tr>
    <td>1.5</td>
    <td>3</td>
  </tr>
  <tr>
    <td>1</td>
    <td>1.5</td>
  </tr>
  <tr>
    <td class="table-heading-line-normal">&nbsp;</td>
    <td class="table-heading-line-normal">&nbsp;</td>
  </tr>
  <tr>
    <td rowspan="4" class="table-title table-title-vertical"><div class="table-title-text">
      <div class="table-title-text-inner ">Case-Control Data</div>
      </div></td>
    <td colspan="2" rowspan="2" class="table-heading-bg table-heading">Case-Control Study Type</td>
    <td colspan="3" rowspan="2" class="table-heading-bg table-heading">Case-Control Quality Criteria</td>
    <td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines </td>
    <td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Scores</td>
    <td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
  </tr>
  <tr>
    <td colspan="2" class='table-heading-bg table-heading table-heading-tight'>Points/Study</td>
    <td class='table-heading-bg table-heading table-heading-tight'>Max</td>
    <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Points</td>
    <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Tally</td>
  </tr>
  <tr>
    <td colspan="2" class="table-title">Single Variant Analysis</td>
    <td colspan="3" rowspan="2" class="text-left">1. Variant Detection Methodology <br>
      2. Power <br>
      3. Bias and confounding <br>
      4. Statistical Significance</td>
    <td colspan="2">0-6</td>
    <td id="GeneticEvidence6Max">12</td>
    <td class="input-width-numbers points-given-bg">{{ $record['score_data_array']['GeneticEvidence']['Case-ControlData']['SingleVariantAnalysis']['value'] ?? null }}</td>
    <td class=" points-tally-bg">{{ $record['score_data_array']['GeneticEvidence']['Case-ControlData']['SingleVariantAnalysis']['tally'] ?? null }}</td>
    <td class="input-width-pmid">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['Case-ControlData']['SingleVariantAnalysis']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td colspan="2" class="table-title">Aggregate Variant Analysis</td>
    <td colspan="2">0-6</td>
    <td id="GeneticEvidence7Max">12</td>
    <td class="input-width-numbers points-given-bg">{{ $record['score_data_array']['GeneticEvidence']['Case-ControlData']['AggregateVariantAnalysis']['value'] ?? null }}</td>
    <td class=" points-tally-bg">{{ $record['score_data_array']['GeneticEvidence']['Case-ControlData']['AggregateVariantAnalysis']['tally'] ?? null }}</td>
    <td class="input-width-pmid">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['GeneticEvidence']['Case-ControlData']['AggregateVariantAnalysis']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Genetic Evidence Points (Maximum <span id="GeneticEvidenceMax">12</span>)</td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">{{ $record['score_data_array']['summary']['GeneticEvidencePointsTotal'] ?? null }}</td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total"><div class="form-group total-notes">
      {{ $record['score_data_array']['GeneticEvidence']['TotalGeneticEvidencePoints']['notes'] ?? null }}
      </div></td>
  </tr>
  <tr>
    <td rowspan="12" class="table-heading-line-thick table-title table-title-vertical"><div class="table-title-text">
      <div class="table-title-text-inner ">Experimental Evidence</div>
      </div></td>
    <td colspan="3" rowspan="2" class="table-heading-bg table-heading ">Evidence Category</td>
    <td colspan="3" rowspan="2" class="table-heading-bg table-heading">Evidence Type</td>
    <td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines </td>
    <td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Scores</td>
    <td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
  </tr>
  <tr>
    <td class='table-heading-bg table-heading table-heading-tight'>Default</td>
    <td class='table-heading-bg table-heading table-heading-tight'>Range</td>
    <td class='table-heading-bg table-heading table-heading-tight'>Max</td>
    <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Points</td>
    <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Tally</td>
  </tr>
  <tr>
    <td colspan="3" rowspan="3" class="table-title  table-border-thin">Function</td>
    <td colspan="3">Biochemical Function</td>
    <td>0.5</td>
    <td>0 - 2</td>
    <td rowspan="3" class='table-border-thin' id="ExperimentalEvidence1Max">2</td>
    <td rowspan="3" class="input-width-numbers points-given-bg table-border-thin">{{ $record['score_data_array']['ExperimentalEvidence']['Function']['value'] ?? null }}</td>
    <td rowspan="3" class=" points-tally-bg table-border-thin">{{ $record['score_data_array']['ExperimentalEvidence']['Function']['tally'] ?? null }}</td>
    <td rowspan="3" class="input-width-pmid table-border-thin">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['ExperimentalEvidence']['Function']['pmid'] ?? null) }}</td>
  </tr>
  <tr>
    <td colspan="3">Protein Interaction</td>
    <td>0.5</td>
    <td>0 - 2</td>
  </tr>
  <tr>
    <td colspan="3" class=' table-border-thin'>Expression</td>
    <td class=' table-border-thin'>0.5</td>
    <td class=' table-border-thin'>0 - 2</td>
  </tr>
  <tr>
    <td colspan="3" rowspan="2" class="table-title table-border-thin">Functional Alteration</td>
    <td colspan="3">Patient cells</td>
    <td>1</td>
    <td>0 - 2</td>
    <td rowspan="2" class=' table-border-thin' id="ExperimentalEvidence2Max">2</td>
    <td rowspan="2" class="input-width-numbers points-given-bg table-border-thin">{{ $record['score_data_array']['ExperimentalEvidence']['FunctionalAlteration']['value'] ?? null }}</td>
    <td rowspan="2" class=" points-tally-bg table-border-thin">{{ $record['score_data_array']['ExperimentalEvidence']['FunctionalAlteration']['tally'] ?? null }}</td>
    <td rowspan="2" class="input-width-pmid table-border-thin">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['ExperimentalEvidence']['FunctionalAlteration']['pmid'] ?? null) }}
    </td>
  </tr>
  <tr>
    <td colspan="3" class='table-border-thin'>Non-patient cells</td>
    <td class='table-border-thin'>0.5</td>
    <td class='table-border-thin'>0 - 1</td>
  </tr>
  <tr>
    <td colspan="3" rowspan="4" class="table-title">Models &amp; Rescue</td>
    <td colspan="3">Animal model</td>
    <td>2</td>
    <td>0 - 4</td>
    <td rowspan="4" id="ExperimentalEvidence3Max">4</td>
    <td rowspan="4" class="input-width-numbers points-given-bg">{{ $record['score_data_array']['ExperimentalEvidence']['ModelsRescue']['value'] ?? null }}</td>
    <td rowspan="4" class=" points-tally-bg">{{ $record['score_data_array']['ExperimentalEvidence']['ModelsRescue']['tally'] ?? null }}</td>
    <td rowspan="4" class="input-width-pmid">
    {{ App\Helper::PrintWrapperPmid($record['score_data_array']['ExperimentalEvidence']['ModelsRescue']['pmid'] ?? null) }}</td>
  </tr>
  <tr>
    <td colspan="3">Cell culture model system</td>
    <td>1</td>
    <td>0 - 2</td>
  </tr>
  <tr>
    <td colspan="3">Rescue in animal model</td>
    <td>2</td>
    <td>0 - 4</td>
  </tr>
  <tr>
    <td colspan="3">Rescue in engineered equivalent</td>
    <td>1</td>
    <td>0 - 2</td>
  </tr>
  <tr>
    <td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Experimental Evidence Points (Maximum <span id="ExperimentalEvidenceMax">6</span>)</td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">{{ $record['score_data_array']['summary']['ExperimentalEvidenceTotal'] ?? null }}</td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total"><div class="form-group total-notes">
      {{ $record['score_data_array']['ExperimentalEvidence']['TotalExperimentalEvidencePoints']['notes'] ?? null }}
      </div></td>
  </tr>
</tbody>
</table>
<hr />
<table class="table table-condensed table-bordered table-border-normal">
<tbody>
  <tr>
    <td style="width:25%" class="table-heading-line-thick table-heading">Assertion criteria</td>
    <td style="width:25%" class="table-heading-line-thick table-heading">Genetic Evidence (0-12 points)</td>
    <td style="width:25%" class="table-heading-line-thick table-heading">Experimental Evidence <br>
      (0-6 points)</td>
    <td style="width:15%" class="table-heading-line-thick table-heading">Total Points <br>
      (0-18) </td>
    <td style="width:10%" class="table-heading-line-thick table-heading">Replication Over Time (Y/N)</td>
  </tr>
  <tr>
    <td class="table-heading-line-thick table-heading">Description</td>
    <td class="table-heading-line-thick table-text">Case-level, family segregation, or case-control data that support the gene-disease association </td>
    <td class="table-heading-line-thick table-text">Gene-level experimental evidence that support the gene-disease association</td>
    <td class="table-heading-line-thick table-text">Sum of Genetic &amp; Experimental <br>
      Evidence </td>
    <td class="table-heading-line-thick table-text">&gt; 2 pubs w/ convincing evidence over time (&gt;3 yrs)</td>
  </tr>
  <tr>
    <td class="table-heading-line-thick table-heading-bg table-heading">Assigned Points</td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border">{{ $record['score_data_array']['summary']['GeneticEvidencePointsTotal'] ?? null }}</td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border">{{ $record['score_data_array']['summary']['ExperimentalEvidencePointsTotal'] ?? null }}</td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border">{{ $record['score_data_array']['summary']['EvidencePointsTotal'] ?? null }}</td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border">{{ $record['score_data_array']['ReplicationOverTime']['YesNo'] ?? null }}</td>
  </tr>
  <tr class="LIMITED">
    <td colspan="2" rowspan="4" class="table-heading-line-thick table-heading ">CALCULATED CLASSIFICATION</td>
    <td class="table-heading EvidenceLimitedBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">LIMITED</td>
    <td colspan="2" class="table-heading EvidenceLimitedBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">1-6</td>
  </tr>
  <tr class="MODERATE">
    <td class="table-heading EvidenceModerateBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">MODERATE</td>
    <td colspan="2" class="table-heading EvidenceModerateBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">7-11</td>
  </tr>
  <tr class="STRONG">
    <td class="table-heading EvidenceStrongBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">STRONG</td>
    <td colspan="2" class="table-heading EvidenceStrongBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">12-18</td>
  </tr>
  <tr class="DEFINITIVE">
    <td class="table-heading-line-thick table-heading EvidenceDefinitiveBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">DEFINITIVE</td>
    <td colspan="2" class="table-heading-line-thick table-heading EvidenceDefinitiveBg {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}">12-18 AND replication over time</td>
  </tr>
  <tr>
    <td class="table-heading-line-thick table-heading">Valid contradictory evidence (Y/N)* <br></td>
    <td colspan="4" class="table-heading-line-thick text-left"><div class="input-width-pmid">
      <div class="form-group">
        <table>
          <tr>
            <td class="col-sm-2">
            			{{ $record['score_data_array']['ValidContradictoryEvidence']['YesNo'] ?? null }}
            </td>
            <td class="col-sm-10">{{ App\Helper::PrintWrapperPmid($record['score_data_array']['ValidContradictoryEvidence']['pmid'] ?? null) }}
            </td>
          </tr>
        </table>
      </div>
      </div></td>
  </tr>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right table-border-thin">CALCULATED CLASSIFICATION (DATE)</td>
    <td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive "><div class='col-sm-8 '>
      {{ $record['score_data_array']['summary']['CalculatedClassification'] ?? null }}
      </div>
      <div class='col-sm-4'>
      {{ $record['score_data_array']['summary']['CalculatedClassificationDate'] ?? null }}
      </div></td>
  </tr>
  		@if (($record['score_data_array']['summary']['CuratorModifyCalculation'] ?? null) == "YES"))

  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right"> MODIFY CALCULATED CLASSIFICATION </td>
    <td colspan="3" class="table-heading-bg table-heading text-left CalculatedClassificationsActive-2 "><div class='col-sm-12'>
      {{ $record['score_data_array']['CuratorModifyCalculation']['YesNo'] ?? null }}
      </div></td>
  </tr>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right table-border-thin"> CURATOR CLASSIFICATION (DATE) </td>
    <td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive-2 "><div class='col-sm-8'>
      {{ $record['score_data_array']['summary']['CuratorClassification'] ?? null }}
      </div>
      <div class='col-sm-4'>
      {{ $record['score_data_array']['summary']['CuratorClassificationDate'] ?? null }}
      </div>
      <div class='col-sm-12'>
      {{ $record['score_data_array']['summary']['CuratorClassificationNotes'] ?? null }}
      </div></td>
  </tr>
  @endif
		@if ($record['score_data_array']['summary']['FinalClassification'] ?? null)
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right">EXPERT CURATION (DATE)</td>
    <td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 "><div class='col-sm-8'> <span style="font-size: 145%;">
      {{ $record['score_data_array']['summary']['FinalClassification'] ?? null }}
      </span> </div>
      <div class='col-sm-4'>
      {{ $record['score_data_array']['summary']['FinalClassificationDate'] ?? null }}
      </div>
      <div class='col-sm-12'>
      {{ $record['score_data_array']['summary']['FinalClassificationNotes'] ?? null }}
      </div></td>
  </tr>
  @endif
</tbody>
</table>
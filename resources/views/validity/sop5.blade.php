<div class="form-group">
<table style="width:100%;" >
  <tr style="">
    <td style="width:15%; padding-top:5px; padding-bottom:5px; padding-right:1%;" valign="top"  nowrap class="text-right text-muted">Gene/Disease Pair:</td>
    <td style="width:85%; padding-bottom:5px"><h3 style="padding:0; margin:0"><strong style="color:#000"><i>
      {{ $score_string_sop5->genes->symbol }}
      </i>:
      {{ $score_string_sop5->condition->name }}
      </strong></h3></td>
  </tr>
  <tr style="">
    <td style="width:15%; padding-right:1%;" nowrap class="text-right text-muted"></td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $score_string_sop5->condition->curie ?? 'XXX_MISSING_XXX' }}
      @if (isset($score_string_sop5->condition->MONDO->curie))
      {{ $score_string_sop5->condition->MONDO->curie }} |
      @endif
      @if (isset($score_string_sop5->condition->OrphaNet->curie))
      {{ $score_string_sop5->condition->OrphaNet->curie }} |
      @endif
      @if (isset($score_string_sop5->condition->OMIM->curie))
      {{ $score_string_sop5->condition->OMIM->curie }}
      @endif
      </strong></td>
  </tr>
      @if (isset($score_string_sop5->scoreJson->ModeOfInheritance))
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">Mode of Inheritance:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $score_string_sop5->scoreJson->ModeOfInheritance }}
      </strong></td>
  </tr>
  @endif
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">SOP:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      <a href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">Gene Clinical Validity Standard Operating Procedures (SOP), Version 5</a>
      </strong></td>
  </tr>
</table>
</div>
<hr />
<table class="table table-compact table-bordered table-border-normal">
	<tbody>
		<tr>
			<td rowspan="17" class="table-heading-line-thick table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Genetic Evidence</div>
				</div>
			</td>
			<td rowspan="12" class="table-heading-line-normal table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Case-Level Data</div>
				</div>
			</td>
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
			<td rowspan="5" class="table-title table-title-vertical table-border-thin">
				<div class="table-title-text">
					<div class="table-title-text-inner">Variant Evidence</div>
				</div>
			</td>
			<td rowspan="3" class="table-title table-border-thin">Autosomal Dominant or X-linked Disorder</td>
			<td colspan="3">Variant is de novo</td>
			<td>2</td>
			<td>0-3</td>
			<td>12</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Value }}
				</div>
			</td>
			<td class="points-tally-bg">
				{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Tally ?? '' }}
			</td>
			<td class="input-width-pmid"><span class="input-width-pmid ">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->evidence ?? null) }}
			</span></td>
		</tr>
		<tr>
			<td colspan="3">Proband with predicted or proven null variant</td>
			<td>1.5</td>
			<td>0-2</td>
			<td id="GeneticEvidence3Max">10</td>
			<td class="input-width-numbers points-given-bg">
				<span class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Value }}
			</span>								</td>
			<td class="points-tally-bg">
				{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Tally ?? ''}}
			<td class="input-width-pmid ">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->evidence ?? null) }}
		  </td>
		</tr>
		<tr>
		  <td colspan="3" class='table-border-thin'>Proband with other variant type with some evidence of gene impact</td>
		  <td class='table-border-thin'>0.5</td>
		  <td class='table-border-thin'>0-1.5</td>
			<td id="GeneticEvidence1Max" class='table-border-thin'>7</td>
			<td class="input-width-numbers points-given-bg table-border-thin">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->Value }}

				</div>
			</td>
			<td class=" points-tally-bg table-border-thin">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->Tally ?? '' }}

			<td class="input-width-pmid table-border-thin">
					{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->evidence ?? null) }}


			  </td>
		</tr>
		<tr>
			<td rowspan="2" class="table-title table-border-thin">Autosomal Recessive Disease</td>
			<td colspan="3">Two variants in trans and at least one de novo or a predicted/proven null variant</td>
			<td>2</td>
			<td>0-3</td>
			<td rowspan="2" id="GeneticEvidence4Max" class=' table-border-thin'>12</td>
			<td class="input-width-numbers  points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->Value }}
				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->Tally ?? null }}
			</td>
			<td class="input-width-pmid">
					{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->evidence ?? null) }}
			</td>
		</tr>
		<tr>
		  <td colspan="3" class='table-border-thin'>Two variants (not predicted/proven null) with some evidence of gene impact in trans</td>
		  <td class='table-border-thin'>1</td>
		  <td class='table-border-thin'>0-1.5</td>
			<td class=' input-width-numbers  points-given-bg table-border-thin'><div class="form-group">
				{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->Value ?? null }}
			</div></td>
			<td class="input-width-pmid  table-border-thin">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->evidence ?? null) }}

			</td>
		</tr>
		<tr>
		  <td colspan="2" rowspan="5" class="table-heading-line-normal table-title">Segregation Evidence</td>
		  <td rowspan="5" class="table-heading-line-normal">Evidence of segregation in one or more families</td>
		  <td class="">&nbsp;</td>
		  <td colspan="2">Sequencing Method</td>
		  <td rowspan="5" class="table-heading-line-normal">0-3</td>
		  <td rowspan="5" class="table-heading-line-normal" id="GeneticEvidence5Max2">3</td>
		  <td rowspan="5" class="table-heading-line-normal input-width-numbers  points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->EvidenceOfSegregationInOneOrMoreFamilies->Value }}

				</div></td>
		  <td rowspan="5" class="table-heading-line-normal points-tally-bg">
				{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->EvidenceOfSegregationInOneOrMoreFamilies->Tally ?? ''}}

		  </td>
		  <td class="" style="border-style: none">&nbsp;</td>
	  </tr>
		<tr>
			<td class="">Total LOD Score</td>
			<td>Canditate Gene Sequencing</td>
			<td>Exome/Genome or all genes sequenced in linkage region</td>
		  <td class="" style="border-style: none">&nbsp;</td>
		</tr>
		<tr>
		  <td class="">2-2.99</td>
		  <td>0.5</td>
		  <td>1</td>
		  <td class="input-width-pmid">
		  	{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->EvidenceOfSegregationInOneOrMoreFamilies->XX1->evidence ?? null) }}

			{{-- App\Helper::PrintWrapperPmid($inputThisData["scoreJson"]["GeneticEvidence"]["CaseLevelData"]["SegregationEvidence"]["EvidenceOfSegregationInOneOrMoreFamilies"][1], "scoreJson[GeneticEvidence][CaseLevelData][SegregationEvidence][EvidenceOfSegregationInOneOrMoreFamilies][1]") --}}
		  </td>
	  </tr>
		<tr>
		  <td class="">3-4.99</td>
		  <td>1</td>
		  <td>2</td>
		  <td class="input-width-pmid">
		  	{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->EvidenceOfSegregationInOneOrMoreFamilies->XX2->evidence ?? null) }}

		  </td>
	  </tr>
		<tr>
		  <td class="table-heading-line-normal">&ge;5</td>
		  <td class="table-heading-line-normal">1.5</td>
		  <td class="table-heading-line-normal">3</td>
		  <td class="input-width-pmid table-heading-line-normal ">
		  	{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->EvidenceOfSegregationInOneOrMoreFamilies->XX3->evidence ?? null) }}

		  </td>
	  </tr>
		<tr>
			<td rowspan="4" class="table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Case-Control Data</div>
				</div>
			</td>
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
			<td colspan="3" rowspan="2" class="text-left">1. Variant Detection Methodology<br>
				2. Power<br>
				3. Bias and confounding<br>
				4. Statistical Significance</td>
			<td colspan="2">0-6</td>
			<td rowspan="2" id="GeneticEvidence6Max">12</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Value }}

				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Tally ?? '' }}

			</td>
			<td class="input-width-pmid">
					{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseControlData->SingleVariantAnalysis->evidence ?? null) }}

			</td>
		</tr>
		<tr>
			<td colspan="2" class="table-title">Aggregate Variant Analysis</td>
			<td colspan="2">0-6</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->Value }}

				</div>
			</td>
			<td class="input-width-pmid">
					{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Genetic Evidence Points (Maximum <span id="GeneticEvidenceMax">12</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->TotalGeneticEvidencePoints->Tally ?? '' }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
					{{ $score_string_sop5->scoreJson->GeneticEvidence->TotalGeneticEvidencePoints->notes }}
        </div>
			</td>
		</tr>
		<tr>
			<td rowspan="14" class="table-heading-line-thick table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Experimental Evidence</div>
				</div>
			</td>
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
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Function->BiochemicalFunction->Value }}

				</div>
			</td>
			<td rowspan="3" class=" points-tally-bg table-border-thin">
					{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Function->Tally ?? ''}}
			</td>
			<td class="input-width-pmid">
			  {{-- App\Helper::PrintWrapperPmid($inputThisData["scoreJson"]["ExperimentalEvidence"]["Function"]["BiochemicalFunction"], "scoreJson[ExperimentalEvidence][Function][BiochemicalFunction]") --}}
			  {{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Function->BiochemicalFunction->evidence ?? null) }}

			</td>
		</tr>
		<tr>
			<td colspan="3">Protein Interaction</td>
			<td>0.5</td>
			<td>0 - 2</td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Function->ProteinInteraction->Value }}
			</span></td>
			<td class="input-width-pmid">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Function->ProteinInteraction->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="3" class=' table-border-thin'>Expression</td>
			<td class=' table-border-thin'>0.5</td>
			<td class=' table-border-thin'>0 - 2</td>
			<td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Function->Expression->Value }}
			</span></td>
			<td class="input-width-pmid table-border-thin">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Function->Expression->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="3" rowspan="2" class="table-title table-border-thin">Functional Alteration</td>
			<td colspan="3">Patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td rowspan="2" class=' table-border-thin' id="ExperimentalEvidence2Max">2</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->ExperimentalEvidence->FunctionalAlteration->PatientCells->Value }}
				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
					{{ $score_string_sop5->scoreJson->ExperimentalEvidence->FunctionalAlteration->Tally ?? '' }}
			</td>
			<td class="input-width-pmid">
			  {{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->FunctionalAlteration->PatientCells->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="3" class='table-border-thin'>Non-patient cells</td>
			<td class='table-border-thin'>0.5</td>
			<td class='table-border-thin'>0 - 1</td>
			<td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
						{{ $score_string_sop5->scoreJson->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->Value }}
			</span></td>
			<td class="input-width-pmid table-border-thin">
			  {{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->evidence ?? null) }}
			</td>
		</tr>
		<tr>
		  <td colspan="3" rowspan="2" class="table-title table-border-thin"><span class="">Models</span></td>
		  <td colspan="3" class=''>Non-human model organism</td>
		  <td class=''>2</td>
		  <td class=''>0 - 4</td>
		  <td rowspan="6" class='' id="">4</td>
		  <td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Models->NonHumanModelOrganism->Value }}
		  </span></td>
		  <td rowspan="6" class=" points-tally-bg">
						{{ $score_string_sop5->scoreJson->ExperimentalEvidence->ModelsRescue->Tally ?? ''}}
		  </td>
		  <td class="input-width-pmid">
		  	{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->ModelsRescue->NonHumanModelOrganism->evidence ?? null) }}
			</td>
	  </tr>
		<tr>
		  <td colspan="3" class='table-border-thin'>Cell culture model </td>
		  <td class='table-border-thin'>1</td>
		  <td class='table-border-thin'>0 - 2</td>
		  <td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Models->CellCultureModel->Value }}
		  </span></td>
		  <td class="input-width-pmid  table-border-thin">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Models->CellCultureModel->evidence ?? null) }}

		  </td>
	  </tr>
		<tr>
			<td colspan="3" rowspan="4" class="table-title">Rescue</td>
			<td colspan="3">Rescue in human</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
					{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInHuman->Value }}
				</div>
			</td>
			<td class="input-width-pmid">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInHuman->evidence ?? null) }}

			</td>
		</tr>
		<tr>
			<td colspan="3">Rescue in non-human model organism</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers points-given-bg"><div class="form-group">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->Value }}
				</div></td>
			<td class="input-width-pmid">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="3">Rescue in cell culture model</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInCellCultureModel->Value }}
			</span></td>
			<td class="input-width-pmid">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInCellCultureModel->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="3">Rescue in patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInPatientCell->Value }}
			</span></td>
			<td class="input-width-pmid">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ExperimentalEvidence->Rescue->RescueInPatientCell->evidence ?? null) }}
			</td>
		</tr>
		<tr>
			<td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Experimental Evidence Points (Maximum <span id="ExperimentalEvidenceMax">6</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->TotalExperimentalEvidencePoints->Tally ?? '' }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
				{{ $score_string_sop5->scoreJson->ExperimentalEvidence->TotalExperimentalEvidencePoints->notes }}
        </div>

			</td>
		</tr>
	</tbody>
</table>
<p>&nbsp;</p>
<hr />
<p>&nbsp;</p>
<table class="table table-condensed table-bordered table-border-normal">
	<tbody>
		<tr>
			<td style="width:25%" class="table-heading-line-thick table-heading">Assertion criteria</td>
			<td style="width:25%" class="table-heading-line-thick table-heading">Genetic Evidence (0-12 points)</td>
			<td style="width:25%" class="table-heading-line-thick table-heading">Experimental Evidence
				<br> (0-6 points)</td>
			<td style="width:15%" class="table-heading-line-thick table-heading">Total Points
				<br> (0-18)
			</td>
			<td style="width:10%" class="table-heading-line-thick table-heading">Replication Over Time (Y/N)</td>
		</tr>
		<tr>
			<td class="table-heading-line-thick table-heading">Description</td>
			<td class="table-heading-line-thick table-text">Case-level, family segregation, or case-control data that support the gene-disease association
			</td>
			<td class="table-heading-line-thick table-text">Gene-level experimental evidence that support the gene-disease association</td>
			<td class="table-heading-line-thick table-text">Sum of Genetic &amp; Experimental
				<br> Evidence
			</td>
			<td class="table-heading-line-thick table-text">&gt; 2 pubs w/ convincing evidence over time (&gt;3 yrs)</td>
		</tr>
		<tr>
			<td class="table-heading-line-thick table-heading-bg table-heading">Assigned Points</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				{{-- $inputThisData["scoreJson"]["summary"]["GeneticEvidenceTotal"] --}}
				{{ $score_string_sop5->scoreJson->summary->GeneticEvidenceTotal }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				{{-- $inputThisData["scoreJson"]["summary"]["ExperimentalEvidenceTotal"] --}}
				{{ $score_string_sop5->scoreJson->summary->ExperimentalEvidenceTotal }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				{{-- $inputThisData["scoreJson"]["summary"]["EvidencePointsTotal"] --}}
				{{ $score_string_sop5->scoreJson->summary->EvidencePointsTotal }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				@if ($score_string_sop5->scoreJson->ReplicationOverTime == "YES")
					YES
				@else
					NO
				@endif
			</td>
		</tr>
		<tr>
			<td colspan="2" rowspan="4" class="table-heading-line-thick table-heading">CALCULATED CLASSIFICATION</td>
			<td class="table-heading EvidenceLimitedBg">LIMITED</td>
			<td colspan="2" class="table-heading EvidenceLimitedBg">1-6</td>
		</tr>
		<tr>
			<td class="table-heading EvidenceModerateBg">MODERATE</td>
			<td colspan="2" class="table-heading EvidenceModerateBg">7-11</td>
		</tr>
		<tr>
			<td class="table-heading EvidenceStrongBg">STRONG</td>
			<td colspan="2" class="table-heading EvidenceStrongBg">12-18</td>
		</tr>
		<tr>
			<td class="table-heading-line-thick table-heading EvidenceDefinitiveBg">DEFINITIVE</td>
			<td colspan="2" class="table-heading-line-thick table-heading EvidenceDefinitiveBg">12-18 AND replication over time</td>
		</tr>
		<tr>
			<td class="table-heading-line-thick table-heading">Valid contradictory evidence (Y/N)*
				<br>
			</td>
			<td colspan="4" class="table-heading-line-thick text-left">

				<div class="input-width-pmid">
					<div class="form-group">
						<table>
							<tr>
								<td class="col-sm-2">
				@if ($score_string_sop5->scoreJson->ValidContradictoryEvidence->value == "YES")
					YES
				@else
					NO
				@endif
								</td>
								<td class="col-sm-10">
				{{ App\Helper::PrintWrapperPmidSop5($score_string_sop5->scoreJson->ValidContradictoryEvidence->evidence ?? null) }}
								</td>
							</tr>
						</table>
					</div>

				</div>
			</td>
		</tr>



		<tr>
			<td colspan="2" class="table-heading-bg table-heading text-right table-border-thin">CALCULATED CLASSIFICATION (DATE)</td>
			<td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive">
				<div class='col-sm-8'>
					{{ $score_string_sop5->scoreJson->summary->CalculatedClassification }}
				</div>
				<div class='col-sm-4'>
					{{ $score_string_sop5->scoreJson->summary->CalculatedClassificationDate }}
				</div>
					</td>
		</tr>
		@if (($score_string_sop5->scoreJson->summary->CuratorModifyCalculation ?? null) == "YES")
		<tr>
			<td colspan="2" class="table-heading-bg table-heading text-right">
				MODIFY CALCULATED CLASSIFICATION
			</td>
			<td colspan="3" class="table-heading-bg table-heading text-left CalculatedClassificationsActive">
				<div class='col-sm-12'>
          @if (($score_string_sop5->scoreJson->summary->CuratorModifyCalculation ?? null) == "YES")
					YES
				@else
					NO
				@endif
          </div>
					</td>
		</tr>
		<tr>
			<td colspan="2" class="table-heading-bg table-heading text-right table-border-thin">
				CURATOR CLASSIFICATION (DATE)
			</td>
			<td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive">
				<div class='col-sm-8'>

          {{ $score_string_sop5->scoreJson->summary->CuratorClassification }}

				</div>
				<div class='col-sm-4'>

          {{ $score_string_sop5->scoreJson->summary->CuratorClassificationDate }}
				</div>
				<div class='col-sm-12'>
          {{ $score_string_sop5->scoreJson->summary->CuratorClassificationNotes }}</div>
					</td>
		</tr>
		@endif

		@if ($score_string_sop5->scoreJson->summary->FinalClassification)
		<tr>
			<td colspan="2" class="table-heading-bg table-heading text-right">EXPERT CURATION (DATE)</td>
			<td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 CalculatedClassificationsActive">
				<div class='col-sm-8'><span style="font-size: 145%;">
          {{ $score_string_sop5->scoreJson->summary->FinalClassification }}</span>
				</div>
				<div class='col-sm-4'>
          {{ $score_string_sop5->scoreJson->summary->FinalClassificationDate }}
				</div>
				<div class='col-sm-12'>
          {{ $score_string_sop5->scoreJson->summary->FinalClassificationNotes }}</div>

					</td>

		</tr>
		@endif
	</tbody>
</table>

<div class="form-group">
<table style="width:100%;" >
  <tr style="">
    <td style="width:15%; padding-top:5px; padding-bottom:5px; padding-right:1%;" valign="top"  nowrap class="text-right text-muted">Gene/Disease Pair:</td>
    <td style="width:85%; padding-bottom:5px"><h3 style="padding:0; margin:0"><strong style="color:#000"><i>
      {{ $geneSymbol ?? '' }}
      </i>:
      {{ $diseaseName ?? '' }}
      </strong></h3></td>
  </tr>
  <tr style="">
    <td style="width:15%; padding-right:1%;" nowrap class="text-right text-muted"></td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $geneCurie ?? '' }}
| 
      {{ $diseaseCurie ?? '' }}
      <!-- TODO - Print out equivalents -->   
      
      </strong></td>
  </tr>
  @if (isset($score_string->ModeOfInheritance))
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">Mode of Inheritance:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $score_string->ModeOfInheritance }}
      </strong></td>
  </tr>
  @endif
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">Expert Panel:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      {{ $assertion->attributions->first->label ?? '' }}
      </strong></td>
  </tr>
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">SOP:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      <a href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">Gene Clinical Validity Standard Operating Procedures (SOP), Version 6</a>
      </strong></td>
  </tr>
</table>
</div>
<hr />
<table class="table table-compact table-bordered table-border-normal">
	<tbody>
		<tr>
			<td rowspan="16" class="table-heading-line-thick table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Genetic Evidence</div>
				</div>
			</td>
			<td rowspan="11" class="table-heading-line-normal table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Case-Level Data</div>
				</div>
			</td>
			<td colspan="2" rowspan="2" class="table-heading-bg table-heading">Evidence Type</td>
			<td rowspan="2" class="table-heading-bg table-heading">Case Information Type</td>
			<td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines</td>
			<td class="table-heading-bg table-heading table-heading-tight"></td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" style="width:40%" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
			<td class="table-heading-bg table-heading table-heading-tight">Default</td>
			<td class="table-heading-bg table-heading table-heading-tight">Range</td>
			<td class="table-heading-bg table-heading table-heading-tight">Max</td>
			<td class="table-heading-bg table-heading table-heading-tight">Count</td>
			<td class="table-heading-bg table-heading table-heading-tight points-given-bg">Total</td>
			<td class="table-heading-bg table-heading table-heading-tight points-tally-bg">Counted</td>
		</tr>
		<tr>
			<td rowspan="5" class="table-title table-title-vertical table-border-thin">
				<div class="table-title-text">
					<div class="table-title-text-inner">Variant Evidence</div>
				</div>
			</td>
			<td rowspan="3" class="table-title table-border-thin">Autosomal Dominant or X-linked Disorder</td>
			<td>Variant is de novo</td>
			<td>2</td>
			<td>0-3</td>
			<td>12</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Count ?? null }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->TotalPoints ?? null }}
				</div>
			</td>
			<td class="points-tally-bg">
				{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid"><span class="input-width-pmid ">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Evidence) ?? null }}
			</span></td>
		</tr>
		<tr>
			<td>Proband with predicted or proven null variant</td>
			<td>1.5</td>
			<td>0-2</td>
			<td id="GeneticEvidence3Max">10</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<span class="form-group">
					{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->TotalPoints ?? null }}
			</span>								</td>
			<td class="points-tally-bg">
				{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->PointsCounted ?? null }}
			<td class="input-width-pmid ">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Evidence) ?? null }}
		  </td>
		</tr>
		<tr>
		  <td class='table-border-thin'>Proband with other variant type with some evidence of gene impact</td>
		  <td class='table-border-thin'>0.5</td>
		  <td class='table-border-thin'>0-1.5</td>
			<td id="GeneticEvidence1Max" class='table-border-thin'>7</td>
			<td class="input-width-numbers table-border-thin"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->Count }}</span></td>
			<td class="input-width-numbers points-given-bg table-border-thin">
				<div class="form-group">
									{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->TotalPoints ?? null }}

				</div>
			</td>
			<td class=" points-tally-bg table-border-thin">
								{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->PointsCounted ?? null }}

			<td class="input-width-pmid table-border-thin">
								{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->Evidence) ?? null }}


			  </td>
		</tr>
		<tr>
			<td rowspan="2" class="table-title table-border-thin">Autosomal Recessive Disease</td>
			<td>Two variants in trans and at least one de novo or a predicted/proven null variant</td>
			<td>2</td>
			<td>0-3</td>
			<td rowspan="2" id="GeneticEvidence4Max" class=' table-border-thin'>12</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->Count }}</span></td>
			<td class="input-width-numbers  points-given-bg">
				<div class="form-group">
					{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->TotalPoints ?? null }}
				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
					{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid">
								{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
		  <td class='table-border-thin'>Two variants (not predicted/proven null) with some evidence of gene impact in trans</td>
		  <td class='table-border-thin'>1</td>
		  <td class='table-border-thin'>0-1.5</td>
		  <td class=' input-width-numbers table-border-thin'><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->Count }}</span></td>
			<td class=' input-width-numbers  points-given-bg table-border-thin'><div class="form-group">
				{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->TotalPoints ?? null }}
			</div></td>
			<td class="input-width-pmid  table-border-thin">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->Evidence) ?? null }}

			</td>
		</tr>
		<tr>
		  <td colspan="2" rowspan="4" class="table-title table-title-vertical table-heading-line-normal">Segregation Evidence</td>
		  <td colspan="2" class='table-border-thin' style="background-color: #f1f1f1">&nbsp;</td>
		  <td colspan="2" class='table-border-thin' style="background-color: #f1f1f1">Summed LOD</td>
		  <td class=' input-width-numbers table-border-thin' style="background-color: #f1f1f1">Family Count</td>
		  <td rowspan="4" class=' input-width-numbers  points-given-bg table-heading-line-normal'>{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->PointsCounted }}</td>
		  <td rowspan="4" class=" points-tally-bg table-heading-line-normal">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->PointsCounted }}</td>
		  <td class="input-width-pmid  table-border-thin" style="background-color: #f1f1f1">&nbsp;</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-border-thin'>Candidate gene sequencing</td>
		  <td colspan="2" class='table-border-thin'>{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->SummedLod }}</td>
		  <td class=' input-width-numbers table-border-thin'>{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->FamilyCount }}</td>
		  <td class="input-width-pmid  table-border-thin">{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->Evidence) }}</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-border-thin'>Exome/genome or all genes sequenced in linkage region</td>
		  <td colspan="2" class='table-border-thin'>{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->SummedLod }}</td>
		  <td class=' input-width-numbers table-border-thin'>{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->FamilyCount }}</td>
		  <td class="input-width-pmid  table-border-thin">{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->Evidence) }}</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-heading-line-normal'>Total Summed LOD Score</td>
		  <td colspan="2" class='table-heading-line-normal'><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseLevelData->SegregationEvidence->TotalPoints }}</span></td>
		  <td class=' input-width-numbers table-heading-line-normal' style="background-color: #f1f1f1">&nbsp;</td>
		  <td class="input-width-pmid  table-heading-line-normal" style="background-color: #f1f1f1">&nbsp;</td>
	  </tr>
		<tr>
			<td rowspan="4" class="table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Case-Control Data</div>
				</div>
			</td>
			<td colspan="2" rowspan="2" class="table-heading-bg table-heading">Case-Control Study Type</td>
			<td rowspan="2" class="table-heading-bg table-heading">Case-Control Quality Criteria</td>
			<td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines </td>
			<td  class="table-heading-bg table-heading table-heading-tight"> </td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
		  <td colspan="2" class='table-heading-bg table-heading table-heading-tight'>Points/Study</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Max</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Count</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Points</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Counted</td>
	  </tr>
		<tr>
			<td colspan="2" class="table-title">Single Variant Analysis</td>
			<td rowspan="2" class="text-left">1. Variant Detection Methodology<br> 
				2. Power<br> 
				3. Bias and confounding<br> 
				4. Statistical Significance</td>
			<td colspan="2">0-6</td>
			<td rowspan="2" id="GeneticEvidence6Max">12</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
														{{ $assertionScoreJsonGci->GeneticEvidence->CaseControlData->SingleVariantAnalysis->TotalPoints ?? null }}

				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg">
														{{ $assertionScoreJsonGci->GeneticEvidence->CaseControlData->PointsCounted ?? null }}

			</td>
			<td class="input-width-pmid">
						  			  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Evidence) ?? null }}

			</td>
		</tr>
		<tr>
			<td colspan="2" class="table-title">Aggregate Variant Analysis</td>
			<td colspan="2">0-6</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
								{{ $assertionScoreJsonGci->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->TotalPoints ?? null }}

				</div>
			</td>
			<td class="input-width-pmid">
						  			  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td colspan="9" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Genetic Evidence Points (Maximum <span id="GeneticEvidenceMax">12</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
												{{ $assertionScoreJsonGci->GeneticEvidence->TotalGeneticEvidencePoints->PointsCounted ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
									{{ $assertionScoreJsonGci->GeneticEvidence->TotalGeneticEvidencePoints->notes ?? null }}
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
			<td rowspan="2" class="table-heading-bg table-heading">Evidence Type</td>
			<td colspan="3" class="table-heading-bg table-heading table-heading-tight">Guidelines </td>
			<td class="table-heading-bg table-heading table-heading-tight"></td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
		  <td class='table-heading-bg table-heading table-heading-tight'>Default</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Range</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Max</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Count</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Total</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Counted</td>
	  </tr>
		<tr>
			<td colspan="3" rowspan="3" class="table-title  table-border-thin">Function</td>
			<td>Biochemical Function</td>
			<td>0.5</td>
			<td>0 - 2</td>
			<td rowspan="3" class='table-border-thin' id="ExperimentalEvidence1Max">2</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->BiochemicalFunction->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->BiochemicalFunction->TotalPoints ?? null }}

				</div>
			</td>
			<td rowspan="3" class=" points-tally-bg table-border-thin">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid">
						  			  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Function->BiochemicalFunction->Evidence) ?? null }}

			</td>
		</tr>
		<tr>
			<td>Protein Interaction</td>
			<td>0.5</td>
			<td>0 - 2</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->ProteinInteraction->Count }}</span></td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
				{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->ProteinInteraction->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Function->ProteinInteraction->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td class=' table-border-thin'>Expression</td>
			<td class=' table-border-thin'>0.5</td>
			<td class=' table-border-thin'>0 - 2</td>
			<td class="input-width-numbers table-border-thin"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->Expression->Count }}</span></td>
			<td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Function->Expression->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid table-border-thin">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Function->Expression->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td colspan="3" rowspan="2" class="table-title table-border-thin">Functional Alteration</td>
			<td>Patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td rowspan="2" class=' table-border-thin' id="ExperimentalEvidence2Max">2</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->PatientCells->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->PatientCells->TotalPoints ?? null }}
				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid">
			  {{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->PatientCells->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td class='table-border-thin'>Non-patient cells</td>
			<td class='table-border-thin'>0.5</td>
			<td class='table-border-thin'>0 - 1</td>
			<td class="input-width-numbers table-border-thin"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->Count }}</span></td>
			<td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid table-border-thin">
			  {{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
		  <td colspan="3" rowspan="2" class="table-title table-border-thin"><span class="">Models</span></td>
		  <td class=''>Non-human model organism</td>
		  <td class=''>2</td>
		  <td class=''>0 - 4</td>
		  <td rowspan="6" class='' id="">4</td>
		  <td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Models->NonHumanModelOrganism->Count }}</span></td>
		  <td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints ?? null }}
		  </span></td>
		  <td rowspan="6" class=" points-tally-bg">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->ModelsRescue->PointsCounted ?? null }}
		  </td>
		  <td class="input-width-pmid">
		  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->ModelsRescue->NonHumanModelOrganism->Evidence) ?? null }}
			</td>
	  </tr>
		<tr>
		  <td class='table-border-thin'>Cell culture model </td>
		  <td class='table-border-thin'>1</td>
		  <td class='table-border-thin'>0 - 2</td>
		  <td class="input-width-numbers table-border-thin"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Models->CellCultureModel->Count }}</span></td>
		  <td class="input-width-numbers points-given-bg table-border-thin"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Models->CellCultureModel->TotalPoints ?? null }}
		  </span></td>
		  <td class="input-width-pmid  table-border-thin">
						  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Models->CellCultureModel->Evidence) ?? null }}

		  </td>
	  </tr>
		<tr>
			<td colspan="3" rowspan="4" class="table-title">Rescue</td>
			<td>Rescue in human</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers "><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInHuman->Count }}</span></td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInHuman->TotalPoints ?? null }}
				</div>
			</td>
			<td class="input-width-pmid">
						  	{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInHuman->Evidence) ?? null }}

			</td>
		</tr>
		<tr>
			<td>Rescue in non-human model organism</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->Count }}</span></td>
			<td class="input-width-numbers points-given-bg"><div class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->TotalPoints ?? null }}
				</div></td>
			<td class="input-width-pmid">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td>Rescue in cell culture model</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInCellCultureModel->Count }}</span></td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInCellCultureModel->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInCellCultureModel->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td>Rescue in patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers"><span class="form-group">{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInPatientCell->Count }}</span></td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInPatientCell->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ExperimentalEvidence->Rescue->RescueInPatientCell->Evidence) ?? null }}
			</td>
		</tr>
		<tr>
			<td colspan="9" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Experimental Evidence Points (Maximum <span id="ExperimentalEvidenceMax">6</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->TotalExperimentalEvidencePoints->PointsCounted ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
						{{ $assertionScoreJsonGci->ExperimentalEvidence->TotalExperimentalEvidencePoints->notes ?? null }}
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
				<?=$inputThisData["summary"]["GeneticEvidenceTotal"]?>
				{{ $assertionScoreJsonGci->summary->GeneticEvidenceTotal ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				<?=$inputThisData["summary"]["ExperimentalEvidenceTotal"]?>
				{{ $assertionScoreJsonGci->summary->ExperimentalEvidenceTotal ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				<?=$inputThisData["summary"]["EvidencePointsTotal"]?>
				{{ $assertionScoreJsonGci->summary->EvidencePointsTotal ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-total table-total-border">
				@if ($assertionScoreJsonGci.dig("ReplicationOverTime") == "YES ?? null }}
					YES
				@endif
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
				@if (($assertionScoreJsonGci->ValidContradictoryEvidence->Value ?? null) == "YES")
					YES
				@elseif
					NO
				@endif
								</td>
								<td class="col-sm-10">
				{{ PrintWrapperPmidSop5Gci($assertionScoreJsonGci->ValidContradictoryEvidence->Evidence) ?? null }}
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
					{{ $assertionScoreJsonGci->summary->CalculatedClassification ?? null }}
				</div>
				<div class='col-sm-4'>
					{{ PrintDate($assertionScoreJsonGci->summary->CalculatedClassificationDate) ?? null }}
				</div>
					</td>
		</tr>
		@if (($assertionScoreJsonGci->summary->CuratorModifyCalculation") ?? null) == "YES")
		<tr>
			<td colspan="2" class="table-heading-bg table-heading text-right">
				MODIFY CALCULATED CLASSIFICATION
			</td>
			<td colspan="3" class="table-heading-bg table-heading text-left CalculatedClassificationsActive">
				<div class='col-sm-12'>
          @if (($assertionScoreJsonGci->summary->CuratorModifyCalculation) ?? null) == "YES)
					YES
				@endif
					NO
				@endif
          </div>
					</td>
		</tr>
		<tr>
			<td colspan="2" valign="top" class="table-heading-bg table-heading text-right 
			@if ( !$assertionScoreJsonGci->summary->CuratorClassificationNotes)
				table-border-thin
			@endif
			">
				MODIFIED CLASSIFICATION (DATE)
			</td>
			<td colspan="3" class="table-heading-bg table-heading 
			@if ( !$assertionScoreJsonGci->summary->CuratorClassificationNotes)
				table-border-thin
			@endif
			 CalculatedClassificationsActive">
				<div class='col-sm-8'>
          
          {{ $assertionScoreJsonGci->summary->CuratorClassification ?? null }}
				</div>
				<div class='col-sm-4'>
					{{ PrintDate($assertionScoreJsonGci->summary->CuratorClassificationDate") ?? null }}
				</div>
				</td>
		</tr>
				@if ($assertionScoreJsonGci.dig("summary->CuratorClassificationNotes") ?? null }}
				<tr>
					<td colspan="2" valign="top" class="table-heading-bg table-heading text-right table-border-thin">
						REASON(S) FOR CHANGE
					</td>
					<td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive">
						<div class='col-sm-12'>
		          {{ $assertionScoreJsonGci->summary->CuratorClassificationNotes ?? null }}
		        </div>
					</td>
				</tr>
				@endif
		@endif

		@if ($assertionScoreJsonGci->summary->FinalClassification)
		<tr>
			<td colspan="2" valign="top" class="table-heading-bg table-heading text-right">EXPERT CURATION (DATE)</td>
			<td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 CalculatedClassificationsActive">
				<div class='col-sm-8'><span style="font-size: 145%;">
          {{ $assertionScoreJsonGci->summary->FinalClassification }}</span>
				</div>
				<div class='col-sm-4'>
          {{ PrintDate($assertionScoreJsonGci->summary->FinalClassificationDate) ?? null }}
				</div>
		</tr>
			@if ($assertionScoreJsonGci->summary->FinalClassificationNotes)
			<tr>
					<td colspan="2" valign="top" class="table-heading-bg table-heading text-right">EVIDENCE SUMMARY</td>
					<td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 CalculatedClassificationsActive">
						<div class='col-sm-12'>
		          {{ $assertionScoreJsonGci->summary->FinalClassificationNotes }}</div>

							</td>
				</tr>
				@endif
		@endif
	</tbody>
</table>

<h3>Classification Matrix</h3>
<table id='validity_classification_matrix' class="table table-compact table-bordered table-border-normal">
	<tbody>
		<tr>
			<td rowspan="15" class="table-heading-line-thick table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Genetic Evidence</div>
				</div>
			</td>
			<td rowspan="10" class="table-heading-line-normal table-title table-title-vertical">
				<div class="table-title-text">
					<div class="table-title-text-inner ">Case-Level Data</div>
				</div>
			</td>
			<td colspan="2" rowspan="2" class="table-heading-bg table-heading">Evidence Type</td>
			<td rowspan="2" class="table-heading-bg table-heading">Case Information Type</td>
			<td colspan="5" class="table-heading-bg table-heading table-heading-tight">Guidelines</td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" style="width:40%" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
			<td class="table-heading-bg table-heading table-heading-tight">Default <div class="text-10px">(per variant)</div></td>
			<td class="table-heading-bg table-heading table-heading-tight">Range <div class="text-10px">(per variant)</div></td>
			<td class="table-heading-bg table-heading table-heading-tight">Variant Count</td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight">Proband Count</td>
			<td class="table-heading-bg table-heading table-heading-tight points-given-bg">Total<div class="text-10px">(Max)</div></td>
			<td class="table-heading-bg table-heading table-heading-tight points-tally-bg">Counted</td>
		</tr>
		<tr>
			<td rowspan="4" class="table-title table-title-vertical table-border-thin">
				<div class="table-title-text">
					<div class="table-title-text-inner">Variant Evidence</div>
				</div>
			</td>
			<td rowspan="2" class="table-title table-border-thin">Autosomal Dominant or X-linked Disorder</td>
			<td>Predicted  or proven null variant</td>
			<td>1.5</td>
			<td>0-3</td>
			<td style="background-color: #eff5fc">{{ $record->sop8_proband_with_predicted_variant_count }}</td>
			<td colspan="2" class="input-width-numbers" style="background-color: #eff5fc">{{ $record->sop8_proband_with_predicted_proband_count }}</td>
			<td class="input-width-numbers points-given-bg">
				{{ $record->sop8_proband_with_predicted_total }}
						@if ($record->sop8_proband_with_predicted_total)
                <div class="text-sm info-max">(12)</div>
						@endif
			</td>
			<td class="points-tally-bg">
				{{ $record->sop8_proband_with_predicted_points }}			</td>
			<td class="input-width-pmid">
				{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->Evidence ?? null ) !!}		  </td>
		</tr>
		<tr>
		  <td class='table-border-thin'>Other variant type</td>
		  <td class='table-border-thin'>0.1</td>
		  <td class='table-border-thin'>0-1.5</td>
		  <td id="GeneticEvidence1Max" class='table-border-thin' style="background-color: #eff5fc">{{ $record->sop8_proband_with_other_variant_count }}</td>
		  <td colspan="2" class="input-width-numbers table-border-thin" style="background-color: #eff5fc">{{ $record->sop8_proband_with_other_proband_count }}</td>
		  <td class="input-width-numbers points-given-bg table-border-thin">
			{{ $record->sop8_proband_with_other_total }}
						@if ($record->sop8_proband_with_other_total)
              <div class="text-sm info-max">(12)</div>
					  @endif
							</td>
			<td class=" points-tally-bg table-border-thin">
								{{ $record->sop8_proband_with_other_points }}

			<td class="input-width-pmid table-border-thin">
								{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantType->Evidence ?? "null" ) !!}


			  </td>
		</tr>
		<tr>
			<td rowspan="2" class="table-title table-border-thin">Autosomal Recessive Disease</td>
			<td>Predicted  or proven null variant</td>
			<td>1.5 <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="In the case of AR conditions, each variant (in trans) is scored independently, then combined"><i class='fas fa-info-circle'></i></span></td>
			<td>0-3 <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="In the case of AR conditions, each variant (in trans) is scored independently, then combined"><i class='fas fa-info-circle'></i></span></td>
			<td id="GeneticEvidence4Max" class=' ' style="background-color: #eff5fc">{{ $record->sop8_variants_autosomal_recessive_disease_with_variant_count }}</td>
			<td colspan="2" rowspan="2" class=" table-border-thin input-width-numbers" style="background-color: #eff5fc">{{ $record->sop8_variants_autosomal_recessive_disease_proband_count }}</td>
			<td rowspan="2" class="table-border-thin input-width-numbers  points-given-bg">
			{{ $record->sop8_variants_autosomal_recessive_disease_total_points }}

						@if ($record->sop8_variants_autosomal_recessive_disease_total_points)
                <div class="text-sm info-max">(12)</div>
						@endif
							</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
					{{ $record->sop8_variants_autosomal_recessive_disease_points_counted }}
		  </td>
			<td class="input-width-pmid">
								{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->ProbandWithPredictedOrProvenNull->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
		  <td class='table-border-thin'>Other variant type</td>
		  <td class='table-border-thin'>0.1 <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="In the case of AR conditions, each variant (in trans) is scored independently, then combined"><i class='fas fa-info-circle'></i></span></td>
		  <td class='table-border-thin' nowrap>0-1.5 <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="" data-original-title="In the case of AR conditions, each variant (in trans) is scored independently, then combined"><i class='fas fa-info-circle'></i></span></td>
		  <td id="GeneticEvidence4Max" class=' table-border-thin' style="background-color: #eff5fc">{{ $record->sop8_variants_autosomal_recessive_disease_other_variant_count }}</td>
		  <td class="input-width-pmid  table-border-thin">
			  {!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->ProbandWithOtherVariantType->Evidence ?? null ) !!}

			</td>
		</tr>
		<tr>
		  <td colspan="2" rowspan="4" class="table-title table-title-vertical table-heading-line-normal">Segregation Evidence</td>
		  <td colspan="2" class='table-border-thin' style="background-color: #f1f1f1">&nbsp;</td>
		  <td class='table-border-thin' style="background-color: #f1f1f1">Range</td>
		  <td class='table-border-thin' style="background-color: #f1f1f1">Summed LOD</td>
		  <td colspan="2" class=' input-width-numbers table-border-thin' style="background-color: #f1f1f1">Family Count</td>
		  <td rowspan="4" class=' input-width-numbers  points-given-bg table-heading-line-normal'>{{ $record->sop8_segregation_evidence_points_counted }}

						@if ($record->sop8_segregation_evidence_points_counted)
				<div class="text-sm info-max">(3)</div>
						@endif
				</td>
		  <td rowspan="4" class=" points-tally-bg table-heading-line-normal">{{ $record->sop8_segregation_evidence_points_counted }}</td>
		  <td class="input-width-pmid  table-border-thin" style="background-color: #f1f1f1">&nbsp;</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-border-thin'>Candidate gene sequencing</td>
		  <td rowspan="3" class='table-border-thin'>0-3</td>
		  <td class='table-border-thin' style="background-color: #eff5fc">{{ $record->sop8_candi_gene_summed }}</td>
		  <td colspan="2" class=' input-width-numbers table-border-thin' style="background-color: #eff5fc">{{ $record->sop8_candi_gene_family }}</td>
		  <td class="input-width-pmid  table-border-thin">{{ $record->sop8_candi_gene_family_evidence }}</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-border-thin'>Exome/genome or all genes sequenced in linkage region</td>
		  <td class='table-border-thin' style="background-color: #eff5fc">{{ $record->score_data->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->SummedLod ?? null}}</td>
		  <td colspan="2" class=' input-width-numbers table-border-thin' style="background-color: #eff5fc">{{ $record->score_data->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->FamilyCount ?? null}}</td>
		  <td class="input-width-pmid  table-border-thin">{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseLevelData->SegregationEvidence->ExomeSequencingMethod->Evidence ?? null) !!}</td>
	  </tr>
		<tr>
		  <td colspan="2" class='table-heading-line-normal'>Total Summed LOD Score</td>
		  <td class='table-heading-line-normal' style="background-color: #eff5fc">{{ $record->score_data->GeneticEvidence->CaseLevelData->SegregationEvidence->TotalPoints ?? null}}</td>
		  <td colspan="2" class=' input-width-numbers table-heading-line-normal' style="background-color: #f1f1f1">&nbsp;</td>
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
			<td colspan="5" class="table-heading-bg table-heading table-heading-tight">Guidelines  </td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
		  <td colspan="2" class='table-heading-bg table-heading table-heading-tight'>Points/Study</td>
		  <td colspan="3" class='table-heading-bg table-heading table-heading-tight'>Count</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Total</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Counted</td>
	  </tr>
		<tr>
			<td colspan="2" class="table-title">Single Variant Analysis</td>
			<td rowspan="2" class="text-left">1. Variant Detection Methodology<br>
				2. Power<br>
				3. Bias and confounding<br>
				4. Statistical Significance</td>
			<td colspan="2">0-6</td>
			<td colspan="3" id="GeneticEvidence6Max" style="background-color: #eff5fc">{{ $record->score_data->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
														{{ $record->score_data->GeneticEvidence->CaseControlData->SingleVariantAnalysis->TotalPoints ?? null }}

						@if ($record->score_data->GeneticEvidence->CaseControlData->SingleVariantAnalysis->TotalPoints ?? null)
                <div class="text-sm info-max">(12)</div>
						@endif

				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg">
														{{ $record->score_data->GeneticEvidence->CaseControlData->PointsCounted ?? null }}

			</td>
			<td class="input-width-pmid">
						  			  	{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseControlData->SingleVariantAnalysis->Evidence ?? null ) !!}

			</td>
		</tr>
		<tr>
			<td colspan="2" class="table-title">Aggregate Variant Analysis</td>
			<td colspan="2">0-6</td>
			<td colspan="3" id="GeneticEvidence6Max" style="background-color: #eff5fc">{{ $record->score_data->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
								{{ $record->score_data->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->TotalPoints ?? null }}

						@if ($record->score_data->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->TotalPoints ?? null)
                <div class="text-sm info-max">(12)</div>
						@endif

				</div>
			</td>
			<td class="input-width-pmid">
						  			  	{!! PrintWrapperPmidSop5Gci($record->score_data->GeneticEvidence->CaseControlData->AggregateVariantAnalysis->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Genetic Evidence Points (Maximum <span id="GeneticEvidenceMax">12</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
												{{ $record->score_data->GeneticEvidence->TotalGeneticEvidencePoints->PointsCounted ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
									{{ $record->score_data->GeneticEvidence->TotalGeneticEvidencePoints->notes ?? null }}
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
			<td colspan="4" class="table-heading-bg table-heading table-heading-tight">Guidelines </td>
			<td class="table-heading-bg table-heading table-heading-tight"></td>
			<td colspan="2" class="table-heading-bg table-heading table-heading-tight points-given-bg">Points</td>
			<td rowspan="2" class="table-heading-bg table-heading">PMIDs/Notes</td>
		</tr>
		<tr>
		  <td class='table-heading-bg table-heading table-heading-tight'>Default </td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Range</td>
		  <td colspan="2" class='table-heading-bg table-heading table-heading-tight'>Max</td>
		  <td class='table-heading-bg table-heading table-heading-tight'>Count</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-given-bg'>Total</td>
		  <td class='table-heading-bg table-heading table-heading-tight points-tally-bg'>Counted</td>
	  </tr>
		<tr>
			<td colspan="3" rowspan="3" class="table-title  table-border-thin">Function</td>
			<td>Biochemical Function</td>
			<td>0.5</td>
			<td>0 - 2</td>
			<td colspan="2" rowspan="3" class=' table-border-thin' id="ExperimentalEvidence1Max" style="">2</td>
			<td class="input-width-numbers" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Function->BiochemicalFunction->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $record->score_data->ExperimentalEvidence->Function->BiochemicalFunction->TotalPoints ?? null }}

				</div>
			</td>
			<td rowspan="3" class=" points-tally-bg table-border-thin">
						{{ $record->score_data->ExperimentalEvidence->Function->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid">
						  			  	{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Function->BiochemicalFunction->Evidence ?? null ) !!}

			</td>
		</tr>
		<tr>
			<td>Protein Interaction</td>
			<td>0.5</td>
			<td>0 - 2</td>
			<td class="input-width-numbers" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Function->ProteinInteraction->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
				{{ $record->score_data->ExperimentalEvidence->Function->ProteinInteraction->TotalPoints ?? null }}
			</td>
			<td class="input-width-pmid">
				{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Function->ProteinInteraction->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td class=' table-border-thin'>Expression</td>
			<td class=' table-border-thin'>0.5</td>
			<td class=' table-border-thin'>0 - 2</td>
			<td class="input-width-numbers table-border-thin" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Function->Expression->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg table-border-thin">
						{{ $record->score_data->ExperimentalEvidence->Function->Expression->TotalPoints ?? null }}
			</td>
			<td class="input-width-pmid table-border-thin">
				{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Function->Expression->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td colspan="3" rowspan="2" class="table-title table-border-thin">Functional Alteration</td>
			<td>Patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td colspan="2" rowspan="2" class=' table-border-thin' id="ExperimentalEvidence2Max" style="">2</td>
			<td class="input-width-numbers" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->FunctionalAlteration->PatientCells->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
				<div class="form-group">
						{{ $record->score_data->ExperimentalEvidence->FunctionalAlteration->PatientCells->TotalPoints ?? null }}
				</div>
			</td>
			<td rowspan="2" class=" points-tally-bg table-border-thin">
						{{ $record->score_data->ExperimentalEvidence->FunctionalAlteration->PointsCounted ?? null }}
			</td>
			<td class="input-width-pmid">
			  {!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->FunctionalAlteration->PatientCells->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td class='table-border-thin'>Non-patient cells</td>
			<td class='table-border-thin'>0.5</td>
			<td class='table-border-thin'>0 - 1</td>
			<td class="input-width-numbers table-border-thin" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg table-border-thin">
						{{ $record->score_data->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->TotalPoints ?? null }}
			</td>
			<td class="input-width-pmid table-border-thin">
			  {!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->FunctionalAlteration->NonPatientCells->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
		  <td colspan="3" rowspan="2" class="table-title table-border-thin"><span class="">Models</span></td>
		  <td class=''>Non-human model organism</td>
		  <td class=''>2</td>
		  <td class=''>0 - 4</td>
		  <td colspan="2" rowspan="6" class='' id="" style="">4</td>
		  <td class="input-width-numbers " style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->Count ?? null }}</td>
		  <td class="input-width-numbers points-given-bg">
						{{ $record->score_data->ExperimentalEvidence->Models->NonHumanModelOrganism->TotalPoints ?? null }}
		  </td>
		  <td rowspan="6" class=" points-tally-bg">
						{{ $record->score_data->ExperimentalEvidence->ModelsRescue->PointsCounted ?? null }}
		  </td>
		  <td class="input-width-pmid">
		  	{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->ModelsRescue->NonHumanModelOrganism->Evidence ?? null ) !!}
			</td>
	  </tr>
		<tr>
		  <td class='table-border-thin'>Cell culture model </td>
		  <td class='table-border-thin'>1</td>
		  <td class='table-border-thin'>0 - 2</td>
		  <td class="input-width-numbers  table-border-thin" style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Models->CellCultureModel->Count ?? null }}</td>
		  <td class="input-width-numbers points-given-bg table-border-thin">
						{{ $record->score_data->ExperimentalEvidence->Models->CellCultureModel->TotalPoints ?? null }}
		  </td>
		  <td class="input-width-pmid  table-border-thin">
						  	{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Models->CellCultureModel->Evidence ?? null ) !!}

		  </td>
	  </tr>
		<tr>
			<td colspan="3" rowspan="4" class="table-title">Rescue</td>
			<td>Rescue in human</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers " style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInHuman->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
			{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInHuman->TotalPoints ?? null }}			</td>
			<td class="input-width-pmid">
						  	{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Rescue->RescueInHuman->Evidence ?? null ) !!}

			</td>
		</tr>
		<tr>
			<td>Rescue in non-human model organism</td>
			<td>2</td>
			<td>0 - 4</td>
			<td class="input-width-numbers " style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg">
						{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->TotalPoints ?? null }}				</td>
			<td class="input-width-pmid">
				{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Rescue->RescueInNonHumanModelOrganism->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td>Rescue in cell culture model</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers " style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInCellCultureModel->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInCellCultureModel->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid">
				{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Rescue->RescueInCellCultureModel->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td>Rescue in patient cells</td>
			<td>1</td>
			<td>0 - 2</td>
			<td class="input-width-numbers " style="background-color: #eff5fc">{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInPatientCell->Count ?? null }}</td>
			<td class="input-width-numbers points-given-bg"><span class="form-group">
						{{ $record->score_data->ExperimentalEvidence->Rescue->RescueInPatientCell->TotalPoints ?? null }}
			</span></td>
			<td class="input-width-pmid">
				{!! PrintWrapperPmidSop5Gci($record->score_data->ExperimentalEvidence->Rescue->RescueInPatientCell->Evidence ?? null ) !!}
			</td>
		</tr>
		<tr>
			<td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Experimental Evidence Points (Maximum <span id="ExperimentalEvidenceMax">6</span>)</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg">
						{{ $record->score_data->ExperimentalEvidence->TotalExperimentalEvidencePoints->PointsCounted ?? null }}
			</td>
			<td class="table-heading-line-thick table-heading-bg table-heading table-total">
			<div class="form-group total-notes">
						{{ $record->score_data->ExperimentalEvidence->TotalExperimentalEvidencePoints->notes ?? null }}
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
          <td class="table-heading-line-thick table-heading-bg table-total table-total-border"> {{ $record->score_data->summary->GeneticEvidenceTotal ?? null }} </td>
          <td class="table-heading-line-thick table-heading-bg table-total table-total-border"> {{ $record->score_data->summary->ExperimentalEvidenceTotal ?? null }} </td>
          <td class="table-heading-line-thick table-heading-bg table-total table-total-border"> {{ $record->score_data->summary->EvidencePointsTotal ?? null }} </td>
          <td class="table-heading-line-thick table-heading-bg table-total table-total-border"> @if (($record->score_data->ReplicationOverTime ?? null) == "YES")
            YES
            @else
            NO
            @endif </td>
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
          <td class="table-heading-line-thick table-heading">Valid contradictory evidence (Y/N)* <br></td>
          <td colspan="4" class="table-heading-line-thick text-left"><div class="input-width-pmid">
            <div class="form-group">
              <table>
                <tr>
                  <td class="col-sm-2"> @if (($record->score_data->ValidContradictoryEvidence->Value ?? null) == "YES")
                    YES
                    @else
                    NO
                    @endif </td>
                  <td class="col-sm-10"> {!! PrintWrapperPmidSop5Gci($record->score_data->ValidContradictoryEvidence->Evidence ?? null ) !!} </td>
                </tr>
              </table>
            </div>
          </div></td>
        </tr>
        <tr>
          <td colspan="2" class="table-heading-bg table-heading text-right table-border-thin">CALCULATED CLASSIFICATION</td>
          <td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive"><div class='col-sm-8'> {{ $record->score_data->summary->CalculatedClassification ?? null }} </div>
            <div class='col-sm-4'> {{-- {{ displayDate($record->score_data->summary->CalculatedClassificationDate ?? null ) }} --}} </div></td>
        </tr>
      @if (($record->score_data->summary->CuratorModifyCalculation ?? null) == "YES")
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right"> MODIFY CALCULATED CLASSIFICATION </td>
    <td colspan="3" class="table-heading-bg table-heading text-left CalculatedClassificationsActive"><div class='col-sm-12'> @if (($record->score_data->summary->CuratorModifyCalculation ?? null) == "YES")
      YES
      @else
      NO
      @endif </div></td>
  </tr>
  <tr>
    <td colspan="2" valign="top" class="table-heading-bg table-heading text-right
			@if ( isset($score_json->summary->CuratorClassificationNotes))
				table-border-thin
			@endif
			"> MODIFIED CLASSIFICATION (DATE) </td>
    <td colspan="3" class="table-heading-bg table-heading
			@if (isset($score_json->summary->CuratorClassificationNotes))
				table-border-thin
			@endif
			 CalculatedClassificationsActive"><div class='col-sm-8'> {{ $record->score_data->summary->CuratorClassification ?? null }} </div>
      <div class='col-sm-4'> {{-- {{ displayDate($record->score_data->summary->CuratorClassificationDate ?? null ) }} --}} </div></td>
  </tr>
      @if ($record->score_data->summary->CuratorClassificationNotes ?? null)
  <tr>
    <td colspan="2" valign="top" class="table-heading-bg table-heading text-right table-border-thin"> REASON(S) FOR CHANGE </td>
    <td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive"><div class='col-sm-12'> {{ $record->score_data->summary->CuratorClassificationNotes ?? null }} </div></td>
  </tr>
      @endif
      @endif

      @if ($record->score_data->summary->FinalClassification ?? null)
      <tr>
        <td colspan="2" valign="top" class="table-heading-bg table-heading text-right">EXPERT CURATION (DATE)</td>
        <td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 CalculatedClassificationsActive"><div class='col-sm-8'><span style="font-size: 145%;"> @if (isset($record->score_data->summary->FinalClassification) && strtoupper($record->score_data->summary->FinalClassification) == "NO REPORTED EVIDENCE")
          No Known Disease Relationship
          @else
          {{ $record->score_data->summary->FinalClassification ?? null }}
          @endif </span> </div>
          <div class='col-sm-4'> {{ displayDate($record->score_data->summary->FinalClassificationDate ?? null ) }} </div>
        </tr>
      @if ($record->score_data->summary->FinalClassificationNotes ?? null)
  <tr>
    <td colspan="2" valign="top" class="table-heading-bg table-heading text-right">EVIDENCE SUMMARY</td>
    <td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 CalculatedClassificationsActive"><div class='col-sm-12 text-left'> {{ $record->score_data->summary->FinalClassificationNotes ?? null }}</div></td>
  </tr>
      @endif
      @endif
    </table>
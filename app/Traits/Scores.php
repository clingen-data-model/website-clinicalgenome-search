<?php

namespace App\Traits;


trait Scores
{
    /**
     * Return a displayable for replication over time out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ReplicationOverTimeAttribute()
	{
		$j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
			return $j->scoreJson->ReplicationOverTime ?? 'Unknown';
		else if (isset($j->ReplicationOverTime->YesNo)) // SOP4
			return $j->ReplicationOverTime->YesNo ?? 'Unknown';
		else // SOP5
			return $j->ReplicationOverTime ?? 'Unknown';
	}


	/**
     * Return a displayable for valid contradictory evidence out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ValidContradictoryEvidenceAttribute()
	{
		$j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
			$k = $j->scoreJson->ValidContradictoryEvidence->Value ?? '';
		elseif (isset($j->ValidContradictoryEvidence->Value))
			$k = $j->ValidContradictoryEvidence->Value ?? '';
		else
			$k = $j->ValidContradictoryEvidence->YesNo ?? '';

		return (empty($k) ? "NO" : $k);

	}


	/**
     * Return a displayable for an EP or WG name out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7AffiliationNameAttribute()
	{
		if (isset($this->attributed_to->label))
			return $this->attributed_to->label;

		$j = json_decode($this->legacy_json);

		return $j->affiliation->gcep_name ?? '';

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ContributorsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->summary->contributors ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7FinalClassificationNotesAttribute()
	{
		$j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
        {
            $k = $j->scoreJson->summary->FinalClassificationNotes;
        }
		else
        {
            $k = $j->summary->FinalClassificationNotes ?? null;
        }

        if (empty($k))
            return $k;

        $k = str_replace("\n", "\n\n", $k);

		return $k;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7VariantIsDeNovoCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Count ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7VariantIsDeNovoTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->TotalPoints ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7VariantIsDeNovoPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->PointsCounted ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithPredictedCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->Count ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithPredictedTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->TotalPoints ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithPredictedPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNullVariant->PointsCounted ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithOtherCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->Count ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithOtherTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->TotalPoints ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7ProbandWithOtherPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantTypeWithSomeEvidenceOfGeneImpact->PointsCounted ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7TwoVariantsCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->Count ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7TwoVariantsTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsInTransAndAtLeastOneDeNovoOrAPredictedProvenNullVariant->TotalPoints ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7TwoVariantsPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->PointsCounted ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7TwoVariantsNotPredictedCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->Count ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7TwoVariantsNotPredictedTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->TotalPoints ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7CandiGeneSummedAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->SummedLod ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7CandiGeneFamilyAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->FamilyCount ?? null;

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7CandiGeneFamilyEvidenceAttribute()
	{
		$j = json_decode($this->legacy_json);

		$records = [];

		$t = $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->Evidence->Publications ?? [];

		foreach ($t as $publication)
		{
			$records[] = $publication->author . ' et al. ' . $publication->pubdate . ' (PMID:'
							. $publication->pmid . ')';
		}
		return implode('; ', $records);

	}


	/**
     * Return a displayable for contributors out of the SOP7 results
     *
     * @param
     * @return string
     */
	public function getSop7SegregationEvidencePointsCountedAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->PointsCounted ?? null;

	}






	//  SOP 8 START

	/**
	 * Return a displayable for replication over time out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ReplicationOverTimeAttribute()
	{
		$j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
		return $j->scoreJson->ReplicationOverTime ?? 'Unknown';
		else if (isset($j->ReplicationOverTime->YesNo)) // SOP4
		return $j->ReplicationOverTime->YesNo ?? 'Unknown';
		else // SOP5
		return $j->ReplicationOverTime ?? 'Unknown';
	}


	/**
	 * Return a displayable for valid contradictory evidence out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ValidContradictoryEvidenceAttribute()
	{
		$j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
		$k = $j->scoreJson->ValidContradictoryEvidence->Value ?? '';
		elseif (isset($j->ValidContradictoryEvidence->Value))
		$k = $j->ValidContradictoryEvidence->Value ?? '';
		else
		$k = $j->ValidContradictoryEvidence->YesNo ?? '';

		return (empty($k) ? "NO" : $k);
	}


	/**
	 * Return a displayable for an EP or WG name out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8AffiliationNameAttribute()
	{
		if (isset($this->attributed_to->label))
		return $this->attributed_to->label;

		$j = json_decode($this->legacy_json);

		return $j->affiliation->gcep_name ?? '';
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ContributorsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->summary->contributors ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8FinalClassificationNotesAttribute()
	{

        $j = json_decode($this->legacy_json);

		if (isset($j->scoreJson))
        {
            $k = $j->scoreJson->summary->FinalClassificationNotes;
        }
		else
        {
            $k = $j->summary->FinalClassificationNotes ?? null;
        }

        if (empty($k))
            return $k;

        $k = str_replace("\n", "\n\n", $k);

		return $k;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantIsDeNovoCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->Count ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantIsDeNovoTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->TotalPoints ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantIsDeNovoPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->VariantIsDeNovo->PointsCounted ?? null;
	}


	/**
	 * Return a displayable for ProbandCount out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithPredictedProbandCountAttribute()
	{
		$j = json_decode($this->legacy_json);
		//dd($j);
		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->ProbandCount ?? null;
	}

	/**
	 * Return a displayable for ProbandCount out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithPredictedVariantCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->VariantCount ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithPredictedTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->TotalPoints ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithPredictedPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithPredictedOrProvenNull->PointsCounted ?? null;
	}


	/**
	 * Return a displayable for ProbandCount out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithOtherProbandCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantType->ProbandCount ?? null;
	}

	/**
	 * Return a displayable for VariantCount out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithOtherVariantCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantType->VariantCount ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithOtherTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantType->TotalPoints ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8ProbandWithOtherPointsAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalDominantOrXlinkedDisorder->ProbandWithOtherVariantType->PointsCounted ?? null;
	}






	/**
	 * Return a displayable for PointsCounted out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantsAutosomalRecessiveDiseaseWithVariantCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->ProbandWithPredictedOrProvenNull->VariantCount ?? null;
	}

	/**
	 * Return a displayable for PointsCounted out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantsAutosomalRecessiveDiseaseOtherVariantCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->ProbandWithOtherVariantType->VariantCount ?? null;
	}

	/**
	 * Return a displayable for PointsCounted out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantsAutosomalRecessiveDiseaseProbandCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->ProbandCount ?? null;
	}

	/**
	 * Return a displayable for TotalPoints out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantsAutosomalRecessiveDiseaseTotalPointsAttribute()
	{
		$j = json_decode($this->legacy_json);
		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TotalPoints ?? null;
	}

	/**
	 * Return a displayable for PointsCounted out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8VariantsAutosomalRecessiveDiseasePointsCountedAttribute()
	{
		$j = json_decode($this->legacy_json);
		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->PointsCounted ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8TwoVariantsNotPredictedCountAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->Count ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8TwoVariantsNotPredictedTotalAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->VariantEvidence->AutosomalRecessiveDisease->TwoVariantsNotPredictedProvenNullWithSomeEvidenceOfGeneImpactInTrans->TotalPoints ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8CandiGeneSummedAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->SummedLod ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8CandiGeneFamilyAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->FamilyCount ?? null;
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8CandiGeneFamilyEvidenceAttribute()
	{
		$j = json_decode($this->legacy_json);

		$records = [];

		$t = $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->CandidateSequencingMethod->Evidence->Publications ?? [];

		foreach ($t as $publication) {
			$records[] = $publication->author . ' et al. ' . $publication->pubdate . ' (PMID:'
			. $publication->pmid . ')';
		}
		return implode('; ', $records);
	}


	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getSop8SegregationEvidencePointsCountedAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->scoreJson->GeneticEvidence->CaseLevelData->SegregationEvidence->PointsCounted ?? null;
	}

	/**
	 * Return a displayable for contributors out of the SOP8 results
	 *
	 * @param
	 * @return string
	 */
	public function getJsonMessageVersionAttribute()
	{
		$j = json_decode($this->legacy_json);

		return $j->jsonMessageVersion ?? null;
	}

}

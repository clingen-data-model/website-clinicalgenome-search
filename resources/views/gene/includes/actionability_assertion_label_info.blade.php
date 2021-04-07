@if($assertion == "Assertion Pending")
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="'Assertion Pending' were generated prior to the implementation of the process for making actionability assertions. Topics needing assertions are actively being reviewed."><i class="fas fa-info-circle text-muted"></i></span>

  @elseif($assertion == "N/A - Insufficient evidence: expert review")
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="A summary report was created to assess the evidence. After review by the expert group, it was decided not to score this gene-disease pair because of insufficient evidence in the context of a secondary finding. Actionability is NOT currently considered in the context of population-wide screening or the diagnostic setting."><i class="fas fa-info-circle text-muted"></i></span>

  @elseif($assertion == "N/A - Insufficient evidence: early rule-out")
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="This indicates that the gene-disease pairs failed early rule-out in the context of a secondary finding. Actionability is NOT currently considered in the context of population-wide screening or the diagnostic setting."><i class="fas fa-info-circle text-muted"></i></span>
  @else
  {{-- Everything else --}}
  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="Actionability assertions are made in the context of a secondary finding. Actionability is NOT currently considered in the context of population-wide screening or the diagnostic setting."><i class="fas fa-info-circle text-muted"></i></span>
@endif
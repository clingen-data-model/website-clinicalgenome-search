<div class="form-group">
<table style="width:100%;" >
  <tr style="">
    <td style="width:15%; padding-top:5px; padding-bottom:5px; padding-right:1%;" valign="top"  nowrap class="text-right text-muted">Gene/Disease Pair:</td>
    <td style="width:85%; padding-bottom:5px"><h3 style="padding:0; margin:0"><strong style="color:#000"><i>
      <?=$Gene?><%= @assertionScoreJson['data']['Gene'] %>
      </i>:
      <?=$Disease?><%= @assertionScoreJson['data']['Disease'] %>
      </strong></h3></td>
  </tr>
  <tr style="">
    <td style="width:15%; padding-right:1%;" nowrap class="text-right text-muted"></td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      HGNC:<%= @assertionScoreJson['data']['Hgnc'] %>
      <% if @assertionScoreJson['data']['OrphaNet'] %>
      | OrphaNet:
      <%= @assertionScoreJson['data']['OrphaNet'] %>
      <% end %>
      <% if @assertionScoreJson['data']['Omim'] %>
      | OMIM:<%= @assertionScoreJson['data']['Omim'] %>
      <% end %>
      </strong></td>
  </tr>
  <% if @assertionScoreJson['data']['ModeOfInheritance'] %>
  <tr style="">
    <td style="width:15%; padding-right:1%; padding-bottom:5px" nowrap class="text-right text-muted">Mode of Inheritance:</td>
    <td style="width:85%; padding-bottom:5px"><strong style="color:#000">
      <%= @assertionScoreJson['data']['ModeOfInheritance'] %>
      </strong></td>
  </tr>
  <% end %>
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
      <?=$GeneticEvidence2V?>



      <%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithLOF","value" %>


      </div></td>
    <td class="points-tally-bg"><?=$GeneticEvidence2Tally?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithLOF","tally" %></td>
    <td class="input-width-pmid">
      <?=PrintWrapperPmid("GeneticEvidence2", $GeneticEvidence2Pmid) ?>
      <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithLOF","pmid")).html_safe %>

      </td>
  </tr>
  <tr>
    <td colspan="3">Proband with predicted or proven null variant</td>
    <td>1.5</td>
    <td>0-2</td>
    <td id="GeneticEvidence2Max">10</td>
    <td class="input-width-numbers points-given-bg"><?=$GeneticEvidence3V?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","VariantIsDeNovo","value" %></td>
    <td class=" points-tally-bg"><?=$GeneticEvidence3Tally?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","VariantIsDeNovo","tally" %></td>
    <td class="input-width-pmid"><?=PrintWrapperPmid("GeneticEvidence3", $GeneticEvidence3Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","VariantIsDeNovo","pmid")).html_safe %>
    </td>
  </tr>
  <tr>
    <td colspan="3" class='table-border-thin'>Proband with other variant type with some evidence of gene impact</td>
    <td class='table-border-thin'>0.5</td>
    <td class='table-border-thin'>0-1.5</td>
    <td id="GeneticEvidence3Max" class='table-border-thin'>7</td>
    <td class="input-width-numbers points-given-bg table-border-thin"><?=$GeneticEvidence1V?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithNon-LOF","value" %></td>
    <td class=" points-tally-bg table-border-thin"><span class="points-tally-bg">
      <?=$GeneticEvidence1Tally?> <%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithNon-LOF","tally" %>
      </span></td>
    <td class="input-width-pmid table-border-thin"><span class="input-width-pmid">
      <?=PrintWrapperPmid("GeneticEvidence1", $GeneticEvidence1Pmid) ?>
      <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","ProbandWithNon-LOF","pmid")).html_safe %>

      </span></td>
  </tr>
  <tr>
    <td rowspan="2" class="table-title table-border-thin">Autosomal Recessive Disease</td>
    <td colspan="3">Two variants in trans and at least one de novo or a predicted/proven null variant</td>
    <td>2</td>
    <td>0-3</td>
    <td rowspan="2" id="GeneticEvidence4Max" class=' table-border-thin'>12</td>
    <td class="input-width-numbers  points-given-bg"><?=$GeneticEvidence4Vb?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","TwoNariantsInTransAndAtLeastOneIsLOFOrDeNovo","value" %></td>
    <td rowspan="2" class=" points-tally-bg table-border-thin"><?=$GeneticEvidence4Vb?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","TwoNon-LOFVariantsInTrans","tally" %></td>
    <td rowspan="2" class="input-width-pmid  table-border-thin"><?=PrintWrapperPmid("GeneticEvidence4", $GeneticEvidence4Pmid) ?>
    	<%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","CaseLevelData","VariantEvidence","AutosomalDominantDisease","TwoNon-LOFVariantsInTrans","pmid")).html_safe %>
    </td>
  </tr>
  <tr>
    <td colspan="3" class='table-border-thin'>Two variants (not predicted/proven null) with some evidence of gene impact in trans</td>
    <td class='table-border-thin'>1</td>
    <td class='table-border-thin'>0-1.5</td>
    <td class=' input-width-numbers  points-given-bg table-border-thin'><%= @assertionScoreJson.dig 'GeneticEvidence','CaseLevelData','VariantEvidence','AutosomalDominantDisease','TwoNon-LOFVariantsInTrans','value' %></td>
  </tr>
  <tr>
    <td colspan="2" rowspan="5" class="table-heading-line-normal table-title">Segregation Evidence</td>
    <td rowspan="5" class="table-heading-line-normal">Evidence of segregation in one or more families</td>
    <td rowspan="5" class="table-heading-line-normal">LOD Score Examples</td>
    <td>3</td>
    <td>5</td>
    <td rowspan="5" class="table-heading-line-normal">0-7</td>
    <td rowspan="5" id="GeneticEvidence5Max" class="table-heading-line-normal">7</td>
    <td rowspan="5" class="table-heading-line-normal input-width-numbers  points-given-bg"><?=$GeneticEvidence5V?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","SegregationEvidence","EvidenceOfSegregationInOneOrMoreFamilies","value" %></td>
    <td rowspan="5" class="table-heading-line-normal points-tally-bg"><?=$GeneticEvidence5Tally?><%= @assertionScoreJson.dig "GeneticEvidence","CaseLevelData","SegregationEvidence","EvidenceOfSegregationInOneOrMoreFamilies","tally" %></td>
    <td rowspan="5" class="table-heading-line-normal input-width-pmid"><?=PrintWrapperPmid("GeneticEvidence5", $GeneticEvidence5Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","CaseLevelData","SegregationEvidence","EvidenceOfSegregationInOneOrMoreFamilies","pmid")).html_safe %>
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
    <td class="input-width-numbers points-given-bg"><?=$GeneticEvidence6V?><%= @assertionScoreJson.dig "GeneticEvidence","Case-ControlData","SingleVariantAnalysis","value" %></td>
    <td class=" points-tally-bg"><?=$GeneticEvidence6Tally?><%= @assertionScoreJson.dig "GeneticEvidence","Case-ControlData","SingleVariantAnalysis","tally" %></td>
    <td class="input-width-pmid"><?=PrintWrapperPmid("GeneticEvidence6", $GeneticEvidence6Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","Case-ControlData","SingleVariantAnalysis","pmid")).html_safe %>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="table-title">Aggregate Variant Analysis</td>
    <td colspan="2">0-6</td>
    <td id="GeneticEvidence7Max">12</td>
    <td class="input-width-numbers points-given-bg"><?=$GeneticEvidence7V?><%= @assertionScoreJson.dig "GeneticEvidence","Case-ControlData","AggregateVariantAnalysis","value" %></td>
    <td class=" points-tally-bg"><?=$GeneticEvidence7Tally?><%= @assertionScoreJson.dig "GeneticEvidence","Case-ControlData","AggregateVariantAnalysis","tally" %></td>
    <td class="input-width-pmid"><?=PrintWrapperPmid("GeneticEvidence7", $GeneticEvidence7Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "GeneticEvidence","Case-ControlData","AggregateVariantAnalysis","pmid")).html_safe %>
    </td>
  </tr>
  <tr>
    <td colspan="10" class="table-heading-line-thick table-heading-bg table-heading table-total text-right">Total Genetic Evidence Points (Maximum <span id="GeneticEvidenceMax">12</span>)</td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg"><?=$GeneticEvidenceTotal?><%= @assertionScoreJson.dig "summary","GeneticEvidencePointsTotal" %></td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total"><div class="form-group total-notes">
      <?=$GeneticEvidence8N ?><%= @assertionScoreJson.dig "GeneticEvidence","TotalGeneticEvidencePoints","notes" %>
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
    <td rowspan="3" class="input-width-numbers points-given-bg table-border-thin"><?=$ExperimentalEvidence1V?><%= @assertionScoreJson.dig "ExperimentalEvidence","Function","value" %></td>
    <td rowspan="3" class=" points-tally-bg table-border-thin"><?=$ExperimentalEvidence1Tally?><%= @assertionScoreJson.dig "ExperimentalEvidence","Function","tally" %></td>
    <td rowspan="3" class="input-width-pmid table-border-thin"><?=PrintWrapperPmid("ExperimentalEvidence1", $ExperimentalEvidence1Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "ExperimentalEvidence","Function","pmid")).html_safe %></td>
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
    <td rowspan="2" class="input-width-numbers points-given-bg table-border-thin"><?=$ExperimentalEvidence2V?><%= @assertionScoreJson.dig "ExperimentalEvidence","FunctionalAlteration","value" %></td>
    <td rowspan="2" class=" points-tally-bg table-border-thin"><?=$ExperimentalEvidence2Tally?><%= @assertionScoreJson.dig "ExperimentalEvidence","FunctionalAlteration","tally" %></td>
    <td rowspan="2" class="input-width-pmid table-border-thin"><?=PrintWrapperPmid("ExperimentalEvidence2", $ExperimentalEvidence2Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "ExperimentalEvidence","FunctionalAlteration","pmid")).html_safe %>
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
    <td rowspan="4" class="input-width-numbers points-given-bg"><?=$ExperimentalEvidence3V?><%= @assertionScoreJson.dig "ExperimentalEvidence","ModelsRescue","value" %></td>
    <td rowspan="4" class=" points-tally-bg"><?=$ExperimentalEvidence3Tally?><%= @assertionScoreJson.dig "ExperimentalEvidence","ModelsRescue","tally" %></td>
    <td rowspan="4" class="input-width-pmid"><?=PrintWrapperPmid("ExperimentalEvidence3", $ExperimentalEvidence3Pmid) ?>
    <%= PrintWrapperPmid('0', (@assertionScoreJson.dig "ExperimentalEvidence","ModelsRescue","pmid")).html_safe %></td>
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
    <td class="table-heading-line-thick table-heading-bg table-heading table-total points-tally-bg"><?=$ExperimentalEvidenceTotal?><%= @assertionScoreJson.dig "summary","ExperimentalEvidenceTotal" %></td>
    <td class="table-heading-line-thick table-heading-bg table-heading table-total"><div class="form-group total-notes">
      <?=$ExperimentalEvidence4N?><%= @assertionScoreJson.dig "ExperimentalEvidence","TotalExperimentalEvidencePoints","notes" %>
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
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border"><?=$GeneticEvidencePointsTotal?><%= @assertionScoreJson.dig "summary","GeneticEvidencePointsTotal" %></td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border"><?=$ExperimentalEvidencePointsTotal?><%= @assertionScoreJson.dig "summary","ExperimentalEvidencePointsTotal" %></td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border"><?=$EvidencePointsTotal?><%= @assertionScoreJson.dig "summary","EvidencePointsTotal" %></td>
    <td class="table-heading-line-thick table-heading-bg table-total table-total-border"><?=($ReplicationOverTimeYN == 'YES') ? "YES" : "NO"; ?><%= @assertionScoreJson.dig "ReplicationOverTime","YesNo" %></td>
  </tr>
  <tr class="LIMITED">
    <td colspan="2" rowspan="4" class="table-heading-line-thick table-heading ">CALCULATED CLASSIFICATION</td>
    <td class="table-heading EvidenceLimitedBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">LIMITED</td>
    <td colspan="2" class="table-heading EvidenceLimitedBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">1-6</td>
  </tr>
  <tr class="MODERATE">
    <td class="table-heading EvidenceModerateBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">MODERATE</td>
    <td colspan="2" class="table-heading EvidenceModerateBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">7-11</td>
  </tr>
  <tr class="STRONG">
    <td class="table-heading EvidenceStrongBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">STRONG</td>
    <td colspan="2" class="table-heading EvidenceStrongBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">12-18</td>
  </tr>
  <tr class="DEFINITIVE">
    <td class="table-heading-line-thick table-heading EvidenceDefinitiveBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">DEFINITIVE</td>
    <td colspan="2" class="table-heading-line-thick table-heading EvidenceDefinitiveBg <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>">12-18 AND replication over time</td>
  </tr>
  <tr>
    <td class="table-heading-line-thick table-heading">Valid contradictory evidence (Y/N)* <br></td>
    <td colspan="4" class="table-heading-line-thick text-left"><div class="input-width-pmid">
      <div class="form-group">
        <table>
          <tr>
            <td class="col-sm-2"><?=($ValidContradictoryEvidenceYN == 'YES') ? "YES" : "NO"; ?>
            		<% if @assertionScoreJson.dig "ValidContradictoryEvidence","YesNo" %>
            			<%= @assertionScoreJson.dig "ValidContradictoryEvidence","YesNo" %>
			    <% end %>
            </td>
            <td class="col-sm-10"><?=PrintWrapperPmid("ValidContradictoryEvidence", $ValidContradictoryEvidencePmid) ?><%= PrintWrapperPmidArray('0', (@assertionScoreJson.dig "ValidContradictoryEvidence","pmid")).html_safe %>
            </td>
          </tr>
        </table>
      </div>
      </div></td>
  </tr>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right table-border-thin">CALCULATED CLASSIFICATION (DATE)</td>
    <td colspan="3" class="table-heading-bg table-heading table-border-thin <?=$CalculatedClassificationCSS ?> CalculatedClassificationsActive "><div class='col-sm-8 '>
      <?=$CalculatedClassification?>
      <%= @assertionScoreJson.dig "summary","CalculatedClassification" %>
      </div>
      <div class='col-sm-4'>
        <?=$CalculatedClassificationDate?>
      <%= @assertionScoreJson.dig "summary","CalculatedClassificationDate" %>
      </div></td>
  </tr>
  <? if($CuratorModifyCalculationYN == 'YES') { ?>
  <% if @assertionScoreJson.dig "CuratorModifyCalculation","YesNo" == "YES" %>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right"> MODIFY CALCULATED CLASSIFICATION </td>
    <td colspan="3" class="table-heading-bg table-heading text-left CalculatedClassificationsActive-2 <?=$CuratorClassificationCSS ?>"><div class='col-sm-12'>
      <?=($CuratorModifyCalculationYN == 'YES') ? "YES" : "NO"; ?>
      <%= @assertionScoreJson.dig "CuratorModifyCalculation","YesNo" %>
      </div></td>
  </tr>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right table-border-thin"> CURATOR CLASSIFICATION (DATE) </td>
    <td colspan="3" class="table-heading-bg table-heading table-border-thin CalculatedClassificationsActive-2 <?=$CuratorClassificationCSS ?>"><div class='col-sm-8'>
      <?=$CuratorClassification; ?>
      <%= @assertionScoreJson.dig "summary","CuratorClassification" %>
      </div>
      <div class='col-sm-4'>
        <?=$CuratorClassificationDate ?>
      <%= @assertionScoreJson.dig "summary","CuratorClassificationDate" %>
      </div>
      <div class='col-sm-12'>
        <?=$CuratorClassificationNotes?>
      <%= @assertionScoreJson.dig "summary","CuratorClassificationNotes" %>
      </div></td>
  </tr>
  <% end %>
  <? if($FinalClassification) { ?>
  <% if @assertionScoreJson.dig "summary","FinalClassification" %>
  <tr>
    <td colspan="2" class="table-heading-bg table-heading text-right">EXPERT CURATION (DATE)</td>
    <td colspan="3" class="table-heading-bg table-heading CalculatedClassificationsActive-3 <?=$FinalClassificationCSS ?>"><div class='col-sm-8'> <span style="font-size: 145%;">
      <?=$FinalClassification?>
      <%= @assertionScoreJson.dig "summary","FinalClassification" %>
      </span> </div>
      <div class='col-sm-4'>
        <?=$FinalClassificationDate?>
      <%= @assertionScoreJson.dig "summary","FinalClassificationDate" %>
      </div>
      <div class='col-sm-12'>
        <?=$FinalClassificationNotes?>
      <%= @assertionScoreJson.dig "summary","FinalClassificationNotes" %>
      </div></td>
  </tr>
  <? } ?>
  <% end %>
</tbody>
</table>
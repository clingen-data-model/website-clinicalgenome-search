<h3><%= @geneSymbol %> - <%= @diseaseName %></h3>
<div class="form-group">
<table class='table table-striped text-left' style="width:100%;" >
<tr style="font-size:14px">
    <td style="border-top-width:6px" nowrap class="text-left">Gene:</td>
    <td  style="border-top-width:6px"><%= @geneSymbol %> (<%= @geneCurie %>)</td>
    <td colspan="2" rowspan="3"  style="border-top-width:6px; border-left-width:6px; border-left-color:rgb(221, 221, 221); border-left-style:solid; text-align: center;">
    <div class='badge badge-primary' style="font-size: 20px; padding:15px;">
      <a tabindex="0" class="text-white" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://www.clinicalgenome.org/site/assets/files/5967/gene-validity_classification.pdf" data-content="Gene-Disease Validity classification and scoring information"><%= @assertionScoreJsonSop5.dig "scoreJson","summary","FinalClassification" %>  <i class="glyphicon glyphicon-info-sign text-white"></i></a>
    </div>
    <div>Classification - <%= @assertionScoreJsonSop5.dig "scoreJson","summary","FinalClassificationDate" %></div></td>
  </tr>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Disease:</td>
    <td  style=""><%= @diseaseName %> (<%= @diseaseCurie %>)</td>
  </tr>
  <% if @assertionScoreJsonSop5.dig("scoreJson","ModeOfInheritance") %>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Mode of Inheritance:</td>
    <td style="">
      <%= @assertionScoreJsonSop5.dig "scoreJson","ModeOfInheritance" %>
      </td>
  </tr>
  <% end %>

  <tr style="font-size:14px">
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Replication over time:</td>
    <td style="width:40%;  border-top-width:6px">
      <% if @assertionScoreJsonSop5.dig("scoreJson","ReplicationOverTime") == "YES" %>
      YES
      <% else %>
      NO
      <% end %>
      
    </td>
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Contradictory Evidence:</td>
    <td style="width:40%;  border-top-width:6px">
      <% if @assertionScoreJsonSop5.dig("scoreJson","ValidContradictoryEvidence","Value") == "YES" %>
      <a href="#ValidContradictoryEvidence">YES</a>
<% else %>
      NO
<% end %>
    </td>
  </tr>
  <% if @assertion.attributions.first %>
  <tr style="font-size:14px">
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">Expert Panel:</td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid"><%= @assertion.attributions.first.label if @assertion.attributions.first %> GCEP</td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left"></td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid"> </td>
  </tr>
  <% end %>
  <tr style="font-size:14px">
    <td style="vertical-align:top" nowrap class="text-left">Evidence Summary:</td>
    <td colspan="3" style="">
      <%= @assertionScoreJsonSop5.dig "summary","FinalClassificationNotes" %>
      <div><a style="color:#000" href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">
        Gene Clinical Validity Standard Operating Procedures (SOP) - SOP5
      </a></div>
    </td>
  </tr>
  <% # if EXPERT PANEL %>
  <% # end %>
  <% # if EXPERT PANEL %>
  <% # end %>
  </table>
</div>

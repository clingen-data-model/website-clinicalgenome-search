<h3><%= @geneSymbol %> - <%= @diseaseName %></h3>
<div class="form-group">
<table class='table table-striped text-left' style="width:100%;" >
<tr style="font-size:14px">
    <td style="border-top-width:6px" nowrap class="text-left">Gene:</td>
    <td  style="border-top-width:6px"><%= @geneSymbol %> (<%= @geneCurie %>)</td>
    <td colspan="2" rowspan="3"  style="border-top-width:6px; border-left-width:6px; border-left-color:rgb(221, 221, 221); border-left-style:solid; text-align: center;">
    <div class='badge badge-primary' style="font-size: 20px; padding:15px;">
      <a tabindex="0" class="text-white" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more about classifications " data-href="https://www.clinicalgenome.org/site/assets/files/5967/gene-validity_classification.pdf" data-content="Gene-Disease Validity classification and scoring information"><%= @assertionScoreJsonGci.dig "summary","FinalClassification" %>  <i class="glyphicon glyphicon-info-sign text-white"></i></a>
    </div>
    <div>Classification - <%= PrintDate(@assertionScoreJsonGci.dig "summary","FinalClassificationDate") %></div></td>
  </tr>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Disease:</td>
    <td  style=""><%= @diseaseName %> (<%= @diseaseCurie %>)</td>
  </tr>
  <% if @assertionScoreJsonGci.dig("ModeOfInheritance") %>
  <tr style="font-size:14px">
    <td style="" nowrap class="text-left">Mode of Inheritance:</td>
    <td style="">
      <%= @assertionScoreJsonGci['ModeOfInheritance'] %>
      </td>
  </tr>
  <% end %>

  <tr style="font-size:14px">
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Replication over time:</td>
    <td style="width:40%;  border-top-width:6px">
      <% if @assertionScoreJsonGci.dig("ReplicationOverTime") == "YES" %>
      YES
      <% else %>
      NO
      <% end %>
      
    </td>
    <td style="width:10%;  border-top-width:6px" nowrap class="text-left">Contradictory Evidence:</td>
    <td style="width:40%;  border-top-width:6px">
      <% if @assertionScoreJsonGci.dig("ValidContradictoryEvidence","Value") == "YES" %>
      <a href="#ValidContradictoryEvidence">YES</a>
<% else %>
      NO
<% end %>
    </td>
  </tr>
  <tr style="font-size:14px">
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">Expert Panel:</td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid"><%= @assertion.attributions.first.label if @assertion.attributions.first %> GCEP</td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid" nowrap class="text-left">
    <% if @assertionScoreJsonGci.dig("summary","contributors") %>
      Contributors:
        <% end %>
    </td>
    <td style="border-bottom-width:6px; border-bottom-color:rgb(221, 221, 221); border-bottom-style:solid">
    
    <% if @assertionScoreJsonGci.dig("summary","contributors") %>
        <% (@assertionScoreJsonGci.dig "summary","contributors").each do |contributor| %>
          <%= contributor["name"] %>
        <% end %>
      <% end %>
      
    </td>
  </tr>

  <tr style="font-size:14px">
    <td style="vertical-align:top" nowrap class="text-left">Evidence Summary:</td>
    <td colspan="3" style="">
      <%= @assertionScoreJsonGci.dig "summary","FinalClassificationNotes" %>
      <div><a style="color:#000" href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/educational-and-training-materials/standard-operating-procedures/">
        Gene Clinical Validity Standard Operating Procedures (SOP) - 
        <% if @assertionScoreJsonGci.dig("selectedSOPVersion") %>
          <%= gci_SOP_version(@assertionScoreJsonGci['selectedSOPVersion']) %>
        <% else %>
          <%= gci_SOP_version(@assertion[:jsonMessageVersion]) %>
        <% end %>
      </a></div>
    </td>
  </tr>
  </table>
</div>

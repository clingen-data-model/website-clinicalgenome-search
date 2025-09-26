@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">

      <table class="mt-3 mb-2">
        <tr>
          <td class="valign-top"><img src="/images/acmg.png" width="45" height="45"></td>
          <td class="pl-2"><h1 class="h2 p-0 m-0"> ACMG Secondary Findings Genes and Diseases</h1>
          </td>
        </tr>
      </table>
		</div>

		<div class="col-md-4">
			<div class="">
				<div class="text-right p-2">
					<ul class="list-inline pb-0 mb-0 small">
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
					<li class="text-stats line-tight text-center pl-3 pr-3"><span class="countDiseases text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Diseases</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-12 mb-2 border" style="background: #f2f7fc">
			<p class="p-2">
				ClinGen has partnered with the ACMG Secondary Findings Maintenance Working Group (SFWG) to provide guidance 
				related to which findings are reportable from the SF gene list. The reporting guidance includes reportability by condition, and may include specific variants, 
				variant allelic states, gene region, transcript, pathogenicity mechanism or other variant type considerations. 
				While some of these considerations are direct footnotes included in the SFWG’s publications, others are provided by ClinGen Expert Panels, 
				discussion with the SFWG or via other expert input. We welcome further requests from the community when additional clarification 
				or guidance is needed. Please send questions to: clingen@clinicalgenome.org 
			</p>
			<p class="p-2">
				The published ACMG SF v3.3 list along with reporting guidance is available as a spreadsheet <a href="https://docs.google.com/spreadsheets/d/1ecSUe0bJ-2gAMXBsrf5U1wMEDOmiIjtR_A5388uj7HY" target="_acmg"><b><u>here</u></b></a>. 
			</p>

			<p class="p-2">
				Considerations for using this table:
				<ul>
					<li>
						The table below lists the genes included in the ACMG SF v3.3 list. Each row must be expanded by clicking on the [˅] to reveal the following:
						<ol type="a">
							<li>
								<strong>Reporting recommendations by disease.</strong> This resource is intended to be used in conjunction with the SFWG publications and your own clinical judgment.
									<ul>
										<li>Yes - Reporting is recommended for P/LP variants associated with this disease, along with any reporting guidance noted</li>
										<li>No - Reporting is not recommended for P/LP variants associated with this disease </li>
										<li>Pending - Decision to report P/LP variants associated with disease  is under review by the SFWG</li>
										<li>N/A -  Gene-disease relationship not applicable to SF reporting due to Limited or below evidence level</li>
									</ul>
							</li>
							<li>
								<strong>Reporting guidance</strong>, when available, appears above the ClinGen Curated Diseases table for each gene.
								<ul>
									<li>
										If no additional considerations are available, the entry will say “There are no reporting guidance details at this time”.
									</li>
									<li>
										Please note that while some of the reporting guidances are direct footnotes included in the SFWG’s publications, 
										others are provided by ClinGen Expert Panels, discussion with the SFWG or via other expert input.
									</li>
								</ul>
							</li>
						</ol>
					</li>
					<li>
						The disease terms listed in the table below may not match the disease terms listed in the spreadsheet provided by the SFWG due to differences in the disease identifiers used by the SFWG (OMIM) and ClinGen (Mondo). 
						Furthermore, given that different curation efforts within ClinGen (e.g. Gene-Disease Validity, Actionability, etc) 
						have sometimes used distinct disease labels or IDs, the presence of other curations may be missing due to use of a distinct Mondo ID
						compared to the gene-disease validity curated disease entity.
					</li>
					<li>
						The SFWG welcomes nominations for new genes to be considered for addition to the list. To nominate a gene for consideration, 
						please fill out the form <a href="https://form.jotform.com/203275021199048" target="_acmgform"><b><u>here</u></b></a>.

					</li>
					<li>
						The SFWG also welcomes reportability questions and clarifications around specific variants, 
						variant types and conditions associated with each gene on the list, which will be added to this web resource. 
						To submit a question or suggestion, please email to clingen@clinicalgenome.org.
					</li>
				</ul>
			</p>

			<hr>
			<p class="p-2">
				<strong>Current ACMG Secondary Findings (SF) List</strong>
				<ul>
					<li class="mb-2">2025 - ACMG Policy Statement with updated list of 84 genes (ACMG SF v3.3)
						<p class="p-2">Publication:
							<a href="https://www.gimjournal.org/article/S1098-3600(25)00101-7/fulltext" target="_acmg">PMID:  40568962</a>
						</p>
					</li>
				</ul>
			</p>
			<hr >
			<p class="p-2">	
				<strong>Policy Updates and Prior Versions of the ACMG Secondary Findings (SF) List</strong>
				<ul>
					<li class="mb-2">2023 - ACMG Policy Statement with updated list of 81 genes (ACMG SF v3.2)
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(23)00879-1/fulltext" target="_acmg">PMID:  37347242; PMCID:  PMC10524344</a>
						</p>
					</li>
					<li class="mb-2">2023 - ACMG SF Policy Update - Considerations of Penetrance
						<p class="p-2">Publication:  
							<a href="https://pubmed.ncbi.nlm.nih.gov/38819344/" target="_acmg">PMID: 38819344; PMCID: PMC11227955</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(22)00723-7/fulltext" target="sf">
								Miller DT, Lee K, Abul-Husn NS, Amendola LM, Brothers K, Chung WK, Gollob MH, Gordon AS, Harrison SM, Hershberger RE, Klein TE, Richards CS, Stewart DR, Martin CL; ACMG Secondary Findings Working Group. Electronic address: documents@acmg.net. ACMG SF v3.1 list for reporting of secondary findings in clinical exome and genome sequencing: A policy statement of the American College of Medical Genetics and Genomics (ACMG). Genet Med. 2022 Jul;24(7):1407-1414. doi: 10.1016/j.gim.2022.04.006. Epub 2022 Jun 17. PMID: 35802134
							</a>
						</p>-->
					</li>
					<li class="mb-2">2022 - ACMG Policy Statement with updated list of 78 genes (ACMG SF v3.1)
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(22)00723-7/fulltext" target="_acmg">PMID:  35802134</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(22)00723-7/fulltext" target="sf">
								Miller DT, Lee K, Abul-Husn NS, Amendola LM, Brothers K, Chung WK, Gollob MH, Gordon AS, Harrison SM, Hershberger RE, Klein TE, Richards CS, Stewart DR, Martin CL; ACMG Secondary Findings Working Group. Electronic address: documents@acmg.net. ACMG SF v3.1 list for reporting of secondary findings in clinical exome and genome sequencing: A policy statement of the American College of Medical Genetics and Genomics (ACMG). Genet Med. 2022 Jul;24(7):1407-1414. doi: 10.1016/j.gim.2022.04.006. Epub 2022 Jun 17. PMID: 35802134
							</a>
						</p>-->
					</li>
					<li class="mb-2">2021 - ACMG Policy Statement with updated list of 73 genes (ACMG SF v3.0)
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(21)05076-0/fulltext" target="_acmg">PMID:  34012068</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(21)05076-0/fulltext" target="sf">
								Miller DT, Lee K, Chung WK, Gordon AS, Herman GE, Klein TE, Stewart DR, Amendola LM, Adelman K, Bale SJ, Gollob MH, Harrison SM, Hershberger RE, McKelvey K, Richards CS, Vlangos CN, Watson MS, Martin CL; ACMG Secondary Findings Working Group. ACMG SF v3.0 list for reporting of secondary findings in clinical exome and genome sequencing: a policy statement of the American College of Medical Genetics and Genomics (ACMG). Genet Med. 2021 Aug;23(8):1381-1390. doi: 10.1038/s41436-021-01172-3. Epub 2021 May 20. Erratum in: Genet Med. 2021 Aug;23(8):1582-1584. doi: 10.1038/s41436-021-01278-8. PMID: 34012068
							</a>
						</p>-->
					</li>
					<li class="mb-2">2021- ACMG SF Policy Update
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(21)05075-9/fulltext" target="_acmg">PMID:  34012069</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(21)05075-9/fulltext" target="sf">
								Miller DT, Lee K, Gordon AS, Amendola LM, Adelman K, Bale SJ, Chung WK, Gollob MH, Harrison SM, Herman GE, Hershberger RE, Klein TE, McKelvey K, Richards CS, Vlangos CN, Stewart DR, Watson MS, Martin CL; ACMG Secondary Findings Working Group. Recommendations for reporting of secondary findings in clinical exome and genome sequencing, 2021 update: a policy statement of the American College of Medical Genetics and Genomics (ACMG). Genet Med. 2021 Aug;23(8):1391-1398. doi: 10.1038/s41436-021-01171-4. Epub 2021 May 20. PMID: 34012069
							</a>
						</p>-->
					</li>
					<li class="mb-2">2017 - ACMG Policy Statement with updated list of 59 genes (ACMG SF v2.0)
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(21)01500-8/fulltext" target="_acmg">PMID:  27854360</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(21)01500-8/fulltext" target="sf">
								Kalia SS, Adelman K, Bale SJ, Chung WK, Eng C, Evans JP, Herman GE, Hufnagel SB, Klein TE, Korf BR, McKelvey KD, Ormond KE, Richards CS, Vlangos CN, Watson M, Martin CL, Miller DT. Recommendations for reporting of secondary findings in clinical exome and genome sequencing, 2016 update (ACMG SF v2.0): a policy statement of the American College of Medical Genetics and Genomics. Genet Med. 2017 Feb;19(2):249-255. doi: 10.1038/gim.2016.190. Epub 2016 Nov 17. Erratum in: Genet Med. 2017 Apr;19(4):484. doi: 10.1038/gim.2017.17. PMID: 27854360
							</a>
						</p>-->
					</li>
					<li class="mb-2">2013 - Original ACMG Policy Statement and minimum list of 56 genes
						<p class="p-2">Publication:  
							<a href="https://www.gimjournal.org/article/S1098-3600(21)02762-3/fulltext" target="_acmg">PMID: 23788249; PMCID: PMC3727274</a>
						</p>
						<!--<p class="p-2">Citation:
							<a href="https://www.gimjournal.org/article/S1098-3600(21)02762-3/fulltext" target="sf">
								Green RC, Berg JS, Grody WW, Kalia SS, Korf BR, Martin CL, McGuire AL, Nussbaum RL, O'Daniel JM, Ormond KE, Rehm HL, Watson MS, Williams MS, Biesecker LG; American College of Medical Genetics and Genomics. ACMG recommendations for reporting of incidental findings in clinical exome and genome sequencing. Genet Med. 2013 Jul;15(7):565-74. doi: 10.1038/gim.2013.73. Epub 2013 Jun 20. Erratum in: Genet Med. 2017 May;19(5):606. doi: 10.1038/gim.2017.18. PMID: 23788249; PMCID: PMC3727274
							</a>
						</p>-->
					</li>
				</ul>
			</p>
			<hr>
			<p class="p-2">
				<strong>ClinGen ACMG SF v3.3 Curation Data  </strong>
				<div class="pl-4 pb-4">
					<span>Click on the button to the right to download the applicable ClinGen curation information for all ACMG SF v3.3 genes shown on this page.</span>
					<span class="float-right">
						<a href="{{ route('acmg-activity-summary-cvs') }}"  class="btn btn-primary watchdownloadclick" title="ClinGen ACMG SF Summary Report CVS"><i class="fas fa-download mr-1"></i> CSV</a>
					</span>
				</div>
			</p>
		</div>

		<!-- <div class="col-md-12 mb-0 p-0">
			<div class="card">
				<table class="table table-striped table-hover mb-0">
					<tr>
						<td><strong>ClinGen ACMG SF v3.3 Curation Data  </strong>
							<div class="small">This file provides ClinGen summary curation information for all ACMG SF v3.3 genes.</div>
						</td>
						<td></td>
						<td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('acmg-activity-summary-cvs') }}"  class="btn btn-primary watchdownloadclick" title="ClinGen ACMG SF Summary Report CVS"><i class="fas fa-download"></i> CSV</a></td>
						<td class="text-10px" nowrap=""></td>
					</tr>
				</table>
			</div>
		</div>-->
			

		<div class="col-md-12 light-arrows dark-table pl-0 pr-0 dark-detail">
				@include('_partials.genetable', ['expand' => true, 'no_expand_click' => true])

		</div>
	</div>
</div>

@endsection

@section('heading')
<div class="content ">
		<div class="section-heading-content">
		</div>
</div>
@endsection


@section('script_css')
	<link href="https://cdn.jsdelivr.net/npm/jquery-treegrid@0.3.0/css/jquery.treegrid.css" rel="stylesheet">
	<link href="/css/bootstrap-table.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-table-filter-control.css">
	<link href="/css/bootstrap-table-group-by.css" rel="stylesheet">
	<link href="/css/bootstrap-table-sticky-header.css" rel="stylesheet">
@endsection

@section('script_js')

<script src="/js/tableExport.min.js"></script>
<script src="/js/jspdf.min.js"></script>
<script src="/js/xlsx.core.min.js"></script>
<script src="/js/jspdf.plugin.autotable.js"></script>

<script src="/js/bootstrap-table.min.js"></script>
<script src="/js/bootstrap-table-locale-all.min.js"></script>
<script src="/js/bootstrap-table-export.min.js"></script>
<script src="/js/bootstrap-table-addrbar.min.js"></script>

<script src="/js/sweetalert.min.js"></script>

<script src="/js/bootstrap-table-filter-control.js"></script>
<script src="/js/bootstrap-table-sticky-header.min.js"></script>


<!-- load up all the local formatters and stylers -->
<script src="/js/genetable.js"></script>

<script>

	/**
	**
	**		Globals
	**
	*/

	var $table = $('#table')
	var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";

	window.ajaxOptions = {
    beforeSend: function (xhr) {
		if (Cookies.get('clingen_dash_token') != undefined)
      		xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
    }
  }

	function responseHandler(res) {
		$('.countGenes').html(res.ngenes);
		$('.countDiseases').html(res.ndiseases);

    	return res
  	}

	  var activelist=['Actionability', 'Dosage Sensitivity', 'Gene Validity', 'Variant Pathogenicity'];

	function checkactive(text, value, field, data)
  	{
	  switch (text)
	  {
		  case 'actionability':
			  return value.indexOf('A') != -1;
		  case 'dosage sensitivity':
			  return value.indexOf('D') != -1;
		  case 'gene validity':
			  return value.indexOf('V') != -1;
		  case 'variant pathogenicity':
			  return value.indexOf('R') != -1;
		  default:
			  return true;
	  }

  	}

  	function AddReadMore() {
		//This limit you can set after how much characters you want to show Read More.
		var carLmt = 280;
		// Text to show when text is collapsed
		var readMoreTxt = "<span class='ml-1 text-info'><i>  ...continue reading </i> <i class='fas fa-chevron-down'></i></span>";
		// Text to show when text is expanded
		var readLessTxt = "<span class='ml-1 text-info'><i>  ...show less </i> <i class='fas fa-chevron-up'></i></span>";


		//Traverse all selectors with this class and manipulate HTML part to show Read More
		$(".add-read-more").each(function () {
			if ($(this).find(".first-section").length)
				return;

			var allstr = $(this).text();
			if (allstr.length > carLmt) {
				var firstSet = allstr.substring(0, carLmt);
				var secdHalf = allstr.substring(carLmt, allstr.length);
				var strtoadd = firstSet + "<span class='second-section'>" + secdHalf + "</span><span class='read-more'  title='Click to Show More'>" + readMoreTxt + "</span><span class='read-less' title='Click to Show Less'>" + readLessTxt + "</span>";
				$(this).html(strtoadd);
			}
		});

		//Read More and Read Less Click Event binding
		$(document).on("click", ".read-more,.read-less", function () {
			$(this).closest(".add-read-more").toggleClass("show-less-content show-more-content");
		});
	}

  	function inittable() {
		$table.bootstrapTable('destroy').bootstrapTable({
		stickyHeader: true,
		stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
    	stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
		locale: 'en-US',
		sortName: 'symbol',
		sortOrder: 'asc',
		columns: [
		{
			title: 'Gene Symbol',
			field: 'symbol',
			formatter: symbolHgncFormatter,
			cellStyle: cellFormatter,
			filterControl: 'input',
			sortable: true,
			searchFormatter: false,
			width: 140
		},
		/*{
			title: 'ClinGen Curation Activity',
			field: 'curation',
			formatter: badgeFormatter,
			cellStyle: cellFormatter,
			filterControl: 'select',
			sortable: true,
			searchFormatter: false,
			filterData: 'var:activelist',
			filterCustomSearch: checkactive,
          	width: 220
		},*/
		{
			title: 'ClinGen Curated Activities',
			field: 'curation',
			sortable: true,
			filterControl: 'input',
			formatter: diseaseCountFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter,
			width: 300
		},
		{
			title: 'Variant Classifications',
			field: 'comments',
			sortable: true,
			filterControl: 'input',
			formatter: variantGuidanceFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
		},
		{
			title: '<span class="pr-3">Reporting Guidance</span>',
			field: 'comments',
			sortable: true,
			filterControl: 'input',
			formatter: reportingGuidanceFormatter,
			searchFormatter: false,
			align: 'center',
			cellStyle: cellFormatter
		}
		/*
		{
			title: 'Other Resources',
			field: 'clinvar_link',
			sortable: true,
			filterControl: 'input',
			formatter: acmglinkFormatter,
			searchFormatter: false,
			cellStyle: cellFormatter
        }/*,
		{
			field: 'date',
			title: 'Reviewed',
			sortable: true,
			filterControl: 'input',
			cellStyle: cellFormatter,
			formatter: reportFormatter,
			sortName: 'rawdate'
        }*/
      ]
    })

	$table.on('load-error.bs.table', function (e, name, args) {

			$("body").css("cursor", "default");

			swal({
				title: "Load Error",
				text: "The system could not retrieve data from server",
				icon: "error"
			});
		})

		$table.on('load-success.bs.table', function (e, name, args) {
			$("body").css("cursor", "default");

			if (name.hasOwnProperty('error'))
			{
				swal({
					title: "Load Error",
					text: name.error,
					icon: "error"
				});
			}
		})

		$table.on('post-body.bs.table', function (e, name, args) {

			$('[data-toggle="tooltip"]').tooltip();

			AddReadMore();

			/*var columns = $table.bootstrapTable('getOptions').columns

        	if (columns && columns[0][1].visible) {
          		$table.treegrid({
            		treeColumn: 0,
					initialState: "collapsed",
            		onChange: function() {
              			$table.bootstrapTable('resetView')
            	}
          		})
        	}*/
		})


		$table.on('page-change.bs.table', function (e, name, args) {
			//$('[data-toggle="tooltip"]').tooltip();
			AddReadMore();

		})



		$table.on('click-row.bs.table', function (e, row, $obj, field) {
			
			if (field == 'comments')
			{
				// change the icon
				var far = $obj.find('.action-acmg-expand');
				if (far.hasClass('fa-caret-square-up'))
				{
					$table.bootstrapTable('collapseRowByUniqueId', row.id)
				}
				else
				{
					$table.bootstrapTable('expandRowByUniqueId', row.id)
				}
			}
			
		})

		$table.on('expand-row.bs.table', function (e, index, row, $obj) {

			$obj.attr('colspan',4);

			var t = $obj.closest('tr');

			var stripe = t.prev().hasClass('bt-even-row');

			t.addClass('dosage-row-bottom').addClass('dosage-row-left').addClass('dosage-row-right');

			if (stripe)
				t.addClass('bt-even-row');
			else
				t.addClass('bt-odd-row');

			t.prev().addClass('dosage-row-top').addClass('dosage-row-left').addClass('dosage-row-right');

			$obj.load( "/api/genes/acmg/expand/" + row.hgnc_id , function() {
				$(this).find('[data-toggle="tooltip"]').tooltip();
			});

			// change the icon
			var far = t.prev().find('.action-acmg-expand');
			far.removeClass('fa-caret-square-down').addClass('fa-caret-square-up');

			$('[data-toggle="tooltip"]').tooltip();
			

			return false;
		})


		$table.on('collapse-row.bs.table', function (e, index, row, $obj) {

				$obj.closest('tr').prev().removeClass('dosage-row-top').removeClass('dosage-row-left').removeClass('dosage-row-right');
				
				var far = $obj.closest('tr').prev().find('.action-acmg-expand');
				far.addClass('fa-caret-square-down').removeClass('fa-caret-square-up');
				return false;
		});

	}


	$(function() {

		// Set cursor to busy prior to table init
		$("body").css("cursor", "progress");

		// initialize the table and load the data
		inittable();

		// make some mods to the search input field
		var search = $('.fixed-table-toolbar .search input');
		search.attr('placeholder', 'Search in table');

		$( ".fixed-table-toolbar" ).show();
    	$('[data-toggle="tooltip"]').tooltip();
    	$('[data-toggle="popover"]').popover();

		$("button[name='filterControlSwitch']").attr('title', 'Column Search');
		$("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

		/*$('.action-acmg-expand').on('click', function({

		}));*/

  })

</script>
@endsection

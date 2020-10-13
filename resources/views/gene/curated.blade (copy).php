@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 curated-genes-table">
        <h1><span id="gene-count"></span><img src="/images/clinicalValidity-on.png" width="50" height="50"><img src="/images/clinicalActionability-on.png" width="50" height="50"><img src="/images/dosageSensitivity-on.png" width="50" height="50">  Curated Genes</h1>
        {{-- <h3>Clingen had information on <span id="gene-count">many</span> curated genes</h3> --}}
      </div>

      <div class="col-md-4">
        <div class="">
          <div class="text-right p-2">
            <ul class="list-inline pb-0 mb-0 small">
              <li class="small line-tight text-center pl-3 pr-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
              <li class="small line-tight text-center pl-3 pr-3"><span class="countValidity text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Validity<br />Genes</li>
              <li class="small line-tight text-center pl-3 pr-3"><span class="countActionability text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Actionability<br />Genes</li>
              <li class="small line-tight text-center pl-3 pr-3"><span class="countDosage text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Dosage<br />Genes</li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-md-12">
        @include('_partials.genetable')
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

@section('script_js')

<link href="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.css" rel="stylesheet">

<script src="https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/bootstrap-table-locale-all.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/export/bootstrap-table-export.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/addrbar/bootstrap-table-addrbar.min.js"></script>
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/toolbar/bootstrap-table-toolbar.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.css">
<script src="https://unpkg.com/bootstrap-table@1.18.0/dist/extensions/filter-control/bootstrap-table-filter-control.js"></script>


<style>
  .fixed-table-toolbar .search-input {
    min-width: 300px;
  }
  .swal-overlay--show-modal, .swal-modal {
    animation: none !important;
}
</style>

<script>
	var $table = $('#table')
	//var selections = []

  function table_buttons ()
	{
		return {
    	}
  }

  function responseHandler(res) {
    //$('#gene-count').html(res.total);
    $('.countGenes').html(res.total);
    $('.countValidity').html(res.nvalid);
    $('.countActionability').html(res.naction);
    $('.countDosage').html(res.ndosage);
    return res
  }

  function detailFormatter(index, row) {
    var html = []
    $.each(row, function (key, value) {
      html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    })
    return html.join('')
  }

  function symbolFormatter(index, row) {
	  return '<a href="/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
  }

  function validityFormatter(index, row) {

    if (row.has_validity)
    {
        return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
    }

	  return '';
  }


  function actionabilityFormatter(index, row) {
	
    if (row.has_actionability)
    {
        return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id
            + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
    }

	  return '';
  }


  function haploFormatter(index, row) {

    if (row.has_dosage_haplo)
    {
        return '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym='
             + row.symbol + '&subject'
            + '"><span class="hidden-sm hidden-xs">'
            + row.has_dosage_haplo + '</span></a>';
    }

	  return '';
  }


  function triploFormatter(index, row) {

    if (row.has_dosage_triplo)
    {
        return '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym='
             + row.symbol + '&subject'
            + '"><span class="hidden-sm hidden-xs">'
            + row.has_dosage_triplo + '</span></a>';
    }

	  return '';
  }


  function initTable() {
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [
        [{
          title: 'Gene',
          field: 'symbol',
          rowspan: 2,
          formatter: symbolFormatter,
          filterControl: 'input',
          sortable: true
        },
        {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/gene-disease-validity/" data-content="Can variation in this gene cause disease?" aria-describedby="popover"> <img src="/images/clinicalValidity-on.png" width="40" height="40"><br> Gene-Disease Validity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
			    align: 'center'
        },
		    {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/clinical-actionability/" data-content="How does this genetic diagnosis impact medical management?"> <img src="/images/clinicalActionability-on.png" width="40" height="40"><br> Clinical Actionability <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
          align: 'center',
        },
        {
          title: '<a tabindex="0" class="info-popover" data-container="body" data-toggle="popover" data-placement="top" data-trigger="focus" role="button" data-title="Learn more" data-href="https://www.clinicalgenome.org/curation-activities/dosage-sensitivity/" data-content="Is haploinsufficiency or triplosensitivity an established disease mechanism for this gene?" aria-describedby="popover954864"> <img src="/images/dosageSensitivity-on.png" width="40" height="40"><br> Dosage Sensitivity <i class="glyphicon glyphicon-question-sign text-muted"></i></a>',
          colspan: 2,
          align: 'center',
        }],
		    [{
          title: 'Clinical Validity Classifications',
          field: 'has_validity',
          formatter: validityFormatter,
          align: 'center',
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'Evidence-Based Summary',
          field: 'has_actionability',
          formatter: actionabilityFormatter,
          align: 'center',
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'Haploinsufficiency Score',
          field: 'has_dosage_haplo',
          formatter: haploFormatter,
          align: 'center',
          filterControl: 'input',
          sortable: true
        },
        {
          title: 'Triplosensitivity Score',
          field: 'has_dosage_triplo',
          formatter: triploFormatter,
          align: 'center',
          filterControl: 'input',
          sortable: true
        }]
      ]
    })

    $table.on('load-error.bs.table', function (e, name, args) {
      $("body").css("cursor", "default");
      
      swal({
            title: "Load Error",
            text: "The system could not retrieve data from GeneGraph",
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

  }

  $(function() {
    $("body").css("cursor", "progress");
    
    initTable();

	  var $search = $('.fixed-table-toolbar .search input');
	  $search.attr('placeholder', 'Search in table');
    $( ".fixed-table-toolbar" ).show();
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();
	  //$search.css('border', '1px solid red');

  })

</script>
@endsection

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center mt-3" style="margin-left: -100px; margin-right: -100px">  <!--style="box-shadow: 0 0 30px black;""> -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <table class="mt-3 mb-2">
                            <tr>
                                <td class="valign-top"><img src="/images/adept-icon-circle-gene.png" width="40" height="40"></td>
                                <td class="pl-2"><h1 class="h2 p-0 m-0">  {{  $type }} Location Search Results</h1>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="text-right p-2">
                    <ul class="list-inline pb-0 mb-0 small">
                        <li class="text-stats line-tight text-center"><span class="curatedGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Curated<br />Genes</li>
                        <li class="text-stats line-tight text-center pl-3"><span class="countGenes text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Genes</li>
                        <li class="text-stats line-tight text-center pl-3"><span class="curatedRegions text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Curated<br />Regions</li>
                        <li class="text-stats line-tight text-center pl-3"><span class="countRegions text-18px"><i class="glyphicon glyphicon-refresh text-18px text-muted"></i></span><br />Total<br />Regions</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-0 medium-font-size" style="margin-left: -100px; margin-right: -100px">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-3">
                        <span class="strong">Location:</span>
                    </div>
                    <div class="col-md-9">
                        <span>
                                    {{ $region }}
                            @if ($region == 'INVALID')
                                &nbsp;(Original: {{ $original }})
                            @endif
                    </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <span class="strong text-right d-inline">Genes:</span>
                    </div>
                    <div class="col-md-9 genes-statement">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <span class="strong text-right">Regions:</span>
                    </div>
                    <div class="col-md-9 regions-statement">

                    </div>
                </div>
            </div>
            <div class="col-md-7 grayblur mr-2 pt-2 pb-2">
                <div class="row">
                    <div class="col-md-3 pl-4">
                        <span class="font-weight-bold">Include:</span>
                        <div>
                            <input type="checkbox" name="dos" class="action-show-dosage" />
                            <label class="mb-0 font-weight-normal" for="dos">Dosage Scores</label>
                        </div>
                        <div>
                            <input type="checkbox" name="val" />
                            <label class="mb-0 font-weight-normal" for="val">Validity Scores</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span class="font-weight-bold">Display:</span>
                        <div>
                            <input class="action-show-genes" type="checkbox" name="gen" checked />
                            <label class="mb-0 font-weight-normal" for="gen">Genes</label>
                        </div>
                        <div>
                            <input class="action-show-regions" type="checkbox" name="reg" checked />
                            <label class="mb-0 font-weight-normal" for="reg">Regions</label>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <span class="font-weight-bold">&nbsp;</span>
                        <div>
                            <input class="action-show-contain" type="checkbox" name="con" checked />
                            <label class="mb-0 font-weight-normal" for="con">Contain</label>
                        </div>
                        <div>
                            <input class="action-show-overlap" type="checkbox" name="ovr" checked />
                            <label class="mb-0 font-weight-normal" for="ovr">Overlap</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <span class="font-weight-bold">&nbsp;</span>
                        <div>
                            <input class="action-show-psuedo" type="checkbox" name="psu" checked />
                            <label class="mb-0 font-weight-normal" for="psu">Psuedogenes</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center" style="margin-left: -100px; margin-right: -100px">
            <div class="col-md-12 mt-2">
                <input class="action-get-type" type="hidden" name="type" value="{{ $type ?? '' }}">
                <input class="action-get-region" type="hidden" name="region" value="{{ $region ?? '' }}">
                <button type="button" class="btn-link p-0 m-0" data-toggle="modal" data-target="#modalFilter">
                    <span class="text-muted font-weight-bold mr-1"><small><i class="glyphicon glyphicon-tasks" style="top: 2px"></i> Advanced Filters:  </small></span><span class="filter-container"></span>
                </button>
                <span class="text-info font-weight-bold mr-1 float-right action-hidden-columns hidden"><small>Click on <i class="glyphicon glyphicon-th icon-th" style="top: 2px"></i> below to view hidden columns</small></span>
            </div>
            <div class="col-md-12 light-arrows dark-table">
                @include('_partials.genetable', ['expand' => true])
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

@section('modals')

    @include('modals.resultsfilter')

@endsection

@section('script_css')
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

    <script src="/js/sweetalert.min.js"></script>

    <script src="/js/bootstrap-table-filter-control.js"></script>
    <script src="/js/bootstrap-table-sticky-header.min.js"></script>

    <!-- load up all the local formatters and stylers -->
    <script src="/js/genetable.js"></script>
    <script src="/js/filters.js"></script>
    <script src="/js/bookmark.js"></script>

    <script>

        /**
         **
         **		Globals
         **
         */

        var $table = $('#table');
        var showadvanced = true;
        var report = "{{ env('CG_URL_CURATIONS_DOSAGE') }}";
        window.token = "{{ csrf_token() }}";

        window.ajaxOptions = {
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', 'Bearer ' + Cookies.get('clingen_dash_token'))
            }
        }

        /**
         *
         * Table response handler for updating page counters after data load
         *
         * */
        function responseHandler(res) {

            // update the counters
            $('.countGenes').html(res.gene_count);
            $('.curatedGenes').html(res.curated_gene_count);
            $('.countRegions').html(res.region_count);
            $('.curatedRegions').html(res.curated_region_count);

            var pseudogenesCount = res.rows.filter( function (row) {
                return row.type != 0 && row.type != 1;
            }).length

            var completedGenesCount = res.rows.filter( function (row) {
                return row.workflow === 'Complete' && row.type == 0
            }).length

            var completedRegionsCount = res.rows.filter( function (row) {
                return row.workflow === 'Complete' && row.type == 1
            }).length

            let genesStatement = `${res.gene_count} total genes`;
            let regionsStatement = `${res.region_count} total regions`;

            $('.genes-statement').html(genesStatement).parent().removeClass('hidden');
            $('.regions-statement').html(regionsStatement).parent().removeClass('hidden');

            return res
        }

        var choices=['Yes', 'No'];

        var hibin=['<= 10%', '<= 25%', '<= 50%', '<= 75%'];
        var plibin=['< 0.9', '>= 0.9'];
        var plofbin=['<= 0.2', '<= 0.35', '<= 1'];

        // HI bin
        function checkbin(text, value, field, data)
        {
            switch (text)
            {
                case '<= 10%':
                    return value <= 10;
                case '<= 25%':
                    return value <= 25;
                case '<= 50%':
                    return value <= 50;
                case '<= 75%':
                    return value <= 75;
                default:
                    return true;
            }
        }


        function checkpli(text, value, field, data)
        {
            if (text == '< .9')
                return value < .9;
            else
                return value >= .9;
        }


        function checkplof(text, value, field, data)
        {
            switch (text)
            {
                case '<= 0.2':
                    return value <= .2;
                case '<= 0.35':
                    return value <= .35;
                case  '<= 1':
                    return value <= 1;
                default:
                    return true;
            }

        }

        var activelist=['Actionability', 'Dosage Sensitivity', 'Gene Validity', 'Variant Pathogenicity', 'Pharmacogenomics'];

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
            case 'pharmacogenomics':
                return value.indexOf('P') != -1;
            default:
                return true;
        }

    }

        function inittable() {
            $table.bootstrapTable('destroy').bootstrapTable({
                stickyHeader: true,
                stickyHeaderOffsetLeft: parseInt($('body').css('padding-left'), 10),
                stickyHeaderOffsetRight: parseInt($('body').css('padding-right'), 10),
                locale: 'en-US',
                sortName:  "coordinates",
                sortOrder: "asc",
                rowStyle:  function(row, index) {
                    if (index % 2 === 0) {
                        return {
                            classes: 'bt-even-row bt-hover-row'
                        }
                    }
                    else {
                        return {
                            classes: 'bt-odd-row bt-hover-row'
                        }
                    }
                },
                columns: [
                    {
                        title: 'Type<hr class="mt-1 mb-1 bg-white mr-4">Overlap',
                        field: 'relationship',
                        formatter: relationFormatter,
                        //cellStyle: typeFormatter,
                        //align: 'center',
                        sorter: relationSorter,
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                    },
                    {
                        title: 'Gene / Region',
                        field: 'symbol',
                        formatter: symbol2Formatter,
                        cellStyle: cellFormatter,
                        filterControl: 'input',
                        width: 240,
                        searchFormatter: false,
                        sortable: true
                    },
                    {
                        title: 'HGNC/<br>Dosage ID',
                        field: 'symbol_id',
                        //formatter: hgncFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        title: '{{ $type }}',
                        field: 'coordinates',
                        formatter: location02Formatter,
                        cellStyle: cellFormatter,
                        sorter: locationSorter,
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                    },
                    {
                        title: 'Cytoband',
                        field: 'location',
                        //formatter: locationFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title=" ClinGen Haploinsufficiency score"></i></div>HI Score',
                        field: 'haplo_assertion',
                        formatter: haplo2Formatter,
                        cellStyle: cellFormatter,
                        //align: 'center',
                        filterControl: 'select',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="ClinGen Triplosensitivity score"></i></div>TS Score',
                        field: 'triplo_assertion',
                        formatter: triplo2Formatter,
                        cellStyle: cellFormatter,
                        //align: 'center',
                        filterControl: 'select',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        title: 'OMIM<hr class="mt-1 mb-1 bg-white mr-4">Morbid',
                        field: 'omim',
                        formatter: omimFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:choices',
                        searchFormatter: false,
                        sortable: true,
                    },
                    /*{
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="OMIM morbid map"></i></div>Morbid',
                        field: 'morbid',
                        formatter: morbidFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:choices',
                        searchFormatter: false,
                        sortable: true
                    },*/
                    {
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="DECIPHER Haploinsufficiency index.  Values less than 10% predict that a gene is more likely to exhibit haploinsufficiency."></i></div>%HI',
                        field: 'hi',
                        formatter: hiFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:hibin',
                        filterCustomSearch: checkbin,
                        searchFormatter: false,
                        sortable: true,
                    },
                    {
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD pLI score.  Values greater than or equal to 0.9 indicate that a gene appears to be intolerant of loss of function variation."></i></div>pLI',
                        field: 'pli',
                        formatter: pliFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:plibin',
                        filterCustomSearch: checkpli,
                        searchFormatter: false,
                        sortable: true,
                    },
                    {
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="gnomAD predicted loss-of-function.  Values less than 0.35 indicate that a gene appears to be intolerant of loss of function variation."></i></div>LOEUF',
                        field: 'plof',
                        formatter: plofFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:plofbin',
                        filterCustomSearch: checkplof,
                        searchFormatter: false,
                        sortable: true,
                    },
                    {
                        field: 'workflow',
                        title: 'Report',
                        formatter: dsreportFormatter,
                        cellStyle: cellFormatter,
                        //align: 'center',
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        field: 'curation',
                        title: 'Curation Activity',
                        formatter: badge2Formatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        filterData: 'var:activelist',
                        filterCustomSearch: checkactive,
                        sorter: dateSorter,
                        width: 190,
                        searchFormatter: false,
                        sortable: true
                    },
                    {
                        field: 'precuration',
                        title: 'Curation Activity',
                        formatter: precurationFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'select',
                        //filterData: 'var:activelist',
                        //filterCustomSearch: checkactive,
                        sorter: dateSorter,
                        width: 190,
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    },
                    {
                        field: 'date_last_curated',
                        title: '<div><i class="fas fa-info-circle color-white" data-toggle="tooltip" data-placement="top" title="Date of last curation against gene, if known."></i></div> Last Eval.',
                        //formatter: badgeFormatter,
                        cellStyle: cellFormatter,
                        filterControl: 'input',
                        searchFormatter: false,
                        sortable: true,
                        visible: false
                    }
                ]
            });

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
                window.update_addr();

                if (name.hasOwnProperty('error'))
                {
                    swal({
                        title: "Load Error",
                        text: name.error,
                        icon: "error"
                    });
                }

                var hidden = $table.bootstrapTable('getHiddenColumns');

                if (hidden.length > 0)
                    $('.action-hidden-columns').removeClass('hidden');
                else
                    $('.action-hidden-columns').addClass('hidden');
            })

            $table.on('column-switch.bs.table', function (e, name, args) {
                var hidden = $table.bootstrapTable('getHiddenColumns');

                if (hidden.length > 0)
                    $('.action-hidden-columns').removeClass('hidden');
                else
                    $('.action-hidden-columns').addClass('hidden');
            });

            $table.on('post-body.bs.table', function (e, name, args) {

                $('[data-toggle="tooltip"]').tooltip();
            })


            $table.on('click-cell.bs.table', function (event, field, value, row, $obj) {
                //console.log(e);
                event.preventDefault();
                event.stopPropagation();
                event.stopImmediatePropagation();

            });


            $table.on('expand-row.bs.table', function (e, index, row, $obj) {

                $obj.attr('colspan',12);

                var t = $obj.closest('tr');

                var stripe = t.prev().hasClass('bt-even-row');

                t.addClass('dosage-row-bottom').addClass('dosage-row-left').addClass('dosage-row-right');

                if (stripe)
                    t.addClass('bt-even-row');
                else
                    t.addClass('bt-odd-row');

                t.prev().addClass('dosage-row-top').addClass('dosage-row-left').addClass('dosage-row-right');

                $obj.load( "/api/search/expand/" + row.symbol_id , function() {
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

            $(".fixed-table-toolbar .search .input-group").attr("style","width:800px;");
            $(".fixed-table-toolbar .search .input-group:first").attr("style","float:left; width:200px;");

            $("button[name='filterControlSwitch']").attr('title', 'Column Search');
            $("button[aria-label='Columns']").attr('title', 'Show/Hide Columns');

            region_listener();

            $('.fixed-table-toolbar').on('change', '.toggle-all', function (e, name, args) {

                var hidden = $table.bootstrapTable('getHiddenColumns');

                if (hidden.length > 0)
                    $('.action-hidden-columns').removeClass('hidden');
                else
                    $('.action-hidden-columns').addClass('hidden');
            });

            $('.action-standard-view').on('click', function(e) {
                if ($(this).hasClass('btn-default'))
                {
                    // switch to scores
                    $(this).removeClass('btn-default').addClass('btn-primary');
                    $('.action-dosage-view').addClass('btn-default').removeClass('btn-primary');
                    $table.bootstrapTable('hideColumn', ['haplo_assertion', 'triplo_assertion']);
                    $table.bootstrapTable('showColumn', ['curation', 'date_last_curated']);
                    $('#dosage-view-filters').addClass('hidden');
                    //TODO:  CLEAR ALL THE DOSAGE FILTERS

                }
            });

            $('.action-show-dosage').on('click', function(e) {
               // if ($(this).hasClass('btn-default'))
                //{
                    // switch to scores
                    //$(this).removeClass('btn-default').addClass('btn-primary');
                    //$('.action-standard-view').addClass('btn-default').removeClass('btn-primary');

                if ($(this).prop('checked')) 
                {
                    $table.bootstrapTable('hideColumn', ['curation']);
                    $table.bootstrapTable('showColumn', ['haplo_assertion', 'triplo_assertion', 'precuration']);
                    $('#dosage-view-filters').removeClass('hidden');
                }
                else
                {
                    $table.bootstrapTable('showColumn', ['curation']);
                    $table.bootstrapTable('hideColumn', ['haplo_assertion', 'triplo_assertion', 'precuration']);
                    $('#dosage-view-filters').addClass('hidden');
                }
                //}
            });

        });

    </script>

@endsection

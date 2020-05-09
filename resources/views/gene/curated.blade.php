@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" id="gene_curations_all">
        <div class="col-md-12 text-center">
          <h1 class=" display-4 "><strong>{{ count($records)}}</strong> Curated Genes
          </h1>
          <div class="col-sm-4 col-sm-offset-4">
            <div class="input-group">
                <span class="input-group-addon" id="basic-addon1"><i class="glyphicon glyphicon-search"></i></span>
                <input type="text" class="form-control input-block search" id="interactive_curations_search" placeholder="Search within this table">
            </div>
          </div>
        </div>
        <div class="col-md-12">

            <div class="">
                <table id=interactive_curations_table class="table table-striped table-hover table-bordered border-top-0 border-left-0 border-right-0">
                    <thead>
                    <tr class="floating-hide small text-center border-left-0 border-0 text-light bg-white">
                        <th class=" floating-hide  text-dark border-left-0 bg-white text-center border-0 pb-2"></th>
                        <th class=" floating-hide text-dark bg-white text-center border-0 pb-2">
                            <div class="h3 mb-0">
                                <span class='hidden-sm hidden-xs'><i class="glyphicon glyphicon-ok"></i></span>
                            </div>
                            Gene-Disease Validity
                        </th>
                        <th class=" floating-hide text-dark bg-white text-center border-0 pb-2">
                            <div class="h3 mb-0">
                                <span class='hidden-sm hidden-xs'><i class="glyphicon glyphicon-ok"></i></span>
                            </div>
                            Clinical Actionability</th>
                        <th class=" floating-hide text-dark bg-white text-center border-0 pb-2" colspan="2">
                            <div class="h3 mb-0">
                                <span class='hidden-sm hidden-xs'><i class="glyphicon glyphicon-ok"></i></span>
                            </div>
                            Gene Dosage Sensitivity</th>
                    </tr>
                    <tr class="small text-center border-bottom-3 text-secondary">
                        <th class="th-sort  bg-white border-1  text-uppercase">Gene</th>
                        <th class="th-sort  bg-white text-center border-1 text-uppercase">Clinical Validity <br /><span class='hidden-sm hidden-xs'>Classifications</span></th>
                        <th class="th-sort  bg-white text-center border-1 text-uppercase">Evidence-Based Summary</th>
                        <th class="th-sort  bg-white text-center border-1 text-uppercase"><span class='hidden-sm hidden-xs'>Haploinsufficiency</span><span class='visible-md visible-xs'>HI</span> Score</th>
                        <th class="th-sort  bg-white text-center border-1 text-uppercase"><span class='hidden-sm hidden-xs'>Triplosensitivity</span><span class='visible-sm visible-xs'>TI</span> Score</th>
                    </tr>
                    </thead>
                    <tbody class="list">
                    @isset($records)
                        @foreach($records as $item)
                        <tr class="text-center">
                            <td class="text-left"><a class=" filter-gene" href="{{ route('gene-show', $item->hgnc_id) }}">
                                <span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{$item->href}}"><i class="fas fa-info-circle text-muted"></i></span><strong> {{ $item->label }}</strong>
                                </a>
                            </td>
                                <td>
                                    @if($item->has_validity)<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> <span class='hidden-sm hidden-xs'>Curated</span></a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->has_actionability)<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> <span class='hidden-sm hidden-xs'>Curated</span></a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->has_dosage_triplo !== false)
                                    <a class="btn btn-success btn-sm pb-0 pt-0" href="{{ env('CG_URL_CURATIONS_DOSAGE') . $item->label }}">
										<i class="glyphicon glyphicon-ok"></i> 
										<span class='hidden-sm hidden-xs'> {{ \App\GeneLib::dosageAssertionString($item->has_dosage_triplo) }}</span>
									</a>
                                    @endif
                                </td>
                                <td>
                                    @if($item->has_dosage_haplo !== false)
                                    <a class="btn btn-success btn-sm pb-0 pt-0" href="{{ env('CG_URL_CURATIONS_DOSAGE') . $item->label }}">
										<i class="glyphicon glyphicon-ok"></i>
										<span class='hidden-sm hidden-xs'> {{ \App\GeneLib::dosageAssertionString($item->has_dosage_haplo) }}</span>
									</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>

            </div>
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
    <script>
        $(document).ready(function() {
            var table = $('#interactive_curations_table').DataTable(
                {
                    pageLength: 100,
                    lengthChange: false,
                    fixedHeader: true
                }
            );
            // #myInput is a <input type="text"> element
            $('#interactive_curations_search').on( 'keyup', function () {
                table.search( this.value ).draw();
            } );
        } );
    </script>
@endsection

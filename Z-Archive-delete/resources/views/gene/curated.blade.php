@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center">
          <h1 class=" display-4 "><strong>{{ count($collection)}}</strong> Curated Genes 
          </h1>
        </div>
        <div class="col-md-12">

            <div class="">
                <table class="table table-striped table-hover table-bordered border-top-0 border-left-0 border-right-0">
                    <tr class="small text-center border-left-0 border-0 text-light bg-white"> 
                        <th class="  text-dark border-left-0 bg-white text-center border-0 pb-2"></th>
                        <th class="  text-dark bg-white text-center border-0 pb-2">
                            <div class="h3">
                                <i class="glyphicon glyphicon-ok"></i>
                            </div>
                            Gene-Disease Validity
                        </th>
                        <th class="  text-dark bg-white text-center border-0 pb-2">
                            <div class="h3">
                                <i class="glyphicon glyphicon-ok"></i>
                            </div>
                            Clinical Actionability</th>
                        <th class="  text-dark bg-white text-center border-0 pb-2" colspan="2">
                            <div class="h3">
                                <i class="glyphicon glyphicon-ok"></i>
                            </div>
                            Gene Dosage Sensitivity</th>
                    </tr>
                    <tr class="small text-center border-0 text-light"> 
                        <th class="  bg-secondary border-0">Gene</th>
                        <th class="  bg-secondary text-center border-0">Clinical Validity Classifications</th>
                        <th class="  bg-secondary text-center border-0">Evidence-Based Summary</th>
                        <th class="  bg-secondary text-center border-0">Haploinsufficiency Score</th>
                        <th class="  bg-secondary text-center border-0">Triplosensitivity Score</th>
                    </tr>
                    @isset($collection)
                        @foreach($collection as $item)
                        <tr class="text-center">
                                <td class="text-left"><a class="" href="{{ route('gene-show', $item->href) }}"><strong>{{ $item->label }}</strong></a></td>
                                <td>
                                    @if(count($item->validity))<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> Curated</a>
                                    @endif
                                </td>
                                <td>
                                    @if(count($item->actionability))<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> Curated</a>
                                    @endif
                                </td>
                                <td>
                                    @isset($item->dosage[0])<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> {{$item->dosage[0]['short_label']}}</a>
                                    @endisset
                                </td>
                                <td>
                                    @isset($item->dosage[1])<a class="btn btn-success btn-sm pb-0 pt-0" href="{{ route('gene-show', $item->href) }}"><i class="glyphicon glyphicon-ok"></i> {{$item->dosage[1]['short_label']}}</a>
                                    @endisset
                                </td>
                            </tr>
                        @endforeach
                    @endisset
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

@endsection
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h1 class=" display-4 ">Genes 
          </h1>
        </div>
        <div class="col-md-12">

            <div class="card">
                <div class="card-body">
                    
                </div>
                <table class="table table-striped table-hover">
                    <tr class="small">
                        <th>Gene</th>
                        <th>Location</th>
                        <th>Gene Validity</th>
                        <th>Actionability</th>
                        <th>Variant Path.</th>
                        <th>HI <i class="fas fa-info-circle text-info"></i></th>
                        <th>TI <i class="fas fa-info-circle text-info"></i></th>
                        <th>pLI <i class="fas fa-info-circle text-info"></i></th>
                        <th>Reviewed</th>
                    </tr>
                    <tr>
                        <td><a href="{{ route('dosage-show') }}"><strong>BRCA1</strong></a></td>
                        <td>
                            <table>
                                <tr>
                                    <td class="pr-2 text-22px text-normal" rowspan="2">11</td>
                                    <td class="text-10px">1234567</td>
                                </tr>
                                <tr>
                                    <td class="text-10px ">5342343</td>
                                </tr>
                            </table>
                        </td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">3 Reports <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">2 Reports <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">15 Reports <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">Sufficient Evidence <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="No evidence for dosage pathogenicity">No Evidence <i class="far fa-chevron-circle-down"></i></td>
                        <td>0.00 </td>
                        <td>09/11/2017</td>
                    </tr>
                    <tr>
                        <td><a href="{{ route('dosage-show') }}"><strong>BRCA2</strong></a></td>
                        <td>
                            <table>
                                <tr>
                                    <td class="pr-2 text-22px text-normal" rowspan="2">11</td>
                                    <td class="text-10px">1234567</td>
                                </tr>
                                <tr>
                                    <td class="text-10px ">5342343</td>
                                </tr>
                            </table>
                        </td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">5 Reports <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2"> </td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">9 Reports <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">Sufficient Evidence <i class="far fa-chevron-circle-down"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="No evidence for dosage pathogenicity">No Evidence <i class="far fa-chevron-circle-down"></i></td>
                        <td>0.00 </td>
                        <td>09/11/2017</td>
                    </tr>
                </table>

            </div>
            <nav class="text-center" aria-label="Page navigation">
              <ul class="pagination">
                <li>
                  <a href="#" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                  </a>
                </li>
                <li class="active"><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">4</a></li>
                <li><a href="#">5</a></li>
                <li>
                  <a href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                  </a>
                </li>
              </ul>
            </nav>
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
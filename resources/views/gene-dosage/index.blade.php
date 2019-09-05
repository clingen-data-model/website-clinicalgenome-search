@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        
        <div class="col-md-12">
            <h1 class="display-4 mb-1">Dosage Sensitivity Curations</h1>
            <p class="mb-4 small"><strong>1434 genes and regions</strong> have been assesses whether there is evidence to support if it is dosage sensitive and should be targeted on a cytogenomic array.</p>

        </div>
        <div class="col-md-10">

                <table class="table table-bordered bg-light mb-0 small">
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold" nowrap=""role="button" data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters"><i class="fas fa-plus-circle"></i> FILTERS</td>
                        <td style="width:98%" class="pt-2 pb-1" role="button" data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                            <span class="badge badge-info">Protein coding <i class="far fa-times-square"></i></span>
                        </td>
                        <td style="width:1%" class="text-muted bold" nowrap="">RESULTS: 1 to 2 of 2</td>
                    </tr>
                </table>
                <div class="collapse" id="collapseFilters">
                  <div class="bg-light border-1 border-muted p-2">
                    <table class="table table-condensed border-none bg-light mb-0">
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right" nowrap="">Show/Hide:</td>
                        <td style="width:99%" colspan="2">
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3" checked="" class="text-success"> Protein coding <i class="fas fa-info-circle text-muted-more"></i>
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3"> OMIM <i class="fas fa-info-circle text-muted-more"></i>
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3"> Morbid <i class="fas fa-info-circle text-muted-more"></i>
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3"> Location Overlap <i class="fas fa-info-circle text-muted-more"></i>
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3"> Location Contained <i class="fas fa-info-circle text-muted-more"></i>
                            </label>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">Gene(s):</td>
                        <td style="width:99%" colspan="2">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="All">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">Region(s):</td>
                        <td style="width:99%" colspan="2">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="All">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">Disease(s):</td>
                        <td style="width:99%" colspan="2">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="All">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">Location:</td>
                        <td style="width:99%" colspan="2">
                            <div class="col-sm-12 m-0 p-0">
                            <div class="form-group input-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="...">
                                <div class="input-group-btn">
                                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">GRCh37 <span class="caret"></span></button>
                                        <ul class="dropdown-menu dropdown-menu-right">
                                          <li><a href="#">GRCh37</a></li>
                                          <li><a href="#">GRCh38</a></li>
                                        </ul>
                                      </div>
                              </div>
                          </div>
                              <div class="text-10px text-muted">example: chr2:44,000,000-45,500,000, 2p21-2p16.2</div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">HI Score:</td>
                        <td style="width:99%" colspan="2">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="All">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">TI Score:</td>
                        <td style="width:99%" colspan="2">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="All">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right" nowrap="">ClinGen:</td>
                        <td style="width:99%" colspan="2">
                            <label class="checkbox-inline pl-0 ml-0 text-muted">
                                Gene curated by:
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3" checked=""> Gene-Disease Validity
                            </label>
                            <label class="checkbox-inline">
                              <input type="checkbox" id="inlineCheckbox3" value="option3" checked=""> Clinical Actionability
                            </label>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">%HI Range:</td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="0">
                              </div>
                        </td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="100">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">pLI Range:</td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="0">
                              </div>
                        </td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="100">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                    <tr class="small">
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap="">Reviewed:</td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="Start date">
                              </div>
                        </td>
                        <td style="width:50%">
                            <div class="form-group input-group-sm mb-0">
                                <input type="text" class="form-control" id="" placeholder="End date">
                              </div>
                        </td>
                        <td style="width:1%" class="text-muted bold text-right pt-2" nowrap=""><i class="fas fa-info-circle text-muted-more"></td>
                    </tr>
                </table>
                  </div>
                </div>

            <div class="card mt-2">

                <table class="table table-striped table-hover mb-0">
                    <tr class="small bg-secondary">
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> Gene/Region</th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> Location</th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> HI Score <i class="fas fa-info-circle"></i></th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> TI Score <i class="fas fa-info-circle"></i></th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> ClinGen</th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> Morbid</th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> OMIM</th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> %HI <i class="fas fa-info-circle"></i></th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> pLI <i class="fas fa-info-circle"></i></th>
                        <th class="text-light small" nowrap=""><i class="fas fa-sort"></i> Reviewed</th>
                    </tr>
                    <tr class="small">
                        <td><a href="{{ route('dosage-show') }}"><strong>BRCA1</strong></a></td>
                        <td>
                            <table>
                                <tr>
                                    <td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">11</td>
                                    <td class="text-10px line-height-normal">1234567</td>
                                </tr>
                                <tr>
                                    <td class="text-10px line-height-normal">5342343</td>
                                </tr>
                            </table>
                        </td>
                        <td class="" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">Sufficient Evidence (3) <i class="far fa-info-circle pointer text-muted-more"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="No evidence for dosage pathogenicity">No Evidence (0)<i class="far fa-info-circle pointer text-muted-more"></i></td>
                        <td class="text-success"><i class="fas fa-check"></i>+</td>
                        <td class="text-success"><i class="fas fa-check"></i>+</td>
                        <td class="text-success"><i class="fas fa-check"></i></td>
                        <td class="text-danger">13.11 </td>
                        <td class="text-success">0.00 </td>
                        <td>09/11/2017</td>
                    </tr>
                    <tr class="small">
                        <td><a href="{{ route('dosage-show') }}"><strong>6q24 region (includes PLAGL1)</strong></a></td>
                        <td>
                            <table>
                                <tr>
                                    <td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">11</td>
                                    <td class="text-10px line-height-normal">1234567</td>
                                </tr>
                                <tr>
                                    <td class="text-10px line-height-normal">5342343</td>
                                </tr>
                            </table>
                        </td>
                        <td class="" data-container="body" data-toggle="popover" data-placement="bottom" data-content="Phenotype: BREAST-OVARIAN CANCER, FAMILIAL, SUSCEPTIBILITY TO, 2; BROVCA2">Sufficient Evidence (3) <i class="far fa-info-circle pointer text-muted-more"></i></td>
                        <td class="pointer" data-container="body" data-toggle="popover" data-placement="bottom" data-content="No evidence for dosage pathogenicity">No Evidence (3)<i class="far fa-info-circle pointer text-muted-more"></i></td>
                        <td class="text-success"></td>
                        <td class="text-success"></td>
                        <td class="text-success"></td>
                        <td class="text-danger">13.11 </td>
                        <td class="text-success">0.00 </td>
                        <td>09/11/2017</td>
                    </tr>
                </table>
            </div>
            
                    <nav class="text-center" aria-label="Page navigation">
                      <ul class="pagination pagination-sm">
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
        @include('_partials.nav_side.dosage',['navActive' => "index"])
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
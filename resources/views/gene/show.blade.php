@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <h1 class=" display-4 ">{{ $record->symbol }} 
              <a class="btn btn-default btn-sm pl-2 pr-2 pt-1 pb-1 text-10px" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <i class="far fa-chevron-circle-down"></i>Gene Facts 
              </a>
          </h1>
        </div>
        <div class="col-md-10">
            
            <div class="collapse" id="collapseExample">
                <div class="row">
                    <div class="col-sm-12  mt-0 pt-0 small">
                        <h4 class="border-bottom-1">Gene Facts</h4>

                        <dl class="dl-horizontal">
                          <dt>HGNC Symbol</dt>
                          <dd>{{ $record->symbol }} ({{ $record->hgnc_id }})</dd>
                          <dt>HGNC Name</dt>
                          <dd>{{ $record->name }}</dd>
                          <dt>Gene type</dt>
                          <dd>{{ $record->locus_group }}</dd>
                          <dt>Locus type</dt>
                          <dd>{{ $record->locus_type }}</dd>
                          <dt>Previous symbols</dt>
                          <dd>{{ $record->prev_symbols_string }}</dd>
                          <dt>Alias symbols</dt>
                          <dd>{{ $record->alias_symbols_string }}</dd>
                          <dt>Genomic Coordinate</dt>
                          <dd>
                            <table>
                                <tr>
                                    <td>GRCh37/hg19</td>
                                    <td>chr17: 41,196,312-41,277,500
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> 
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
                                    </td>
                                </tr>  
                                <tr>
                                    <td class="pr-3">GRCh38/hg38</td>
                                    <td>chr17: 43,044,295-43,125,483
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> 
                                        <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> NCBI</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> Ensembl</a> <a href="" class="badge-info badge pointer"><i class="fas fa-external-link"></i> UCSC</span>
                                    </td>
                                </tr> 
                            </table>
                          </dd>
                          <dt>Chromosomal location</dt>
                          <dd>{{ $record->location }} <a href="" class="badge-info badge pointer"><i class="fas fa-search"></i> ClinGen</a> </dd>
                          <dt>Function</dt>
                          <dd>Involved in double-strand break repair and/or homologous recombination. Binds RAD51 and potentiates recombinational DNA repair by promoting assembly of RAD51 onto single-stranded DNA (ssDNA). Acts by targeting RAD51 to ssDNA over double-stranded DNA, enabling RAD51 to displace … Source: UniProt</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <h2 class="h3 mb-0">ClinGen's Curations Summary Report</h2>

			@forelse ($record->diseases as $disease)
            <div class="card">
				<div class="card-header text-white bg-info">
					{{ $record->symbol }} - {{ $disease['label'] }} | {{ $disease['id'] }}
				</div>
				<div class="card-body">
				
					<ul class="list-group list-group-flush">
						
						<!-- Gene Disease Validity				-->
						@foreach($record->findValidity($disease['id']) as $validity)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">G - Gene-Disease Validity</td>
									<td class="col-sm-6">{{ $validity['classification'] }}</td>
									<td class="col-sm-2">{{ $record->displayDate($validity['date']) }} </td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $validity['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach
						
						<!-- Gene Dosage						-->
						@foreach($record->findDosage($disease['id']) as $dosage)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">D - Dosage</td>
									<td class="col-sm-6">{{ $dosage['classification'] }}</td>
									<td class="col-sm-2">{{ $record->displayDate($dosage['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $dosage['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach
						
						<!-- Actionability					-->
						@foreach($record->findActionability($disease['id']) as $actionability)
						<li class="list-group-item">
							<table style="table-layout:fixed;">
								<tr>
									<td class="col-sm-3">A - Actionability</td>
									<td class="col-sm-6">{{ $actionability['type'] }} - View Report For Scoring Details</td>
									<td class="col-sm-2">{{ $record->displayDate($actionability['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $actionability['report'] }}">View report</a></td>
								</tr>
							</table>
						</li>
						@endforeach
					</ul>

                </div>
            </div>
            @empty
            THIS GENE HAS NOT BEEN CURATED
            @endforelse
        </div>
        @include('_partials.nav_side.gene',['navActive' => "show"])
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

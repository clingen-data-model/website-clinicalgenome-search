@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
		  <h1 class=" display-4 ">{{ $record->symbol }}
				@include("_partials.facts.gene-button")
		  </h1>
		</div>
		<div class="col-md-12">

			@include("_partials.facts.gene-panel")

			<h2 class="h3 mb-0">ClinGen's Curations Summary Report</h2>
			<ul class="nav nav-tabs">
          <li class="active">
            <a href="{{ route('gene-show', $record->hgnc_id) }}" class=" bg-primary text-white">
              ClinGen's Curation Summaries
            </a>
          </li>
          <li class="">
            <a href="{{ route('gene-external', $record->hgnc_id) }}">External Genomic Resources </a>
          </li>
          <li class="">
            <a href="https://www.ncbi.nlm.nih.gov/clinvar/?term={{ $record->symbol }}%5Bgene%5D" class="" target="clinvar">ClinVar Variants  <i class="glyphicon glyphicon-new-window text-xs" id="external_clinvar_gene_variants"></i></a>
          </li>
        </ul>
			@forelse ($record->diseases as $disease)
			<div class="card">
				<div class="card-header text-white bg-primary">
					<h3 class="text-white h5 p-0 m-0">{{ $record->symbol }} - {{ $disease['label'] }} <small class="text-white">| {{ $disease['id'] }}</small></h3>
				</div>
				<div class="card-body p-0 m-0">

					<table class="panel-body table table-hover">
          <thead class="thead-labels">
            <tr>
              <th class="col-sm-3 th-curation-group text-left">Curated by</th>
              <th class="col-sm-4 text-left"> Classification</th>
              <th class="col-sm-2 text-left"> </th>
              <th class="col-sm-2 text-center">Date</th>
              <th class="col-sm-1 text-center">Report</th>
            </tr>
          </thead>

					<tbody class="">

						<!-- Gene Disease Validity				-->
						@foreach($record->findValidity($disease['id']) as $validity)
								<tr>
									<td class="col-sm-3">G - Gene-Disease Validity</td>
									<td class="col-sm-6">{{ $validity['classification'] }}</td>
									<td class="col-sm-2"><span class="cursor-pointer" data-toggle="tooltip" data-placement="top" title="{{ $record->displayMoi($validity['moi'], 'long') }}"><i class="fas fa-info-circle text-muted"></i></span> {{ $record->displayMoi($validity['moi']) }}</td>
									<td class="col-sm-2">{{ $record->displayDate($validity['date']) }} </td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $validity['report'] }}">View report</a></td>
								</tr>
						@endforeach

						<!-- Actionability					-->
						@foreach($record->findActionability($disease['id']) as $actionability)
								<tr>
									<td class="col-sm-3">A - Actionability</td>
									<td class="col-sm-6">{{ $actionability['type'] }} - View Report For Scoring Details</td>
									<td class="col-sm-2"></td>
									<td class="col-sm-2">{{ $record->displayDate($actionability['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $actionability['report'] }}">View report</a></td>
								</tr>
						@endforeach


						<!-- Gene Dosage						-->
						@foreach($record->findDosage($disease['id']) as $dosage)
								<tr>
									<td class="col-sm-3">D - Dosage</td>
									<td class="col-sm-6">{{ $dosage['classification'] }}</td>
									<td class="col-sm-2"></td>
									<td class="col-sm-2">{{ $record->displayDate($dosage['date']) }}</td>
									<td class="col-sm-1"><a class="btn btn-xs btn-success" href="{{ $dosage['report'] }}">View report</a></td>
								</tr>
						@endforeach
					</tbody>


        </table>


				</div>
			</div>
			@empty
			THIS GENE HAS NOT BEEN CURATED
			@endforelse
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

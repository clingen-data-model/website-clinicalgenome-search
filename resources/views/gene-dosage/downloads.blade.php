@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h1><img src="/images/dosageSensitivity-on.png" width="50" height="50">  Dosage Sensitivity</h1>
        </div>

        <div class="col-md-4">
            <div class="">
              <div class="text-right p-2">
                  <ul class="list-inline pb-0 mb-0 small">
                    <li class="small line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
                  </ul>
              </div>
            </div>
          </div>

        <div class="col-md-12 pt-3">

            <div class="card">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Gene List</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh37.tsv"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (tsv)</button><a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh38.tsv"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (tsv)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Region List</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh37.tsv"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (tsv)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh38.tsv"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (tsv)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Haploinsufficiency Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh37.bed"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (bed)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh38.bed"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (bed)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Triplosensitivity Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh37.bed"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (bed)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh38.bed"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (bed)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>

                    <tr>
                        <td><strong>Dosage Sensitivity Recurrent CNV</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center"">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg19.bed"><button class="btn btn-default btn-sm mt-1 mb-3"><i class="fas fa-download"></i> HG19 (bed)</button></a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg19.aed"><button class="btn btn-default btn-sm mb-1"><i class="fas fa-download"></i> HG19 (aed)</button></a>
                        </td>
                        <td style="text-align:center">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg38.bed"><button class="btn btn-default btn-sm mt-1 mb-3"><i class="fas fa-download"></i> HG38 (bed)</button></a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg38.aed"><button class="btn btn-default btn-sm mb-1"><i class="fas fa-download"></i> HG38 (aed)</button></a>
                        </td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>README</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/README"><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> README</button><a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    
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
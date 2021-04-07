@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-7">

            <table class="mt-3 mb-2">
                <tr>
                <td class="valign-top"><img src="/images/dosageSensitivity-on.png" width="40" height="40"></td>
                <td class="pl-2"><h1 class="h2 p-0 m-0">  Dosage Sensitivity Downloads</h1>
                </td>
                </tr>
            </table>
        </div>

        <div class="col-md-5">
            <div class="">
              <div class="text-right p-2">
                  <ul class="list-inline pb-0 mb-0 small">
                    <li class="text-stats line-tight text-center pl-3 pr-3"><a href="{{ route('dosage-index') }}"><i class="glyphicon glyphicon-circle-arrow-left text-18px text-muted"></i><br />Return to<br />Dosage Listing</a></li>
                  </ul>
              </div>
            </div>
          </div>

        <div class="col-md-12 pt-3">
            <div class="row mb-3">
                <div class="col-md-9">
                    <a target="_blank" class="text-primary" href="https://visitor.r20.constantcontact.com/d.jsp?llr=dagk4ypab&p=oi&m=dagk4ypab&sit=crnqtbqib&f=9d0e9a0e-a316-452d-9078-4b40e00dd3a6" >Sign up for the ClinGen General Interest Mailing list to get news and updates delivered to your mailbox. If you are interested in notifications when ClinGen file formats change, please opt in to the ClinGen Website File Format Change Notification List.</a>
                </div>
                <div class="col-md-3">
                    <a target="_blank" class="btn btn-block btn-primary mt-2" href="https://visitor.r20.constantcontact.com/d.jsp?llr=dagk4ypab&p=oi&m=dagk4ypab&sit=crnqtbqib&f=9d0e9a0e-a316-452d-9078-4b40e00dd3a6" >Sign-up</a>
                </div>
            </div>
            <div class="card">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><strong>README</strong>
                            <div class="small">This files are produced by the ClinGen Dosage Sensitivity Curation Working Group, formerly the International Standards for Cytogenomic Arrays (ISCA) Consortium Evidence-Based Review Committee. The goal of this group is to curate regions of the genome (both single genes and particular genomic regions) with respect to their haploinsufficiency and/or triplosensitivity. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/README"><button class="btn btn-default"><i class="fas fa-download"></i> README</button><a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Gene List</strong>
                            <div class="small">The files in this directory contain data for genes that have been through the review process. These files are updated daily. Files are available for genes and regions localized on both GRCh37 and GRCh38.  The tsv files have a header and contain all of the curation information found on the ClinGen Dosage Sensitivity Map web pages, including disease name (when applicable), PMIDs used as evidence, and comments.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh37.tsv"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh37 (tsv)</button><a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh38.tsv"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh38 (tsv)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Region List</strong>
                            <div class="small">The files in this directory contain data for regions that have been through the review process. These files are updated daily. Files are available for genes and regions localized on both GRCh37 and GRCh38.  The tsv files have a header and contain all of the curation information found on the ClinGen Dosage Sensitivity Map web pages, including disease name (when applicable), PMIDs used as evidence, and comments.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh37.tsv"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh37 (tsv)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh38.tsv"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh38 (tsv)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Haploinsufficiency Genes</strong>
                            <div class="small">BED files are available for gene curation. There are separate files for haploinsufficiency and triplosensitivity as the BED file only has one column for 'score'. Because the score column expects a number rather than text, "Dosage sensitivity unlikely" is represented by the score of 40, and "Gene associated with autosomal recessive phenotype" is represented by the score of 30.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh37.bed"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh37 (bed)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh38.bed"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh38 (bed)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Triplosensitivity Genes</strong>
                            <div class="small">BED files are available for gene curation. There are separate files for haploinsufficiency and triplosensitivity as the BED file only has one column for 'score'. Because the score column expects a number rather than text, "Dosage sensitivity unlikely" is represented by the score of 40, and "Gene associated with autosomal recessive phenotype" is represented by the score of 30.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh37.bed"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh37 (bed)</button></a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh38.bed"><button class="btn btn-default"><i class="fas fa-download"></i> GRCh38 (bed)</button></a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>

                    <tr>
                        <td><strong>Dosage Sensitivity Recurrent CNV</strong>
                            <div class="small">These files contains recurrent copy number variations (CNV) that have been, or are in the process of being, reviewed by the recurrent CNV ClinGen DSC Subgroup. This file is available in genome build hg19 and hg38 and can be opened in ChAS software (.aed) or in array analysis software that utilizes (.bed) files as well as the UCSC genome browser. The orange bars represent each recurrent region. The black bars represent the segmental duplication clusters/breakpoints.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center"">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg19.bed"><button class="btn btn-default mt-3 mb-3"><i class="fas fa-download"></i> HG19 (bed)</button></a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg19.aed"><button class="btn btn-default mb-1"><i class="fas fa-download"></i> HG19 (aed)</button></a>
                        </td>
                        <td style="text-align:center">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg38.bed"><button class="btn btn-default mt-3 mb-3"><i class="fas fa-download"></i> HG38 (bed)</button></a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg38.aed"><button class="btn btn-default mb-1"><i class="fas fa-download"></i> HG38 (aed)</button></a>
                        </td>
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
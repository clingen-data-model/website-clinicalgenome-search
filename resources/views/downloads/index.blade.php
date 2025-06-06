@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <h1 class="h2 mt-4">  ClinGen's File Downloads &amp; APIs</h1>

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
            <hr />

            <p>This page provides files for download and information about API resources for the following activities.</p>
            <ul>
                <li><a href="#section_summary">Summary Report</a></li>
                <li><a href="#section_gene-disease-validity">Gene-Disease Validity</a></li>
                <li><a href="#section_dosage">Dosage Sensitivity</a></li>
                <li><a href="#section_actionability">Clinical Actionability</a></li>
                <li><a href="#section_variant">Variant Pathogenicity</a></li>
            </ul>
            <hr />

            <h3 class="pt-4" id="section_summary">Summary Report</h3>
            <div class="card mb-4">
                <table class="table table-striped table-hover mb-0">
                    <tr>
                        <td><strong>ClinGen Curation Activity Summary Report </strong>
                            <div class="small">This file provides summary curation information about Gene-Disease Validity, Dosage Sensitivity, and Clinical Actionability. Curations for each activity are grouped by gene (HGNC) and disease (MONDO). This file is helpful for those seeking to understand all of the genes and/or diseases ClinGen has curated along with summary information.</div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('curation-activity-summary-cvs') }}"  class="btn btn-default watchdownloadclick" title="ClinGen Curation Activity Summary Report CVS"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                </table>
            </div>

            <h3 class="pt-4" id="section_gene-disease-validity"><img src="/images/clinicalValidity-on.png" width="35" height="35"> Gene-Disease Validity Downloads</h3>
            <div class="card mb-4">
                <table class="table table-striped table-hover mb-0">
                    <tr>
                        <td><strong>Gene-Disease Validity Curations </strong>
                            <div class="small">This file provides a summary of the Gene-Disease Validity curations completed by ClinGen's GCEPs. This file is built in real-time. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('validity-download') }}"  class="btn btn-default watchdownloadclick" title="Gene-Disease Validity Curations"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <!--
                    <tr>
                        <td><strong>Gene-Disease Validity Curations w/ Lumping & Splitting </strong>
                            <div class="small">This file provides a summary of the Gene-Disease Validity curations completed by ClinGen's GCEPs. This file is built in real-time and includes the associated Lumping & Splitting details.</div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('validity-ls-download') }}"  class="btn btn-default watchdownloadclick" title="Gene-Disease Validity Curations w/ Lumping & Splitting"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                -->
                </table>
            </div>

            <h3 class="pt-4" id="section_dosage"><img src="/images/dosageSensitivity-on.png" width="35" height="35"> Dosage Sensitivity Downloads</h3>
            <div class="card">
                <table class="table table-striped table-hover  mb-0">
                    <!--<tr>
                        <td colspan="4">
                            <div><p class="text-danger text-center"><strong><i>As previously announced, important changes were made to the Dosage Sensitivity download files on December 19th, 2021.<br />Please view the README file for more information.</i></strong></p></div>
                        </td>
                    </tr> -->
                    <tr>
                        <td><strong>README</strong>
                            <div class="small">This files are produced by the ClinGen Dosage Sensitivity Curation Working Group, formerly the International Standards for Cytogenomic Arrays (ISCA) Consortium Evidence-Based Review Committee. The goal of this group is to curate regions of the genome (both single genes and particular genomic regions) with respect to their haploinsufficiency and/or triplosensitivity. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/README" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download README"><i class="fas fa-download"></i> README</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curations (Genes Only)</strong>
                            <div class="small">This file provides a summary of the Dosage Sensitivity gene curations completed by ClinGen. This file is built in real-time. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('dosage-download') }}" target="_blank"  class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download CVS"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curations (Genes and Regions)</strong>
                            <div class="small">This file provides a summary of the Dosage Sensitivity gene and region curations completed by ClinGen. This file is built in real-time. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="{{ route('dosagefull-download') }}" target="_blank"  class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download CVS"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Gene List</strong>
                            <div class="small">The files in this directory contain data for genes that have been through the review process. These files are updated daily. Files are available for genes and regions localized on both GRCh37 and GRCh38.  The tsv files have a header and contain all of the curation information found on the ClinGen Dosage Sensitivity Map web pages, including disease name (when applicable), PMIDs used as evidence, and comments.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh37.tsv" target="_blank" class="btn btn-default watchdownloadclick "><i class="fas fa-download"></i> GRCh37 (tsv)</button><a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_gene_curation_list_GRCh38.tsv" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download GRCh38 (tsv)"><i class="fas fa-download"></i> GRCh38 (tsv)</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Curated Region List</strong>
                            <div class="small">The files in this directory contain data for regions that have been through the review process. These files are updated daily. Files are available for genes and regions localized on both GRCh37 and GRCh38.  The tsv files have a header and contain all of the curation information found on the ClinGen Dosage Sensitivity Map web pages, including disease name (when applicable), PMIDs used as evidence, and comments.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh37.tsv" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download GRCh37 (tsv)"><i class="fas fa-download"></i> GRCh37 (tsv)</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_region_curation_list_GRCh38.tsv" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download GRCh38 (tsv)"><i class="fas fa-download"></i> GRCh38 (tsv)</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Haploinsufficiency Genes</strong>
                            <div class="small">BED files are available for gene curation. There are separate files for haploinsufficiency and triplosensitivity as the BED file only has one column for 'score'. Because the score column expects a number rather than text, "Dosage sensitivity unlikely" is represented by the score of 40, and "Gene associated with autosomal recessive phenotype" is represented by the score of 30.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh37.bed" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download Haploinsufficiency GRCh37 (bed)" target="_blank"><i class="fas fa-download"></i> GRCh37 (bed)</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_haploinsufficiency_gene_GRCh38.bed" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download Haploinsufficiency GRCh38 (bed)"><i class="fas fa-download"></i> GRCh38 (bed)</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Triplosensitivity Genes</strong>
                            <div class="small">BED files are available for gene curation. There are separate files for haploinsufficiency and triplosensitivity as the BED file only has one column for 'score'. Because the score column expects a number rather than text, "Dosage sensitivity unlikely" is represented by the score of 40, and "Gene associated with autosomal recessive phenotype" is represented by the score of 30.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh37.bed" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download Triplosensitivity GRCh37 (bed)"><i class="fas fa-download"></i> GRCh37 (bed)</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="ftp://ftp.clinicalgenome.org/ClinGen_triplosensitivity_gene_GRCh38.bed" target="_blank" class="btn btn-default watchdownloadclick" title="Dosage Sensitivity Download Triplosensitivity GRCh38 (bed)"><i class="fas fa-download"></i> GRCh38 (bed)</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>

                    <tr>
                        <td><strong>Dosage Sensitivity Recurrent CNV</strong>
                            <div class="small">These files contains recurrent copy number variations (CNV) that have been, or are in the process of being, reviewed by the recurrent CNV ClinGen DSC Subgroup. This file is available in genome build hg19 and hg38 and can be opened in ChAS software (.aed) or in array analysis software that utilizes (.bed) files as well as the UCSC genome browser. The orange bars represent each recurrent region. The black bars represent the segmental duplication clusters/breakpoints.</div>
                        </td>
                        <td></td>
                        <td style="text-align:center"">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg19.bed" target="_blank" class="btn btn-default watchdownloadclick mt-3 mb-3" title="Dosage Sensitivity Recurrent CNV HG19 (bed)"><i class="fas fa-download"></i> HG19 (bed)</a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg19.aed" target="_blank" class="btn btn-default watchdownloadclick mb-1" title="Dosage Sensitivity Recurrent CNV HG19 (aed)"><i class="fas fa-download"></i> HG19 (aed)</a>
                        </td>
                        <td style="text-align:center">
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.bed%20file%20V1.1-hg38.bed" target="_blank" class="btn btn-default watchdownloadclick mt-3 mb-3" title="Dosage Sensitivity Recurrent CNV HG38 (bed)"><i class="fas fa-download"></i> HG38 (bed)</a>
                            <a href="ftp://ftp.clinicalgenome.org/ClinGen%20recurrent%20CNV%20.aed%20file%20V1.1-hg38.aed" target="_blank" class="btn btn-default watchdownloadclick mb-1" title="Dosage Sensitivity Recurrent CNV HG38 (aed)"><i class="fas fa-download"></i> HG38 (aed)</a>
                        </td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>All Dosage Sensitivity Download FTP Files and Archives</strong>
                            <div class="small">All Dosage Sensitivity download files are refreshed nightly are available for the last 60 days.  </div>
                        </td><td style="text-align:center" colspan="2">
                            <a href="https://ftp.clinicalgenome.org" target="_blank" class="btn btn-default watchdownloadclick mt-2 mb-1" title="Dosage Sensitivity FTP download files"><i class="fas fa-download"></i> Current FTP Files</a>
                        </td>
                        <td style="text-align:center" colspan="2">
                            <a href="https://ftp.clinicalgenome.org/archive" target="_blank" class="btn btn-default watchdownloadclick mt-2 mb-1" title="Dosage Sensitivity FTP download files"><i class="fas fa-download"></i> FTP File Archives</a>
                        </td>
                    </tr>
                </table>
            </div>



            <h3 class="pt-4" id="section_actionability"><img src="/images/clinicalActionability-on.png" width="35" height="35"> Clinical Actionability Downloads</h3>
            <div class="card mb-4">
                <table class="table table-striped table-hover mb-0">
                    <tr>
                        <td><strong>Clinical Actionability API - Adult JSON </strong>
                            <div class="small">REST-API endpoints to get the actionability curation data for the Adult context in a JSON format.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ" target="_blank" class="btn btn-default watchdownloadclick" title="Clinical Actionability API - Adult JSON Nester"><i class="fas fa-download"></i> NESTED JSON</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ?flavor=flat" target="_blank"   class="btn btn-default watchdownloadclick" title="Clinical Actionability API - Adult JSON Flat"><i class="fas fa-download"></i> FLAT JSON</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Clinical Actionability API - Pediatric JSON </strong>
                            <div class="small">REST-API endpoints to get the actionability curation data for the Pediatric context in a JSON format.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability - Pediatric JSON Nested"><i class="fas fa-download"></i> NESTED JSON</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ?flavor=flat" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability - Pediatric JSON Flat"><i class="fas fa-download"></i> FLAT JSON</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Summary of Overall Scores from Released Reports</strong>
                            <div class="small">Summary of overall scores from released reports is available for Adult and Pediatric contexts.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ?format=tsv" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of Overall Scores from Released Reports - Adult TSV"><i class="fas fa-download"></i> ADULT - TSV</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ?format=tsv" target="_blank"   class="btn btn-default watchdownloadclick"  title="Actionability Summary of Overall Scores from Released Reports - Pediatric TSV"><i class="fas fa-download"></i> Pediatric - TSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Summary of Individual Scores from Released Reports</strong>
                            <div class="small">Summary of individual scores from released reports is available for Adult and Pediatric contexts.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ/scoring?format=tsv&excludeNotScored=true" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of Individual Scores from Released Reports - ADULT"><i class="fas fa-download"></i> ADULT - TSV</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ/scoring?format=tsv&excludeNotScored=true" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of Individual Scores from Released Reports - Pediatric"><i class="fas fa-download"></i> Pediatric - TSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Summary of All Assertions from Released Reports</strong>
                            <div class="small">Summary of all assertions from released reports is available for Adult and Pediatric contexts.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ/assertion?format=tsv&assertionType=all" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of All Assertions from Released Reports - ADULT"><i class="fas fa-download"></i> ADULT - TSV</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ/assertion?format=tsv&assertionType=all" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of All Assertions from Released Reports - Pediatric"><i class="fas fa-download"></i> Pediatric - TSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Summary of Consensus Assertions from Released Reports</strong>
                            <div class="small">Summary of consensus assertions from released reports is available for Adult and Pediatric contexts.</div>
                        </td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Adult/api/summ/assertion?format=tsv" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of Consensus Assertions from Released Reports - ADULT"><i class="fas fa-download"></i> ADULT - TSV</a></td>
                        <td style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/Pediatric/api/summ/assertion?format=tsv" target="_blank"   class="btn btn-default watchdownloadclick" title="Actionability Summary of Consensus Assertions from Released Reports - Pediatric"><i class="fas fa-download"></i> Pediatric - TSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>Additional Clinical Actionability Download Options</strong>
                            <div class="small">Clinical Actionability data can be downloaded by clicking the link to the right and choosing the “Export” option on the top-right of the reports table.  </div>
                        </td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="https://actionability.clinicalgenome.org/ac/"  class="btn btn-default watchdownloadclick" title="Additional Clinical Actionability Download Options" target="_blank"> Website</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                </table>
            </div>


            <h3 class="pt-4" class="pt-4" id="section_variant"><img src="/images/variantPathogenicity-on.png" width="35" height="35"> Variant Pathogenicity Downloads</h3>
            <div class="card mb-4">
                <table class="table table-striped table-hover mb-0">
                    <tr>
                        <td><strong>Variant Pathogenicity </strong>
                            <div class="small">This file provides a summary of the Variant Pathogenicitycurations completed by ClinGen's VCEPs. </div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a href="http://erepo.clinicalgenome.org/evrepo/api/classifications/all?format=tabbed"  target="_blank" class="btn btn-default watchdownloadclick" title="Variant Pathogenicity Downloads CVS"><i class="fas fa-download"></i> CSV</a></td>
                        <td class="text-10px" nowrap=""></td>
                    </tr>
                    <tr>
                        <td><strong>API Documentation </strong>
                            <div class="small">OpenAPI specification and documentation for the ClinGen Evidence Repository JSON-LD REST API</div>
                        </td>
                        <td></td>
                        <td colspan="2" style="text-align:center; vertical-align:middle"><a target="_blank" href="https://brl-bcm.stoplight.io/docs/erepo"  class="btn btn-default watchdownloadclick" title="Variant Pathogenicity Downloads Documentation"> Documentation</a></td>
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class=" display-4 ">Dosage Sensitivity Downloads</h1>
        </div>
        <div class="col-md-10">

            <div class="card">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><strong>Dosage Sensitivity Gene List</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> All (tsv)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (tsv)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (tsv)</button></td>
                        <td class="text-10px" nowrap="">2/25/18<br />7:00:00 PM</td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Region List</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> All (tsv)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (tsv)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (tsv)</button></td>
                        <td class="text-10px" nowrap="">2/25/18<br />7:00:00 PM</td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Haploinsufficiency Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> All (bed)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (bed)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (bed)</button></td>
                        <td class="text-10px" nowrap="">2/25/18<br />7:00:00 PM</td>
                    </tr>
                    <tr>
                        <td><strong>Dosage Sensitivity Triplosensitivity Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> All (bed)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh37 (bed)</button></td>
                        <td><button class="btn btn-default btn-sm"><i class="fas fa-download"></i> GRCh38 (bed)</button></td>
                        <td class="text-10px" nowrap="">2/25/18<br />7:00:00 PM</td>
                    </tr>

                    <tr>
                        <td><strong>Dosage Sensitivity Recurrent CNV</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td></td>
                        <td>
                            <button class="btn btn-default btn-sm mb-2"><i class="fas fa-download"></i> HG19 (bed)</button>
                            <button class="btn btn-default btn-sm"><i class="fas fa-download"></i> HG19 (aed)</button>
                        </td>
                        <td>
                            <button class="btn btn-default btn-sm mb-2"><i class="fas fa-download"></i> HG38 (bed)</button>
                            <button class="btn btn-default btn-sm"><i class="fas fa-download"></i> HG38 (aed)</button>
                        </td>
                        <td class="text-10px" nowrap="">2/25/18<br />7:00:00 PM</td>
                    </tr>

                </table>
            </div>
        </div>
        @include('_partials.nav_side.dosage',['navActive' => "download"])
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
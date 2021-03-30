@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class=" display-4 ">Dosage Sensitivity Reports</h1>
        </div>
        <div class="col-md-10">

            <div class="card">
                <table class="table table-striped table-hover">
                    <tr>
                        <td><strong>Pathogenic CNV regions</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default">View Online</button></td>
                        <td><button class="btn btn-default">Download PDF</button></td>
                        <td><button class="btn btn-default">Download (CSV)</button></td>
                    </tr>
                    <tr>
                        <td><strong>ACMG 59 Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default">View Online</button></td>
                        <td><button class="btn btn-default">Download PDF</button></td>
                        <td><button class="btn btn-default">Download (CSV)</button></td>
                    </tr>
                    <tr>
                        <td><strong>Pathogenic CNV regions</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default">View Online</button></td>
                        <td><button class="btn btn-default">Download PDF</button></td>
                        <td><button class="btn btn-default">Download (CSV)</button></td>
                    </tr>
                    <tr>
                        <td><strong>ACMG 59 Genes</strong>
                            <div class="small text-muted">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu.</div>
                        </td>
                        <td><button class="btn btn-default">View Online</button></td>
                        <td><button class="btn btn-default">Download PDF</button></td>
                        <td><button class="btn btn-default">Download (CSV)</button></td>
                    </tr>
                </table>
            </div>
        </div>
        @include('_partials.nav_side.dosage',['navActive' => "reports"])
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
    var ctx = document.getElementById('chart_gene_vs_region').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Gene Curation', 'Region Curation'],
            datasets: [{
                label: '# of Votes',
                data: [1452, 319],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)'
                ],
                borderWidth: 0
            }]
        },
        options: {
                responsive: true,
                legend: {
                  position: 'bottom',
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                circumference: Math.PI,
                rotation: -Math.PI / 1
              }
        });
</script>
<script>
    var ctx = document.getElementById('chart_curation_status').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Review Complete', 'Under Primary Review', 'Under Secondary Review', 'Under Group Review', 'Awaiting Review'],
            datasets: [{
                label: '# of Votes',
                data: [1452, 319, 53, 15, 42324],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)'
                ],
                borderWidth: 0
            }]
        },
        options: {
                responsive: true,
                legend: {
                  position: 'bottom',
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                circumference: Math.PI,
                rotation: -Math.PI / 1
              }
        });
</script>
<script>
    var ctx = document.getElementById('chart_haploinsufficiency').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Associated with AR Phenotype', 'Sufficient Evidence', 'Moderate Evidence', 'Minimal Evidence', 'No Evidence'],
            datasets: [{
                label: '# of Votes',
                data: [452, 319, 53, 15, 52],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)'
                ],
                borderWidth: 0
            }]
        },
        options: {
                responsive: true,
                legend: {
                  position: 'bottom',
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                circumference: Math.PI,
                rotation: -Math.PI / 1
              }
        });
</script>
<script>
    var ctx = document.getElementById('chart_triplosensitivity').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Associated with AR Phenotype', 'Sufficient Evidence', 'Moderate Evidence', 'Minimal Evidence', 'No Evidence'],
            datasets: [{
                label: '# of Votes',
                data: [12, 59, 3, 5, 2],
                backgroundColor: [
                    'rgba(255, 99, 132)',
                    'rgba(54, 162, 235)',
                    'rgba(255, 206, 86)',
                    'rgba(75, 192, 192)',
                    'rgba(153, 102, 255)'
                ],
                borderWidth: 0
            }]
        },
        options: {
                responsive: true,
                legend: {
                  position: 'bottom',
                },
                animation: {
                  animateScale: true,
                  animateRotate: true
                },
                circumference: Math.PI,
                rotation: -Math.PI / 1
              }
        });
</script>
@endsection
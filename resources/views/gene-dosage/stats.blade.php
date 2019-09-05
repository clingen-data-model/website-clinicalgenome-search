@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h1 class=" display-4 ">Dosage Sensitivity Statistics</h1>
        </div>
        <div class="col-md-10">

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <h4 class="mb-0"> Genes VS Regions</h4>
                            <div class="bold small pb-2">1700 Total</div>
                            <p class="small">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu. Lorem ipsum dolor sit amet. </p>
                                <div class="small">
                                    <a href="#"><i class="fas fa-search"></i> View Dosage Genes</a> [Count: 1450]
                                    <br /><a href="#"><i class="fas fa-search"></i> View Dosage Regions</a> [Count: 250]
                                </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            <canvas id="chart_gene_vs_region"></canvas>
                        </div>
                        <div class="col-sm-12">
                            <hr />
                        </div>
                        <div class="col-sm-6">
                            <h4 class="mb-0"> Curation Statuses</h4>
                            <div class="bold small pb-2">1700 Total</div>
                            <p class="small">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu. Lorem ipsum dolor sit amet. </p>
                                <div class="small">
                                    <a href="#"><i class="fas fa-search"></i> Review Complete</a> [Count: 1450]
                                    <br /><a href="#"><i class="fas fa-search"></i> Under Primary Review</a> [Count: 150]
                                    <br /><a href="#"><i class="fas fa-search"></i> Under Secondary Review</a> [Count: 110]
                                    <br /><a href="#"><i class="fas fa-search"></i> Under Group Review</a> [Count: 90]
                                    <br /><a href="#"><i class="fas fa-search"></i> Awaiting Review</a> [Count: 22250]
                                </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            <canvas id="chart_curation_status"></canvas>
                        </div>
                        <div class="col-sm-12">
                            <hr />
                        </div>
                        <div class="col-sm-6">
                            <h4 class="mb-0"> Haploinsufficiency Genes & Regions</h4>
                            <div class="bold small pb-2">1204 Genes &amp; Regions Scored</div>
                            <p class="small">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu. Lorem ipsum dolor sit amet. </p>
                                <div class="small">
                                    <a href="#"><i class="fas fa-search"></i> Associated with AR Phenotype</a> [Count: 1450]
                                    <br /><a href="#"><i class="fas fa-search"></i> Sufficient Evidence</a> [Count: 150]
                                    <br /><a href="#"><i class="fas fa-search"></i> Moderate Evidence</a> [Count: 110]
                                    <br /><a href="#"><i class="fas fa-search"></i> Minimal Evidence</a> [Count: 90]
                                    <br /><a href="#"><i class="fas fa-search"></i> No Evidence</a> [Count: 50]
                                </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            <canvas id="chart_haploinsufficiency"></canvas>
                        </div>
                        <div class="col-sm-12">
                            <hr />
                        </div>
                        <div class="col-sm-6">
                            <h4 class="mb-0"> Triplosensitivity Genes & Regions</h4>
                            <div class="bold small pb-2">304 Genes &amp; Regions Scored</div>
                            <p class="small">Lorem ipsum dolor sit amet, at suas esse iracundia qui, has electram mediocrem forensibus ex, virtute adipiscing quo cu. Lorem ipsum dolor sit amet. </p>
                                <div class="small">
                                    <a href="#"><i class="fas fa-search"></i> Associated with AR Phenotype</a> [Count: 250]
                                    <br /><a href="#"><i class="fas fa-search"></i> Sufficient Evidence</a> [Count: 50]
                                    <br /><a href="#"><i class="fas fa-search"></i> Moderate Evidence</a> [Count: 10]
                                    <br /><a href="#"><i class="fas fa-search"></i> Minimal Evidence</a> [Count: 9]
                                    <br /><a href="#"><i class="fas fa-search"></i> No Evidence</a> [Count: 450]
                                </div>
                        </div>
                        <div class="col-sm-6 text-center">
                            <canvas id="chart_triplosensitivity"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('_partials.nav_side.dosage',['navActive' => "stats"])
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
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'ClinGen') }}</title>

  <!-- Scripts -->

  <script src="{{ asset('js/app.js') }}"></script>

  <!-- Fonts -->

  <!-- Styles -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  @livewireStyles
</head>
<body>
  <div id="app">

    @include('_partials._wrapper.header-micro',['navActive' => "summary"])
    @include('_partials._wrapper.header',['navActive' => "summary"])

    <main id='section_main' role="main">
      <section id='section_heading' class="pt-0 pb-0 mb-2 section-heading section-heading-groups text-light">
        <div  class="container">
          <span id="navSearchBar">
             @livewire('header-search-bar')
          </span>
          @hasSection ('heading')
            @yield('heading')
          @else
            <div class="mb-3"></div>
          @endif
          @isset($display_tabs['active'])
          <ul class="nav-tabs-search nav nav-tabs ml-0 mt-1">
            {{-- <li class="nav-item @if ($display_tabs['active'] == "home") active @endif ">
              <a class="nav-link" href="{{ route('home') }}">
                Overview
              </a>
            </li> --}}
            <li class="nav-item @if ($display_tabs['active'] == "gene-curations") active @endif ">
              <a class="nav-link" href="{{ route('gene-curations') }}">
                Curated Genes
              </a>
            </li>
            <li class="nav-item @if ($display_tabs['active'] == "validity") active @endif ">
              <a class="nav-link" href="{{ route('validity-index') }}">
                Gene-Disease Validity
              </a>
            </li>
            <li class="nav-item @if ($display_tabs['active'] == "dosage") active @endif ">
              <a class="nav-link" href="{{ route('dosage-index') }}">
                Dosage Sensitivity
              </a>
            </li>
            <li class="nav-item @if ($display_tabs['active'] == "actionability") active @endif ">
              <a class="nav-link" target="external-actionability" href="{{ route('actionability-index') }}">
                Clinical Actionability <i class="fas fa-external-link-alt small text-light"></i>
              </a>
            </li>
            <li class="nav-item @if ($display_tabs['active'] == "actionability") active @endif ">
              <a class="nav-link" target="external-erepo" href="{{ route('variant-path-index') }}">
                Curated Variants <i class="fas fa-external-link-alt small text-light"></i>
              </a>
            </li>
            {{-- <li class="nav-item @if ($display_tabs['active'] == "variant_path") active @endif ">
              <a class="nav-link" href="{{ route('variant-path-index') }}">
                Variant Pathogenicity
              </a>
            </li>
            @if ($display_tabs['active'] == "gene")
            <li class="nav-item active  ">
              <a class="nav-link" href="{{ route('gene-index') }}">
                Gene
              </a>
            </li>
            @endif
            @if ($display_tabs['active'] == "disease")
            <li class="nav-item active  ">
              <a class="nav-link" href="{{ route('disease-index') }}">
                Gene
              </a>
            </li>
            @endif
             --}}
            <li role="presentation" class="nav-item dropdown @if (($display_tabs['active'] == "gene") ||  ($display_tabs['active'] == "affiliate") ||  ($display_tabs['active'] == "drug") || ($display_tabs['active'] == "condition")) active @endif">
                <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i> More
                </a>
                <ul class="dropdown-menu">
                  {{-- <li><a href="#">Genomic Browser</a></li> --}}
                  <li><a class="@if ($display_tabs['active'] == "affiliate") font-weight-bold @endif" href="{{ route('affiliate-index') }}">Curations by ClinGen Expert Panels</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a class="@if ($display_tabs['active'] == "gene") font-weight-bold @endif" href="{{ route('gene-index') }}">All Genes</a></li>
                  <li><a class="@if ($display_tabs['active'] == "disease") font-weight-bold @endif" href="{{ route('condition-index') }}">All Disease</a></li>
                  <li><a class="@if ($display_tabs['active'] == "drug") font-weight-bold @endif" href="{{ route('drug-index') }}">All Drugs & Medications</a></li>
                  {{-- <li role="separator" class="divider"></li>
                  <li><a href="#">APIs and Downloads</a></li> --}}
                </ul>
              </li>

            {{--<li role="presentation" class="nav-item dropdown pull-right">
                <a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-cog"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a href="#">Coming soon...</a></li>
                </ul>
              </li>--}}
            <li role="presentation" class="nav-item dropdown pull-right">
                <a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-share-square"></i>
                </a>
                <ul class="dropdown-menu pull-right">
                  <li><a href="#"><i class="fas fa-envelope-open"></i> Email this page...</a></li>
                  <li><a href="#"><i class="fab fa-twitter"></i> Tweet this page...</a></li>
                  <li><a href="#"><i class="fas fa-quote-left"></i> How to cite...</a></li>
                </ul>
              </li>
            {{--<li class="nav-item  pull-right ">
              <a class="nav-link" href="#">
                <i class="fas fa-download"></i>
              </a>
            </li>--}}

            {{--<li class="nav-item  pull-right ">
              <a class="nav-link" href="#">
                <i class="fas fa-print"></i>
              </a>
            </li>--}}
          </ul>
          @endisset
          </div>
        </section>
        <section id='section_content' class="container">
          @if (session('status'))
          <div class="row">
            <div class="col-12">
              <div class="alert alert-success" role="alert">
                  {{ session('status') }}
              </div>
            </div>
          </div>
          @endif
          <div class="row">
            @yield('content')
          </div>
        </section>
      </main>

      @include('_partials._wrapper.footer',['navActive' => "summary"])


      <div class="">

      </div>
  <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


    @yield('script_js')
    <script>

    </script>
    @livewireScripts
  </body>
  </html>

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
            <div id="section_search_wrapper" class="mt-4 mb-3 input-group input-group-xl">

	         <span class="input-group-addon" id=""><i class="fas fa-search"></i></span>
	         <div class="input-group-btn">
	           <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span class='typeQueryLabel'>Gene</span></button>
	           <ul class="dropdown-menu dropdown-menu-left">
	             <li><a class="typeQueryGene pointer">Gene Symbol</a></li>
	             <li><a class="typeQueryDisease pointer">Disease Name</a></li>
	             <li><a class="typeQueryDrug pointer">Drug Name</a></li>
	             {{-- <li><a href="#">HGVS Expression</a></li> --}}
	             {{-- <li><a href="#">Genomic Coordinates</a></li> --}}
	             {{-- <li><a href="#">CAid (Variant)</a></li> --}}
               <li role="separator" class="divider"></li>
               <li><a class="" target="allele_reg" href="http://reg.clinicalgenome.org">Variant <i class="fas fa-external-link-alt mt-1 text-muted"></i> </a></li>
	             <li><a href="https://clinicalgenome.org/search/"> Website Content <i class="fas fa-external-link-alt mt-1 text-muted"></i></a></li>
	           </ul>
           </div><!-- /btn-group -->
           <span class="inputQueryGene">
            <input type="text" class="form-control queryGene " aria-label="..." value="" placeholder="Start typing a gene symbol...">
           </span>
           <span class="inputQueryDisease" style="display: none">
            <input type="text" class="form-control  queryDisease" aria-label="..." value="" placeholder="Start typing a disease..." >
           </span>
           <span class="inputQueryDrug" style="display: none">
           <input type="text" class="form-control queryDrug" aria-label="..." value="" placeholder="Start typing a drug...">
           </span>
	         <span class="input-group-btn">
	                 <button class="btn btn-default btn-search-submit" type="button"> Search</button>
	               </span>
         </div><!-- /input-group -->
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
            {{-- <li role="presentation" class="nav-item dropdown pull-right">
                <a class="nav-link dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  <i class="fas fa-share-square"></i>
                </a>
                <ul class="dropdown-menu pull-right">
                  <li><a href="#"><i class="fas fa-envelope-open"></i> Email this page...</a></li>
                  <li><a href="#"><i class="fab fa-twitter"></i> Tweet this page...</a></li>
                  <li><a href="#"><i class="fas fa-quote-left"></i> How to cite...</a></li>
                </ul>
              </li> --}}
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
  var mybutton = document.getElementById("clingen_top");

  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      mybutton.style.display = "block";
    } else {
      mybutton.style.display = "none";
    }
  }

  // When the user clicks on the button, scroll to the top of the document
  function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
  }
</script>

    </script>
    <script src="/js/typeahead.js"></script>
    <script>
      $( ".typeQueryGene" ).click(function() {
        $( ".inputQueryGene" ).show();
        $( ".inputQueryGene .queryGene" ).show();
        $( ".inputQueryDisease" ).hide();
        $( ".inputQueryDisease .queryDisease" ).hide();
        $( ".inputQueryDrug" ).hide();
        $( ".inputQueryDrug .queryDrug" ).hide();
        $( ".typeQueryLabel").text("Gene");
      });
      $( ".typeQueryDisease" ).click(function() {
        $( ".inputQueryGene" ).hide();
        $( ".inputQueryGene .queryGene" ).hide();
        $( ".inputQueryDisease" ).show();
        $( ".inputQueryDisease .queryDisease" ).show();
        $( ".inputQueryDrug" ).hide();
        $( ".inputQueryDrug .queryDrug" ).hide();
        $( ".typeQueryLabel").text("Disease");
      });
      $( ".typeQueryDrug" ).click(function() {
        $( ".inputQueryGene" ).hide();
        $( ".inputQueryGene .queryGene" ).hide();
        $( ".inputQueryDisease" ).hide();
        $( ".inputQueryDisease .queryDisease" ).hide();
        $( ".inputQueryDrug" ).show();
        $( ".inputQueryDrug .queryDrug" ).show();
        $( ".typeQueryLabel").text("Drug");
      });


      /*var term = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: 'https://search.clinicalgenome.org/kb/home.json?term=%QUERY',
          wildcard: '%QUERY'
        }
      });*/

      var term = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/genes/look/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      var termGene = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/genes/look/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      var termDisease = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/conditions/look/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      var termDrug = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('label'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
          url: '{{  url('api/drugs/look/%QUERY') }}',
          wildcard: '%QUERY'
        }
      });

      $('.queryDisease').typeahead(null,
      {
        name: 'termDisease',
        display: 'label',
        source: termDisease,

        limit: 20,
        minLength: 3,
        highlight: true,
        hint: false,
        autoselect:true,
      }).bind('typeahead:selected',function(evt,item){
        window.location = item.url;
      });

      $('.queryGene').typeahead(null,
      {
        name: 'termGene',
        display: 'label',
        source: termGene,

        limit: 20,
        minLength: 3,
        highlight: true,
        hint: false,
        autoselect:true,
      }).bind('typeahead:selected',function(evt,item){
        window.location = item.url;
      });

      $('.queryDrug').typeahead(null,
      {
        name: 'termDrug',
        display: 'label',
        source: termDrug,

        limit: 20,
        minLength: 3,
        highlight: true,
        hint: false,
        autoselect:true,
      }).bind('typeahead:selected',function(evt,item){
        window.location = item.url;
      });

    </script>
    @livewireScripts
  </body>
  </html>

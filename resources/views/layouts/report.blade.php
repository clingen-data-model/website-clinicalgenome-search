<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>ClinGen Report</title>

  <!-- Scripts -->

  <script src="{{ asset('js/js.cookie.min.js') }}"></script>
  <script src="{{ asset('js/app.js') }}"></script>

  <!-- Fonts -->

  <!-- Styles -->
  @yield('script_css')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-49947422-1', 'auto');
        ga('set', 'dimension7', 'KB Curations - Index');  
        //Page type
        ga('send', 'pageview');
    </script>

</head>

<body>
  <div id="app">
    @include('_partials._wrapper.header',['navActive' => "summary"])

    <main id='section_main' role="main">
        <section id='section_heading' class="pt-0 pb-0 mb-0 section-heading section-heading-groups text-light">
            <div  class="container">
            
                @hasSection ('heading')
                    @yield('heading')
                @else
                    <div class="mb-3"></div>
                @endif
         
            </div>
        </section>
        
        @hasSection ('content-heading')
            <section id='section_content_heading' class="pt-0 pb-0 mb-2 section-heading section-content-heading-groups">
            <div  class="container">
                @yield('content-heading')
            </div>
            </section>
        @endif

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


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->


    <script src="/js/jquery.validate.min.js" ></script>
    <script src="/js/additional-methods.min.js" ></script>

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
  
    <script type="text/javascript">
      
      // Tracking for google of the onclicks
      $( ".externallink" ).on( "click", function() {
          var title = $(this).attr("title");
          //console.log( "externallink" );
          //console.log( title );
          ga('send', 'event', 'external_link', 'click', title );
      });

      $( ".externalresource" ).on( "click", function() {
          var title = $(this).attr("title");
          //console.log( "externallink" );
          //console.log( title );
          ga('send', 'event', 'external_resource', 'click', title );
      });
      $( ".watchreportclick" ).on( "click", function() {
          var title = $(this).attr("title");
          //console.log( "watchreportclick" );
          //console.log( title );
          ga('send', 'event', 'track_report_click', 'click', title );
      });
      $( ".watchdownloadclick" ).on( "click", function() {
          var title = $(this).attr("title");
          //console.log( "watchdownloadclick" );
          //console.log( title );
          ga('send', 'event', 'track_download_click', 'click', title );
      });
      
    </script>

</body>
</html>

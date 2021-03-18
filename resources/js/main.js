// switch between login and register screens
$('.action-login-register').on('click', function(){
    $('#modalLogin').modal('hide');
    $('#modalRegister').modal('show');
});

// switch between register and login screens
$('.action-register-login').on('click', function(){
    $('#modalRegister').modal('hide');
    $('#modalLogin').modal('show');
});

// switch between login and register screens
$('.action-login-forgot').on('click', function(){
    $('#modalLogin').modal('hide');
    $('#modalForgot').modal('show');
});

// switch between register and login screens
$('.action-forgot-login').on('click', function(){
    $('#modalForgot').modal('hide');
    $('#modalLogin').modal('show');
});

$('.action-logout-now').on('click', function(){
    $('#logout-form').submit();
});

// log out 
$( '#logout-form' ).validate( {
    submitHandler: function(form) {

        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true,
            headers:{
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN' : window.token,
                'Authorization':'Bearer ' + Cookies.get('laravel_token')
            }
        });
      
        var url = "/api/logout";
        
        var formData = $(form).serialize();

        $.post(url, formData, function(response)
        {
            Cookies.remove('laravel_token');

            // clear user and revert to login menu
            $('#nav-user-name').html('Member');
            $('#dashboard-menu').hide();
            $('#login-menu').show();
            $('#curated-filter-dashboard').trigger('logout');

            swal({
                title: "You have logged out!",
                text: "",
                type: "success",
                timer: 2500,
                buttons: false
                });
                
                    /*$('.action-login').html('Login').attr('href', '#')
                                .on('click', function() {
                                $('#modalLogin').modal('show');
                                });
                                auth = 0;*/
            window.auth = 0;
            // some pages require a complete reload, so send event
            $('#dashboard-logout').trigger('login');
        }).fail(function(response)
        {
            alert("Error Logging Out");
        });
    }
});

$( '#login-form' ).validate( {
    submitHandler: function(form) {
      
        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true
        });
        
        var url = "/api/login";
        
        var formData = $(form).serialize();

        $.post(url, formData, function(response)
        {
            Cookies.set('laravel_token', response.access_token);

            $('#modalLogin').modal('hide');

            swal({
                title: "You are now logged in!",
                text: "",
                type: "success",
                timer: 2500,
                buttons: false
                });
            
            // initialize user and add dashboard menu
            $('#nav-user-name').html(response.user);
            $('#login-menu').hide();
            $('#dashboard-menu').show();

            //$('.action-login').html('Dashboard').attr('href', '/dashboard').off();

            // we allow login to equate to conformation of an action, so check if there is anything we need to do
            if (response.context)
            {
                var color = $('.stats-banner').find('.fa-star').css('color');

                    if (typeof color !== 'undefined' && color == "rgb(211, 211, 211)")
                { 
                $('.stats-banner').find('.fa-star').css('color', 'green');
                }

                $('#follow-gene-id').collapse("hide");
            }

            // some pages require a complete reload, so send event
            $('#dashboard-logout').trigger('logout');
            $('#curated-filter-dashboard').trigger('login');

            window.auth = 1;
      }).fail(function(response)
      {
        //handle failed validation
        alert("Error Logging in");
      });

    },
    rules: {
      email: {
        required: true,
        email: true,
        maxlength: 80
      }
    },
    messages: {
      email:  {
        required: "Please enter your email address",
        email: "Please enter a valid email address",
        maxlength: "Section names must be less than 80 characters"
      },	
    },
    errorElement: 'em',
    errorClass: 'invalid-feedback',
    errorPlacement: function ( error, element ) {
      // Add the `help-block` class to the error element
      error.addClass( "invalid-feedback" );

      if ( element.prop( "type" ) === "checkbox" ) {
        error.insertAfter( element.parent( "label" ) );
      } else {
        error.insertAfter( element );
      }
    },
    highlight: function ( element, errorClass, validClass ) {
      $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
    },
    unhighlight: function (element, errorClass, validClass) {
      $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
    }
});


$( '#forgot-form' ).validate( {
    submitHandler: function(form) {
      
        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true
        });
        
        var url = "/api/forgot";
        
        var formData = $(form).serialize();

        $.post(url, formData, function(response)
        {
            Cookies.set('laravel_token', response.access_token);

            $('#modalForgot').modal('hide');

            swal({
                title: "Password Reset Link Sent!",
                text: "",
                type: "success",
                timer: 2500,
                buttons: false
                });
            
      }).fail(function(response)
      {
        //handle failed validation
        alert("Error sending link");
      });

    },
    rules: {
      email: {
        required: true,
        email: true,
        maxlength: 80
      }
    },
    messages: {
      email:  {
        required: "Please enter your email address",
        email: "Please enter a valid email address",
        maxlength: "Section names must be less than 80 characters"
      },	
    },
    errorElement: 'em',
    errorClass: 'invalid-feedback',
    errorPlacement: function ( error, element ) {
      // Add the `help-block` class to the error element
      error.addClass( "invalid-feedback" );

      if ( element.prop( "type" ) === "checkbox" ) {
        error.insertAfter( element.parent( "label" ) );
      } else {
        error.insertAfter( element );
      }
    },
    highlight: function ( element, errorClass, validClass ) {
      $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
    },
    unhighlight: function (element, errorClass, validClass) {
      $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
    }
});


$( '#register-form' ).validate( {
    submitHandler: function(form) {
      
        $.ajaxSetup({
            cache: true,
            contentType: "application/x-www-form-urlencoded",
            processData: true
        });
        
        var url = "/api/register";
        
        var formData = $(form).serialize();

        $.post(url, formData, function(response)
        {
            Cookies.set('laravel_token', response.access_token);

            $('#modalRegister').modal('hide');

            swal({
                title: "You are now logged in!",
                text: "",
                type: "success",
                timer: 2500,
                buttons: false                });
                
            // initialize user and add dashboard menu
            $('#nav-user-name').html(response.user);
            $('#login-menu').hide();
            $('#dashboard-menu').show();

            //$('.action-login').html('Dashboard').attr('href', '/dashboard').off();

            // we allow login to equate to conformation of an action, so check if there is anything we need to do
            if (response.context)
            {
                var color = $('.stats-banner').find('.fa-star').css('color');

                    if (typeof color !== 'undefined' && color == "rgb(211, 211, 211)")
                { 
                $('.stats-banner').find('.fa-star').css('color', 'green');
                }

                $('#follow-gene-id').collapse("hide");
            }

            // some pages require a complete reload, so send event
            $('#dashboard-logout').trigger('logout');
            window.auth = 1;

        }).fail(function(response)
        {
            //handle failed validation
            alert("Error Registering");
        });

    },
    rules: {
        email: {
            required: true,
            email: true,
            maxlength: 80
        }
    },
    messages: {
        email:  {
            required: "Please enter your email address",
            email: "Please enter a valid email address",
            maxlength: "Section names must be less than 80 characters"
        },	
    },
    errorElement: 'em',
    errorClass: 'invalid-feedback',
    errorPlacement: function ( error, element ) {
        // Add the `help-block` class to the error element
        error.addClass( "invalid-feedback" );

        if ( element.prop( "type" ) === "checkbox" ) {
            error.insertAfter( element.parent( "label" ) );
        } else {
            error.insertAfter( element );
        }
    },
    highlight: function ( element, errorClass, validClass ) {
        $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
    },
    unhighlight: function (element, errorClass, validClass) {
        $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
    }
});

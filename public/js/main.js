/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/main.js":
/*!******************************!*\
  !*** ./resources/js/main.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

// switch between login and register screens
$('.action-login-register').on('click', function () {
  $('#modalLogin').modal('hide');
  $('#register-form')[0].reset();
  $('#modalRegister').modal('show');
}); // switch between register and login screens

$('.action-register-login').on('click', function () {
  $('#modalRegister').modal('hide');
  $('#login-form')[0].reset();
  $('#modalLogin').modal('show');
}); // switch between login and register screens

$('.action-login-forgot').on('click', function () {
  $('#modalLogin').modal('hide');
  $('#forgot-form')[0].reset();
  $('#modalForgot').modal('show');
}); // switch between register and login screens

$('.action-forgot-login').on('click', function () {
  $('#modalForgot').modal('hide');
  $('#login-form')[0].reset();
  $('#modalLogin').modal('show');
});
$('.action-logout-now').on('click', function () {
  $('#logout-form').submit();
}); // log out

$('#logout-form').validate({
  submitHandler: function submitHandler(form) {
    $.ajaxSetup({
      cache: true,
      contentType: "application/x-www-form-urlencoded",
      processData: true,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': window.token,
        'Authorization': 'Bearer ' + Cookies.get('clingen_dash_token')
      }
    });
    var url = "/api/logout";
    var formData = $(form).serialize();
    $.post(url, formData, function (response) {
      Cookies.remove('clingen_dash_token'); // clear user and revert to login menu

      $('#nav-user-name').html('Member');
      $('#dashboard-menu').hide();
      $('#login-menu').show();
      $('#curated-filter-dashboard').trigger('logout');
      swal({
        title: "You have logged out!",
        text: " ",
        timer: 2500,
        className: "swal-success",
        buttons: false
      });
      /*$('.action-login').html('Login').attr('href', '#')
                  .on('click', function() {
                  $('#modalLogin').modal('show');
                  });
                  auth = 0;*/

      window.auth = 0; // some pages require a complete reload, so send event

      $('#dashboard-logout').trigger('login');
    }).fail(function (response) {
      alert("Error Logging Out");
    });
  }
});
$('#login-form').validate({
  submitHandler: function submitHandler(form) {
    $.ajaxSetup({
      cache: true,
      contentType: "application/x-www-form-urlencoded",
      processData: true
    });
    var url = "/api/login";
    var formData = $(form).serialize();
    $.post(url, formData, function (response) {
      if (response.expires_at == 0) Cookies.set('clingen_dash_token', response.access_token);else Cookies.set('clingen_dash_token', response.access_token, {
        expires: response.expires_at
      });
      $('#modalLogin').modal('hide');
      swal({
        title: "You are now logged in!",
        text: " ",
        timer: 2500,
        className: "swal-success",
        buttons: false
      }); // initialize user and add dashboard menu

      $('#nav-user-name').html(response.user);
      $('#login-menu').hide();
      $('#dashboard-menu').show(); //$('.action-login').html('Dashboard').attr('href', '/dashboard').off();
      // we allow login to equate to conformation of an action, so check if there is anything we need to do

      if (response.context) {
        var color = $('.stats-banner').find('.fa-star').css('color');

        if (typeof color !== 'undefined' && color == "rgb(211, 211, 211)") {
          $('.stats-banner').find('.fa-star').css('color', 'green');
        }

        $('#follow-gene-id').collapse("hide");
      } // some pages require a complete reload, so send event


      $('#dashboard-logout').trigger('logout');
      $('#curated-filter-dashboard').trigger('login');
      $('#preferences-menu').trigger('login');
      window.auth = 1;
    }).fail(function (response) {
      swal({
        title: "Error",
        text: response.responseJSON.message,
        className: "swal-error",
        dangerMode: true
      });
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
    email: {
      required: "Please enter your email address",
      email: "Please enter a valid email address",
      maxlength: "Section names must be less than 80 characters"
    }
  },
  errorElement: 'em',
  errorClass: 'invalid-feedback',
  errorPlacement: function errorPlacement(error, element) {
    // Add the `help-block` class to the error element
    error.addClass("invalid-feedback");

    if (element.prop("type") === "checkbox") {
      error.insertAfter(element.parent("label"));
    } else {
      error.insertAfter(element);
    }
  },
  highlight: function highlight(element, errorClass, validClass) {
    $(element).addClass("is-invalid").removeClass("is-valid");
  },
  unhighlight: function unhighlight(element, errorClass, validClass) {
    $(element).addClass("is-valid").removeClass("is-invalid");
  }
});
$('#forgot-form').validate({
  submitHandler: function submitHandler(form) {
    $.ajaxSetup({
      cache: true,
      contentType: "application/x-www-form-urlencoded",
      processData: true
    });
    var url = "/api/forgot";
    var formData = $(form).serialize();
    $.post(url, formData, function (response) {
      Cookies.set('clingen_dash_token', response.access_token);
      $('#modalForgot').modal('hide');
      swal({
        title: "Password Reset Link Sent!",
        text: " ",
        timer: 2500,
        className: "swal-success",
        buttons: false
      });
    }).fail(function (response) {
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
    email: {
      required: "Please enter your email address",
      email: "Please enter a valid email address",
      maxlength: "Section names must be less than 80 characters"
    }
  },
  errorElement: 'em',
  errorClass: 'invalid-feedback',
  errorPlacement: function errorPlacement(error, element) {
    // Add the `help-block` class to the error element
    error.addClass("invalid-feedback");

    if (element.prop("type") === "checkbox") {
      error.insertAfter(element.parent("label"));
    } else {
      error.insertAfter(element);
    }
  },
  highlight: function highlight(element, errorClass, validClass) {
    $(element).addClass("is-invalid").removeClass("is-valid");
  },
  unhighlight: function unhighlight(element, errorClass, validClass) {
    $(element).addClass("is-valid").removeClass("is-invalid");
  }
});
$('#register-form').validate({
  submitHandler: function submitHandler(form) {
    $.ajaxSetup({
      cache: true,
      contentType: "application/x-www-form-urlencoded",
      processData: true
    });
    var url = "/api/register";
    var formData = $(form).serialize();
    $.post(url, formData, function (response) {
      Cookies.set('clingen_dash_token', response.access_token);
      $('#modalRegister').modal('hide');
      swal({
        title: "You have successfully registered!",
        text: "An confirmation email has been sent to your email address.  Please follow the directions to verify and complete the registration.",
        className: "swal-success"
      }); // initialize user and add dashboard menu
      //$('#nav-user-name').html(response.user);
      //$('#login-menu').hide();
      //$('#dashboard-menu').show();
      //$('.action-login').html('Dashboard').attr('href', '/dashboard').off();
      // we allow login to equate to conformation of an action, so check if there is anything we need to do

      if (response.context) {
        var color = $('.stats-banner').find('.fa-star').css('color');

        if (typeof color !== 'undefined' && color == "rgb(211, 211, 211)") {
          $('.stats-banner').find('.fa-star').css('color', 'green');
        }

        $('#follow-gene-id').collapse("hide");
      } // some pages require a complete reload, so send event
      //$('#dashboard-logout').trigger('logout');
      //window.auth = 1;

    }).fail(function (response) {
      var errors = response.responseJSON.errors;

      if (errors.hasOwnProperty('email')) {
        swal({
          title: "Error",
          text: "Email address is not available",
          className: "swal-error",
          dangerMode: true
        });
        return;
      }

      if (errors.hasOwnProperty('password')) {
        swal({
          title: "Error",
          text: errors.password[0],
          className: "swal-error",
          dangerMode: true
        });
        return;
      }
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
    email: {
      required: "Please enter your email address",
      email: "Please enter a valid email address",
      maxlength: "Section names must be less than 80 characters"
    }
  },
  errorElement: 'em',
  errorClass: 'invalid-feedback',
  errorPlacement: function errorPlacement(error, element) {
    // Add the `help-block` class to the error element
    error.addClass("invalid-feedback");

    if (element.prop("type") === "checkbox") {
      error.insertAfter(element.parent("label"));
    } else {
      error.insertAfter(element);
    }
  },
  highlight: function highlight(element, errorClass, validClass) {
    $(element).addClass("is-invalid").removeClass("is-valid");
  },
  unhighlight: function unhighlight(element, errorClass, validClass) {
    $(element).addClass("is-valid").removeClass("is-invalid");
  }
});

/***/ }),

/***/ 1:
/*!************************************!*\
  !*** multi ./resources/js/main.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/pweller/Projects/website-clinicalgenome-search/resources/js/main.js */"./resources/js/main.js");


/***/ })

/******/ });
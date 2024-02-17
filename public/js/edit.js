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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/edit.js":
/*!******************************!*\
  !*** ./resources/js/edit.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
/*
**	Update server when field value changes anywhere in the tab
*/
$('#modalSettings').on('change', '.api-update', function (e) {
  //var id = $(this).attr('data-uuid');
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
  var url = "/api/profile";
  var eles = new Object();
  eles._token = window.token; //eles.arg = id;

  eles.name = $(this).attr('name');
  if ($(this).attr('type') == "checkbox") eles.value = $(this).is(":checked") ? "1" : "0";else eles.value = $(this).val();
  var save = $(this).attr('value');
  var savethis = $(this);
  $.post(url, eles, function (response) {
    // console.log(response.field);
    // update display
    if (response.field == 'credentials') {
      $('#profile-credentials').html(response.value);
    } else if (response.field == 'name') {
      $('#profile-name').html(response.value);
    } else if (response.field == 'actionability_interest') {
      if (response.value == "1") $('#profile-interest-actionability').show();else $('#profile-interest-actionability').hide();
    } else if (response.field == 'dosage_interest') {
      if (response.value == "1") $('#profile-interest-dosage').show();else $('#profile-interest-dosage').hide();
    } else if (response.field == 'validity_interest') {
      if (response.value == "1") $('#profile-interest-validity').show();else $('#profile-interest-validity').hide();
    } else if (response.field == 'validity_notify') {
      var url = "/api/home/follow/reload"; //submits to the form's action URL

      $.get(url, function (response) {
        //console.log(response.data);
        $('#follow-table').bootstrapTable('load', response.data);
        $('#follow-table').bootstrapTable("resetSearch", "");
      }).fail(function (response) {
        alert("Error reloading table");
      });
    } else if (response.field == 'dosage_notify') {
      var url = "/api/home/follow/reload"; //submits to the form's action URL

      $.get(url, function (response) {
        //console.log(response.data);
        $('#follow-table').bootstrapTable('load', response.data);
        $('#follow-table').bootstrapTable("resetSearch", "");
      }).fail(function (response) {
        alert("Error reloading table");
      });
    } else if (response.field == 'actionability_notify') {
      var url = "/api/home/follow/reload"; //submits to the form's action URL

      $.get(url, function (response) {
        //console.log(response.data);
        $('#follow-table').bootstrapTable('load', response.data);
        $('#follow-table').bootstrapTable("resetSearch", "");
      }).fail(function (response) {
        alert("Error reloading table");
      });
    }
  }).fail(function (response) {
    savethis.val(savethis.attr('value')); //handle failed validation

    swal({
      title: "Error!",
      text: "An error was encountered communicating with the server",
      icon: "warning",
      button: "OK"
    });
  });
});
/*
/*
**	Update server when field value changes anywhere in the tab
*/

$('#modalFollowEp').on('change', '.api-update', function (e) {
  //var id = $(this).attr('data-uuid');
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
  var url = "/api/profile";
  var eles = new Object();
  eles._token = window.token; //eles.arg = id;

  eles.name = $(this).attr('name');

  if ($(this).attr('type') == "checkbox") {
    eles.value = $(this).is(":checked") ? "1" : "0";
    eles.ident = $(this).val();
  } else eles.value = $(this).val();

  var save = $(this).attr('value');
  var savethis = $(this);
  $.post(url, eles, function (response) {
    // console.log(response.field);
    // update display
    if (response.field == 'select[]') {
      var url = "/api/home/follow/reload"; //submits to the form's action URL

      $.get(url, function (response) {
        //console.log(response.data);
        $('#follow-table').bootstrapTable('load', response.data);
        $('#follow-table').bootstrapTable("resetSearch", "");
      }).fail(function (response) {
        alert("Error reloading table");
      });
    }
  }).fail(function (response) {
    savethis.val(savethis.attr('value')); //handle failed validation

    swal({
      title: "Error!",
      text: "An error was encountered communicating with the server",
      icon: "warning",
      button: "OK"
    });
  });
});

/***/ }),

/***/ 2:
/*!************************************!*\
  !*** multi ./resources/js/edit.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/pweller/Projects/website-clinicalgenome-search/resources/js/edit.js */"./resources/js/edit.js");


/***/ })

/******/ });
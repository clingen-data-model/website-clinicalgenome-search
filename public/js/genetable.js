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

/***/ "./resources/js/genetable.js":
/*!***********************************!*\
  !*** ./resources/js/genetable.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

/*
**      Functions and handlers for managing bootstrap table
**
**
*/
var $table = $('#table');
var selections = [];
console.log($table);

function responseHandler(res) {
  $.each(res.rows, function (i, row) {
    row.state = $.inArray(row.id, selections) !== -1;
  });
  return res;
}

function detailFormatter(index, row) {
  var html = [];
  $.each(row, function (key, value) {
    html.push('<p><b>' + key + ':</b> ' + value + '</p>');
  });
  return html.join('');
}

function symbolFormatter(index, row) {
  var html = '<a href="/genes/' + row.hgnc_id + '">' + row.symbol + '</a>';
  return html;
}

function badgeFormatter(index, row) {
  var html = '';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  return html;
}

function initTable() {
  $table.bootstrapTable('destroy').bootstrapTable({
    locale: 'en-US',
    columns: [{
      title: 'Gene Symbol',
      field: 'symbol',
      formatter: symbolFormatter,
      sortable: true
    }, {
      title: 'HGNC ID',
      field: 'hgnc_id'
    }, {
      title: 'Gene Name',
      field: 'name'
    }, {
      title: 'Curations',
      field: 'curations',
      align: 'center',
      formatter: badgeFormatter
    }, {
      field: 'date',
      title: 'Last Curation Date',
      align: 'right'
    }]
  });
  $table.on('all.bs.table', function (e, name, args) {
    console.log(name, args);
  });
  $table.on('load-error.bs.table', function (e, name, args) {
    alert("load error");
  });
}

$(function () {
  console.log("Ready");
  initTable();
  var $search = $('.fixed-table-toolbar .search input');
  $search.attr('placeholder', 'Search in table'); //$search.css('border', '1px solid red');
});

/***/ }),

/***/ 1:
/*!*****************************************!*\
  !*** multi ./resources/js/genetable.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/scottg/SitesDev/website-clinicalgenome-search/resources/js/genetable.js */"./resources/js/genetable.js");


/***/ })

/******/ });
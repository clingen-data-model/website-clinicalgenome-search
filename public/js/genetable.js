<<<<<<< HEAD
/*
**      Common functions and handlers for managing bootstrap-table
**
**
*/

/**
 * Format the expanded detail section
 *
 * @param {} index
 * @param {*} row
 */
function detailFormatter(index, row) {
  var html = [];
  /*$.each(row, function (key, value) {
      html.push('<p><b>' + key + ':</b> ' + value + '</p>')
  })*/

  return false;
}
/**
 * Format the expanded detail section
 *
 * @param {} index
 * @param {*} row
 */


function reportDetailFormatter(index, row, element) {
  var html;
  $.ajaxSetup({
    cache: true,
    contentType: "application/x-www-form-urlencoded",
    processData: true,
    headers: {
      'X-Requested-With': 'XMLHttpRequest',
      'X-CSRF-TOKEN': window.token,
      'Authorization': 'Bearer ' + Cookies.get('laravel_token')
    }
  });
  $.ajax({
    url: "/api/home/rpex/" + row.ident,
    type: 'get',
    dataType: 'html',
    async: false,
    success: function success(data) {
      html = data;
    }
  });
  return html;
}
/**
 * Show the advanced filter toolbar button if the showadvanced
 * global is set.
 */


function table_buttons() {
  if (typeof showadvanced !== 'undefined' && showadvanced) return {
    btnUsersAdd: {
      text: 'Filters',
      icon: 'glyphicon-tasks',
      event: function event() {
        $('#modalFilter').modal('toggle');
      },
      attributes: {
        title: 'Advanced Filters'
      }
    }
  };else return {};
}
/**
 * For a symbol or region cell
 *
 * @param {*} index
 * @param {*} row
 */


function symbolFormatter(index, row) {
  if (row.type == 0 || row.type == 3) return '<a href="/kb/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';else return '<a href="/kb/gene-dosage/region/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
}

function typeFormatter(index, row) {
  if (row.type == 0) return {
    classes: 'global_table_cell gene'
  };else if (row.type == 3) return {
    classes: 'global_table_cell gene'
  };else return {
    classes: 'global_table_cell region'
  };
}

function nullFormatter(index, row) {
  if (row.type == 0) return '<span title="Gene">G</span>';else if (row.type == 3) return '<span title="Gene">P</span>';else return '<span title="Region">R</span>';
}

function geneFormatter(index, row) {
  return '<a href="/kb/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
}

function hgncFormatter(index, row) {
  if (row.type == 0 || row.type == 3) return '<a href="/kb/gene-dosage/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';else return '<a href="/kb/gene-dosage/region/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
}

function location01Formatter(index, row) {
  //if (row.type == 0)
  //   return row.location;
  if (row.location == null) return '';
  var name = row.location.trim(); // strip off chr

  if (name.toLowerCase().indexOf("chr") === 0) name = name.substring(3);
  var chr = name.indexOf(':');
  var pos = name.indexOf('-');
  /*var html = '<table><tr><td class="pr-1 text-22px text-right line-height-normal" rowspan="2">'
      + name.substring(0, chr)
      + '</td><td class="text-10px line-height-normal">'
      + name.substring(chr + 1, pos)
      + '</td></tr><tr><td class="text-10px line-height-normal">'
      + name.substring(pos + 1)
      + '</td></tr></table>';*/

  var html = '<div class="position">' + '<span aria-label="Chromosome" class="chr">' + name.substring(0, chr) + '</span>' + '<span aria-label=" at " class="sr-only">:</span>' + '<span class="start">' + name.substring(chr + 1, pos) + '</span>' + '<span aria-label=" to " class="sr-only">-</span>' + '<span class="end">' + name.substring(pos + 1) + '</span>' + '</div>';
  return html;
}

function locationFormatter(index, row) {
  //if (row.type == 0)
  //   return row.location;
  if (row.grch37 == null) return '';
  var name = row.grch37.trim(); // strip off chr

  if (name.toLowerCase().indexOf("chr") === 0) name = name.substring(3);
  var chr = name.indexOf(':');
  var pos = name.indexOf('-');
  /*var html = '<table><tr><td class="pr-1 text-22px text-right line-height-normal" rowspan="2">'
      + name.substring(0, chr)
      + '</td><td class="text-10px line-height-normal">'
      + name.substring(chr + 1, pos)
      + '</td></tr><tr><td class="text-10px line-height-normal">'
      + name.substring(pos + 1)
      + '</td></tr></table>';*/

  var html = '<div class="position">' + '<span aria-label="Chromosome" class="chr">' + name.substring(0, chr) + '</span>' + '<span aria-label=" at " class="sr-only">:</span>' + '<span class="start">' + name.substring(chr + 1, pos) + '</span>' + '<span aria-label=" to " class="sr-only">-</span>' + '<span class="end">' + name.substring(pos + 1) + '</span>' + '</div>';
  return html;
}

function location38Formatter(index, row) {
  //if (row.type == 0)
  //   return row.location;
  if (row.grch38 == null) return '';
  var name = row.grch38.trim(); // strip off chr

  if (name.toLowerCase().indexOf("chr") === 0) name = name.substring(3);
  var chr = name.indexOf(':');
  var pos = name.indexOf('-');
  /*var html = '<table><tr><td class="pr-1 text-22px text-right line-height-normal" rowspan="2">'
      + name.substring(0, chr)
      + '</td><td class="text-10px line-height-normal">'
      + name.substring(chr + 1, pos)
      + '</td></tr><tr><td class="text-10px line-height-normal">'
      + name.substring(pos + 1)
      + '</td></tr></table>';*/

  var html = '<div class="position">' + '<span aria-label="Chromosome" class="chr">' + name.substring(0, chr) + '</span>' + '<span aria-label=" at " class="sr-only">:</span>' + '<span class="start">' + name.substring(chr + 1, pos) + '</span>' + '<span aria-label=" to " class="sr-only">-</span>' + '<span class="end">' + name.substring(pos + 1) + '</span>' + '</div>';
  return html;
}

function regionFormatter(index, row) {
  var url = "/kb/gene-dosage/region/";
  return '<a href="' + url + row.key + '"><b>' + row.name + '</b></a>';
}
/*
function pliCellStyle(value, row, index) {
        if (row.pli > .50)
            return {
                classes: "format-pli-high"
            }
        else
            return {
                classes: "format-pli-low"
            }
}*/

/*function pliFormatter(index, row) {
    if (row.pli === null)
        return '-';
    else
        return row.pli;
}*/

/*
function hiCellStyle(value, row, index) {
        if (row.hi > .50)
            return {
                classes: "format-hi-high"
            }
        else
            return {
                classes: "format-hi-low"
            }
}*/

/*function hiFormatter(index, row) {
    if (row.hi === null)
        return '-';
    else
        return row.hi;
}*/


function pliFormatter(index, row) {
  if (row.pli === null) return '&hyphen;';
  if (row.pli >= .90) return '<span class="format-pli-high">' + row.pli + '</span>';else return '<span class="format-pli-low">' + row.pli + '</span>';
}

function hiFormatter(index, row) {
  if (row.hi === null) return '&hyphen;';
  if (row.hi <= 10) return '<span class="format-hi-high">' + row.hi + '</span>';else return '<span class="format-hi-low">' + row.hi + '</span>';
}

function plofFormatter(index, row) {
  if (row.plof === null) return '&hyphen;';
  if (row.plof <= .35) return '<span class="format-pli-high">' + row.plof + '</span>';else return '<span class="format-pli-low">' + row.plof + '</span>';
}

function haploFormatter(index, row) {
  if (row.haplo_assertion === false) return '';
  if (row.haplo_assertion == 'Not Yet Evaluated') return '<span class="text-muted">Not Yet Evaluated</span>'; //var html = row.haplo_assertion.replace(' (', '<br />(');

  var html = row.haplo_assertion;
  if (row.haplo_history === null) return html;
  return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.haplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';
}

function triploFormatter(index, row) {
  if (row.triplo_assertion === false) return '';
  if (row.triplo_assertion == 'Not Yet Evaluated') return '<span class="text-muted">Not Yet Evaluated</span>'; //var html = row.triplo_assertion.replace(' (', '<br />(');

  var html = row.triplo_assertion;
  if (row.triplo_history === null) return html;
  return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.triplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';
}

function omimFormatter(index, row) {
  if (row.omimlink) return '<a href="https://omim.org/entry/' + row.omimlink + '" > <span class="text-success"><i class="fas fa-check"></i></span></a>';else return '';
}

function morbidFormatter(index, row) {
  if (row.morbid == "Yes") return '<a href="https://omim.org/entry/' + row.omimlink + '" > <span class="text-success"><i class="fas fa-check"></i></span></a>';else return '';
}

function reportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  if (row.type == 0 || row.type == 3) {
    /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
        + report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';*/
    return '<a class="btn btn-xs btn-success btn-block btn-report" href="' + '/kb/gene-dosage/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
  } else {
    return '<a class="btn btn-xs btn-success btn-block btn-report" href="' + '/kb/gene-dosage/region/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
  }
}

function iscaFormatter(index, row) {
  if (row.type == 0 || row.type == 3) return '<a href="' + '/kb/gene-dosage/' + row.hgnc_id + '">' + row.isca + '</a>';else if (row.type == 1) return '<a href="' + '/kb/gene-dosage/region/' + row.isca + '">' + row.isca + '</a>';else return row.isca;
}
/**
 *
 * @param {*} index
 * @param {*} row
 */


function cellFormatter(index, row) {
  return {
    classes: 'global_table_cell'
  };
}
/**
 * The global header style set in the genetable partial.  If the lightstyle
 * global is set, leave the default style, else go dark.
 *
 * @param {*} column
 */


function headerStyle(column) {
  if (typeof lightstyle !== 'undefined' && lightstyle) return {};else return {
    classes: 'bg-secondary text-light header_class'
  };
}

function affiliateFormatter(index, row) {
  var html = '<a href="/kb/affiliate/' + row.agent + '">' + row.label + '</a>';
  return html;
}

function badgeFormatter(index, row) {
  var html = '';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_variant) html += '<img class="" src="/images/variantPathogenicity-on.png" style="width:30px">';else html += '<img class="" src="/images/variantPathogenicity-off.png" style="width:30px">';
  if (row.has_pharma) html += '<img class="" src="/images/Pharmacogenomics-on.png" style="width:30px">';else html += '<img class="" src="/images/Pharmacogenomics-off.png" style="width:30px">';
  return html;
}

function ashgncFormatter(index, row) {
  return '<a href="/kb/genes/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
}

function asdiseaseFormatter(index, row) {
  return '<a href="/kb/conditions/' + row.mondo + '">' + row.disease + '</a>';
}

function asmondoFormatter(index, row) {
  return '<a href="/kb/conditions/' + row.mondo + '">' + row.mondo.replace('_', ':') + '</a>';
}

function asbadgeFormatter(index, row) {
  return '<a class="btn btn-default btn-block btn-classification" href="/kb/gene-validity/' + row.perm_id + '">' + '' + row.classification + '</a>';
}

function datebadgeFormatter(index, row) {
  return '<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/' + row.perm_id + '"><i class="glyphicon glyphicon-file"></i> ' + '' + row.released + '</a>';
}

function conditionFormatter(index, row) {
  // var html = '<a href="/kb/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>'
  //           + '<div class="small text-dark">' + row.curie + ' <span class="badge text-xs">Condition</span></div>';
  var html = '<a href="/kb/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>' + '<div class="small text-dark">' + row.curie + '</div>';
  if (row.synonym != null) html += '<div class="text-sm text-muted">' + row.synonym + '</div>';
  return html;
}

function cbadgeFormatter(index, row) {
  var html = '';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_variant) html += '<img class="" src="/images/variantPathogenicity-on.png" style="width:30px">';else html += '<img class="" src="/images/variantPathogenicity-off.png" style="width:30px">';
  if (row.has_pharma) html += '<img class="" src="/images/Pharmacogenomics-on.png" style="width:30px">';else html += '<img class="" src="/images/Pharmacogenomics-off.png" style="width:30px">';
  return html;
}

function drsymbolFormatter(index, row) {
  return '<a href="/kb/drugs/' + row.curie + '">' + row.curie + '</a>';
}

function drugFormatter(index, row) {
  return '<a href="/kb/drugs/' + row.curie + '">' + row.label + '</a>';
}

function drbadgeFormatter(index, row) {
  var html = '';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_variant) html += '<img class="" src="/images/variantPathogenicity-on.png" style="width:30px">';else html += '<img class="" src="/images/variantPathogenicity-off.png" style="width:30px">';
  if (row.has_pharma) html += '<img class="" src="/images/Pharmacogenomics-on.png" style="width:30px">';else html += '<img class="" src="/images/Pharmacogenomics-off.png" style="width:30px">';
  return html;
}

var terms = {
  "AD": "Autosomal Dominant",
  "AR": "Autosomal Recessive",
  "XL": "X-Linked",
  "XLR": "X-linked recessive",
  "MT": "Mitochondrial",
  "SD": "Semidominant",
  'Undetermined': 'Undetermined MOI'
};

function moiFormatter(index, row) {
  return '<span class="pointer" data-toggle="tooltip" data-placement="top" title="' + terms[row.moi] + '" ">' + row.moi + '</span>';
}

function hasvalidityFormatter(index, row) {
  if (row.has_validity == null) return '';
  return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
}

function hasPharmaFormatter(index, row) {
  if (row.has_pharma == null) return '';
  return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Curated</span></a>';
}

function hasVariantFormatter(index, row) {
  if (row.has_variant == null) return '';
  return '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&gene=' + row.symbol + '"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Approved VCEP</span></a>';
}

function hasactionabilityFormatter(index, row) {
  if (row.has_actionability == null) return '';
  return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Curated</span></a>';
}

function hasdosageFormatter(index, row) {
  if (row.has_dosage == null) return '';
  return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  /*if (row.has_dosage_haplo) {
      // return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
      //      + row.hgnc_id
      //     + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">'
      //     + row.has_dosage_haplo + '</span></a>';
      return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
          + row.hgnc_id
          + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  }
  if (row.has_dosage_triplo) {
      // return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
      //      + row.hgnc_id
      //     + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">'
      //     + row.has_dosage_haplo + '</span></a>';
      return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
          + row.hgnc_id
          + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  }
   return '';*/
}

function hashaploFormatter(index, row) {
  if (row.has_dosage_haplo) {
    // return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
    //      + row.hgnc_id
    //     + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">'
    //     + row.has_dosage_haplo + '</span></a>';
    return '<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  }

  return '';
}

function hastriploFormatter(index, row) {
  if (row.has_dosage_triplo) {
    // return '<a class="btn btn-success  btn-wrap btn-report btn-sm pb-0 pt-0" href="/kb/gene-dosage/'
    //      + row.hgnc_id
    //     + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">'
    //     + row.has_dosage_triplo + '</span></a>';
    return '<a class="btn btn-success  btn-wrap btn-report btn-sm pb-0 pt-0" href="/kb/gene-dosage/' + row.hgnc_id + '"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs"> Curated</span></a>';
  }

  return '';
}

function region_listener() {
  $('.fixed-table-toolbar').on('click', '.action-select-grch', function () {
    var uuid = $(this).attr('data-uuid');
    $('.action-select-text').html(uuid);
    $('#select-gchr').val(uuid);
  });
}

function cnvlocationFormatter(index, row) {
  var name = row.location.trim();
  if (name == null) return ''; // strip off chr

  if (name.toLowerCase().indexOf("chr") === 0) name = name.substring(3);
  var chr = name.indexOf(':');
  var pos = name.indexOf('-');
  /*var html = '<table><tr><td class="pr-1 text-22px text-right line-height-normal" rowspan="2">'
      + name.substring(0, chr)
      + '</td><td class="text-10px line-height-normal">'
      + name.substring(chr + 1, pos)
      + '</td></tr><tr><td class="text-10px  line-height-normal">'
      + name.substring(pos + 1)
      + '</td></tr></table>';*/

  var html = '<div class="position">' + '<span aria-label="Chromosome" class="chr">' + name.substring(0, chr) + '</span>' + '<span aria-label=" at " class="sr-only">:</span>' + '<span class="start">' + name.substring(chr + 1, pos) + '</span>' + '<span aria-label=" to " class="sr-only">-</span>' + '<span class="end">' + name.substring(pos + 1) + '</span>' + '</div>';
  return html;
}

var score_assertion_strings = {
  '0': 'No Evidence',
  '1': 'Little Evidence',
  '2': 'Emerging Evidence',
  '3': 'Sufficient Evidence',
  //'30': 'Gene Associated with Autosomal Recessive Phenotype',
  '30': 'Autosomal Recessive',
  '40': 'Dosage Sensitivity Unlikely',
  'Not yet evaluated': ''
};

function cnvhaploFormatter(index, row) {
  if (row.haplo_assertion === false) return '';
  /*if (row.haplo_assertion < 10)
      return score_assertion_strings[row.haplo_assertion] + ' for Haploinsufficiency';
  else
      return score_assertion_strings[row.haplo_assertion];*/

  if (row.haplo_assertion == "Not yet evaluated") {
    return '<span class="text-muted">Not Yet Evaluated</span>';
  } //return score_assertion_strings[row.haplo_assertion] + '<br />(' + row.haplo_assertion + ')';


  return row.haplo_assertion + ' (' + score_assertion_strings[row.haplo_assertion] + ')';
}

function cnvtriploFormatter(index, row) {
  if (row.triplo_assertion === false) return '';
  /*if (row.triplo_assertion < 10)
      return score_assertion_strings[row.triplo_assertion] + ' for Triplosensitivity';
  else
      return score_assertion_strings[row.triplo_assertion];*/

  if (row.triplo_assertion == "Not yet evaluated") {
    return '<span class="text-muted">Not Yet Evaluated</span>';
  } //return score_assertion_strings[row.triplo_assertion] + '<br />(' + row.triplo_assertion + ')';


  return row.triplo_assertion + ' (' + score_assertion_strings[row.triplo_assertion] + ')';
}

function cnvreportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  if (row.rawdate === "") return '<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/' + row.key + '"><i class="fas fa-file"></i>  Under Review</a>';
  return '<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/' + row.key + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
}

function acmsymbolFormatter(index, row) {
  var url = "/kb/gene-dosage/";
  return '<a href="' + url + row.hgnc_id + '"><b>' + row.gene + '</b></a>';
}

function acmomimFormatter(index, row) {
  var name = row.omimgene.substring(row.omimgene.lastIndexOf('/') + 1);
  return '<a href="' + row.omimgene + '">' + name + '</a>';
}

function acmomimsFormatter(index, row) {
  var html = '';
  var list = row.omims.split(',');
  var addcomma = false;
  list.forEach(function (item) {
    var trimmed = item.trim();
    if (addcomma) html += ', ';
    html += '<a href="https://omim.org/entry/' + trimmed + '">' + trimmed + '</a>';
    addcomma = true;
  });
  return html;
}

function acmpmidsFormatter(index, row) {
  var html = '';
  var list = row.pmids.split(',');
  var addcomma = false;
  list.forEach(function (item) {
    var trimmed = item.trim();
    if (addcomma) html += ', ';
    html += '<a href="https://ncbi.nlm.nih.gov/pubmed/' + trimmed + '">' + trimmed + '</a>';
    addcomma = true;
  });
  return html;
}

function acmhaploFormatter(index, row) {
  if (row.haplo_assertion === false) return '';
  /*if (row.haplo_assertion < 10)
      return score_assertion_strings[row.haplo_assertion] + ' for Haploinsufficiency';
  else
      return score_assertion_strings[row.haplo_assertion];*/
  //return score_assertion_strings[row.haplo_assertion] + '<br />(' + row.haplo_assertion + ')';

  return score_assertion_strings[row.haplo_assertion] + ' (' + row.haplo_assertion + ')';
}

function acmtriploFormatter(index, row) {
  if (row.triplo_assertion === false) return '';
  /*if (row.triplo_assertion < 10)
      return score_assertion_strings[row.triplo_assertion] + ' for Triplosensitivity';
  else
      return score_assertion_strings[row.triplo_assertion];*/
  //return score_assertion_strings[row.triplo_assertion] + '<br />(' + row.triplo_assertion + ')';

  return score_assertion_strings[row.triplo_assertion] + ' (' + row.triplo_assertion + ')';
}

function acmreportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  return '<a class="btn btn-block btn btn-default btn-xs" href="' + report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
}

function dsreportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  var bclass = row.workflow == "Awaiting Review" ? "default" : "success";
  var title = '';

  if (row.type == 3) {
    bclass = 'unreviewable';
    row.workflow = "Not Reviewable";
    title = "This gene will not be reviewed because it is a pseudogene";
  }

  if (row.type == 0 || row.type == 3) {
    /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
        + report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';*/
    if (row.hgnc_id == null) return '<a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/' + row.isca + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a>';else return '<a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a>';
  } else {
    return '<a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/region/' + row.isca + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a>';
  }
}

function dssymbolFormatter(index, row) {
  if (row.type == 0 || row.type == 3) {
    if (row.hgnc_id == null) return '<a href="/kb/genes/' + row.isca + '"><b>' + row.symbol + '</b></a>';else return '<a href="/kb/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
  } else return '<a href="/kb/gene-dosage/region/' + row.isca + '"><b>' + row.symbol + '</b></a>';
}

function relationFormatter(index, row) {
  var html = '';
  if (row.type == 0) html += '<div class="global_table_cell font-weight-bold gene" title="Gene">G</div>';else if (row.type == 1) html += '<div class="global_table_cell font-weight-bold region" title="Region">R</div>';else html += '<div class="global_table_cell font-weight-bold psuedogene" title="Psuedogene">P</div>';
  if (row.relationship === null) return html;
  var c = row.relationship.substring(0, 1);
  html += '<div class="global_table_cell font-weight-bold carryover mt-1 mb-1" title="' + row.relationship + '">' + c + '</div>';
  return html;
}

function locationSorter(one, two) {
  var oneloc = one.match(/\d+|X|Y/g);
  var twoloc = two.match(/\d+|X|Y/g); // deal with X or Y first

  if (oneloc[0] == 'X') oneloc[0] = 23;else if (oneloc[0] == 'Y') oneloc[0] = 24;else oneloc[0] = parseInt(oneloc[0]);
  if (twoloc[0] == 'X') twoloc[0] = 23;else if (twoloc[0] == 'Y') twoloc[0] = 24;else twoloc[0] = parseInt(twoloc[0]);
  if (oneloc[0] < twoloc[0]) return -1;else if (oneloc[0] > twoloc[0]) return 1;else {
    oneloc[1] = parseInt(oneloc[1]);
    twoloc[1] = parseInt(twoloc[1]);
    if (oneloc[1] < twoloc[1]) return -1;else if (oneloc[1] > twoloc[1]) return 1;else {
      oneloc[2] = parseInt(oneloc[2]);
      twoloc[2] = parseInt(twoloc[2]);
      return oneloc[2] < twoloc[2] ? -1 : 1;
    }
  }
  return 0;
}
=======
function detailFormatter(t,a){return!1}function table_buttons(){return"undefined"!=typeof showadvanced&&showadvanced?{btnUsersAdd:{text:"Filters",icon:"glyphicon-tasks",event:function(){$("#modalFilter").modal("toggle")},attributes:{title:"Advanced Filters"}}}:{}}function symbolFormatter(t,a){return 0==a.type||3==a.type?'<a href="/kb/genes/'+a.hgnc_id+'"><b>'+a.symbol+"</b></a>":'<a href="/kb/gene-dosage/region/'+a.hgnc_id+'"><b>'+a.symbol+"</b></a>"}function typeFormatter(t,a){return 0==a.type?{classes:"global_table_cell gene"}:3==a.type?{classes:"global_table_cell gene"}:{classes:"global_table_cell region"}}function nullFormatter(t,a){return 0==a.type?'<span title="Gene">G</span>':3==a.type?'<span title="Gene">P</span>':'<span title="Region">R</span>'}function geneFormatter(t,a){return'<a href="/kb/genes/'+a.hgnc_id+'"><b>'+a.symbol+"</b></a>"}function hgncFormatter(t,a){return 0==a.type||3==a.type?'<a href="/kb/gene-dosage/'+a.hgnc_id+'">'+a.hgnc_id+"</a>":'<a href="/kb/gene-dosage/region/'+a.hgnc_id+'">'+a.hgnc_id+"</a>"}function location01Formatter(t,a){if(null==a.location)return"";var s=a.location.trim();0===s.toLowerCase().indexOf("chr")&&(s=s.substring(3));var e=s.indexOf(":"),n=s.indexOf("-");return'<div class="position"><span aria-label="Chromosome" class="chr">'+s.substring(0,e)+'</span><span aria-label=" at " class="sr-only">:</span><span class="start">'+s.substring(e+1,n)+'</span><span aria-label=" to " class="sr-only">-</span><span class="end">'+s.substring(n+1)+"</span></div>"}function locationFormatter(t,a){if(null==a.grch37)return"";var s=a.grch37.trim();0===s.toLowerCase().indexOf("chr")&&(s=s.substring(3));var e=s.indexOf(":"),n=s.indexOf("-");return'<div class="position"><span aria-label="Chromosome" class="chr">'+s.substring(0,e)+'</span><span aria-label=" at " class="sr-only">:</span><span class="start">'+s.substring(e+1,n)+'</span><span aria-label=" to " class="sr-only">-</span><span class="end">'+s.substring(n+1)+"</span></div>"}function location38Formatter(t,a){if(null==a.grch38)return"";var s=a.grch38.trim();0===s.toLowerCase().indexOf("chr")&&(s=s.substring(3));var e=s.indexOf(":"),n=s.indexOf("-");return'<div class="position"><span aria-label="Chromosome" class="chr">'+s.substring(0,e)+'</span><span aria-label=" at " class="sr-only">:</span><span class="start">'+s.substring(e+1,n)+'</span><span aria-label=" to " class="sr-only">-</span><span class="end">'+s.substring(n+1)+"</span></div>"}function regionFormatter(t,a){return'<a href="/kb/gene-dosage/region/'+a.key+'"><b>'+a.name+"</b></a>"}function pliFormatter(t,a){return null===a.pli?"&hyphen;":a.pli>=.9?'<span class="format-pli-high">'+a.pli+"</span>":'<span class="format-pli-low">'+a.pli+"</span>"}function hiFormatter(t,a){return null===a.hi?"&hyphen;":a.hi<=10?'<span class="format-hi-high">'+a.hi+"</span>":'<span class="format-hi-low">'+a.hi+"</span>"}function plofFormatter(t,a){return null===a.plof?"&hyphen;":a.plof<=.35?'<span class="format-pli-high">'+a.plof+"</span>":'<span class="format-pli-low">'+a.plof+"</span>"}function haploFormatter(t,a){if(!1===a.haplo_assertion)return"";if("Not Yet Evaluated"==a.haplo_assertion)return'<span class="text-muted">Not Yet Evaluated</span>';var s=a.haplo_assertion;return null===a.haplo_history?s:'<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="'+a.haplo_history+'"><b>'+s+'</b>  <i class="fas fa-comment"></i></span>'}function triploFormatter(t,a){if(!1===a.triplo_assertion)return"";if("Not Yet Evaluated"==a.triplo_assertion)return'<span class="text-muted">Not Yet Evaluated</span>';var s=a.triplo_assertion;return null===a.triplo_history?s:'<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="'+a.triplo_history+'"><b>'+s+'</b>  <i class="fas fa-comment"></i></span>'}function omimFormatter(t,a){return a.omimlink?'<a href="https://omim.org/entry/'+a.omimlink+'" > <span class="text-success"><i class="fas fa-check"></i></span></a>':""}function morbidFormatter(t,a){return"Yes"==a.morbid?'<a href="https://omim.org/entry/'+a.omimlink+'" > <span class="text-success"><i class="fas fa-check"></i></span></a>':""}function reportFormatter(t,a){return 0==a.type||3==a.type?'<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/'+a.hgnc_id+'"><i class="fas fa-file"></i>   '+a.date+"</a>":'<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/'+a.hgnc_id+'"><i class="fas fa-file"></i>   '+a.date+"</a>"}function iscaFormatter(t,a){return 0==a.type||3==a.type?'<a href="/kb/gene-dosage/'+a.hgnc_id+'">'+a.isca+"</a>":1==a.type?'<a href="/kb/gene-dosage/region/'+a.isca+'">'+a.isca+"</a>":a.isca}function cellFormatter(t,a){return{classes:"global_table_cell"}}function headerStyle(t){return"undefined"!=typeof lightstyle&&lightstyle?{}:{classes:"bg-secondary text-light header_class"}}function affiliateFormatter(t,a){return'<a href="/kb/affiliate/'+a.agent+'">'+a.label+"</a>"}function badgeFormatter(t,a){var s="";return a.has_validity?s+='<img class="" src="/images/clinicalValidity-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalValidity-off.png" style="width:30px">',a.has_dosage?s+='<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">':s+='<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">',a.has_actionability?s+='<img class="" src="/images/clinicalActionability-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalActionability-off.png" style="width:30px">',s}function ashgncFormatter(t,a){return'<a href="/kb/genes/'+a.hgnc_id+'">'+a.hgnc_id+"</a>"}function asdiseaseFormatter(t,a){return'<a href="/kb/conditions/'+a.mondo+'">'+a.disease+"</a>"}function asmondoFormatter(t,a){return'<a href="/kb/conditions/'+a.mondo+'">'+a.mondo.replace("_",":")+"</a>"}function asbadgeFormatter(t,a){return'<a class="btn btn-default btn-block btn-classification" href="/kb/gene-validity/'+a.perm_id+'">'+a.classification+"</a>"}function datebadgeFormatter(t,a){return'<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-validity/'+a.perm_id+'"><i class="glyphicon glyphicon-file"></i> '+a.released+"</a>"}function conditionFormatter(t,a){var s='<a href="/kb/conditions/'+a.curie+'"><strong>'+a.label+'</strong></a><div class="small text-dark">'+a.curie+"</div>";return null!=a.synonym&&(s+='<div class="text-sm text-muted">'+a.synonym+"</div>"),s}function cbadgeFormatter(t,a){var s="";return a.has_validity?s+='<img class="" src="/images/clinicalValidity-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalValidity-off.png" style="width:30px">',a.has_dosage?s+='<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">':s+='<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">',a.has_actionability?s+='<img class="" src="/images/clinicalActionability-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalActionability-off.png" style="width:30px">',s}function drsymbolFormatter(t,a){return'<a href="/kb/drugs/'+a.curie+'">'+a.curie+"</a>"}function drugFormatter(t,a){return'<a href="/kb/drugs/'+a.curie+'">'+a.label+"</a>"}function drbadgeFormatter(t,a){var s="";return a.has_validity?s+='<img class="" src="/images/clinicalValidity-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalValidity-off.png" style="width:30px">',a.has_dosage?s+='<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">':s+='<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">',a.has_actionability?s+='<img class="" src="/images/clinicalActionability-on.png" style="width:30px">':s+='<img class="" src="/images/clinicalActionability-off.png" style="width:30px">',s}var terms={AD:"Autosomal Dominant",AR:"Autosomal Recessive",XL:"X-Linked",XLR:"X-linked recessive",MT:"Mitochondrial",SD:"Semidominant",Undetermined:"Undetermined MOI"};function moiFormatter(t,a){return'<span class="pointer" data-toggle="tooltip" data-placement="top" title="'+terms[a.moi]+'" ">'+a.moi+"</span>"}function hasvalidityFormatter(t,a){return null==a.has_validity?"":'<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>'}function hasPharmaFormatter(t,a){return null==a.has_pharma?"":'<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Curated</span></a>'}function hasVariantFormatter(t,a){return null==a.has_variant?"":'<a class="btn btn-success btn-sm pb-0 pt-0" href="https://erepo.clinicalgenome.org/evrepo/ui/classifications?matchMode=exact&gene='+a.symbol+'"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Approved VCEP</span></a>'}function hasactionabilityFormatter(t,a){return null==a.has_actionability?"":'<a class="btn btn-success btn-sm pb-0 pt-0" href="/kb/genes/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i>  <span class="hidden-sm hidden-xs">Curated</span></a>'}function hasdosageFormatter(t,a){return null==a.has_dosage?"":'<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/genes/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>'}function hashaploFormatter(t,a){return a.has_dosage_haplo?'<a class="btn btn-success  btn-wrap btn-sm pb-0 pt-0" href="/kb/gene-dosage/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs">Curated</span></a>':""}function hastriploFormatter(t,a){return a.has_dosage_triplo?'<a class="btn btn-success  btn-wrap btn-report btn-sm pb-0 pt-0" href="/kb/gene-dosage/'+a.hgnc_id+'"><i class="glyphicon glyphicon-file"></i> <span class="hidden-sm hidden-xs"> Curated</span></a>':""}function region_listener(){$(".fixed-table-toolbar").on("click",".action-select-grch",function(){var t=$(this).attr("data-uuid");$(".action-select-text").html(t),$("#select-gchr").val(t)})}function cnvlocationFormatter(t,a){var s=a.location.trim();if(null==s)return"";0===s.toLowerCase().indexOf("chr")&&(s=s.substring(3));var e=s.indexOf(":"),n=s.indexOf("-");return'<div class="position"><span aria-label="Chromosome" class="chr">'+s.substring(0,e)+'</span><span aria-label=" at " class="sr-only">:</span><span class="start">'+s.substring(e+1,n)+'</span><span aria-label=" to " class="sr-only">-</span><span class="end">'+s.substring(n+1)+"</span></div>"}var score_assertion_strings={0:"No Evidence",1:"Little Evidence",2:"Emerging Evidence",3:"Sufficient Evidence",30:"Autosomal Recessive",40:"Dosage Sensitivity Unlikely","Not yet evaluated":""};function cnvhaploFormatter(t,a){return!1===a.haplo_assertion?"":"Not yet evaluated"==a.haplo_assertion?'<span class="text-muted">Not Yet Evaluated</span>':a.haplo_assertion+" ("+score_assertion_strings[a.haplo_assertion]+")"}function cnvtriploFormatter(t,a){return!1===a.triplo_assertion?"":"Not yet evaluated"==a.triplo_assertion?'<span class="text-muted">Not Yet Evaluated</span>':a.triplo_assertion+" ("+score_assertion_strings[a.triplo_assertion]+")"}function cnvreportFormatter(t,a){return""===a.rawdate?'<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/'+a.key+'"><i class="fas fa-file"></i>  Under Review</a>':'<a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/'+a.key+'"><i class="fas fa-file"></i>   '+a.date+"</a>"}function acmsymbolFormatter(t,a){return'<a href="/kb/gene-dosage/'+a.hgnc_id+'"><b>'+a.gene+"</b></a>"}function acmomimFormatter(t,a){var s=a.omimgene.substring(a.omimgene.lastIndexOf("/")+1);return'<a href="'+a.omimgene+'">'+s+"</a>"}function acmomimsFormatter(t,a){var s="",e=a.omims.split(","),n=!1;return e.forEach(function(t){var a=t.trim();n&&(s+=", "),s+='<a href="https://omim.org/entry/'+a+'">'+a+"</a>",n=!0}),s}function acmpmidsFormatter(t,a){var s="",e=a.pmids.split(","),n=!1;return e.forEach(function(t){var a=t.trim();n&&(s+=", "),s+='<a href="https://ncbi.nlm.nih.gov/pubmed/'+a+'">'+a+"</a>",n=!0}),s}function acmhaploFormatter(t,a){return!1===a.haplo_assertion?"":score_assertion_strings[a.haplo_assertion]+" ("+a.haplo_assertion+")"}function acmtriploFormatter(t,a){return!1===a.triplo_assertion?"":score_assertion_strings[a.triplo_assertion]+" ("+a.triplo_assertion+")"}function acmreportFormatter(t,a){return'<a class="btn btn-block btn btn-default btn-xs" href="'+report+a.symbol+'"><i class="fas fa-file"></i>   '+a.date+"</a>"}function dsreportFormatter(t,a){var s="Awaiting Review"==a.workflow?"default":"success",e="";return 3==a.type&&(s="unreviewable",a.workflow="Not Reviewable",e="This gene will not be reviewed because it is a pseudogene"),0==a.type||3==a.type?null==a.hgnc_id?'<a class="btn btn-xs btn-'+s+' btn-block" title="'+e+'" href="/kb/gene-dosage/'+a.isca+'"><i class="fas fa-file"></i>   '+a.workflow+"</a>":'<a class="btn btn-xs btn-'+s+' btn-block" title="'+e+'" href="/kb/gene-dosage/'+a.hgnc_id+'"><i class="fas fa-file"></i>   '+a.workflow+"</a>":'<a class="btn btn-xs btn-'+s+' btn-block" title="'+e+'" href="/kb/gene-dosage/region/'+a.isca+'"><i class="fas fa-file"></i>   '+a.workflow+"</a>"}function dssymbolFormatter(t,a){return 0==a.type||3==a.type?null==a.hgnc_id?'<a href="/kb/genes/'+a.isca+'"><b>'+a.symbol+"</b></a>":'<a href="/kb/genes/'+a.hgnc_id+'"><b>'+a.symbol+"</b></a>":'<a href="/kb/gene-dosage/region/'+a.isca+'"><b>'+a.symbol+"</b></a>"}function relationFormatter(t,a){var s="";if(0==a.type?s+='<div class="global_table_cell font-weight-bold gene" title="Gene">G</div>':1==a.type?s+='<div class="global_table_cell font-weight-bold region" title="Region">R</div>':s+='<div class="global_table_cell font-weight-bold psuedogene" title="Pseudogene">P</div>',null===a.relationship)return s;var e=a.relationship.substring(0,1);return s+='<div class="global_table_cell font-weight-bold carryover mt-1 mb-1" title="'+a.relationship+'">'+e+"</div>"}function locationSorter(t,a){var s=t.match(/\d+|X|Y/g),e=a.match(/\d+|X|Y/g);return"X"==s[0]?s[0]=23:"Y"==s[0]?s[0]=24:s[0]=parseInt(s[0]),"X"==e[0]?e[0]=23:"Y"==e[0]?e[0]=24:e[0]=parseInt(e[0]),s[0]<e[0]?-1:s[0]>e[0]?1:(s[1]=parseInt(s[1]),e[1]=parseInt(e[1]),s[1]<e[1]?-1:s[1]>e[1]?1:(s[2]=parseInt(s[2]),e[2]=parseInt(e[2]),s[2]<e[2]?-1:1))}
>>>>>>> caa5d0a7c819a856564ca31fe388a5f0ec2ef9bd

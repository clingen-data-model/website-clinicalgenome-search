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
      'Authorization': 'Bearer ' + Cookies.get('clingen_dash_token')
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
    },
    btnAdd: {
      text: 'Page Preferences',
      icon: 'glyphicon-bookmark',
      event: function event() {
        if (window.auth !== 1) swal({
          title: "Page Preferences",
          text: "You must be logged in to manage page preferemces."
        });else $('#modalBookmark').modal('toggle');
      },
      attributes: {
        title: 'Page Preferences'
      }
    }
  };else if (typeof bookmarksonly !== 'undefined' && bookmarksonly) {
    return {
      btnUsersAdd: {
        text: 'Page Preferences',
        icon: 'glyphicon-bookmark',
        event: function event() {
          if (window.auth !== 1) swal({
            title: "Page Preferences",
            text: "You must be logged in to manage page preferemces."
          });else {
            $('#modal-bookmark-status').html('&nbsp;');
            $('#modal-new-bookmark').val('');
            $('#bookmark-selected-preference').val('');
            $('#modalBookmark').modal('toggle');
          }
        },
        attributes: {
          title: 'Page Preferences'
        }
      }
    };
  } else return {};
}
/**
 * For a symbol or region cell
 *
 * @param {*} index
 * @param {*} row
 */


function symbolFormatter(index, row) {
  if (row.type == 0 || row.type == 3) return '<span onclick="event.stopPropagation();" ><a href="/kb/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a></span>';else return '<span onclick="event.stopPropagation();" ><a href="/kb/gene-dosage/region/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a></span>';
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
  return '<span onclick="event.stopPropagation();" ><a href="' + url + row.key + '"><b>' + row.name + '</b></a></span>';
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
  if (row.omimlink) return '<span onclick="event.stopPropagation();" ><a href="https://omim.org/entry/' + row.omimlink + '" > <span class="text-success"><i class="fas fa-check"></i></span></a></span>';else return '';
}

function morbidFormatter(index, row) {
  if (row.morbid == "Yes") return '<span onclick="event.stopPropagation();" ><a href="https://omim.org/entry/' + row.omimlink + '" > <span class="text-success"><i class="fas fa-check"></i></span></a></span>';else return '';
}

function reportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  if (row.type == 0 || row.type == 3) {
    /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
        + report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';*/
    return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-success btn-block btn-report" href="' + '/kb/gene-dosage/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.date + '</a></span>';
  } else {
    return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-success btn-block btn-report" href="' + '/kb/gene-dosage/region/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.date + '</a></span>';
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
 *
 * @param {*} index
 * @param {*} row
 */


function noExpCellFormatter(index, row) {
  return {
    classes: 'global_table_cell no-expand'
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
  var html = '<a href="/kb/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>' + '<div class="small text-dark">' + row.curie + ' ';
  if (row.status == 9) html += '<span class="badge bg-light text-muted border-1 text-normal small" title="MONARCH has deprecated this term">Obsolete Term</span>';
  html += '</div>';
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
  return '<a href="/kb/drugs/' + row.curie + '">RXNORM:' + row.curie + '</a>';
}

function drugFormatter(index, row) {
  return '<a href="/kb/drugs/' + row.curie + '">' + row.label + '</a>';
}

function drPortalFormatter(index, row) {
  return '<a target="external" href="https://bioportal.bioontology.org/ontologies/RXNORM?p=classes&conceptid=' + row.curie + '" class="badge-info badge pointer ml-2">BioPortal <i class="fas fa-external-link-alt"></i></a>';
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
  if (row.rawdate === "") return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/' + row.key + '"><i class="fas fa-file"></i>  Under Review</a></span>';
  return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-success btn-block btn-report" href="/kb/gene-dosage/region/' + row.key + '"><i class="fas fa-file"></i>   ' + row.date + '</a></span>';
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
    if (row.hgnc_id == null) return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/' + row.isca + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a></span>';else return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a></span>';
  } else {
    return '<span onclick="event.stopPropagation();" ><a class="btn btn-xs btn-' + bclass + ' btn-block" title="' + title + '" href="' + '/kb/gene-dosage/region/' + row.isca + '"><i class="fas fa-file"></i>   ' + row.workflow + '</a></span>';
  }
}

function dssymbolFormatter(index, row) {
  if (row.type == 0 || row.type == 3) {
    if (row.hgnc_id == null) return '<span onclick="event.stopPropagation();" ><a href="/kb/genes/' + row.isca + '"><b>' + row.symbol + '</b></a></span>';else return '<span onclick="event.stopPropagation();" ><a href="/kb/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a></span>';
  } else return '<span onclick="event.stopPropagation();" ><a href="/kb/gene-dosage/region/' + row.isca + '"><b>' + row.symbol + '</b></a></span>';
}

function relationFormatter(index, row) {
  var html = '';
  if (row.type == 0) html += '<div class="global_table_cell font-weight-bold gene" title="Gene">G</div>';else if (row.type == 1) html += '<div class="global_table_cell font-weight-bold region" title="Region">R</div>';else html += '<div class="global_table_cell font-weight-bold psuedogene" title="Pseudogene">P</div>';
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

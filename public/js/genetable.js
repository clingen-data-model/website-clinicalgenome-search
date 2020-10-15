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
  $.each(row, function (key, value) {
    html.push('<p><b>' + key + ':</b> ' + value + '</p>');
  });
  return html.join('');
}
/**
 * Show the advanced filter toolbar button if the showadvanced
 * global is set.
 */


function table_buttons() {
  if (typeof showadvanced !== 'undefined' && showadvanced) return {
    btnUsersAdd: {
      text: 'Filters',
      icon: 'glyphicon-ok',
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
  if (row.type == 0) return '<a href="/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>  <a href="/gene-dosage/' + row.hgnc_id + '" class="float-right mr-5"><i class="fas fa-map"></i></a>';else return '<a href="https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
}

function geneFormatter(index, row) {
  return '<a href="/genes/' + row.hgnc_id + '"><b>' + row.symbol + '</b></a>';
}

function hgncFormatter(index, row) {
  if (row.type == 0) return '<a href="/gene-dosage/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';else return '<a href="https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id + '"><b>' + row.hgnc_id + '</b></a>';
}

function locationFormatter(index, row) {
  if (row.type == 0) return row.location;
  var name = row.GRCh37_position.trim(); // strip off chr

  if (name.indexOf("chr") === 0) name = name.substring(3);
  var chr = name.indexOf(':');
  var pos = name.indexOf('-');
  var html = '<table><tr><td class="pr-0 text-22px text-normal line-height-normal" rowspan="2">' + name.substring(0, chr) + '</td><td class="text-10px line-height-normal">' + name.substring(chr + 1, pos) + '</td></tr><tr><td class="text-10px line-height-normal">' + name.substring(pos + 1) + '</td></tr></table>';
  return html;
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
  if (row.pli === null) return '-';
  if (row.pli >= .90) return '<span class="format-pli-high">' + row.pli + '</span>';else return '<span class="format-pli-low">' + row.pli + '</span>';
}

function hiFormatter(index, row) {
  if (row.hi === null) return '-';
  if (row.hi <= 10) return '<span class="format-hi-high">' + row.hi + '</span>';else return '<span class="format-hi-low">' + row.hi + '</span>';
}

function haploFormatter(index, row) {
  if (row.haplo_assertion === false) return '';
  var html = row.haplo_assertion.replace(' (', '<br />(');
  if (row.haplo_history === null) return html;
  return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.haplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';
}

function triploFormatter(index, row) {
  if (row.triplo_assertion === false) return '';
  var html = row.triplo_assertion.replace(' (', '<br />(');
  if (row.triplo_history === null) return html;
  return '<span class="pointer text-danger" data-toggle="tooltip" data-placement="top" title="' + row.triplo_history + '"><b>' + html + '</b>  <i class="fas fa-comment"></i></span>';
}

function omimFormatter(index, row) {
  if (row.omimlink) return '<span class="text-success"><i class="fas fa-check"></i></span>';else return '';
}

function morbidFormatter(index, row) {
  if (row.morbid) return '<span class="text-success"><i class="fas fa-check"></i></span>';else return '';
}

function reportFormatter(index, row) {
  /*return '<a class="btn btn-block btn btn-default btn-xs" href="'
          + report + row.symbol + '"><i class="fas fa-file"></i>  View Details</a>'; */
  if (row.type == 0) return '<a class="btn btn-block btn btn-default btn-xs" href="' + report + row.symbol + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';else return '<a class="btn btn-block btn btn-default btn-xs" href="' + 'https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.hgnc_id + '"><i class="fas fa-file"></i>   ' + row.date + '</a>';
}

function iscaFormatter(index, row) {
  if (row.type == 0) return '<a href="' + 'https://dosage.clinicalgenome.org/clingen_gene.cgi?sym=' + row.symbol + '">' + row.isca + '</a>';else if (row.type == 1) return '<a href="' + 'https://dosage.clinicalgenome.org/clingen_region.cgi?id=' + row.isca + '">' + row.isca + '</a>';else return row.isca;
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
  var html = '<a href="/affiliate/' + row.agent + '">' + row.label + '</a>';
  return html;
}

function badgeFormatter(index, row) {
  var html = '';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  return html;
}

function ashgncFormatter(index, row) {
  return '<a href="/genes/' + row.hgnc_id + '">' + row.hgnc_id + '</a>';
}

function asdiseaseFormatter(index, row) {
  return '<a href="/conditions/' + row.mondo + '">' + row.disease + '</a>';
}

function asmondoFormatter(index, row) {
  return '<a href="/conditions/' + row.mondo + '">' + row.mondo.replace('_', ':') + '</a>';
}

function asbadgeFormatter(index, row) {
  return '<a class="btn btn-default btn-xs" href="/gene-validity/' + row.perm_id + '">' + '<i class="glyphicon glyphicon-file"></i> <strong>' + row.classification + '</strong></a>';
}

function conditionFormatter(index, row) {
  // var html = '<a href="/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>'
  //           + '<div class="small text-muted">' + row.curie + ' <span class="badge text-xs">Condition</span></div>';
  var html = '<a href="/conditions/' + row.curie + '"><strong>' + row.label + '</strong></a>' + '<div class="small text-muted">' + row.curie + '</div>'; //if (row.description != null)
  //  html += '<div class="text-sm text-muted">' + row.description + '</div>';

  return html;
}

function cbadgeFormatter(index, row) {
  var html = '';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  return html;
}

function drsymbolFormatter(index, row) {
  return '<a href="/drugs/' + row.curie + '">' + row.curie + '</a>';
}

function drugFormatter(index, row) {
  return '<a href="/drugs/' + row.curie + '">' + row.label + '</a>';
}

function drbadgeFormatter(index, row) {
  var html = '';
  if (row.has_actionability) html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';
  if (row.has_validity) html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';else html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';
  if (row.has_dosage) html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';else html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';
  return html;
}

var terms = {
  "AD": "Autosomal Dominant",
  "AR": "Autosomal Recessive",
  "XL": "X-Linked",
  "XLR": "X-linked recessive",
  "MT": "Mitochondrial",
  "SD": "Semidominant"
};

function moiFormatter(index, row) {
  return '<span class="pointer" data-toggle="tooltip" data-placement="top" title="' + terms[row.moi] + '" ">' + row.moi + '</span>';
}

function hasvalidityFormatter(index, row) {
  if (row.has_validity) {
    return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  }

  return '';
}

function hasactionabilityFormatter(index, row) {
  if (row.has_actionability) {
    return '<a class="btn btn-success btn-sm pb-0 pt-0" href="/genes/' + row.hgnc_id + '"><i class="glyphicon glyphicon-ok"></i> <span class="hidden-sm hidden-xs">Curated</span></a>';
  }

  return '';
}

function hashaploFormatter(index, row) {
  if (row.has_dosage_haplo) {
    return '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym=' + row.symbol + '&subject' + '"><span class="hidden-sm hidden-xs">' + row.has_dosage_haplo + '</span></a>';
  }

  return '';
}

function hastriploFormatter(index, row) {
  if (row.has_dosage_triplo) {
    return '<a class="btn btn-success btn-sm pb-0 pt-0" href="https://dosage.clinicalgenome.org/clingen_gene.cgi?sym=' + row.symbol + '&subject' + '"><span class="hidden-sm hidden-xs">' + row.has_dosage_triplo + '</span></a>';
  }

  return '';
}

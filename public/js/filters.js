var filterstack = [];
/**
 * Push a new filter onto the stack
 */

function filter_push(name, column, value) {
  var o = {
    "name": name,
    "column": column,
    "value": value,
    "active": true
  };
  filterstack.push(o);
  console.log(filterstack);
}
/**
 * Remove a filter from the stack
 */


function filter_pop(name) {
  var x = filterstack.findIndex(function (ele) {
    return ele.name == name;
  });
  if (x != -1) filterstack.splice(x, 1);
  console.log(filterstack);
}
/**
 * Reprocess the filter stack
 */


function filter_process(table) {
  /*type:
  haplo_assertion:
  triplo_assertion:
  locus:
  {thr: 1 or hhr: 1}
  monthFilter*/
  console.log("in filter_process");
  table.bootstrapTable('filterBy', {
    type: [0, 1, 3]
  }, {
    'filterAlgorithm': xxmonthFilter
  });
  table.bootstrapTable('filterBy', {
    type: [1]
  }, {
    'filterAlgorithm': 'or'
  });
  table.bootstrapTable('filterBy', {
    locus: 'protein-coding gene'
  }); //table.bootstrapTable('filterBy', {type: [0, 1, 3]}, {'filterAlgorithm': xxmonthFilter});

  /*table.bootstrapTable('filterBy', {
      type: viz, haplo: ddd, 
  });*/
}

function xxmonthFilter(row, filters) {
  var result = false;
  filterstack.forEach(function (ele) {
    console.log(row[ele.column]);
  }); //return Date.parse(row.rawdate) > timestamp;

  return false;
}

function yymonthFilter(row, filters) {
  var result = false;
  console.log("alternative yy filter"); //return Date.parse(row.rawdate) > timestamp;

  return false;
}

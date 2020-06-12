/*
**      Functions and handlers for managing bootstrap table
**
**
*/

var $table = $('#table');
var selections = [];

console.log($table);

function responseHandler(res)
{
    $.each(res.rows, function (i, row) {
        row.state = $.inArray(row.id, selections) !== -1
    });

    return res;
}


function detailFormatter(index, row)
{
    var html = [];

    $.each(row, function (key, value) {
        html.push('<p><b>' + key + ':</b> ' + value + '</p>')
    });

    return html.join('');
}


function symbolFormatter(index, row)
{ 
	var html = '<a href="/genes/' + row.hgnc_id + '">' + row.symbol + '</a>';
	return html;
}


function badgeFormatter(index, row)
{ 
	var html = '';
	if (row.has_actionability)
    	html += '<img class="" src="/images/clinicalActionability-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/clinicalActionability-off.png" style="width:30px">';

	if (row.has_validity)
    	html += '<img class="" src="/images/clinicalValidity-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/clinicalValidity-off.png" style="width:30px">';

		if (row.has_dosage)
    	html += '<img class="" src="/images/dosageSensitivity-on.png" style="width:30px">';
    else
        html += '<img class="" src="/images/dosageSensitivity-off.png" style="width:30px">';

	return html;
}


  
function initTable()
{
    $table.bootstrapTable('destroy').bootstrapTable({
      locale: 'en-US',
      columns: [ 
        {
			title: 'Gene Symbol',
			field: 'symbol',
			formatter: symbolFormatter,
			sortable: true
        },
        {
			title: 'HGNC ID',
			field: 'hgnc_id'
        },
		{
			title: 'Gene Name',
			field: 'name'
        },
		{
			title: 'Curations',
			field: 'curations',
			align: 'center',
			formatter: badgeFormatter
        },
		{
			field: 'date',
			title: 'Last Curation Date',
			align: 'right'
        }
      ]
    });
    

    $table.on('all.bs.table', function (e, name, args) {
        console.log(name, args)
    });


    $table.on('load-error.bs.table', function (e, name, args) {
        alert("load error");
    });
}


$(function() {
    console.log("Ready");
    initTable()
	var $search = $('.fixed-table-toolbar .search input');
	$search.attr('placeholder', 'Search in table');
	//$search.css('border', '1px solid red');

});

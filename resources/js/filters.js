var filterstack = [
];

$(function() {
    createBadges();
})

function createBadges()
{
    $('.filter-container').html('');
    var advancedFilters = $('.advanced-filter');
    var enabledFilters = advancedFilters.filter( function (filter) {
        return $(this).hasClass('fa-toggle-on')
    });

    if (!enabledFilters.length) {
        var newbadge = $('<span class="badge action-af-badge">None</span>');
        $('.filter-container').append(newbadge);
    } else {
        enabledFilters.each( function () {
            var filterObj = $(this);
            var newbadge = $('<span class="badge action-hi-badge bg-primary mr-1">' + filterObj.data('badge') + '</span>');
            $('.filter-container').append(newbadge);
        })
    }
}



/**
 *
 *
 * ORIGINAL Listener for displfaying only genes
 *
 * */
/*$('.action-show-genes').on('click', function() {
    var viz = [];

    if ($(this).hasClass('btn-primary'))
    {
        $(this).removeClass('btn-primary').addClass('btn-default active');
        $(this).html('<b>Hide Genes</b>');
    }
    else
    {
        viz.push(0);
        viz.push(3);
        $(this).addClass('btn-primary').removeClass('btn-default active');
        $(this).html('<b>Show Genes</b>')
    }

    if ($('.action-show-regions').hasClass('btn-primary'))
        viz.push(1);

    filter_push("geneswitch", "type", viz);
    filter_process($table);
});*/


/**
 *
 * ORIGINAL Listener for displaying only regions
 *
 * */
/*$('.action-show-regions').on('click', function() {
    var viz = [];
    if ($('.action-show-genes').hasClass('btn-primary'))
    {
        viz.push(0);
        viz.push(3);
    }

    if ($(this).hasClass('btn-primary'))
    {
        $(this).removeClass('btn-primary').addClass('btn-default active');
        $(this).html('<b>Hide Regions</b>');
    }
    else
    {
        viz.push(1);
        $(this).addClass('btn-primary').removeClass('btn-default active');
        $(this).html('<b>Show Regions</b>')
    }

    filter_push("geneswitch", "type", viz);
    filter_process($table);
});*/

/**
 *  Clear all the applied dosage filters
 * 
 */
$('.action-show-dosage').on('click', function(e) {

    if ($(this).prop('checked')) 
        return;

    filter_pop("acmgsf");
    $('.action-show-acmgsf').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    $('.action-show-acmgsf-text').html('Off');

    filter_pop("haplo");
    $('.action-show-hiknown').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    $('.action-show-hiknown-text').html('Off');

    filter_pop("triplo");
    $('.action-show-tsknown').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    $('.action-show-tsknown-text').html('Off');

    filter_pop("hits");
    $('.action-show-hitsknown').removeClass('fa-toggle-on').addClass('fa-toggle-off');
    $('.action-show-hitsknown-text').html('Off');

    filter_pop("protein");
    $('.action-show-protein').removeClass('fa-toggle-on').addClass('fa-toggle-off');
	$('.action-show-protein-text').html('Off');

    filter_pop("recent");
    $('.action-show-recent').removeClass('fa-toggle-on').addClass('fa-toggle-off');
	$('.action-show-recent-text').html('Off');

    filter_pop("history");
    $('.action-show-new').removeClass('fa-toggle-on').addClass('fa-toggle-off');
	$('.action-show-recent-new').html('Off');

    createBadges()

});

function legacy_gene_switch(obj)
{
    var viz = [];

    if (obj.hasClass('btn-success'))
    {
        obj.removeClass('btn-success').addClass('btn-default');
        obj.html('<b>Genes: Off</b>');
    }
    else
    {
        viz.push(0);
        viz.push(3);
        obj.addClass('btn-success').removeClass('btn-default');
        obj.html('<b>Genes: On</b>')
    }

    if ($('.action-show-regions').hasClass('btn-success'))
        viz.push(1);

    filter_push("geneswitch", "type", viz);
    filter_process($table);
}
/**
 *
 *
 * Listener for displfaying only genes
 *
 * */
$('.action-show-genes').on('click', function() {
    var viz = [];

    // check for legacy objects
    if ($(this).hasClass('btn'))
        return legacy_gene_switch($(this));

    if ($(this).prop('checked')) 
    {
        viz.push(0);
        viz.push(3);
    }


    if ($('.action-show-regions').prop('checked')) 
        viz.push(1);

    filter_push("geneswitch", "type", viz);
    filter_process($table);
});


function legacy_region_switch(obj)
{
    var viz = [];
    if ($('.action-show-genes').hasClass('btn-success'))
    {
        viz.push(0);
        viz.push(3);
    }

    if (obj.hasClass('btn-success'))
    {
        obj.removeClass('btn-success').addClass('btn-default');
        obj.html('<b>Regions: Off</b>');
    }
    else
    {
        viz.push(1);
        obj.addClass('btn-success').removeClass('btn-default');
        obj.html('<b>Regions: On</b>')
    }

    filter_push("geneswitch", "type", viz);
    filter_process($table);
}


/**
 *
 * Listener for displaying only regions
 *
 * */
$('.action-show-regions').on('click', function() {
    var viz = [];

    // check for legacy objects
    if ($(this).hasClass('btn'))
        return legacy_region_switch($(this));

    if ($(this).prop('checked')) 
    {
        viz.push(1);
    }

    if ($('.action-show-genes').prop('checked')) 
    {
        viz.push(0);
        viz.push(3);
    }

    filter_push("geneswitch", "type", viz);
    filter_process($table);

});


/**
 *
 * Listener for displaying only overlapping genes and regions
 *
 * */
$('.action-show-overlap').on('click', function() {
    var viz = [];

    if ($(this).prop('checked')) 
    {
        viz.push("Overlap");
    }

    if ($('.action-show-contain').prop('checked'))
        viz.push("Contained");

    filter_push("container", "relationship", viz);
    filter_process($table);

});


/**
 *
 * Listener for displaying only contained genes and regions
 *
 * */
$('.action-show-contain').on('click', function() {
    var viz = [];

    if ($(this).prop('checked')) 
    {
        viz.push("Contained");
    }

    if ($('.action-show-overlap').prop('checked'))
        viz.push("Overlap");

    filter_push("container", "relationship", viz);
    filter_process($table);

});

function filterNotPseudos(rows, filter)
    {
        return rows.locus_type != "pseudogene";
    }

$('.action-show-psuedo').on('click', function() {

    if ($(this).prop('checked')) 
    {
        //filter_push("pseudogene", "@filter", filterNotPseudos);
        filter_pop("pseudogene");
        
    }
    else
    {
        filter_push("pseudogene", "@filter", filterNotPseudos);
        //filter_pop("pseudogene");
    }

    filter_process($table);
});


/**
 *
 * Listener for displaying only contained genes and regions
 *
 * */
$('.action-show-contain').on('click', function() {
    var viz = [];

    if ($(this).prop('checked')) 
    {
        viz.push("Contained");
    }

    if ($('.action-show-overlap').prop('checked'))
        viz.push("Overlap");

    filter_push("geneswitch", "relationship", viz);
    filter_process($table);

});


/**
 *
 * Listener for displaying only the known HI
 *
 * */
$('.action-show-hiknown').on('click', function() {

    if ($(this).hasClass('fa-toggle-off'))
    {
        //$table.bootstrapTable('filterBy', {haplo_assertion: '3 (Sufficient Evidence)'});
        filter_push("haplo", "haplo_assertion", 3);

        $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
        $('.action-show-hiknown-text').html('On');

    }
    else
    {
        //$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
        filter_pop("haplo");

        $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
        $('.action-show-hiknown-text').html('Off');

    }

    filter_process($table);
});


/**
 *
 * Listener for displaying only the ACMG SF genes in dosage
 *
 * */
$('.action-show-acmgsf').on('click', function() {

    if ($(this).hasClass('fa-toggle-off'))
    {
        //$table.bootstrapTable('filterBy', {haplo_assertion: '3 (Sufficient Evidence)'});
        filter_push("acmgsf", "acmgsf", 1);

        $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
        $('.action-show-acmgsf-text').html('On');

    }
    else
    {
        //$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
        filter_pop("acmgsf");

        $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
        $('.action-show-acmgsf-text').html('Off');

    }

    filter_process($table);
});



/**
 *
 * Listener for displaying only the known TS
 *
 * */
    $('.action-show-tsknown').on('click', function() {

    if ($(this).hasClass('fa-toggle-off'))
    {
        //$table.bootstrapTable('filterBy', {triplo_assertion: '3 (Sufficient Evidence)'});
        filter_push("triplo", "triplo_assertion", 3);

        $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
        $('.action-show-tsknown-text').html('On');

    }
    else
    {
        //$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
        filter_pop("triplo");

        $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
        $('.action-show-tsknown-text').html('Off');
    }

    filter_process($table);
});


/**
 *
 * Listener for displaying only the known HI
 *
 * */
    $('.action-show-hitsknown').on('click', function() {

    if ($(this).hasClass('fa-toggle-off'))
    {
        //$table.bootstrapTable('filterBy', {haplo_assertion: '3 (Sufficient Evidence)'});
        filter_push("hits", ["haplo_assertion", "triplo_assertion"],
                    [3, 3]);

        $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
        $('.action-show-hitsknown-text').html('On');

    }
    else
    {
        //$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
        filter_pop("hits");

        $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
        $('.action-show-hitsknown-text').html('Off');

    }

    filter_process($table);
});


/**
	 *
	 * Listener for displaying only protein coding genes
	 *
	 * */
	$('.action-show-protein').on('click', function() {

        if ($(this).hasClass('fa-toggle-off'))
		{
			//$table.bootstrapTable('filterBy', {locus: 'protein-coding gene'});
			filter_push("protein", "locus", 'protein-coding gene');

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-protein-text').html('On');

		}
		else
		{
			//$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
			filter_pop("protein");

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-protein-text').html('Off');

		}

		filter_process($table);
	});

    /**
	 *
	 * Listener for displaying only completed genes
	 *
	 * */
	$('.action-show-pseudogenes').on('click', function() {

        if ($(this).hasClass('fa-toggle-on'))
		{
			//$table.bootstrapTable('filterBy', {locus: 'protein-coding gene'});
            filter_push("pseudogene", "@filter", filterPseudogenes);

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-pseudogenes-text').html('Off');
			$('.action-pseudogenes-badge').remove();

            if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

		}
		else
		{
			//$table.bootstrapTable('filterBy', {type: [0, 1, 3]});

            filter_pop("pseudogene");

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-pseudogenes-text').html('On');

		}

		filter_process($table);
	});


    /**
	 *
	 * Listener for displaying only protein coding genes
	 *
	 * */
	$('.action-show-curated').on('click', function() {

        if ($(this).hasClass('fa-toggle-off'))
		{
			filter_push("curated", "has_curations", true);

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-curated-text').html('On');

		}
		else
		{
			filter_pop("curated");

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show--text').html('Off');

		}

		filter_process($table);
	});

    /**
	 *
	 * Listener for displaying only completed genes
	 *
	 * */
	$('.action-show-completed').on('click', function() {

        if ($(this).hasClass('fa-toggle-off'))
		{
			filter_push("complete", "@filter", completedFilter);

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-completed-text').html('On');

		}
		else
		{
			//$table.bootstrapTable('filterBy', {type: [0, 1, 3]});
			filter_pop("complete");

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-completed-text').html('Off');

			$('.action-completed-badge').remove();

		}

		filter_process($table);
	});


	/**
	 *
	 * Listener for displaying only the recent score changes
	 *
	 * */
	$('.action-show-new').on('click', function() {
		var viz = [];

		if ($(this).hasClass('fa-toggle-off'))
		{
			//$table.bootstrapTable('filterBy', {thr: 1, hhr: 1}, {'filterAlgorithm': 'or'});
			filter_push("history", ['thr', 'hhr'], [1, 1]);

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-new-text').html('On');

		}
		else
		{
			//$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});
			filter_pop("history");

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-new-text').html('Off');

			$('.action-new-badge').remove();

		}

		// 'filterAlgorithm': function (){ return true;}
		filter_process($table);
	});

	var timestamp = new Date().getTime() - (90 * 24 * 60 * 60 * 1000);		// 90 days

	function monthFilter(rows, filters)
	{
		return Date.parse(rows.rawdate) > timestamp;
    }

    function completedFilter(rows, filter)
    {
        return rows.workflow === 'Complete'
    }

    function filterPseudogenes(rows, filter)
    {
        return rows.type == 0 || rows.type == 1;
    }

    /**
	 *
	 * Listener for displaying only the recent reviewed items
	 *
	 * */
	 $('.action-show-recent').on('click', function() {

		if ($(this).hasClass('fa-toggle-off'))
		{
			//$table.bootstrapTable('filterBy', {type: [0, 1, 3]}, {'filterAlgorithm': monthFilter});
			filter_push("recent", '@filter', monthFilter);

			$(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
			$('.action-show-recent-text').html('On');

			$('.action-af-badge').remove();

			var newbadge = $('<span class="badge action-nine-badge bg-primary">Recently Reviewed</span>');
			$('.filter-container').append(newbadge);

		}
		else
		{
			//$table.bootstrapTable('filterBy', {thr: [0, 1]}, {'filterAlgorithm': 'or'});
			filter_pop("recent");

			$(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
			$('.action-show-recent-text').html('Off');

			$('.action-nine-badge').remove();

			if ($('.filter-container').html() == '')
			{
				var newbadge = $('<span class="badge action-af-badge">None</span>');
				$('.filter-container').append(newbadge);
			}

		}

		filter_process($table);
	});


    /*
    **  Filter control for acmg59 mode
    */
	$('.action-show-acmg59').on('click', function() {

        if ($(this).hasClass('fa-toggle-off'))
        {
          //$table.bootstrapTable('filterBy', {acmg59: true});
          filter_push("acmg59", 'acmg59', true);

          $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
          $('.action-show-acmg59-text').html('On');
          //$('.action-af-badge').html('ACMG SF v3.0').addClass('bg-primary');

          $('.action-af-badge').remove();

                var newbadge = $('<span class="badge action-acmg-badge bg-primary mr-1">ACMG SF v3.2</span>');
                $('.filter-container').append(newbadge);

        }
        else
        {
          //$table.bootstrapTable('filterBy', {acmg59: [false, true]});
          filter_pop("acmg59");

          $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
          $('.action-show-acmg59-text').html('Off');
          //$('.action-af-badge').html('None').removeClass('bg-primary');

          $('.action-acmg-badge').remove();

                if ($('.filter-container').html() == '')
                {
                    var newbadge = $('<span class="badge action-af-badge">None</span>');
                    $('.filter-container').append(newbadge);
                }

        }

        filter_process($table);

      });


    /*
     **  Filter control for follow mode
    */
	$('.action-show-follow').on('click', function() {

        if ($(this).hasClass('fa-toggle-off'))
        {
          //$table.bootstrapTable('filterBy', {followed: true});
          filter_push("follow", 'followed', true);

          $(this).removeClass('fa-toggle-off').addClass('fa-toggle-on');
          $('.action-show-follow-text').html('On');
          //$('.action-af-badge').html('Followed').addClass('bg-primary');

          $('.action-af-badge').remove();

                var newbadge = $('<span class="badge action-follow-badge bg-primary mr-1">Followed</span>');
                $('.filter-container').append(newbadge);

        }
        else
        {
          //$table.bootstrapTable('filterBy', {followed: [false, true]});
          filter_pop("follow");

          $(this).removeClass('fa-toggle-on').addClass('fa-toggle-off');
          $('.action-show-follow-text').html('Off');
          //$('.action-af-badge').html('None').removeClass('bg-primary');

          $('.action-follow-badge').remove();

          if ($('.filter-container').html() == '')
          {
            var newbadge = $('<span class="badge action-af-badge">None</span>');
            $('.filter-container').append(newbadge);
          }
        }


        filter_process($table);

      });


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

    filter_pop(name);
    filterstack.push(o);
}


/**
 * Remove a filter from the stack
 */
function filter_pop(name) {

    var x = filterstack.findIndex(ele => ele.name == name);

    if (x != -1)
        filterstack.splice(x, 1);
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

    table.bootstrapTable('filterBy', { }, {'filterAlgorithm': checkFilter});
    createBadges()
}

function checkFilter(row, filters)
	{
        var check = true;

        for (var n = 0; n < filterstack.length; n++)
        {
            var ele = filterstack[n];

            if (Array.isArray(ele.column))
            {
                check = false;

                for (var i = 0; i < ele.column.length; i++)
                {
                    check = (row[ele.column[i]] ==  ele.value[i]);

                    if (check === true)
                        break;
                }

                if (check === false)
                    break;
            }
            else if (ele.column.charAt(0) == '@')
            {
               check = ele.value(row, filters);

               if (check === false)
                    break;
            }
            else
            {
                if (Array.isArray(ele.value))
                    check = (ele.value.indexOf(row[ele.column]) != -1);
                else
                    check = (row[ele.column] == ele.value);

                if (check === false)
                    break;
            }
        }

        return check;
    }

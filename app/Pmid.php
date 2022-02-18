<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;


/**
 *
 * @category   Model
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2019 Geisinger
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Pmid extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [
		'ident' => 'alpha_dash|max:80|required',
		'name' => 'name|max:80|required',
		'gene_id' => '',
		'type' => 'integer',
		'status' => 'integer'
	];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'authors' => 'array',
        'lang' => 'array',
        'pubtype' => 'array',
        'articleids' => 'array',
        'history' => 'array',
        'references' => 'array',
        'attributes' => 'array',
        'srccontriblist' => 'array',
        'doccontriblist' => 'array',
        'mesh_terms' => 'array',
        'other' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [	'task_id', 'efetch', 'abstract', 'mesh_terms',
							'pubmed_id', 'user_id', 'pmid', 'uid', 'pubdate', 'epubdate', 'source',
							'authors', 'lastauthor', 'title', 'sorttitle', 'volume',
							'issue', 'pages', 'lang', 'nlmuniqueid', 'issn', 'essn',
							'pubtype', 'recordstatus', 'pubstatus', 'articleids', 'history',
							'references', 'attributes', 'pmcrefcount', 'fullfournalname',
							'elocationid', 'doctype', 'srccontriblist', 'booktitle', 'medium',
							'edition', 'publisherlocation', 'publishername', 'srcdate', 'reportnumber',
							'availablefromurl', 'locationlabel', 'doccontriblist', 'docdate', 'bookname',
							'chapter', 'sortpubdate', 'sortfirstauthor', 'vernaculartitle', 'other',
							'notes', 'priority', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status',
						  'short_abstract', 'pub_year'];

    public const STATUS_NEW = 1;
    public const STATUS_REVIEWING = 2;
    public const STATUS_COMPLETED = 3;
    public const STATUS_LIBRARY = 4;
    public const STATUS_COMPABSTRACT = 5;
    public const STATUS_NA = 7;
    public const STATUS_LIBRARY_RELEVANT = 8;

    /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		1 => 'New, Not Reviewed',
	 		2 => 'In Review',
	 		3 => 'Review Completed',
	 		4 => 'Library Request',
	 		7 => 'Dismissed, Not Applicable',
            8 => 'Library Request; Relevant',
	 		9 => 'Deleted',
	 		20 => 'Esummary PMID search completed',
	 		21 => 'Esummary Article load completed'
	];


	/**
     * Automatically assign an ident on instantiation
     *
     * @param	array	$attributes
     * @return 	void
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['ident'] = (string) Uuid::generate(4);
        parent::__construct($attributes);
    }


    /**
     * Access the user assigned with this pmid
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function user()
    {
		return $this->belongsTo('App\User');
	}


    /**
     * Access the task associated with this pmid
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function task()
    {
		return $this->belongsTo('App\Task');
	}


	/**
     * Access the curations associated with this pmid
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function curations()
    {
		return $this->hasMany('App\Curation');
	}


	/**
     * Return a shortened version of the abstract with links
     *
     * @param
     * @return string
     */
	public function getShortAbstractAttribute()
	{
		if (empty($this->abstract))
			return '';

		if (strlen($this->abstract) < 200)
			return $this->abstract;

		$string = substr($this->abstract, 0, 199)
				. ' <span class="action_pmid_abstract" data-uuid="'
				. $this->ident . '"> ...</span>';

		return $string;
	}


	/**
     * Return only the formatted reference string
     *
     * 		AUTHOR_LAST_NAME et al., YEAR
     *
     * @param
     * @return string
     */
	public function getReferenceAttribute()
	{
		if (empty($this->sortfirstauthor))
			return '';

		// we strip off the trailing initials
		$name = preg_split("/ [A-Z][A-Z]?$/", $this->sortfirstauthor);

		return $name[0] . ' et al., '
				. substr($this->sortpubdate, 0, 4);

	}


	/**
     * Return only the publication year
     *
     * @param
     * @return string
     */
	public function getPubYearAttribute()
	{
		if (empty($this->pubdate))
			return '';

		if (preg_match('/\b(19|2[0-9]\d{2})\b/', $this->pubdate, $matches))
			return $matches[0];

			return '';
	}


	/**
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeIdent($query, $ident)
    {
		return $query->where('ident', $ident);
    }


    /**
     * Query scope by pmid
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopePmid($query, $pmid)
    {
		return $query->where('pmid', $pmid);
    }

}

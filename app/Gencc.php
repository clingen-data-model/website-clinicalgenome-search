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
 * @package    DBDGenes
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2019 Geisinger
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Gencc extends Model
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
		'uuid' => 'string',
        'gene_curie' => 'string',
        'gene_symbol' => 'string',
        'disease_curie' => 'string',
        'disease_title' => 'string',
        'disease_original_curie' => 'string',
        'disease_original_title' => 'string',
        'classification_curie' => 'string',
        'classification_title' => 'string',
        'moi_curie' => 'string',
        'moi_title' => 'string',
        'submitter_curie' => 'string',
        'submitter_title' => 'string',
        'submitted_as_date' => 'string',
		'type' => 'integer',
		'status' => 'integer'
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [ 'uuid', 'gene_curie', 'gene_symbol', 'disease_curie',
                        'disease_title', 'disease_original_curie', 'disease_original_title',
                        'classification_curie', 'classification_title', 'moi_curie', 'moi_title',
                        'submitter_curie', 'submitter_title', 'submitted_as_date',
                        'type', 'status' ];

	/**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = ['display_date', 'list_date', 'display_status'];


    //public const TYPE_MISSENSE = 1;

    /*
     * Status strings for display methods
     *
     * */
     protected $status_strings = [
	 		0 => 'Initialized',
	 		1 => 'Active',
	 		9 => 'Deleted'
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
     * Access the aliases associated with this gene
     *
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function gene()
    {
		return $this->belongsTo('App\Gene');
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
     * Query scope by ident
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
	public function scopeHgnc($query, $id)
    {
		return $query->where('gene_curie', $id);
    }


    public static function score_class($curie)
    {
        switch ($curie)
        {
            case 'GENCC:100001':
                return 'definitive';
            case 'GENCC:100002':
                return 'strong';
            case 'GENCC:100003':
                return 'moderate';
            case 'GENCC:100004':
                return 'limited';
            case 'GENCC:100005':
                return 'disputed';
            case 'GENCC:100006':
                return 'refuted';
            case 'GENCC:100007':
                return 'animal';
            case 'GENCC:100008':
            default:
                return 'unknown';
        }

    }

}

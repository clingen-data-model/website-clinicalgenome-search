<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Display;

use Uuid;

/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2020 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.0.0
 *
 * */
class Dosage extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Display;

    /**
     * The attributes that should be validity checked.
     *
     * @var array
     */
    public static $rules = [];

    /**
     * Map the json attributes to associative arrays.
     *
     * @var array
     */
	protected $casts = [
              'history' => 'array',
              'gain_pheno_omim' => 'array',
              'loss_pheno_omim' => 'array',
    ];

    /**
     * The attributes that are mass assignable.  Remember to fill it
     * in when all the attributes are known.
     *
     * @var array
     */
     protected $fillable = ['label', 'issue', 'curation', 'description', 'cytoband',
                            'chr', 'start', 'stop', 'start38', 'stop38', 'grch37',
                            'grch38', 'pli', 'omiim', 'haplo', 'triplo', 'history',
                            'haplo_history', 'triplo_history',
                            'gain_pheno_omim', 'gain_pheno_ontology', 'gain_pheno_ontology_id',
                            'gain_pheno_name', 'gain_comments',
                            'loss_pheno_omim', 'loss_pheno_ontology', 'loss_pheno_ontology_id',
                            'loss_pheno_name', 'loss_comments',
                            'workflow', 'resolved', 'notes', 'type', 'status'
                            ];

	  /**
     * Non-persistent storage model attributes.
     *
     * @var array
     */
    protected $appends = [];


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
     * Query scope by iddur
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeIssue($query, $issue)
    {
      return $query->where('issue', $issue);
    }


    /**
     * Query scope by type
     *
     * @@param	string	$ident
     * @return Illuminate\Database\Eloquent\Collection
     */
    public function scopeType($query, $type)
    {
      return $query->where('type', $type);
    }


    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getHgncIdAttribute()
    {
		  return $this->issue ?? null;
    }


    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getSymbolAttribute()
    {
		  return $this->label ?? null;
    }
    

    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getChromosomeBandAttribute()
    {
		  return $this->cytoband ?? null;
    }


    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getOmimlinkAttribute()
    {
		  return $this->omim ?? null;
    }
    

    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getTriploAssertionAttribute()
    {
		  return $this->triplo ?? null;
    }
    

    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getHaploAssertionAttribute()
    {
		  return $this->haplo ?? null;
    }
    

    /**
     * Return full name of gene 
     * 
     * @@param	
     * @return 
     */
    public function getResolvedDateAttribute()
    {
		  return $this->resolved ?? null;
	  }
}

<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
//use Maatwebsite\Excel\Concerns\WithValidation;
//use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * 
 * @category   CategoryName
 * @package    PackageName
 * @author     P. Weller <pweller1@geisinger.edu>
 * @copyright  2019 The PHP Group
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.2.0
 * @deprecated Class deprecated in Release 2.0.0
 * 
 * */
 // WithHeadingRow, WithMultipleSheets
class Excel implements ToCollection, WithHeadingRow
{
	use Importable;
	
	/**
     * The attributes that are mass assignable.
     *
     * @param	array	$row
     */
	public function collection(Collection $rows)
    {
		die("cp1");
		Validator::make($rows->toArray(), [
             'onions' => 'required',
         ])->validate();
         
        foreach ($rows as $row) 
        {
            /*User::create([
                'name' => $row[0],
            ]);*/
        }
    }
    
    
    /*public function rules(): array
    {
        return [
            '1' => Rule::in(['patrick@maatwebsite.nl']),

             // Above is alias for as it always validates in batches
             '*.1' => Rule::in(['patrick@maatwebsite.nl']),
             
             // Can also use callback validation rules
             '0' => function($attribute, $value, $onFailure) {
                  if ($value !== 'Patrick Brouwers') {
                       $onFailure('Name is not Patrick Brouwers');
                  }
              }
        ];
    }*/
}

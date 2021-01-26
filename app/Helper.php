<?php

/// CONSIDER DELETING NOT YET


namespace App;

//use App\Traits\Display;


/**
 *
 * @category   Library
 * @package    Search
 * @author     P. Weller <pweller1@geisinger.edu>
 * @author     S. Goehringer <scottg@creationproject.com>
 * @copyright  2019 ClinGen
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    Release: @package_version@
 * @link       http://pear.php.net/package/PackageName
 * @see        NetOther, Net_Sample::Net_Sample()
 * @since      Class available since Release 1.2.0
 * @deprecated Class deprecated in Release 2.0.0
 *
 * */

/*
  * Temporary static model to transistion the helpers.
  * */

class Helper
{
  //use Display;


  /*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
  static function PrintWrapperPmidSop5Gci($data = null)
  {
//die($data);
    // "<div class=\"WrapperPmid\" >"
    // "<div class=\"form-group\">"
    // "<div class=\"WrapperPmidResults\">"
    $varInner = "";
    if ($data !== null) {
      $varStart = "<div class=\"GeneticEvidencePmidData\">";
      if (!empty($data->notes->note))
        $note = "(" . $data->notes->note . ")";
      else
        $note = "";

      if (isset($data->publications)) {
        foreach ($data->publications as $pubs) {
          // ##varInner += pubs.last.inspect
          if (!isset($pubs->last)) {
            $varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->uid . "); ";
          } else {
            //dd($pubs);
            $varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["uid"] . "); ";
          }
        }

        // ##varInner += pubs.inspect
      }

      $varEnd = $note . "</div>";

      return $varStart . $varInner . $varEnd;
    } else
      return '';
  }


  /*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
  static function PrintWrapperPmidSop5($data = null)
  {

    // "<div class=\"WrapperPmid\" >"
    // "<div class=\"form-group\">"
    // "<div class=\"WrapperPmidResults\">"
    $varInner = "";
    if ($data !== null) {
      $varStart = "<div class=\"GeneticEvidencePmidData\">";
      if (!empty($data->notes->note))
        $note = "(" . $data->notes->note . ")";
      else
        $note = "";

      if (isset($data->publications)) {
        foreach ($data->publications as $pubs) {
          // ##varInner += pubs.last.inspect
          if (!isset($pubs->last)) {
            $varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->uid . "); ";
          } else {
            //dd($pubs);
            $varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["uid"] . "); ";
          }
        }

        // ##varInner += pubs.inspect
      }

      $varEnd = $note . "</div>";

      return $varStart . $varInner . $varEnd;
    } else
      return '';
  }


  /*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
  static function PrintWrapperPmid($data = null)
  {
    //	# "<div class=\"WrapperPmid\" >"
    // # "<div class=\"form-group\">"
    // # "<div class=\"WrapperPmidResults\">"

    $varInner = "";
    if (!empty($data)) {
      $varStart = "<div class=\"GeneticEvidencePmidData\">";
      if (!empty($data->notes->note))
        $note = "(" . $data->notes->note . ")";
      else
        $note = "";

      if (!empty($data->publications)) {
        foreach ($data->publications as $pubs)
          $varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->uid . "); ";
        //##varInner += pubs.inspect
      }

      $varEnd = $note . "</div>";

      return $varStart . $varInner . $varEnd;
    } else
      return "";
  }
}

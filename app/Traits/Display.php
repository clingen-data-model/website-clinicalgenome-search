<?php

namespace App\Traits;


trait Display
{
    /**
     * Return a displayable string of update
     *
     * @param
     * @return string
     */
	public function getDisplayDateAttribute()
	{
		if (empty($this->updated_at))
			return '';

		return $this->updated_at->timezone('America/New_York')
					->format("M j, Y");
	}


	/**
     * Return a displayable string of update
     *
     * @param
     * @return string
     */
	public function getListDateAttribute()
	{
		if (empty($this->updated_at))
			return '';

		return $this->updated_at->timezone('America/New_York')
					->format("F Y");
	}


	/**
     * Return a displayable string of status
     *
     * @param
     * @return string
     */
	public function getDisplayStatusAttribute()
	{
		return ($this->status_strings[$this->status] ?? 'Unknown');
	}


	/**
     * Return a string with the text replaced.
		 * This is intended for simple usages if/when, for example, a CURIE isn't formated for
		 * reability... MONDO_1234 should be MONDO:1234.  The dafaults are provided but
		 * params can be send if not the defaults.
		 *
		 * @param [string] $data
		 * @param string $find
		 * @param string $replace_with
		 * @return void
		 */
	public function displayReplaceCharacter($data, $find = "_", $replace_with = ":")
	{
		if (empty($data))
			return '';

		return str_replace($find, $replace_with, $data);
	}

	/**
	 * Return a displayable string of status
	 *
	 * @param
	 * @return string
	 */
	public function getAliasSymbolsStringAttribute()
	{
		if (empty($this->alias_symbol))
			return '';

		return implode(', ', $this->alias_symbol);
	}


	/**
     * Return a displayable string of status
     *
     * @param
     * @return string
     */
	public function getPrevSymbolsStringAttribute()
	{
		if (empty($this->prev_symbol))
			return '';

		return implode(', ', $this->prev_symbol);
	}

	/**
     * Return a displayable string of date parameter
     *
     * @param
     * @return string
     */
	public function displayDate($date, $long = false)
	{
		if (empty($date))
			return '';

		$time = strtotime($date);

		if ($time === false)
			return '';

		return ($long ? date("m/d/Y", $time) : date("m/d/Y", $time));
	}

	/**
	 * Return a sortable date
	 *
	 * @param
	 * @return string
	 */
	public function displaySortDate($date, $long = false)
	{
		if (empty($date))
			return '';

		$time = strtotime($date);

		if ($time === false)
			return '';

		return ($long ? date("Ymd", $time) : date("Ymd", $time));
	}

	/**
	 * Return a Affiliate ID from agent IR
	 *
	 * @param
	 * @return string
	 */
	public function displayAffiliateIdFromIri($data = null)
	{
		//dd($data);
		if (empty($data))
			return '';
		$data = explode("/", $data);
		$data = end($data);
		if ($data === false)
			return '';

		return $data;
	}

	/**
	 * Return a MOI
	 *
	 * @param
	 * @return string
	 */
	public function displayMoi($moi, $long = false)
	{
		if (empty($moi))
			return '';

		//$amoi = explode("(", $moi);

		//if (!isset($amoi[1]))
		//	return 'Other';
			
		switch($moi){
			case "HP:0000006":
				$text = "Autosomal Dominant";
				$abr = "AD";
				break;
			case "HP:0000007":
				$text = "Autosomal Recessive";
				$abr = "AR";
				break;
			case "HP:0001417":
				$text = "X-Linked";
				$abr = "XL";
				break;
			case "HP:0001419":
				$text = "X-linked recessive";
				$abr = "XLR";
				break;
			case "HP:0001427":
				$text = "Mitochondrial";
				$abr = "MT";
				break;
			case "HP:0032113":
				$text = "Semidominant";
				$abr = "SD";
				break;
			default:
				$text = $moi;
				$abr = "Other";
				break;
				}

		return ($long ? $text : $abr);
	}
}

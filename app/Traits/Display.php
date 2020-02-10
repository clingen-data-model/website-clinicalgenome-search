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
}

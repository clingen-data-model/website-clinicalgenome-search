<?php



		/*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
    function PrintWrapperPmidSop5Gci($data = null)
    {

		  // "<div class=\"WrapperPmid\" >"
		  // "<div class=\"form-group\">"
		  // "<div class=\"WrapperPmidResults\">"
		$varInner = "";
		if ($data !== null)
		{
			$varStart = "<div class=\"GeneticEvidencePmidData\">";
			if (!empty($data->notes->note))
			  $note = "(" . $data->notes->note . ")";
			else
			  $note = "";
			//dd($data->Publications);
			if (isset($data->Publications))
			{
				foreach ($data->Publications as $pubs)
				{
					// ##varInner += pubs.last.inspect
					if (!isset($pubs->last))
					{
						$varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->pmid . "); ";
					}
					else
					{
						dd($pubs);
						$varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["pmid"] . "); ";
					}
				}

			  // ##varInner += pubs.inspect
			}

			$varEnd = $note . "</div>";

			return $varStart . $varInner . $varEnd;
		}
		else
			return '';
	}


	/*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
   function PrintWrapperPmidSop5($data = null)
    {

		  // "<div class=\"WrapperPmid\" >"
		  // "<div class=\"form-group\">"
		  // "<div class=\"WrapperPmidResults\">"
		$varInner = "";
		if ($data !== null)
		{
			$varStart = "<div class=\"GeneticEvidencePmidData\">";
			if (!empty($data->notes->note))
			  $note = "(" . $data->notes->note . ")";
			else
			  $note = "";
			//dd($data->Publications);
			if (isset($data->Publications))
			{
				foreach ($data->Publications as $pubs)
				{
					// ##varInner += pubs.last.inspect
					if (!isset($pubs->last))
					{
						$varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->pmid . "); ";
					}
					else
					{
						dd($pubs);
						$varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["pmid"] . "); ";
					}
				}

			  // ##varInner += pubs.inspect
			}

			$varEnd = $note . "</div>";

			return $varStart . $varInner . $varEnd;
		}
		else
			return '';
	}


		/**
     * Return a displayable string of date parameter
     *
     * @param
     * @return string
     */
	function displayDate($date, $long = false)
	{
		if (empty($date))
			return '';

		$time = strtotime($date);

		if ($time === false)
			return '';

		return ($long ? date("m/d/Y", $time) : date("m/d/Y", $time));
	}


     function PrintDate($data = null)
    {
			return $data;
		}
	/*
     *
     *
     * @param	string	$mondo
     * @return 	array
     */
     function PrintWrapperPmid($data = null)
    {
			//return "asdasd";
		//	# "<div class=\"WrapperPmid\" >"
  		// # "<div class=\"form-group\">"
  		// # "<div class=\"WrapperPmidResults\">"
		$varInner = "";
		if (!empty($data))
		{
			$varStart = "<div class=\"GeneticEvidencePmidData\">";
			if (!empty($data['notes']['note']))
				$note = "(" . $data['notes']['note'] . ")";
			else
				$note = "";

			if (!empty($data['publications']))
			{
				foreach ($data['publications'] as $pub)
				  $varInner .= $pub["author"] . " et al. " . $pub["pubdate"] . " (PMID:" . $pub["uid"] . "); ";
				//##varInner += pubs.inspect
			}

			$varEnd = $note . "</div>";

			return $varStart . $varInner . $varEnd;
		}
		else
			return "";

}

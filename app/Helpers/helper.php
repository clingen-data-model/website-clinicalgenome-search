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

			if (isset($data->publications))
			{
				foreach ($data->publications as $pubs)
				{
					// ##varInner += pubs.last.inspect
					if (!isset($pubs->last))
					{
						$varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->uid . "); ";
					}
					else
					{
						dd($pubs);
						$varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["uid"] . "); ";
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

			if (isset($data->publications))
			{
				foreach ($data->publications as $pubs)
				{
					// ##varInner += pubs.last.inspect
					if (!isset($pubs->last))
					{
						$varInner .= $pubs->author . " et al. " . $pubs->pubdate . " (PMID:" . $pubs->uid . "); ";
					}
					else
					{
						dd($pubs);
						$varInner .= $pubs->last["author"] . " et al. " . $pubs->last["pubdate"] . " (PMID:" . $pubs->last["uid"] . "); ";
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
     function PrintWrapperPmid($id = '', $data = null)
    {
		//	# "<div class=\"WrapperPmid\" >"
  		// # "<div class=\"form-group\">"
  		// # "<div class=\"WrapperPmidResults\">"
		$varInner = "";
		if (!empty($data))
		{
			$varStart = "<div class=\"GeneticEvidencePmidData\">";
			if (!empty($data->notes->note))
				$note = "(" . $data->notes->note . ")";
			else
				$note = "";

			if (!empty($data->publications))
			{
				foreach ($data->publications as $pub)
				  $varInner .= $pubs["author"] . " et al. " . $pubs["pubdate"] . " (PMID:" . $pubs["uid"] . "); ";
				//##varInner += pubs.inspect
			}

			$varEnd = $note . "</div>";

			return $varStart . $varInner . $varEnd;
		}
		else
			return "";

}

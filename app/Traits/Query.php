<?php

namespace App\Traits;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

use Exception;

use Carbon\Carbon;
use App\GeneLib;

trait Query
{
    /**
     * Return a displayable for replication over time out of the SOP7 results
     *
     * @param
     * @return string
     */
	public static function query($query)
	{
		try {
			Log::info("Querying Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));
			$response = Genegraph::fetch($query);
			Log::info("Return from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));
			
		} catch (RequestException $exception) {	// guzzle exceptions and error responses from gql
	
			Log::info("Guzzle Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			$response = $exception->getResponse();
			if (is_null($response))				// empty reply from server
			{
				//GeneLib::putError($errors);
				
				// for now, just return an empty list
				return collect();
			}
			
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
			die("cp1");
			Log::info("Generic Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};

		return $response;
	}

}

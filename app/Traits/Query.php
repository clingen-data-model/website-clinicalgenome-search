<?php

namespace App\Traits;

use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectionException;

use EUAutomation\GraphQL\Exceptions\GraphQLMissingData;


use Exception;

use Carbon\Carbon;
use App\GeneLib;
use App\Minute;

trait Query
{
    /**
     * Return a displayable for replication over time out of the SOP7 results
     *
     * @param
     * @return string
     */
	public static function query($query, $method = '')
	{
		try {

			$begin = Carbon::now();
			$response = Genegraph::fetch($query);
			$end = Carbon::now();
			$record = new Minute([
				'system' => 'Search',
				'subsystem' => $method,
				'method' => 'query',
				'start' => $begin,
				'finish' => $end,
				'status' => 1

			]);
			$record->save();
			Log::info("Query Genegraph: From=" . $method . ", start=" . $begin->format('Y-m-d H:i:s.u')
						. ', end=' . $end->format('Y-m-d H:i:s.u'));
		} catch (RequestException $exception) {	// guzzle exceptions and error responses from gql

			Log::info("Guzzle Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			$response = $exception->getResponse();
			if (is_null($response))				// likely a connection error
			{
				$errors = $exception->getHandlerContext();
				GeneLib::putError($errors['error']);
				return null;
			}
	
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);

			if (is_array($errors) && isset($errors["errors"]))
			{
				// check if there is a message bag
				$messages = array_column($errors["errors"], 'message');
				if (is_array($messages))
					$errors = implode($messages);
			}

			GeneLib::putError($errors);
			
			return null;
		
		} catch (GraphQLMissingData $exception) {		// graphql one
	
			Log::info("Generic Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			$errors = $exception->getMessage();
			
			GeneLib::putError($errors);
			
			return null;
			
		} catch (Exception $exception) {		// everything else
	
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

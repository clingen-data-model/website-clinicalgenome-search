<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;

use Exception;

use Carbon\Carbon;
use App\GeneLib;
use App\Minute;

trait Query
{
	/**
     * Run query for a graphql call and format either response data or errors
     *
     * @param
     * @return string
     */
	public static function query($query, $method = '')
	{

		try {

			$url = env('GRAPHQL_ENDPOINT_URL', 'https://genegraph.prod.clingen.app/graphql');

			$begin = Carbon::now();	

			$client = new \GuzzleHttp\Client();
			$headers = [];
			$variables = [];

			$response = $client->request('POST', $url, [
				'json' => [
					'query' => $query,
					'variables' => $variables
				],
				'headers' => $headers
			]);

			if ($response->getStatusCode() != 200)
			{
				Log::info("Error from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

				GeneLib::putError("Failed to retrieve requested data");

				return null;
			}

			$end = Carbon::now();
			$record = new Minute([
				'system' => 'Search',
				'subsystem' => $method,
				'method' => 'query',
				'start' => $begin,
				'finish' => $end,
				'status' => 1

			]);

			if (env('APP_DEBUG', false))
				$record->save();

			Log::info("Query Genegraph: From=" . $method . ", start=" . $begin->format('Y-m-d H:i:s.u') . ', end=' . $end->format('Y-m-d H:i:s.u'));
		
		} catch (ConnectException $exception) {	// networking error

			Log::info("Guzzle ConnectException from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			GeneLib::putError("Cannot connect to Genegraph");
			
			return null;

		} catch (RequestException $exception) {	// 4XX and 5XX errors from genegraph

			Log::info("Guzzle RequestException from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			GeneLib::putError("Error receiving data from genegraph");
			
			return null;

		} catch (Exception $exception) {		// everything else
	
			Log::info("Generic Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			GeneLib::putError("Error communicating with genegraph");
			
			return null;
			
		};
		
		$responseJson = json_decode($response->getBody()->getContents(), false);

		return $responseJson->data;

	}

}

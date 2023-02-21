<?php

namespace App\Traits;

//use Alexaandrov\GraphQL\Facades\Client as Genegraph;
use BendeckDavid\GraphqlClient\Facades\GraphQL;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectionException;

//use EUAutomation\GraphQL\Exceptions\GraphQLMissingData;

use Illuminate\Support\Facades\Http;

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

			$response = Http::connectTimeout(180)->withHeaders([
				'Content-Type' => 'application/json',
			])->post($url, [
				'query' => $query
			]);

			if (!$response->successful())
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
			$record->save();
			Log::info("Query Genegraph: From=" . $method . ", start=" . $begin->format('Y-m-d H:i:s.u') . ', end=' . $end->format('Y-m-d H:i:s.u'));
		
		} catch (RequestException $exception) {	// guzzle exceptions and error responses from gql

			dd("in request exception");
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

		} catch (Exception $exception) {		// everything else
	dd($exception);
			Log::info("Generic Exception from Genegraph: " . Carbon::now()->format('Y-m-d H:i:s.u'));

			$response = $exception->getResponse();
			$code = $response->getStatusCode();
			$reason = $response->getReasonPhrase();
			$errors = json_decode($exception->getResponse()->getBody()->getContents(), true);
			
			GeneLib::putError($errors);
			
			return null;
			
		};
		
		$body = $response->body();

		$data = json_decode($body);

		return $data->data;

	}

}

<?php

namespace Sburina\Whmcs;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;

class Client
{
	/**
	 * @var GuzzleClient
	 */
	protected $client;

	/**
	 * Client constructor.
	 */
	public function __construct()
	{
		$options = [
			'timeout'     => 30,
			'debug'       => false,
			'http_errors' => true,
			'base_uri'    => rtrim(config('whmcs.url'), '/').'/includes/api.php',
			'verify'      => false,
			'headers'     => [
				'User-Agent' => 'sburina/laravel-whmcs-up',
			],
		];

		if (!empty(config('whmcs.api_access_key'))) $options['query'] = ['accesskey' => config('whmcs.api_access_key')];

		$this->client = new GuzzleClient($options);
	}

	/**
	 * @param  array  $data
	 *
	 * @return array
	 */
	public function post($data)
	{
		try {
			$fp = array_merge(
				config('whmcs.auth.'.config('whmcs.auth.type')),
				['responsetype' => 'json'],
				$data
			);

			$response = $this->client->post('', ['form_params' => $fp]);
			$contents = $response->getBody()->getContents();

			if (config('whmcs.use_floats', false)) {
				$contents = preg_replace('/":"(-?\d+\.\d\d)"/', '":\1', $contents);
			}

			return json_decode($contents, config('whmcs.result_as_array', true));
		} catch (ConnectException $e) {

			return ['result' => 'error', 'message' => $e->getMessage()];
		} catch (Exception $e) {

			return ['result' => 'error', 'message' => $e->getMessage()];
		}
	}
}

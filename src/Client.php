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
	 * Full URL to the WHMCS API endpoint.
	 *
	 * @var string
	 */
	protected $apiUrl;

	/**
	 * Client constructor.
	 */
	public function __construct()
	{
		$this->apiUrl = rtrim(config('whmcs.url'), '/') . '/includes/api.php';

		$options = [
			'timeout'     => 30,
			'debug'       => false,
			'http_errors' => true,
			'verify'      => config('whmcs.verify_ssl', true),
			'headers'     => [
				'User-Agent' => 'sburina/laravel-whmcs-up',
			],
		];

		if (!empty(config('whmcs.api_access_key'))) {
			$options['query'] = ['accesskey' => config('whmcs.api_access_key')];
		}

		$this->client = new GuzzleClient($options);
	}

	/**
	 * Send a POST request to the WHMCS API.
	 *
	 * @param  array  $data
	 * @return array|object
	 */
	public function post($data)
	{
		$asArray = config('whmcs.result_as_array', true);

		try {
			$authType = config('whmcs.auth.type');
			if (!in_array($authType, ['api', 'password'], true)) {
				return $this->errorResponse("Invalid WHMCS auth type: '{$authType}'. Must be 'api' or 'password'.", $asArray);
			}

			$authConfig = config('whmcs.auth.' . $authType);
			if (!is_array($authConfig)) {
				return $this->errorResponse("WHMCS auth config for '{$authType}' is not configured.", $asArray);
			}

			$fp = array_merge(
				$authConfig,
				['responsetype' => 'json'],
				$data
			);

			$response = $this->client->post($this->apiUrl, ['form_params' => $fp]);
			$contents = $response->getBody()->getContents();

			if (config('whmcs.use_floats', false)) {
				$contents = $this->convertFloats($contents);
			}

			$decoded = json_decode($contents, $asArray);

			if ($decoded === null && json_last_error() !== JSON_ERROR_NONE) {
				return $this->errorResponse('Invalid JSON response from WHMCS: ' . json_last_error_msg(), $asArray);
			}

			return $decoded;
		} catch (ConnectException $e) {
			return $this->errorResponse($e->getMessage(), $asArray);
		} catch (Exception $e) {
			return $this->errorResponse($e->getMessage(), $asArray);
		}
	}

	/**
	 * Build an error response matching the configured return type.
	 *
	 * @param  string  $message
	 * @param  bool    $asArray
	 * @return array|object
	 */
	protected function errorResponse($message, $asArray)
	{
		$error = ['result' => 'error', 'message' => $message];

		return $asArray ? $error : (object) $error;
	}

	/**
	 * Convert numeric string values to floats in JSON content.
	 * Parses JSON first, walks the structure, converts strings matching decimal patterns.
	 *
	 * @param  string  $contents
	 * @return string
	 */
	protected function convertFloats($contents)
	{
		$data = json_decode($contents, true);
		if (!is_array($data)) {
			return $contents;
		}

		array_walk_recursive($data, function (&$value) {
			if (is_string($value) && preg_match('/^-?\d+\.\d+$/', $value)) {
				$value = (float) $value;
			}
		});

		return json_encode($data);
	}
}

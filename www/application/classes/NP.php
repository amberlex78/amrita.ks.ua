<?php

/**
 * Class NP
 */
class NP
{
	public static $config = [
		'modelName'        => 'Address',
		'calledMethod'     => 'getCities',
		'methodProperties' => [
			'' => '',
		],
		'apiKey'           => '64353160cf1c70a1689b6667f50ada67',
	];

	/**
	 * @param $config
	 * @return bool|mixed
	 */
	public static function send(array $config)
	{
		$data = json_encode($config);

		$response = self::query($data);

		if ($response) {
			$response = (array)json_decode($response);
		} else {
		}

		return $response;
	}

	/**
	 * Запрос cURL
	 * @param $data
	 * @return bool|mixed
	 */
	protected static function query($data)
	{
		try {
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.novaposhta.ua/v2.0/json/');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: text/plain"]);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

			$response = curl_exec($ch);

			curl_close($ch);

			return $response;
		} catch (\Exception $e) {
			return false;
		}
	}
}
<?php namespace Awebsome\Serverpilot\Classes;

use Log;
use ValidationException;
use Awebsome\Serverpilot\Classes\ServerPilot;
use Awebsome\Serverpilot\Models\Settings as Conf;

class Api
{
    public $client_id;
    public $api_key;

	/**
	 * core request function
	 * ===================================================================
	 * used as the main communication layer between API and local code
	 *
	 * @param string $method defines the method for the request
	 * @return void
	 */
    public static function auth($client_id, $api_key)
    {
        $self = new Self;

        $self->client_id    = current($client_id);
        $self->api_key      = end($api_key);

        return $self;
    }

	public function request($resource=null, $data=null, $method=ServerPilot::SP_HTTP_METHOD_GET) {

        $auth = $this->client_id.':'.$this->api_key;

		$url = ServerPilot::SP_API_ENDPOINT .'/'. $resource;

		$ch = curl_init();
		$options = array(
			// general
			CURLOPT_URL => $url,
			CURLOPT_TIMEOUT => ServerPilot::SP_TIMEOUT,
			CURLOPT_USERAGENT => ServerPilot::SP_USERAGENT,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_ENCODING => 'gzip',

			// ssl
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,

			// auth
			CURLOPT_USERPWD => $auth,
			CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		);

		// handle the data
		switch($method) {
			case ServerPilot::SP_HTTP_METHOD_GET:
				if($data !== null && !empty($data)) $options[CURLOPT_URL] = $url . '?' . implode('&', $data);
			break;
			case ServerPilot::SP_HTTP_METHOD_POST:
				if($data === null || empty($data)) throw new Exception('Curl::request() - parameter 2 is required for method ServerPilot::SP_HTTP_METHOD_POST');

				$data = json_encode($data);

				$options[CURLOPT_CUSTOMREQUEST] = ServerPilot::SP_HTTP_METHOD_POST;
				$options[CURLOPT_POST] = TRUE;
				$options[CURLOPT_POSTFIELDS] = $data;

				$options[CURLOPT_HTTPHEADER] = array(
				    'Content-Type: application/json',
				    'Content-Length: ' . strlen($data)
				);
			break;
			case ServerPilot::SP_HTTP_METHOD_DELETE:
				$options[CURLOPT_CUSTOMREQUEST] = ServerPilot::SP_HTTP_METHOD_DELETE;
			break;
		}

		// set the options
		curl_setopt_array($ch, $options);

		// response
        $response = curl_exec($ch);
		$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		// check for common errors
		switch ($status_code) {
			case 200: break;
			case 400:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code, ['data' => json_encode($data, JSON_PRETTY_PRINT)]);
                break;
			case 401:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code);
                break;
			case 402:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code);
                break;
			case 403:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code);
                break;
			case 404:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code, ['data' => json_encode($data, JSON_PRETTY_PRINT)]);
                break;
			case 409:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code);
                break;
			case 500:
                    $error = trans('awebsome.serverpilot::lang.error.'.$status_code, ['data' => json_encode($data, JSON_PRETTY_PRINT)]);
                break;
			default:  break;
		}

		// close connection
        curl_close($ch);

        //debug Log::info($response);

        if($status_code != 200)
        {
            if($method == ServerPilot::SP_HTTP_METHOD_POST || $method == ServerPilot::SP_HTTP_METHOD_DELETE)
            {
                if(Conf::get('log_errors'))
                Log::error($error);

                throw new ValidationException(['error_mesage' => $error]);
            }else return json_decode(json_encode(['error' => ['code' => $status_code, 'message' => $error]]));
        } else return json_decode($response);
	}
}

/**
 * ServerPilot Exceptions
 */
class Exception extends \Exception {
    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) {
        $message = '[ServerPilot]: ' . $message;

        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}

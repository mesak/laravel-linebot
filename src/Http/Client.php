<?php

namespace Mesak\LineBot\Http;

use LINE\LINEBot\HTTPClient as ContractsHttpClient;
use LINE\LINEBot\HTTPClient\CurlHTTPClient as HttpClient;
use Illuminate\Support\Facades\Http;
use Mesak\LineBot\Exception\RequestException;

/**
 * Class CurlHTTPClient.
 *
 * A HTTPClient that uses cURL.
 *
 * @package LINE\LINEBot\HTTPClient
 */
class Client implements ContractsHttpClient
{
  protected $client;
  /**
   * CurlHTTPClient constructor.
   *
   * @param string $channelToken Access token of your channel.
   */
  public function __construct($channelToken = null)
  {
    if ($channelToken) {
      $this->setToken($channelToken);
    }
  }

  public function setToken($channelToken)
  {
    $this->client = new HttpClient($channelToken);
  }

  public function get($url, array $data = [], array $headers = [])
  {
    return $this->handleResponse($this->client->get($url, $data, $headers));
  }

  /**
   * Sends POST request to LINE Messaging API.
   *
   * @param string $url Request URL.
   * @param array $data Request body or resource path.
   * @param array|null $headers Request headers.
   * @return Response Response of API request.
   * @throws CurlExecutionException
   */
  public function post($url, array $data, array $headers = null)
  {
    return $this->handleResponse($this->client->post($url, $data, $headers));
  }

  /**
   * Sends PUT request to LINE Messaging API.
   *
   * @param string $url Request URL.
   * @param array $data Request body.
   * @param array|null $headers Request headers.
   * @return Response Response of API request.
   */
  public function put($url, array $data, array $headers = null)
  {
    return $this->handleResponse($this->client->put($url, $data, $headers));
  }

  /**
   * Sends DELETE request to LINE Messaging API.
   *
   * @param string $url Request URL.
   * @return Response Response of API request.
   * @throws CurlExecutionException
   */
  public function delete($url)
  {
    return $this->handleResponse($this->client->delete($url));
  }

  /**
   * set curl timeout second
   *
   * @param int|null $timeout timeout(sec)
   */
  public function setTimeout($timeout): void
  {
    $this->client->setTimeout($timeout);
  }

  /**
   * set curl connect timeout second
   *
   * @param int|null $connectTimeout connectTimeout(sec)
   */
  public function setConnectTimeout($connectTimeout): void
  {
    $this->client->setConnectTimeout($connectTimeout);
  }

  public function handleResponse($response)
  {
    return new Response($response);
  }

  /**
   * Get the access token response for the given code.
   *
   * @param  string  $code
   * @return array|RequestException
   */
  public static function getAccessToken(String $client_id, String $client_secret): array|RequestException
  {
    return tap( Http::asForm()->post(\LINE\LINEBot::DEFAULT_ENDPOINT_BASE . '/v2/oauth/accessToken', [
      'grant_type' => 'client_credentials',
      'client_id' => $client_id,
      'client_secret' => $client_secret
    ]) , function ($response) {
      if( $response->failed() ) {
        throw new RequestException($response);
      }
    })->json();
  }
}

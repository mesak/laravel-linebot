<?php

namespace Mesak\LineBot\Exception;

use Illuminate\Http\Client\HttpClientException;
use Illuminate\Http\Client\Response;

class RequestException extends HttpClientException
{
  /**
   * The response instance.
   *
   * @var \Illuminate\Http\Client\Response
   */
  public $response;

  /**
   * Create a new exception instance.
   *
   * @param  \Illuminate\Http\Client\Response  $response
   * @return void
   */
  public function __construct(Response $response)
  {
    $data = $response->json();

    $message = data_get($data, 'message') ?? data_get($data, 'error');

    parent::__construct($message, $response->status());

    $this->response = $response;
  }
}

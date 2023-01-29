<?php

namespace Mesak\LineBot\Http;

use Illuminate\Http\Client\Response as BaseResponse;

class Response extends BaseResponse
{

  /**
   * Get the status code of the response.
   *
   * @return int
   */
  public function status()
  {
    return (int) $this->response->getHTTPStatus();
  }

  /**
   * Create an exception if a server or client error occurred.
   *
   * @return \Illuminate\Http\Client\RequestException|null
   */
  public function toException()
  {
    if ($this->failed()) {
      return new \Exception($this->json('message'));
    }
  }
  /**
   * Get the body of the response.
   *
   * @return string
   */
  public function body()
  {
    return (string) $this->response->getRawBody();
  }
}

<?php

namespace Mesak\LineBot\Http;

use Illuminate\Http\Client\Response as BaseResponse;
use Mesak\LineBot\Exception\RequestException;

class Response extends BaseResponse
{

  /**
   * Get the status code of the response.
   *
   * @return int
   */
  public function status(): int
  {
    return (int) $this->response->getHTTPStatus();
  }

  /**
   * Create an exception if a server or client error occurred.
   *
   * @return \Illuminate\Http\Client\RequestException|null
   */
  public function toException(): ?RequestException
  {
    if ($this->failed()) {
      return new RequestException($this);
    }
  }
  /**
   * Get the body of the response.
   *
   * @return string
   */
  public function body(): string
  {
    return (string) $this->response->getRawBody();
  }
}

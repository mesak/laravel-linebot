<?php

namespace Mesak\LineBot;

use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\Exception\InvalidSignatureException;
use LINE\LINEBot\Exception\InvalidEventRequestException;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Contracts\Events\Dispatcher;

use Mesak\LineBot\Http\Client;
use Mesak\LineBot\Contracts\Bot as BotContract;

class EntityBot extends LINEBot implements BotContract
{

  protected $config;

  protected $httpClient;

  protected $channelId;

  protected $channelSecret;

  /**
   * The event dispatcher instance.
   *
   * @var \Illuminate\Contracts\Events\Dispatcher
   */
  protected $events;

  protected static $eventRequestParser;

  public function __construct(array $config, Dispatcher $events)
  {

    $this->events = $events;

    $this->config = $config;

    $this->httpClient = new Client();

    $this->channelId = Arr::get($this->config, 'line.client_id', null);

    $this->channelSecret = Arr::get($this->config, 'line.client_secret', null);
    
    self::$eventRequestParser = Arr::get($this->config, 'line.event_parser', 'Mesak\LineBot\Events\EventRequestParser');

    parent::__construct($this->httpClient, ['channelSecret' => $this->channelSecret]);
  }

  /**
   * boot function
   *
   * @return void
   */
  public function boot(): void
  {

    if (is_null($this->channelId) || is_null($this->channelSecret)) {

      throw new \Exception('LINE Client ID or Channel Secret is not set.');
    }

    $channelToken = Cache::get('line.channel_token');

    if (is_null($channelToken)) {

      $response = Client::getAccessToken($this->channelId, $this->channelSecret);

      $channelToken = Arr::get($response, 'access_token', null);

      $expiresIn = Arr::get($response, 'expires_in', null);

      if (is_null($channelToken) || is_null($expiresIn)) {

        throw new \Exception('LINE Channel Token is not set.');

      }

      $expiresAt = Carbon::now()->addSeconds($expiresIn - 43200); //2592000

      Cache::put('line.channel_token', $channelToken, $expiresAt);
      //2592000 is 30 days
      //12hr = 43200
    }
    $this->httpClient->setToken($channelToken);
  }

  /**
   * initzalize event
   *
   * @return void
   */
  protected function initEvent(): void
  {

    Event::listen('Mesak\LineBot\Events\MessageEvent', [\Mesak\LineBot\Facades\Listener::class, 'handle']);

  }

  /**
   * Handle an incoming request.
   *
   * @param Request $request
   * @return void
   */
  public function handle(Request $request): void
  {
    /* 註冊事件 */
    $this->initEvent();

    $signature = $request->header(\LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE);

    $body = $request->getContent();

    // $requestEvents = parent::parseEventRequest($body, $signature, false);

    foreach ($this->parseEventRequest($body, $signature) as $event) {

      $this->events->dispatch($event);

    };
  }

  /**
   * Parse event request.
   *
   * @param string $body
   * @param string $signature
   * @param bool $eventOnly
   * @return array
   * @throws InvalidSignatureException
   * @throws InvalidEventRequestException
   */
  public function parseEventRequest($body, $signature, $eventOnly = true): array
  {

    if (trim($signature) === '') {

      throw new InvalidSignatureException('Request does not contain signature');

    }

    if (!SignatureValidator::validateSignature($body, $this->channelSecret, $signature)) {

      throw new InvalidSignatureException('Invalid signature has given');

    }

    $parseResult = json_decode($body, true);

    if (is_null($parseResult)) {

      throw new InvalidEventRequestException('Invalid request body has given');

    }

    if (!isset($parseResult['events'])) {

      throw new InvalidEventRequestException('Request does not contain events property');

    }
    // return parent::parseEventRequest($body, $signature, false);
    return self::$eventRequestParser::parseEventRequest($parseResult, $eventOnly);
  }

  /**
   * Send message to user
   *
   * @param String $target
   * @param MessageBuilder $message
   * @return void
   */
  public function execute(String $type, String $target, MessageBuilder $message): void
  {

    if ($type == 'reply') {

      $replyResponse = $this->replyMessage($target, $message);

      if( $replyResponse->isSucceeded() ){

        // echo 'Succeeded!';

      }

    }

  }

}

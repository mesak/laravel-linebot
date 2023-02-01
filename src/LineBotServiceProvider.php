<?php

namespace Mesak\LineBot;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Mesak\LineBot\Contracts\Bot as BotContract;
use Mesak\LineBot\EntityBot;

class LineBotServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    if ($this->app->runningInConsole()) {
      $this->publishes([
        __DIR__ . '/../config/linebot.php' => config_path('linebot.php'),
      ], 'mesak-linebot.config');
      
      $this->publishes([
        __DIR__ . '/Listener/SimpleListener.php' => config_path('line.php'),
      ],'mesak-linebot.listener');
    }
    $this->registerBotEvent();
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //註冊 config 檔案
    $this->mergeConfigFrom(__DIR__ . '/../config/linebot.php', 'line');
    $this->registerSingleton();
  }

  /**
   * Register bot singleton
   *
   * @return void
   */
  public function registerSingleton()
  {
    $this->app->singleton(BotContract::class, function ($app) {
      return tap(new EntityBot(config('line') , $app['events']) ,function($bot){
        $bot->boot();
      });
    });
  }
  
  /**
   * Register Bot Event
   *
   * @return void
   */
  public function registerBotEvent(){
    //subscribe
    // $this->app->make(BotContract::class)->subscribe();
    Event::subscribe(Listener\BotEventSubscribe::class);
  }
}

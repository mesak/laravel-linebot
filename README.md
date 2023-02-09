# Laravel LINEBOT

採用 laravel response 的方式來回應 LINEBOT 的訊息

## 安裝

```bash
composer require mesak/laravel-linebot
```

## 設定檔替換

將設定檔複製到專案中

```bash
php artisan vendor:publish --tag=mesak-linebot.config --force
```

修改 `config/linebot.php` 中的設定

```php
    'listener' => 'App\Listeners\LineBotListener',
```


## 使用

利用標準檔案 `App\Listeners\LineBotListener` 來建立 LINEBOT 的 Listener

```bash
php artisan vendor:publish --tag=mesak-linebot.listener
```

`LineBotListener.php`

LINEBOT 預設的事件：

- onMessage
- onUnsend
- onFollow
- onUnfollow
- onJoin
- onLeave
- onPostback
- onVideoPlayComplete
- onBeacon
- onAccountLink
- onMemberJoined
- onMemberLeft
- onThings


## 設定

在 `.env` 中加入

```env
LINE_CLIENT_ID=xxxxxxxx
LINE_CLIENT_SECRET=xxxxxxxx
```

### Event 事件

套件利用處理 Request 把內容塞入 `Mesak\LineBot\Events\MessageEvent` 中，利用 BotEventSubscribe 發起對話事件，最後回傳 `Mesak\LineBot\Actions\BaseAction` 的事件來處理需要的回應


### 擴充

如果需要擴充 LINEBOT 的功能，可以在 `App\Providers\AppServiceProvider` 中的 register 加入 `\Mesak\LineBot\Contracts\Bot::class` 合約綁定

```php

    $this->app->singleton(\Mesak\LineBot\Contracts\Bot::class, function ($app) {
        return tap(new \App\Services\EntityBot(config('linebot') , $app['events']) ,function($bot){
            $bot->boot();
        });
    });

```

自訂 `EntityBot` 類別

```php
<?php

namespace App\Services\LineBot;

use Mesak\LineBot\EntityBot as BaseEntityBot;
use Mesak\LineBot\Contracts\Bot as BotContract;

class EntityBot extends BaseEntityBot implements BotContract
{
  //do something...
}

```

如果要從外部呼叫 Bot，可以使用 Facades 靜態介面

舉例：
```
$response = \Facades\Mesak\LineBot\Contracts\Bot::getProfile($userId);
```
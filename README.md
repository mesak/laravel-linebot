# Laravel LINEBOT

採用 laravel response 的方式來回應 LINEBOT 的訊息

## 安裝

```bash
composer require mesak/laravel-linebot
```


## 設定檔替換

將設定檔複製到專案中

```bash
php artisan vendor:publish --tag=mesak-linebot --force
```

修改 `config/linebot.php` 中的設定

```php
    'listener' => 'App\Listeners\LineBotListener',
```


## 使用

利用標準檔案  `Mesak\LineBot\Listener\SimpleListener` 來建立 LINEBOT 的 Listener

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


### 設定

在 `.env` 中加入

```env
LINE_CLIENT_ID=xxxxxxxx
LINE_CLIENT_SECRET=xxxxxxxx
```

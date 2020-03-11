## Install

- `cd server && composer install`

## Start

`php app.php`

## Apache2 config

```
<VirtualHost 80.211.53.42:80>
    ServerName ws.coolhd.hu

    ProxyRequests On
    ProxyPass           /   ws://127.0.0.1:2053
    ProxyPassReverse    /   ws://127.0.0.1:2053
</VirtualHost>

<VirtualHost 80.211.53.42:443>
    ServerName ws.coolhd.hu

    ProxyRequests On
    ProxyPass           /   ws://127.0.0.1:2053
    ProxyPassReverse    /   ws://127.0.0.1:2053
</VirtualHost>

```

With WS, you won't have issues with CORS, but you need to use websocket with SSL. Cloudflare supports ws ;)

### Source code & examples:

https://www.twilio.com/blog/create-php-websocket-server-build-real-time-even-driven-application
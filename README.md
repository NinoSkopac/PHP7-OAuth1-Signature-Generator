Request Token Example

```php
$requestURL = 'TOKEN REQUEST ENDPOINT';

$OAuth = new OAuth1_Utils;

$OAuth
->setConsumerKey('CONSUMER KEY')
->setCallback('REDIRECT URL')
->setSignature($OAuth->createSignature(
    $requestURL,
    'CONSUMER SECRET',
    null, // token secret doesn't exist yet as this is request token stage
    'POST'
));

// make a request with CURL using constructed OAuth1 payload
$curl = curl_init($requestURL . '?' . http_build_query($OAuth->getPayload()));

// or if you only need the OAuth1 signature:
$signature = $OAuth->getSignature();
```

For more examples, go to the /examples folder.

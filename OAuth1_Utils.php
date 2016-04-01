<?php
/**
 * @author Nino Škopac <nino@recgr.com>
 * @copyright 2016 Nino Škopac
 * @license whatever lol
 * @version 0.1
 * @summary OAuth1 signature generator, PHP7 style.
*/
declare(strict_types=1);
namespace Recgr;

class OAuth1_Utils extends OAuth1 {
    /**
     * Constructor
     */
    public function __construct() {
        return $this;
    }
    
    /**
     * Invoker (if class is accessed as function)
     */
    public function __invoke() : self {
        return $this;
    }
    
    /**
     * OAuth1 signature algorithm.
     *
     * PHP7 Implementation of https://dev.twitter.com/oauth/overview/creating-signatures
     *
     * @param $requestURL string Request URL without query string. Example: https://www.upwork.com/api/profiles/v1/providers/~013469808662f577d0.json
     * @param $consumerSecret string Consumer secret token. Example: 2o19udj939jf10sd
     * @param $tokenSecret string|null Token secret or null if you don't have it yet. Example: 8jf2sm2c3z6aa22
     * @param $requestMethod string GET, POST, PUT, DELETE, etc
     * @param $hashAlgo string (optional) Hash algo. sha1 by default.
     *
     * @return string OAuth Signature
     */
    public function createSignature(
        string $requestURL,
        string $consumerSecret,
        $tokenSecret,
        string $requestMethod,
        string $hashAlgo = 'sha1'
    ) : string {
        $this->refreshPayload();
        
        $payload = $this->getPayload();
        
        array_walk($payload, function(&$value, &$key) {
            $key = rawurlencode($key);
            $value = rawurlencode((string) $value);
        });
        
        ksort($payload);
        
        // http_build_query won't work here
        $query = '';
        
        foreach ($payload as $key => $value) {
            $query .= $key;
            $query .= '=';
            $query .= $value;
            $query .= '&';
        }
        
        $query = rtrim($query, '&');
        
        $signatureBase = strtoupper($requestMethod) . '&' . rawurlencode($requestURL) . '&' . rawurlencode($query);
        
        $signingKey = rawurlencode($consumerSecret) . '&' . (isset($tokenSecret) ? rawurlencode($tokenSecret) : '');
        
        return base64_encode(hash_hmac($hashAlgo, $signatureBase, $signingKey, true));
    }
}

<?php
die("I was too lazy to do a real examples file so I just cp'ed this bad boy");
declare(strict_types=1);
namespace Recgr;

class Upwork {
    const Endpoints = [
        'requestToken'   => '/auth/v1/oauth/token/request',
        'authentication' => '/services/api/auth',
        'accessToken'    => '/auth/v1/oauth/token/access',
        'profile'        => '/profiles/v1/providers/'
    ];
    
    public function getTokens(string $response, array $tokenNames) : array {
        parse_str($response, $tokens);
        
        foreach ($tokenNames as $tokenName) {
            if (!isset($tokens[$tokenName])) {
                throw new Exception($tokenName . ' is not set. ');
            }
        }
        
        return $tokens;
    }
    
    public function requestToken() : string {
        $requestURL = Config::Upwork_API['endpoint'] . self::Endpoints['requestToken'];
        
        $OAuth = new OAuth1_Utils;
        
        $OAuth
        ->setConsumerKey(Config::Upwork_API['consumerKey'])
        ->setCallback(Config::Upwork_API['authCallback'])
        ->setSignature($OAuth->createSignature(
            $requestURL,
            Config::Upwork_API['consumerSecret'],
            null,
            'POST'
        ));
        
        try {
            return Wrappers::curl($requestURL . '?' . http_build_query($OAuth->getPayload()), 'POST');
        } catch (Exception $e) {
            $traces = Common::traces($e->getMessage(), null, __METHOD__, $e->getTraces());
            
            throw new Exception('Requesting the token was unsuccessful. ', 0, null, $traces);
        }
    }
    
    public function getVerifier(string $OAuthToken) {
        $url = Config::Upwork_API['site'] . self::Endpoints['authentication'] . '?oauth_token=' . $OAuthToken;
        
        header ("location: $url");
        die();
    }
    
    public function getAccessToken(string $OAuthToken, string $OAuthVerifier, string $OAuthTokenSecret) : string {
        $requestURL = Config::Upwork_API['endpoint'] . self::Endpoints['accessToken'];
        
        $OAuth = new OAuth1_Utils;
        
        $OAuth
        ->setConsumerKey(Config::Upwork_API['consumerKey'])
        ->setOAuthToken($OAuthToken)
        ->setVerifierToken($OAuthVerifier)
        ->setSignature($OAuth->createSignature(
            $requestURL,
            Config::Upwork_API['consumerSecret'],
            $OAuthTokenSecret,
            'POST'
        ));
        
        try {
            return Wrappers::curl($requestURL . '?' . http_build_query($OAuth->getPayload()), 'POST');
        } catch (Exception $e) {
            $traces = Common::traces($e->getMessage(), null, __METHOD__, $e->getTraces());
            
            throw new Exception('Getting the access token was unsuccessful. ', 0, null, $traces);
        }
    }
    
    public function getProfileDetails(string $profileKey) : string {
        $requestURL = Config::Upwork_API['endpoint'] . self::Endpoints['profile'] . $profileKey . '.json';
        
        $OAuth = new OAuth1_Utils;
        
        $OAuth
        ->setConsumerKey(Config::Upwork_API['consumerKey'])
        ->setAccessToken(Config::Upwork_API['accessToken'])
        ->setSignature($OAuth->createSignature(
            $requestURL,
            Config::Upwork_API['consumerSecret'],
            Config::Upwork_API['tokenSecret'],
            'GET'
        ));
        
        try {
            return Wrappers::curl($requestURL . '?' . http_build_query($OAuth->getPayload()));
        } catch (Exception $e) {
            $traces = Common::traces($e->getMessage(), null, __METHOD__, $e->getTraces());
            
            throw new Exception('Getting profile details was unsuccessful. ', 0, null, $traces);
        }
    }
}

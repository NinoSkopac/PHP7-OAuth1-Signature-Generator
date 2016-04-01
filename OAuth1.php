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

class OAuth1 {
    private $payload = [
        'oauth_consumer_key'     => null,
        'oauth_nonce'            => null,
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_timestamp'        => null,
        'oauth_version'          => '1.0'
    ];
    
    /**
     * Gets OAUth payload
     */
    public function getPayload() : array {
        return $this->payload;
    }
    
    /**
     * Sets consumer key
     */
    public function setConsumerKey(string $consumerKey) : self {
        $this->payload['oauth_consumer_key'] = $consumerKey;
        
        return $this;
    }
    
    /**
     * Sets oauth_token
     */
    public function setOAuthToken(string $OAuthToken) : self {
        $this->payload['oauth_token'] = $OAuthToken;
        
        return $this;
    }
    
    /**
     * Sets access_token
     */
    public function setAccessToken(string $accessToken) : self {
        return $this->setOAuthToken($accessToken);
    }
    
    /**
     * Sets verifier token
     */
    public function setVerifierToken(string $verifierToken) : self {
        $this->payload['oauth_verifier'] = $verifierToken;
        
        return $this;
    }
    
    /**
     * Sets oauth_signature
     */
    public function setSignature(string $signature) : self {
        $this->payload['oauth_signature'] = $signature;
        
        return $this;
    }
    
    /**
     * Gets oauth_signature
     */
    public function getSignature() : string {
        return $this->payload['oauth_signature'] ?? '';
    }
    
    /**
     * Sets oauth_callback
     */
    public function setCallback(string $callbackUrl) : self {
        $this->payload['oauth_callback'] = $callbackUrl;
        
        return $this;
    }
    
    /**
     * Sets oauth_signature_method
     */
    public function setSignatureMethod(string $hashAlgo) : self {
        $this->payload['oauth_signature_method'] = $hashAlgo;
        
        return $this;
    }
    
    /**
     * Set arbitrary payload param
     */
    public function setArbitrary(string $fieldName, string $fieldValue) : self {
        $this->payload[$fieldName] = $fieldValue;
        
        return $this;
    }
    
    /**
     * Unset arbitrary payload param
     */
    public function unsetArbitrary(string $fieldName) : self {
        unset($this->payload[$fieldName]);
        
        return $this;
    }
    
    /**
     * Updates oauth_timestamp and oauth_nonce.
     */
    public function refreshPayload() : self {
        $this->payload['oauth_nonce'] = bin2hex(random_bytes(32));
        $this->payload['oauth_timestamp'] = time();
        
        return $this;
    }
}

<?php

namespace App\Encoder;

use JWT\Authentication\JWT;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTDecodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\JWTEncodeFailureException;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

class APIJWTEncoder implements JWTEncoderInterface
{

    private $key;

    /**
     * @param string $key
     */
    public function __construct(string $key = 'super_secret_key')
    {
        $this->key = $key;
    }

    /**
     * @inheritDoc
     */
    public function encode(array $data): string
    {
        try {
            return JWT::encode($data, $this->key);
        }catch (\Exception $e){
            throw new JWTEncodeFailureException(JWTEncodeFailureException::INVALID_CONFIG, 'An error occurred while trying to encode the JWT token.', $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function decode($token): array
    {
        try {
            return (array) JWT::decode($token, $this->key);
        }catch (\Exception $e){
            throw new JWTDecodeFailureException(JWTDecodeFailureException::INVALID_TOKEN, 'Invalid JWT Token', $e);
        }
    }
}
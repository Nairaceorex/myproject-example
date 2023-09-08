<?php

namespace App\Repositories;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Laravel\Passport\Bridge\RefreshTokenRepository as PassportRefreshTokenRepository;
use Laravel\Passport\TokenRepository as PassportTokenRepository;
use Laravel\Passport\Passport;

class TokenRepository extends PassportTokenRepository
{
    /**
     * {@inheritdoc}
     */
    public function getNewToken(ClientEntityInterface $clientEntity, array $scopes, $userIdentifier = null)
    {
        return parent::getNewToken($clientEntity, $scopes, $userIdentifier)
            ->setIdentifier($this->generateUniqueIdentifier(40));
    }

    /**
     * {@inheritdoc}
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $accessTokenEntity)
    {
        $this->deleteExistingAccessTokens($accessTokenEntity);

        return parent::persistNewAccessToken($accessTokenEntity);
    }

    /**
     * {@inheritdoc}
     */
    public function revokeAccessToken($tokenId)
    {
        $accessToken = $this->accessToken->find($tokenId);
        if ($accessToken) {
            $this->refreshToken->revokeRefreshTokensByAccessTokenId($tokenId);
            $accessToken->revoke();
        }
    }
}
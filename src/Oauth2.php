<?php
/**
 * User: Aleksandrov Artem
 * Date: 22.10.2019
 * Time: 20:09
 */

namespace dugard\myTarget\api\oauth2;

use GuzzleHttp\Client;
use dugard\myTarget\api\oauth2\grant\AgencyCredentialsGrant;
use dugard\myTarget\api\oauth2\grant\AuthorizationCodeGrant;
use dugard\myTarget\api\oauth2\grant\Authorize;
use dugard\myTarget\api\oauth2\grant\ClientCredentialsGrant;
use dugard\myTarget\api\oauth2\grant\RefreshToken;
use dugard\myTarget\api\oauth2\token\DeleteToken;

/**
 * Class Oauth2
 * @package dugard\myTarget\api\oauth2
 * @link https://target.my.com/adv/api-marketing/doc/authorization
 */
class Oauth2
{
    /** @var Transport */
    private $transport;

    /**
     * Oauth2 constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $base_uri = @$config['sandbox'] ? 'https://target-sandbox.my.com/api/v2/oauth2/' : 'https://target.my.com/api/v2/oauth2/';
        $client = new Client([
            'base_uri' => $base_uri,
            'http_errors' => false,
            'debug' => @$config['debug'],
        ]);
        $this->transport = new Transport($client, $config);
    }

    /**
     * Client Credentials Grant используется для работы с данными собственного аккаунта через API
     * @param string $client_id
     * @param string $client_secret
     * @return ClientCredentialsGrant
     */
    public function clientCredentialsGrant(string $client_id, string $client_secret): ClientCredentialsGrant
    {
        return new ClientCredentialsGrant($this->transport, $client_id, $client_secret);
    }

    /**
     * Agency Client Credentials Grant используется для работы с данными собственных клиентов агентств\менеджеров
     * @param string $client_id
     * @param string $client_secret
     * @param string $agency_client_name
     * @param string $access_token
     * @return AgencyCredentialsGrant
     */
    public function agencyCredentialsGrant(
        string $client_id,
        string $client_secret,
        string $agency_client_name,
        string $access_token = ""): AgencyCredentialsGrant
    {
        return new AgencyCredentialsGrant($this->transport, $client_id, $client_secret, $agency_client_name, $access_token);
    }

    /**
     * Authorization Code Grant используется для получения доступа к данным сторонних аккаунтов myTarget
     * @param string $code
     * @param string $client_id
     * @return AuthorizationCodeGrant
     */
    public function authorizationCodeGrant(string $code, string $client_id): AuthorizationCodeGrant
    {
        return new AuthorizationCodeGrant($this->transport, $code, $client_id);
    }

    /**
     * Запрос на получение кода, который будет отправлен по адресу заданному параметром "redirect_uri" при регистрации клиента
     * @param string $client_id
     * @param string $state
     * @param string $scopes
     * @return Authorize
     */
    public function authorize(string $client_id, string $state, string $scopes): Authorize
    {
        return new Authorize($this->transport, $client_id, $state, $scopes);
    }

    /**
     * Обновление токена доступа
     * @param string $refresh_token
     * @param string $client_id
     * @param string $client_secret
     * @return RefreshToken
     */
    public function refreshToken(string $refresh_token, string $client_id, string $client_secret): RefreshToken
    {
        return new RefreshToken($this->transport, $refresh_token, $client_id, $client_secret);
    }

    /**
     * При достижении лимита на количество токенов можно самостоятельно удалить все токены конкретного пользователя
     * @param string $client_id
     * @param string $client_secret
     * @param string $username
     * @return DeleteToken
     */
    public function deleteToken(string $client_id, string $client_secret, string $username = ""): DeleteToken
    {
        return new DeleteToken($this->transport, $client_id, $client_secret, $username);
    }
}
<?php

namespace Tests\Feature\Controllers\Api\V1\Auth;

use Tests\ApiTestCase;

/**
 * AccessTokenController test class.
 * 
 * @author Valuto <tech@valuto.io>
 */
class AccessTokenControllerTest extends ApiTestCase
{
    /**
     * Test access token for 'vlumarketusers' client.
     *
     * @return void
     */
    public function testVluMarketUsers()
    {
        $this->createTestUser();

        $params = [
            'grant_type' => 'password',
            'client_id' => 'vlumarketusers',
            'client_secret' => env('API_VLUMARKET_USERS_CLIENT_SECRET'),
            'scope' => '',
            'username' => $this->testUser->username,
            'password' => $this->testUser->password,
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);

        $decoded = json_decode((string)$response->getBody());

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
        $this->assertTrue(isset($decoded->token_type));
        $this->assertTrue(isset($decoded->expires_in));
        $this->assertTrue(isset($decoded->access_token));
        $this->assertTrue(isset($decoded->refresh_token));
    }

    /**
     * Test access token for 'vlumarketusers' client with wrong secret.
     *
     * @expectedException GuzzleHttp\Exception\ClientException
     * @expectedExceptionMessageRegExp /client_secret_incorrect/
     * @return void
     */
    public function testVluMarketUsersWrongSecret()
    {
        $this->createTestUser();

        $params = [
            'grant_type' => 'password',
            'client_id' => 'vlumarketusers',
            'client_secret' => 'wrong-secret',
            'scope' => '',
            'username' => $this->testUser->username,
            'password' => $this->testUser->password,
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);
    }


    /**
     * Test access token for 'vlumarketusers' client with wrong user password.
     *
     * @expectedException GuzzleHttp\Exception\ClientException
     * @expectedExceptionMessageRegExp /invalid_credentials/
     * @return void
     */
    public function testVluMarketUsersWrongPassword()
    {
        $this->createTestUser();

        $params = [
            'grant_type' => 'password',
            'client_id' => 'vlumarketusers',
            'client_secret' => env('API_VLUMARKET_USERS_CLIENT_SECRET'),
            'scope' => '',
            'username' => $this->testUser->username,
            'password' => 'wrong-password',
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);
    }

    /**
     * Test access token for 'vlumarketsystem' client.
     *
     * @return void
     */
    public function testVluMarketSystem()
    {
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => 'vlumarketsystem',
            'client_secret' => env('API_VLUMARKET_SYSTEM_CLIENT_SECRET'),
            'scope' => '',
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);

        $decoded = json_decode((string)$response->getBody());

        $this->assertEquals($response->getStatusCode(), 200);
        $this->assertEquals(json_last_error(), JSON_ERROR_NONE);
        $this->assertTrue(isset($decoded->token_type));
        $this->assertTrue(isset($decoded->expires_in));
        $this->assertTrue(isset($decoded->access_token));
        $this->assertFalse(isset($decoded->refresh_token));
    }
    
    /**
     * Test access token for 'vlumarketsystem' client with wrong secret.
     *
     * @expectedException GuzzleHttp\Exception\ClientException
     * @expectedExceptionMessageRegExp /client_secret_incorrect/
     * @return void
     */
    public function testVluMarketSystemWrongSecret()
    {
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => 'vlumarketsystem',
            'client_secret' => 'wrong-secret',
            'scope' => '',
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);
    }

    /**
     * Test error on non-existing client with 'client_credentials' grant type.
     *
     * @expectedException GuzzleHttp\Exception\ClientException
     * @expectedExceptionMessageRegExp /client_not_found/
     * @return void
     */
    public function testNonExistingClientClientCredentials()
    {
        $params = [
            'grant_type' => 'client_credentials',
            'client_id' => 'not-existing-client',
            'client_secret' => 'random-password',
            'scope' => '',
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);
    }

    /**
     * Test error on non-existing client with 'password' grant type.
     *
     * @expectedException GuzzleHttp\Exception\ClientException
     * @expectedExceptionMessageRegExp /client_not_found/
     * @return void
     */
    public function testNonExistingClientPassword()
    {
        $params = [
            'grant_type' => 'password',
            'client_id' => 'not-existing-client',
            'client_secret' => 'random-password',
            'scope' => '',
            'username' => 'random-username',
            'password' => 'random-password',
        ];

        $response = $this->http->post('/api/v1/access-token', [
            'headers' => $this->defaultHeaders(),
            'form_params' => $params,
        ]);
    }

}
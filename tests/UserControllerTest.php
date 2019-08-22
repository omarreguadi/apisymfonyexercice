<?php

namespace App\tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
        $client = static::createClient();
        $client->request('GET', '/api/users', [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/json',
            'HTTP_X-AUTH-TOKEN' => 'admin', //a modifier (apiKey) selon le test du user qu'on essayer
        ]);
        // http_accept : header
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        // verifie la reponse
        $this->assertJson($content);
        // verifie que le contenu est bien du json
        $arrayContent = \json_decode($content, true);
        $this->assertCount(10, $arrayContent);
    }

    public function testPostUsers()
    {
        $client = static::createClient();
        $client->request('POST', '/api/users', [], [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
            ],
            // => headers
            '{"email": "test@test.com","firstname":"test", "lastname":"test","birthday":"2017-05-12 00:00:00"}'
        );
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($content);
    }

    public function testPatchUsers()
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/patch/users/1', [], [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => 'admin',
            ],
            // => headers
            '{"firstname":"toto"}'
        );
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($content);
    }


    public function testPatchUsersWithWrongURL()
    {
        $client = static::createClient();
        $client->request('PATCH', '/api/patch/users', [], [],
            [
                'HTTP_ACCEPT' => 'application/json',
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-AUTH-TOKEN' => 'admin',
            ],
            // => headers
            '{"firstname":"toto"}'
        );
        $response = $client->getResponse();
        $content = $response->getContent();
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertJson($content);
    }
}

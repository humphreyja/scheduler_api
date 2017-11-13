<?php
namespace AppBundle\Tests\Controller\Api;
use Lakion\ApiTestCase\JsonApiTestCase;

class ManagerControllerTest extends JsonApiTestCase
{
    /**
     * Create a client with a default Authorization header.
     *
     * @param string $username
     * @param string $password
     *
     * @return \Symfony\Bundle\FrameworkBundle\Client
     */
    private function createAuthenticatedClient($username = 'user', $password = 'password')
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/auth/get_token',
            array(
                '_username' => $username,
                '_password' => $password,
            )
        );

        $data = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));

        return $client;
    }

    public function testManagerIndexActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('johndoe', 'password123');
        $authclient->request('GET', '/api/managers');

        $response = $authclient->getResponse();

        $this->assertResponse($response, 'manager', 200);
    }
}

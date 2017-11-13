<?php
namespace AppBundle\Tests\Controller\Api;
use Lakion\ApiTestCase\JsonApiTestCase;

class EmployeeControllerTest extends JsonApiTestCase
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


    public function testEmployeeHistoryActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('johndoe', 'password123');

        $authclient->request('GET', '/api/history');

        $response = $authclient->getResponse();

        $this->assertResponse($response, 'history', 200);
    }

    public function testEmployeeIndexActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('randyrhoads', 'password123');
        $authclient->request('GET', '/api/employees');

        $response = $authclient->getResponse();

        $this->assertResponse($response, 'employee', 200);
    }
}

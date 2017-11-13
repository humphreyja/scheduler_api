<?php
namespace AppBundle\Tests\Controller\Api;
use Lakion\ApiTestCase\JsonApiTestCase;

/**
 * Little bit of testing.
 */
class AuthTest extends JsonApiTestCase
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

    /**
     * Tests for general access
     */
    public function testAuthenticatedResponse()
    {
        $this->client->request('GET', '/api/shifts');
        $response = $this->client->getResponse();
        $this->assertEquals(401, $response->getStatusCode());
    }

    /**
     * Tests for user specific access
     */
    public function testUserSpecificRoutesResponses()
    {
        $employeeAuthClient = $this->createAuthenticatedClient('johndoe', 'password123');
        $managerAuthClient = $this->createAuthenticatedClient('randyrhoads', 'password123');

        $managerOnlyRoutes = [
                '/api/employees' => 'GET',
                '/api/shifts/new' => 'POST',
                '/api/shifts/edit/1' => 'PUT',
                '/api/shifts/1/assign/1' => 'PUT'];

        $employeeOnlyRoutes = [
                '/api/shifts/1' => 'GET',
                '/api/history' => 'GET'];

        foreach ($managerOnlyRoutes as $route => $method) {
            $employeeAuthClient->request($method, $route);
            $employeeResponse = $employeeAuthClient->getResponse();
            $this->assertEquals(403, $employeeResponse->getStatusCode());
        }

        foreach ($employeeOnlyRoutes as $route => $method) {
            $managerAuthClient->request($method, $route);
            $managerResponse = $managerAuthClient->getResponse();
            $this->assertEquals(403, $managerResponse->getStatusCode());
        }
    }

}

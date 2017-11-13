<?php
namespace AppBundle\Tests\Controller\Api;
use Lakion\ApiTestCase\JsonApiTestCase;

class ShiftControllerTest extends JsonApiTestCase
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
     * Tests the employee shift list
     */
    public function testEmployeeIndexActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('johndoe', 'password123');

        $authclient->request('GET', '/api/shifts');

        $response = $authclient->getResponse();

        $this->assertResponse($response, 'employee_shift', 200);
    }

    /**
     * Tests the manager shift list
     */
    public function testManagerIndexActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('randyrhoads', 'password123');
        $authclient->request('GET', '/api/shifts');

        $response = $authclient->getResponse();

        $this->assertResponse($response, 'manager_shift', 200);
    }

    /**
     * Tests the create action
     */
    public function testScheduleShiftActionResponse()
    {
        $authclient = $this->createAuthenticatedClient('randyrhoads', 'password123');
        $data = [
            "start_time" => "Tue, 10 Oct 2017 01:00:00 +0000",
            "end_time" => "Tue, 10 Oct 2017 09:00:00 +0000",
            "break" => 1.2
        ];
        $authclient->request('POST', '/api/shifts/new', $data);

        $response = $authclient->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertTrue($response->headers->has('Location'));

        $data = [
            "start_time" => "Tue, 11 Oct 2017 01:00:00 +0000",
            "end_time" => "Tue, 10 Oct 2017 09:00:00 +0000",
            "break" => 1.2
        ];
        $authclient->request('POST', '/api/shifts/new', $data);

        $response = $authclient->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }
}

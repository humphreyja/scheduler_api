<?php

namespace AppBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use JMS\Serializer\SerializationContext;

/**
 * Base class for all API controllers.  Includes standard request formats, serializer helpers, logging help,
 * and request body parsing.
 */
abstract class BaseController extends Controller
{
    // Standard status codes
    const CREATED_STATUS_CODE = 201;
    const UPDATED_STATUS_CODE = 204;
    const SUCCESS_STATUS_CODE = 200;
    const ERROR_STATUS_CODE = 400;
    const NOT_FOUND_ERROR_STATUS_CODE = 404;

    // JSON default headers to be merged with provided headers
    const DEFAULT_HEADERS = ['Content-Type' => 'application/json'];


    /**
     * Generates a standard JSON response
     * @param string $data Message/Object to be sent
     * @param Array $groups Array of groups to include in json parsing.  Uses JMS Serialiser annotations.
     * @return Request
     */
    protected function createStandardResponse($data, $groups)
    {
        return $this->createResponse($data, self::SUCCESS_STATUS_CODE, $groups);
    }

    /**
     * Generates a standard JSON error response
     * @param string $error Error message/object
     * @return Request
     */
    protected function createErrorResponse($error = 'An error occurred')
    {
        $data = [
            'error' => $error,
            'status' => self::ERROR_STATUS_CODE
        ];

        return $this->createResponse($data, self::ERROR_STATUS_CODE);
    }

    /**
     * Generates a standard JSON not found error response
     * @param string $error Error message/object
     * @return Request
     */
    protected function createNotFoundErrorResponse($error = 'The resource requested was not found')
    {
        $data = [
            'error' => $error,
            'status' => self::NOT_FOUND_ERROR_STATUS_CODE
        ];

        return $this->createResponse($data, self::NOT_FOUND_ERROR_STATUS_CODE);
    }

    /**
     * Generates a standard JSON successfully created object response
     * @param string $savedObjectPath Request url to new resource
     * @return Request
     */
    protected function createCreateSuccessResponse($savedObjectPath)
    {
        $response = 'Success';
        return $this->createResponse($response, self::CREATED_STATUS_CODE, ['Default'], ['Location' => $savedObjectPath]);
    }

    /**
     * Generates a standard JSON successfully updated object response
     * @param string $savedObjectPath Request url to updated resource
     * @return Request
     */
    protected function createUpdateSuccessResponse($savedObjectPath)
    {
        $response = 'Success';
        return $this->createResponse($response, self::UPDATED_STATUS_CODE, ['Default'], ['Location' => $savedObjectPath]);
    }

    /**
     * Fetches the contents of the request body from different content sources
     * @return Array
     */
    protected function getRequestBody($request)
    {
        if (0 == strpos($request->headers->get('Content-Type'), "multipart/form-data")) {
            return $request->request->all();
        } else {
            // Default to 'application/json'
            return json_decode($request->getContent());
        }
    }

    /**
     * Builds a logger and outputs text to the console for debugging purposes.
     */
    protected function log($text)
    {
        $output = new ConsoleOutput();
        $output->setFormatter(new OutputFormatter(true));
        $output->writeln($text);
    }

    /**
     * Responsible for generating a JSON response.
     */
    private function createResponse($data, $statusCode = self::SUCCESS_STATUS_CODE, $groups = ['Default'], $additionalHeaders = [])
    {
        $json = $this->serialize($data, $groups);
        $headers = array_merge(self::DEFAULT_HEADERS, $additionalHeaders);
        return new Response($json, $statusCode, $headers);
    }

    /**
     * Responsible for serializing objects into json based on a list of groups
     * and class annotations.
     */
    private function serialize($data, $groups = ['Default'])
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);
        $context->enableMaxDepthChecks();
        $request = $this->get('request_stack')->getCurrentRequest();
        $context->setGroups($groups);
        return $this->container->get('jms_serializer')
            ->serialize($data, 'json', $context);
    }
}

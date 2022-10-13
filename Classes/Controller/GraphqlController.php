<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2022
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Nyholm\Psr7\Factory\Psr17Factory;


/**
 * Controller for the GraphQL API
 *
 * @package TYPO3
 */
class GraphqlController extends AbstractController
{
    /**
     * Creates a new resource object or a list of resource objects
     *
     * @return \Psr\Http\Message\ResponseInterface Response object containing the generated output
     */
    public function indexAction()
    {
        return \Aimeos\Admin\Graphql::execute($this->contextBackend(''), $this->getPsrRequest());
    }


    /**
     * Returns a PSR-7 request object for the current request
     *
     * @return \Psr\Http\Message\RequestInterface PSR-7 request object
     */
    protected function getPsrRequest() : \Psr\Http\Message\RequestInterface
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        return $creator->fromGlobals();
    }


    /**
     * Set the response data from a PSR-7 response object and returns the message content
     *
     * @param \Psr\Http\Message\ResponseInterface $response PSR-7 response object
     * @return string Generated output
     */
    protected function setPsrResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        if (!isset($this->responseFactory)) // TYPO3 10
        {
            $this->response->setStatus($response->getStatusCode());

            foreach ($response->getHeaders() as $key => $value) {
                foreach ((array) $value as $val) {
                    $this->response->setHeader($key, $val);
                }
            }

            return (string) $response->getBody();
        }

        return $response;
    }
}

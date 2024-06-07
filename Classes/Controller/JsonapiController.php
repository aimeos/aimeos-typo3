<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2017-2024
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Log\LoggerAwareInterface;


/**
 * Controller for the frontend JSON REST API
 *
 * @package TYPO3
 */
class JsonapiController extends AbstractController implements LoggerAwareInterface
{
    use \Psr\Log\LoggerAwareTrait;

    /**
     * Dispatches the REST API requests
     *
     * @return string Generated output
     */
    public function indexAction()
    {
        $resource = $related = null;

        if ($this->request->hasArgument('resource')
            && ($value = $this->request->getArgument('resource')) != ''
        ) {
            $resource = $value;
        }

        if ($this->request->hasArgument('related')
            && ($value = $this->request->getArgument('related')) != ''
        ) {
            $related = $value;
        }

        try {
            switch ($this->request->getMethod())
            {
                case 'DELETE': return $this->deleteAction((string) $resource, $related);
                case 'PATCH': return $this->patchAction((string) $resource, $related);
                case 'POST': return $this->postAction((string) $resource, $related);
                case 'PUT': return $this->putAction((string) $resource, $related);
                case 'GET': return $this->getAction((string) $resource, $related);
                default: return $this->optionsAction($resource);
            }
        } catch (\Throwable $e) {
            $this->logger->alert(
                'Error while processing JSON API request: ' . $e->getMessage(),
                ['exception' => $e, 'request' => $this->request, 'resource' => $resource, 'related' => $related]
            );

            return $this->responseFactory->createResponse(503, 'Internal server error');
        }
    }


    /**
     * Deletes the resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function deleteAction(string $resource, string $related = null)
    {
        return $this->createClient($resource, $related)->delete($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Returns the requested resource object or list of resource objects
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function getAction(string $resource, string $related = null)
    {
        return $this->createClient($resource, $related)->get($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Updates a resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function patchAction(string $resource, string $related = null)
    {
        return $this->createClient($resource, $related)->patch($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Creates a new resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function postAction(string $resource, string $related = null)
    {
        return $this->createClient($resource, $related)->post($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Creates or updates a single resource object
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function putAction(string $resource, string $related = null)
    {
        return $this->createClient($resource, $related)->put($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Returns the available HTTP verbs and the resource URLs
     *
     * @param string Resource location, e.g. "product"
     * @return \Psr\Http\Message\ResponseInterface PSR-7 response
     */
    public function optionsAction(string $resource = null)
    {
        $request = $this->getPsrRequest();

        return $this->createClient($resource ?? '')->options($request, (new Psr17Factory)->createResponse())
            ->withHeader('access-control-allow-headers', 'authorization,content-type')
            ->withHeader('access-control-allow-methods', 'DELETE, GET, OPTIONS, PATCH, POST, PUT')
            ->withHeader('access-control-allow-origin', $request->getHeaderLine( 'origin'));
    }


    /**
     * Returns the resource client
     *
     * @param string Resource location, e.g. "basket"
     * @param string|null Related resource, e.g. "product"
     * @return \Aimeos\Client\JsonApi\Iface Jsonapi client
     */
    protected function createClient(string $resource, string $related = null) : \Aimeos\Client\JsonApi\Iface
    {
        $context = $this->context('client/jsonapi/templates');
        return \Aimeos\Client\JsonApi::create($context, $resource . '/' . $related);
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
}

<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;
use Nyholm\Psr7\Factory\Psr17Factory;


/**
 * Controller for the JSON API
 *
 * @package TYPO3
 */
class JsonadmController extends AbstractController
{
    /**
     * Initializes the object before the real action is called.
     */
    protected function initializeAction()
    {
    }


    /**
     * Dispatches the REST API requests
     *
     * @return string Generated output
     */
    public function indexAction()
    {
        $resource = null;

        if ($this->request->hasArgument('resource')
            && ($value = $this->request->getArgument('resource')) != ''
        ) {
            $resource = $value;
        }

        switch ($this->request->getMethod()) {
            case 'DELETE': return $this->deleteAction($resource);
            case 'PATCH': return $this->patchAction($resource);
            case 'POST': return $this->postAction($resource);
            case 'PUT': return $this->putAction($resource);
            case 'GET': return $this->getAction($resource);
            default: return $this->optionsAction($resource);
        }
    }


    /**
     * Deletes the resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function deleteAction($resource)
    {
        return $this->createAdmin($resource)->delete($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Returns the requested resource object or list of resource objects
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function getAction($resource)
    {
        return $this->createAdmin($resource)->get($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Updates a resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function patchAction($resource)
    {
        return $this->createAdmin($resource)->patch($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Creates a new resource object or a list of resource objects
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function postAction($resource)
    {
        return $this->createAdmin($resource)->post($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Creates or updates a single resource object
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function putAction($resource)
    {
        return $this->createAdmin($resource)->put($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Returns the available HTTP verbs and the resource URLs
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return string Generated output
     */
    public function optionsAction($resource)
    {
        return $this->createAdmin($resource ?? '')->options($this->getPsrRequest(), (new Psr17Factory)->createResponse());
    }


    /**
     * Returns the resource client
     *
     * @param string Resource location, e.g. "product/property/type"
     * @return \Aimeos\Admin\JsonAdm\Iface Jsonadm client
     */
    protected function createAdmin(string $resource) : \Aimeos\Admin\JsonAdm\Iface
    {
        $context = $this->contextBackend('admin/jsonadm/templates');
        return \Aimeos\Admin\JsonAdm::create($context, Base::aimeos(), $resource);
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

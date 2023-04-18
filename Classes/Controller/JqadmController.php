<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Controller for the JSON API
 *
 * @package TYPO3
 */
class JqadmController extends AbstractController
{
    private static $aimeos;


    /**
     * Initializes the object before the real action is called.
     */
    protected function initializeAction()
    {
        parent::initializeAction();
        $this->uriBuilder->setArgumentPrefix('tx_aimeos_web_aimeostxaimeosadmin');
    }


    /**
     * Returns the CSS/JS file content
     *
     * @return string CSS/JS files content
     */
    public function fileAction()
    {
        $contents = '';
        $files = [];
        $type = $this->request->getArgument('type');

        foreach (Base::aimeos()->getCustomPaths('admin/jqadm') as $base => $paths) {
            foreach($paths as $path) {
                $jsbAbsPath = $base . '/' . $path;
                $jsb2 = new \Aimeos\MW\Jsb2\Standard($jsbAbsPath, dirname($jsbAbsPath));
                $files = array_merge($files, $jsb2->getFiles($type));
            }
        }

        foreach ($files as $file) {
            if (($content = file_get_contents($file)) === false) {
                throw new \RuntimeException(sprintf('File "%1$s" not found', $jsbAbsPath));
            }

            $contents .= $content;
        }

        if (!isset($this->responseFactory)) { // TYPO3 10
            if ($type === 'js') {
                $this->response->setHeader('Content-Type', 'application/javascript');
            } elseif ($type === 'css') {
                $this->response->setHeader('Content-Type', 'text/css');
            }

            return $contents;
        }

        $response = $this->responseFactory->createResponse()
            ->withBody($this->streamFactory->createStream($contents));

        if ($type === 'js') {
            $response = $response->withAddedHeader('Content-Type', 'application/javascript');
        } elseif ($type === 'css') {
            $response = $response->withAddedHeader('Content-Type', 'text/css');
        }

        return $response;
    }


    /**
     * Returns the HTML code for batch operations on resource objects
     *
     * @return string Generated output
     */
    public function batchAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->batch()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Returns the HTML code for a copy of a resource object
     *
     * @return string Generated output
     */
    public function copyAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->copy()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Returns the HTML code for a new resource object
     *
     * @return string Generated output
     */
    public function createAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->create()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Deletes the resource object or a list of resource objects
     *
     * @return string Generated output
     */
    public function deleteAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->delete()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Exports the resource object
     *
     * @return string Generated output
     */
    public function exportAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->export()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Returns the HTML code for the requested resource object
     *
     * @return string Generated output
     */
    public function getAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->get()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Imports the resource object
     *
     * @return string Generated output
     */
    public function importAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->import()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Saves a new resource object
     *
     * @return string Generated output
     */
    public function saveAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->save()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Returns the HTML code for a list of resource objects
     *
     * @return string Generated output
     */
    public function searchAction()
    {
        $cntl = $this->createAdmin();

        if (($html = $cntl->search()) == '') {
            return $this->setPsrResponse($cntl->response());
        }

        $this->view->assign('content', $html);
        return $this->render();
    }


    /**
     * Returns the resource controller
     *
     * @return \Aimeos\Admin\JQAdm\Iface JQAdm client
     */
    protected function createAdmin() : \Aimeos\Admin\JQAdm\Iface
    {
        $resource = 'dashboard';

        if ($this->request->hasArgument('resource')
            && ($value = $this->request->getArgument('resource')) != ''
        ) {
            $resource = $value;
        }

        $aimeos = Base::aimeos();
        $context = $this->contextBackend('admin/jqadm/templates');

        $view = $context->view();

        $view->aimeosType = 'TYPO3';
        $view->aimeosVersion = Base::getVersion();
        $view->aimeosExtensions = implode(',', $aimeos->getExtensions());

        $context->setView($view);

        return \Aimeos\Admin\JQAdm::create($context, $aimeos, $resource);
    }


    /**
     * Returns a PSR-7 response for TYPO3 11+
     */
    protected function render()
    {
        if (isset($this->responseFactory)) { // TYPO3 11
            return $this->responseFactory->createResponse()
                ->withAddedHeader('Content-Type', 'text/html; charset=utf-8')
                ->withBody($this->streamFactory->createStream($this->view->render()));
        }
    }


    /**
     * Uses default view.
     *
     * return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface View object
     */
    protected function resolveView() : \TYPO3Fluid\Fluid\View\ViewInterface
    {
        if ($this->request->hasArgument('locale') && ($value = $this->request->getArgument('locale')) != '') {
            $lang = $value;
        } elseif (isset($GLOBALS['BE_USER']->uc['lang']) && $GLOBALS['BE_USER']->uc['lang'] != '') {
            $lang = $GLOBALS['BE_USER']->uc['lang'];
        } else {
            $lang = 'en';
        }

        $view = \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::resolveView();

        $view->assign('theme', ($_COOKIE['aimeos_backend_theme'] ?? null) == 'dark' ? 'dark' : 'light');
        $view->assign('localeDir', in_array($lang, ['ar', 'az', 'dv', 'fa', 'he', 'ku', 'ur']) ? 'rtl' : 'ltr');
        $view->assign('locale', $lang);

        return $view;
    }


    /**
     * Set the response data from a PSR-7 response object and returns the message content
     *
     * @param \Psr\Http\Message\ResponseInterface $response PSR-7 response object
     * @return string Generated output
     */
    protected function setPsrResponse(\Psr\Http\Message\ResponseInterface $response)
    {
        if (!isset($this->responseFactory)) { // TYPO3 10
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

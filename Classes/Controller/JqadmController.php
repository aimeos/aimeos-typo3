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
    /**
     * Returns the CSS/JS file content
     *
     * @return string CSS/JS files content
     */
    public function fileAction()
    {
        $files = [];
        $name = $this->request->getArgument('name');

        foreach (Base::aimeos()->getCustomPaths('admin/jqadm') as $base => $paths)
        {
            foreach ($paths as $path) {
                $files[] = $base . '/' . $path;
            }
        }

        $response = $this->responseFactory->createResponse()
            ->withBody($this->streamFactory->createStream(\Aimeos\Admin\JQAdm\Bundle::get( $files, $name )));

        if (str_ends_with($name, 'js')) {
            $response = $response->withAddedHeader('Content-Type', 'application/javascript');
        } elseif (str_ends_with($name, 'css')) {
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
            return $cntl->response();
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
        $params = $this->request->getArguments();
        $resource = $params['ai']['resource'] ?? 'dashboard';

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
        return $this->responseFactory->createResponse()
            ->withAddedHeader('Content-Type', 'text/html; charset=utf-8')
            ->withBody($this->streamFactory->createStream($this->view->render()));
    }


    /**
     * Uses default view.
     *
     * return \TYPO3\CMS\Extbase\Mvc\View\ViewInterface View object
     */
    protected function resolveView() : \TYPO3Fluid\Fluid\View\ViewInterface
    {
        $params = $this->request->getArguments();
        $value = $params['ai']['locale'] ?? '';

        if ($value) {
            $lang = $value;
        } elseif (($GLOBALS['BE_USER']->user['lang'] ?? '') != 'default') {
            $lang = $GLOBALS['BE_USER']->user['lang'];
        } else {
            $lang = 'en';
        }

        $view = \TYPO3\CMS\Extbase\Mvc\Controller\ActionController::resolveView();

        $view->assign('theme', ($_COOKIE['aimeos_backend_theme'] ?? null) == 'dark' ? 'dark' : 'light');
        $view->assign('localeDir', in_array($lang, ['ar', 'az', 'dv', 'fa', 'he', 'ku', 'ur']) ? 'rtl' : 'ltr');
        $view->assign('locale', $lang);

        return $view;
    }
}

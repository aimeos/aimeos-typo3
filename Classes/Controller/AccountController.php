<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Metaways Infosystems GmbH, 2013
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Controller;


use Aimeos\Aimeos\Base;


/**
 * Aimeos account controller.
 *
 * @package TYPO3
 */
class AccountController extends AbstractController
{
    /**
     * Renders the saved account baskets.
     */
    public function basketAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/basket');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account download
     */
    public function downloadAction()
    {
        $context = $this->context();
        $view = $context->view();

        $client = \Aimeos\Client\Html::create($context, 'account/download');
        $client->setView($view)->init();

        return $view->response();
    }


    /**
     * Renders the account history.
     */
    public function historyAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/history');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account favorites.
     */
    public function favoriteAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/favorite');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account profile.
     */
    public function profileAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/profile');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account review.
     */
    public function reviewAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/review');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account subscriptions.
     */
    public function subscriptionAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/subscription');
        return $this->getClientOutput($client);
    }


    /**
     * Renders the account watch list.
     */
    public function watchAction()
    {
        $client = \Aimeos\Client\Html::create($this->context(), 'account/watch');
        return $this->getClientOutput($client);
    }
}

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
	 * Renders the account download
	 */
	public function downloadAction()
	{
		$paths = Base::getAimeos()->getCustomPaths( 'client/html' );
		$context = $this->getContext();
		$view = $context->getView();

		$client = \Aimeos\Client\Html::create( $context, 'account/download' );
		$client->setView( $view );
		$client->process();

		$response = $view->response();
		$this->response->setStatus( $response->getStatusCode() );

		foreach( $response->getHeaders() as $key => $value ) {
			$this->response->setHeader( $key, implode( ', ', $value ) );
		}

		return (string) $response->getBody();
	}


	/**
	 * Renders the account history.
	 */
	public function historyAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/history' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the account favorites.
	 */
	public function favoriteAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/favorite' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the account profile.
	 */
	public function profileAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/profile' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the account review.
	 */
	public function reviewAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/review' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the account subscriptions.
	 */
	public function subscriptionAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/subscription' );
		return $this->getClientOutput( $client );
	}


	/**
	 * Renders the account watch list.
	 */
	public function watchAction()
	{
		$client = \Aimeos\Client\Html::create( $this->getContext(), 'account/watch' );
		return $this->getClientOutput( $client );
	}
}

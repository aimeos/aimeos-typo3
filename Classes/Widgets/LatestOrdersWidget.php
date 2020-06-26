<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2020
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Widget;


/**
 * Renders a widgent of the latest orders
 * @package TYPO3
 */
class LatestOrdersWidget implements \TYPO3\CMS\Dashboard\Widgets\WidgetInterface
{
	private $view;


	public function __construct( \TYPO3\CMS\Fluid\View\StandaloneView $view )
	{
		$this->view = $view;
	}


	/**
	 * Renders the content for the widget
	 *
	 * @return string HTML code
	 */
	public function renderWidgetContent() : string
	{
		return $this->view->setTemplate( 'Widget/LatestOrdersWidget' )
			->assign( 'items', $this->getOrderItems() )
			->render();
	}


	/**
	 * Returns the latest order items
	 *
	 * @return array Associative list of order IDs as keys and order items as values
	 */
	protected function getOrderItems() : array
	{
		$config = \Aimeos\Aimeos\Base::getConfig();
		$context = \Aimeos\Aimeos\Base::getContext( $config );

		$manager = \Aimeos\MShop::create( $context, 'order' );
		$filter = $manager->filter()->sort( '-order.id' )->slice( 0, 20 );

		return $manager->search( $filter, ['order/base', 'order/base/address'] )->toArray();
	}
}
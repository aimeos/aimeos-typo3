<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2020-2023
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Widgets;

use TYPO3\CMS\Core\View\ViewFactoryInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetConfigurationInterface;
use TYPO3\CMS\Dashboard\Widgets\WidgetInterface;


/**
 * Renders a widgent of the latest orders
 * @package TYPO3
 */
class LatestOrdersWidget implements WidgetInterface
{
    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly ViewFactoryInterface $viewFactory,
    ) {
    }


    public function getOptions(): array
    {
        return [];
    }


    /**
     * Renders the content for the widget
     *
     * @return string HTML code
     */
    public function renderWidgetContent(): string
    {
        $view = $this->viewFactory->create(
            new \TYPO3\CMS\Core\View\ViewFactoryData(
                templateRootPaths: ['EXT:aimeos/Resources/Private/Templates/Dashboard/'],
            )
        );
        return $view->assign('items', $this->getOrderItems())->render('Widgets/LatestOrdersWidget');
    }


    /**
     * Returns the latest order items
     *
     * @return array Associative list of order IDs as keys and order items as values
     */
    protected function getOrderItems() : array
    {
        $config = \Aimeos\Aimeos\Base::config();
        $context = \Aimeos\Aimeos\Base::context($config);

        $manager = \Aimeos\MShop::create( $context, 'locale' );
        $item = $manager->bootstrap( 'default', '', '', true );
        $context->setLocale( $item );

        $manager = \Aimeos\MShop::create($context, 'order');
        $filter = $manager->filter()->order('-order.id')->slice(0, 10);

        return $manager->search($filter, ['order/address'])->toArray();
    }
}
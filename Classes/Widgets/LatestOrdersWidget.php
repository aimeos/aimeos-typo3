<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2020
 * @package TYPO3
 */


namespace Aimeos\Aimeos\Widgets;


/**
 * Renders a widgent of the latest orders
 * @package TYPO3
 */
class LatestOrdersWidget implements \TYPO3\CMS\Dashboard\Widgets\WidgetInterface
{
    private ServerRequestInterface $request;


    public function __construct(
        private readonly WidgetConfigurationInterface $configuration,
        private readonly Cache $cache,
        private readonly BackendViewFactory $backendViewFactory,
        private readonly ?ButtonProviderInterface $buttonProvider = null,
        private readonly array $options = []
    ) {
    }


    public function getOptions(): array
    {
        return $this->options;
    }


    public function setRequest(ServerRequestInterface $request): void
    {
        $this->request = $request;
    }


    /**
     * Renders the content for the widget
     *
     * @return string HTML code
     */
    public function renderWidgetContent(): string
    {
        $view = $this->backendViewFactory->create($this->request);
        return $this->view->assign('items', $this->getOrderItems())
            ->render('Widgets/LatestOrdersWidget');
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

        $manager = \Aimeos\MShop::create($context, 'order');
        $filter = $manager->filter()->sort('-order.id')->slice(0, 20);

        return $manager->search($filter, ['order/base', 'order/base/address'])->toArray();
    }
}
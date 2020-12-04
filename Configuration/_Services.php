<?php

namespace Aimeos\Aimeos;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;


return function( ContainerConfigurator $configurator, ContainerBuilder $containerBuilder )
{
	$services = $configurator->services();

	if( \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::isLoaded( 'aimeos' ) )
	{
		$services->set( 'widgets.dashboard.widget.latestorders' )
			->class( \Aimeos\Aimeos\Widgets\LatestOrderWidget::class )
			->arg( '$view', new \Symfony\Component\DependencyInjection\Reference( 'dashboard.views.widget' ) )
			->tag( 'dashboard.widget', [
			   'identifier' => 'aimeos-latestorders',
			   'title' => 'LLL:EXT:aimeos/Resources/Private/Language/extension.xlf:widgets.dashboard.latestorder.title',
			   'description' => 'LLL:EXT:aimeos/Resources/Private/Language/extension.xlf:widgets.dashboard.latestorder.description',
			   'iconIdentifier' => 'aimeos-widget-latestorders',
			   'height' => 'medium',
			   'width' => 'large'
			] );
	}
};

<?php

/**
 * @license GPLv3, http://www.gnu.org/copyleft/gpl.html
 * @copyright Aimeos (aimeos.org), 2015
 * @package TYPO3_Aimeos
 */


namespace Aimeos\Aimeos\Command;

use Aimeos\Aimeos\Base;
use Aimeos\Aimeos\Scheduler;


/**
 * Aimeos job command controller
 *
 * @package TYPO3_Aimeos
 */
class JobsCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController
{
	/**
	 * Executes the Aimeos maintenance jobs
	 *
	 * The Aimeos shop system needs some maintenance tasks that must be
	 * regularly executed. Each of these maintenance tasks must be executed for
	 * all shop instances if you have more than one site in your installation.
	 *
	 * @param string $jobs List of job names separated by a space character like "admin/job catalog/index/rebuild"
	 * @param string $sites List of sites separated by a space character the jobs should be executed for, e.g. "default unittest"
	 * @param string $tsconfig TypoScript string for individual configuration
	 * @return boolean True on succes
	 */
	public function runCommand( $jobs, $sites = 'default', $tsconfig = '' )
	{
		$conf = Base::parseTS( $tsconfig );
		$context = Scheduler\Base::getContext( $conf );

		Scheduler\Base::execute( $context, explode( ' ', $jobs ), $sites );

		return true;
	}


	/**
	 * Executes the Aimeos e-mail jobs
	 *
	 * The Aimeos shop system needs some maintenance tasks that must be
	 * regularly executed. Each of these maintenance tasks must be executed for
	 * all shop instances if you have more than one site in your installation.
	 *
	 * @param string $jobs List of job names separated by a space character like "customer/email/watch order/email/payment"
	 * @param string $sites List of sites separated by a space character the jobs should be executed for, e.g. "default unittest"
	 * @param string $tsconfig TypoScript string for individual configuration
	 * @param string $senderFrom Name of the sender in the e-mail
	 * @param string $senderEmail Sender e-mail address
	 * @param string $replyEmail E-Mail address customers can reply to
	 * @param string $detailPid Page ID of the catalog detail page for generating product URLs
	 * @param string $baseUrl URL of the Aimeos uploads directory, e.g. https://yourdomain.tld/uploads/tx_aimeos
	 * @param string $themeDir Absolute path or relative path from to TYPO3 root to the CSS files of the theme directory
	 * @return boolean True on succes
	 */
	public function emailCommand( $jobs = 'customer/email/watch order/email/delivery order/email/payment',
		$sites = 'default', $tsconfig = '', $senderFrom = 'Aimeos shop', $senderEmail = '', $replyEmail = '',
		$detailPid = '', $baseUrl = '', $themeDir = 'typo3conf/ext/aimeos/Resources/Public/Themes/elegance' )
	{
		Scheduler\Base::initFrontend( $detailPid );

		if( $themeDir != '' && $themeDir[0] !== '/' ) {
			$themeDir = realpath( PATH_site . $themeDir );
		}

		$conf = Base::parseTS( $tsconfig );

		if( !isset( $conf['client']['html']['catalog']['detail']['url']['config'] ) ) {
			$conf['client']['html']['catalog']['detail']['url']['config'] = array(
				'plugin' => 'catalog-detail',
				'extension' => 'aimeos',
				'absoluteUri' => 1,
			);
		}

		$conf['client']['html']['email']['from-name'] = $senderFrom;
		$conf['client']['html']['email']['from-email'] = $senderEmail;
		$conf['client']['html']['email']['reply-email'] = $replyEmail;
		$conf['client']['html']['common']['content']['baseurl'] = $baseUrl;
		$conf['client']['html']['common']['template']['baseurl'] = $themeDir;
		$conf['client']['html']['catalog']['detail']['url']['target'] = $detailPid;

		$context = $this->_getContext( $conf );

		Scheduler\Base::execute( $context, explode( ' ', $jobs ), $sites );

		return true;
	}


	/**
	 * Returns a context object for the jobs command
	 *
	 * @param array $localConf Local TypoScript configuration settings
	 * @return \MShop_Context_Item_Default Context object
	 */
	protected function _getContext( array $localConf = array() )
	{
		$aimeos = Base::getAimeos();
		$tmplPaths = $aimeos->getCustomPaths( 'controller/jobs/layouts' );
		$tmplPaths = array_merge( $tmplPaths, $aimeos->getCustomPaths( 'client/html' ) );

		$uriBuilder = $this->objectManager->get( 'TYPO3\CMS\Extbase\Mvc\Web\Routing\UriBuilder' );
		$uriBuilder->setArgumentPrefix( 'ai' );

		$config = Base::getConfig( $localConf );
		$context = Base::getContext( $config );

		$langManager = \MShop_Factory::createManager( $context, 'locale/language' );
		$langids = array_keys( $langManager->searchItems( $langManager->createSearch( true ) ) );

		$i18n = Base::getI18n( $langids, ( isset( $conf['i18n'] ) ? (array) $conf['i18n'] : array() ) );
		$context->setI18n( $i18n );

		$view = Base::getView( $config, $uriBuilder, $tmplPaths, $this->request );
		$context->setView( $view );

		$context->setEditor( 'aimeos:jobs' );

		return $context;
	}
}
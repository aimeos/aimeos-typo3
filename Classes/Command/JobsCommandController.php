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
		Scheduler\Base::execute( Base::parseTS( $tsconfig ), explode( ' ', $jobs ), $sites );

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

		Scheduler\Base::execute( $conf, explode( ' ', $jobs ), $sites );

		return true;
	}
}
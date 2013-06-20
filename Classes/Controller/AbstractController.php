<?php
namespace TYPO3\Amazingcomments\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Benjamin Mack <benni@typo3.org>
 *  
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Controller related to "unstructured" comments, not caring about the discussions from the outside
 *
 * @package amazingcomments
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {


	/**
	 * persistence manager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;


	/**
	 * commentRepository
	 *
	 * @var \TYPO3\Amazingcomments\Domain\Repository\CommentRepository
	 * @inject
	 */
	protected $commentRepository;


	/**
	 * discussionRepository
	 *
	 * @var \TYPO3\Amazingcomments\Domain\Repository\DiscussionRepository
	 * @inject
	 */
	protected $discussionRepository;

	/**
	 * User Repository
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Repository\FrontendUserRepository
	 * @inject
	 */
	protected $userRepository;


	/**
	 * initialize the controller
	 *
	 * @return void
	 */
	protected function initializeAction() {
		parent::initializeAction();

		// fallback to current pid if no storagePid is defined
		$configuration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		if (empty($configuration['persistence']['storagePid'])) {
			$configuration['persistence']['storagePid'] = $GLOBALS['TSFE']->id;
			$this->configurationManager->setConfiguration($configuration);
		}
	}

	public function getCurrentUser() {
		if ($this->currentUser == NULL && $GLOBALS['TSFE']->fe_user->user['uid'] > 0) {
			$this->currentUser = $this->userRepository->findByUid($GLOBALS['TSFE']->fe_user->user['uid']);
		}

		return $this->currentUser;	
	}
	
	
	/**
	 * generic function to send an email through a Fluid template
	 * 
	 * @param array $recipient recipient of the email in the format array('recipient@domain.tld' => 'Recipient Name')
	 * @param array $sender sender of the email in the format array('sender@domain.tld' => 'Sender Name')
	 * @param string $subject subject of the email
	 * @param string $templateName template name (UpperCamelCase)
	 * @param array $variables variables to be passed to the Fluid view
	 * @return boolean TRUE on success, otherwise false
	 */
	protected function sendTemplateEmail(array $recipient, array $sender, $subject, $templateName, array $variables = array()) {
		$emailView = $this->objectManager->create('TYPO3\\CMS\\Fluid\\View\\StandaloneView');
		$emailView->setFormat('html');
		$extbaseFrameworkConfiguration = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$templateRootPath = \TYPO3\CMS\Core\Utility\GeneralUtility::getFileAbsFileName($extbaseFrameworkConfiguration['view']['templateRootPath']);
		$templatePathAndFilename = $templateRootPath . 'Email/' . $templateName . '.html';
		$emailView->setTemplatePathAndFilename($templatePathAndFilename);
		$emailView->assignMultiple($variables);
		$emailBody = $emailView->render();
		
		$message = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('t3lib_mail_Message');
		$message->setTo($recipient)
		->setFrom($sender)
		->setSubject($subject);
		// Possible attachments here
		//foreach ($attachments as $attachment) {
		//    $message->attach($attachment);
		//}
		
		// Plain text example
		//$message->setBody($emailBody, 'text/plain');
		
		// HTML Email
		$message->setBody($emailBody, 'text/html');
		$message->send();
		return $message->isSent();
	}


}

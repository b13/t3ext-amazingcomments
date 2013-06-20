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
class CommentController extends \TYPO3\Amazingcomments\Controller\AbstractController {

	/**
	 * lists all comments of this page, in chronological order
	 *
	 * @param	\TYPO3\Amazingcomments\Domain\Model\Comment	$newComment
	 * @dontvalidate $newComment
	 */
	public function listAction(\TYPO3\Amazingcomments\Domain\Model\Comment $newComment = NULL) {
		$discussion = $this->getDefaultDiscussion();
		$this->view->assign('discussion', $discussion);

			// used to add a new comment directly
		if (!$newComment) {
			$newComment = $this->objectManager->create('TYPO3\\Amazingcomments\\Domain\\Model\\Comment');
		}

		$this->view->assign('newComment', $newComment);
		$this->view->assign('currentUser', $this->getCurrentUser());
	}

	/**
	 * lists the latest 3 comments of this page
	 *
	 * @param	\TYPO3\Amazingcomments\Domain\Model\Comment	$newComment
	 * @dontvalidate $newComment
	 */
	public function latestAction(\TYPO3\Amazingcomments\Domain\Model\Comment $newComment = NULL) {
		$discussion = $this->getDefaultDiscussion();
		$this->view->assign('discussion', $discussion);

		// get the X latest posts
		$latestComments = array();
		foreach ($discussion->getComments() as $comment) {
			if ($comment->getCrdate()) {
				$latestComments[$comment->getCrdate()->format('U')] = $comment;
			}
		}
		
		// Removed by jlochstampfer - we want asc sorting
		// caution: array_slice($latestComments, 0, 3);
		// sort comments by date desc
//		krsort($latestComments);
		
		// only show the last 3 comments
		// @todo: make this configurable
		$latestComments = array_slice($latestComments, -3);

		
		$this->view->assign('latestComments', $latestComments);


		if (!$newComment) {
			$newComment = $this->objectManager->create('TYPO3\\Amazingcomments\\Domain\\Model\\Comment');
		}

		$this->view->assign('newComment', $newComment);
		$this->view->assign('currentUser', $this->getCurrentUser());
	}


	/**
	 * saves a new comment to the database
	 *
	 * @param	\TYPO3\Amazingcomments\Domain\Model\Comment	$newComment
	 */
	public function addAction(\TYPO3\Amazingcomments\Domain\Model\Comment $newComment = NULL) {
		if (!$newComment) {
			$newComment = $this->objectManager->create('TYPO3\\Amazingcomments\\Domain\\Model\\Comment');
		}
		$this->view->assign('newComment', $newComment);
		$this->view->assign('currentUser', $this->getCurrentUser());
	}
	
	/**
	 * saves a new comment to the database
	 *
	 * @param	\TYPO3\Amazingcomments\Domain\Model\Comment	$comment
	 */
	public function createAction(\TYPO3\Amazingcomments\Domain\Model\Comment $comment) {
		$currentUser = $this->getCurrentUser();
		// get all comments in this discussion to send mails to the users
		$comments = $this->commentRepository->findAll();
		// go over all comments to get the authors and put them in the receiver array for mailing purposes
		foreach ($comments as $singlecomment) {
			$receivers[] = $singlecomment->getAuthor();
		}
		$receivers = array_unique($receivers);
		
		$comment->setCrdate(new \DateTime());
		$comment->setAuthor($currentUser);
		
		// check if there is a "main" discussion on the page, and add the comment to the discussion
		$discussion = $this->getDefaultDiscussion();
		$discussion->addComment($comment);
		$comment->setDiscussion($discussion);

		$this->discussionRepository->update($discussion);
		$this->commentRepository->add($comment);
		
		$articleTitle = $GLOBALS['TSFE']->page['nav_title'];
			// sends mails to all addresses given on the current page (article) and to all commenters taking part on the discussion
		if (count($receivers) > 0) {
			foreach ($receivers as $receiver) {
				$this->sendTemplateEmail(
					array($receiver->getEmail() => $receiver->getFirstName() . ' ' . $receiver->getLastName()),
					array($this->settings['notificationMailSenderEmail'] => $this->settings['notificationMailSenderName']),
					$this->settings['notificationMailSubject'] . ' ' . $articleTitle,
					'NotificationMail',
					array(
						'receiver' => $receiver,
						'subject' => $subject,
						'uri' => $uri,
						'articleTitle' => $articleTitle,
						'pageId' => $GLOBALS['TSFE']->id,
					)
				);
			}
		}
		
		// send a mail to all receivers set on the article page in typo3
		$additionalReceivers = $GLOBALS['TSFE']->page['tx_amazingcomments_emailnotification'];
		$additionalReceivers = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $additionalReceivers);
		if (!empty($additionalReceivers)) {
			if (count($additionalReceivers) > 0) {
				foreach ($additionalReceivers as $additionalReceiver) {
					$this->sendTemplateEmail(
						array($additionalReceiver),
						array($this->settings['notificationMailSenderEmail'] => $this->settings['notificationMailSenderName']),
						$this->settings['notificationMailSubject'] . ' ' . $articleTitle,
						'NotificationMail',
						array(
							'currentauthor' => $comment->getAuthor(),
							'subject' => $subject,
							'uri' => $uri,
							'articleTitle' => $articleTitle,
							'pageId' => $GLOBALS['TSFE']->id,
						)
					);
				}
			}
		}
		// make sure the redirect adds the query string
		$uri = $this->uriBuilder->reset()->setCreateAbsoluteUri(TRUE)
			->setAddQueryString(TRUE)
			->setArgumentsToBeExcludedFromQueryString(array('tx_amazingcomments_listcomments[action]'))
			->setUseCacheHash(FALSE)
			->uriFor('list');
		$this->redirectToUri($uri);
		// not used, otherwise another plugin on the same page does not show the new record as it is just a forward
		//$this->persistenceManager->persistAll();
		//$this->forward('list');
	}
	
	/**
	 * check if there is a "main" discussion on the page
	 */
	protected function getDefaultDiscussion() {
		$allDiscussions = $this->discussionRepository->findAll();
		if (count($allDiscussions) > 0) {
			$defaultDiscussion = $allDiscussions->getFirst();
		} else {
			$defaultDiscussion = $this->objectManager->create('TYPO3\\Amazingcomments\\Domain\\Model\\Discussion');
			$currentUser = $this->getCurrentUser();
			$defaultDiscussion->setAuthor($currentUser);
			$defaultDiscussion->setHeadline($GLOBALS['TSFE']->page['nav_title'] ? $GLOBALS['TSFE']->page['nav_title'] : $GLOBALS['TSFE']->page['title']);
			$this->discussionRepository->add($defaultDiscussion);
		}
		return $defaultDiscussion;
	}

}

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
 *
 *
 * @package amazingcomments
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class DiscussionController extends \TYPO3\Amazingcomments\Controller\AbstractController {

	/**
	 * lists the latest 5 discussions of all pages, from the start page
	 *
	 */
	public function latestAction() {
		$discussions = $this->discussionRepository->findLatest(5);
		$this->view->assign('discussions', $discussions);
	}

	/**
	 * action new
	 *
	 * @param \TYPO3\Amazingcomments\Domain\Model\Discussion $newDiscussion
	 * @dontvalidate $newDiscussion
	 * @return void
	 */
	public function newAction(\TYPO3\Amazingcomments\Domain\Model\Discussion $newDiscussion = NULL) {
		$this->view->assign('newDiscussion', $newDiscussion);
	}

	/**
	 * action create
	 *
	 * @param \TYPO3\Amazingcomments\Domain\Model\Discussion $newDiscussion
	 * @return void
	 */
	public function createAction(\TYPO3\Amazingcomments\Domain\Model\Discussion $newDiscussion) {
		$this->discussionRepository->add($newDiscussion);
		$this->flashMessageContainer->add('Your new Discussion was created.');
		$this->redirect('list');
	}

}

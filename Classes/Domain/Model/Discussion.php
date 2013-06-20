<?php
namespace TYPO3\Amazingcomments\Domain\Model;

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
class Discussion extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * Headline
	 *
	 * @var \string
	 */
	protected $headline;

	/**
	 * comments
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\TYPO3\Amazingcomments\Domain\Model\Comment>
	 * @lazy
	 */
	protected $comments;

	/**
	 * Author
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	 */
	protected $author;


	/**
	 * Returns the headline
	 *
	 * @return \string $headline
	 */
	public function getHeadline() {
		return $this->headline;
	}

	/**
	 * Sets the headline
	 *
	 * @param \string $headline
	 * @return void
	 */
	public function setHeadline($headline) {
		$this->headline = $headline;
	}

	/**
	 * Returns the comments
	 *
	 * @return \TYPO3\Amazingcomments\Domain\Model\Comment $comments
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Sets the comments
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage $comments
	 * @return void
	 */
	public function setComments(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $comments) {
		$this->comments = $comments;
	}


	/**
	 * Adds a comment to this object
	 *
	 * @param \TYPO3\Amazingcomments\Domain\Model\Comment $item comment to add
	 * @return void
	 */
	public function addComment(\TYPO3\Amazingcomments\Domain\Model\Comment $item) {
		// this is a workaround as the object is sometimes empty with TYPO3 6.0 and then a "called a member function on a non-object-function" error shows up
		if(!$this->comments) {
			#$this->comments = new Tx_Extbase_Persistence_ObjectStorage();
			$this->comments = $this->objectManager->create('\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		}
		$this->comments->attach($item);
	}

	/**
	 * Remove a comment from this object
	 *
	 * @param \TYPO3\Amazingcomments\Domain\Model\Comment $itemToRemove comment to be removed
	 * @return void
	 */
	public function removeComment(\TYPO3\Amazingcomments\Domain\Model\Comment $itemToRemove) {
		// this is a workaround as the object is sometimes empty with TYPO3 6.0 and then a "called a member function on a non-object-function" error shows up
		if(!$this->comments) {
			$this->comments = $this->objectManager->create('\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
		}
		$this->comments->detach($itemToRemove);
	}

	/**
	 * Remove all comments from this object
	 *
	 * @return void
	 */
	public function removeAllComments() {
		$this->comments = $this->objectManager->create('\\TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage');
	}


	/**
	 * Returns the author
	 *
	 * @return \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $author
	 */
	public function getAuthor() {
		return $this->author;
	}

	/**
	 * Sets the author
	 *
	 * @param \TYPO3\CMS\Extbase\Domain\Model\FrontendUser $author
	 * @return void
	 */
	public function setAuthor(\TYPO3\CMS\Extbase\Domain\Model\FrontendUser $author) {
		$this->author = $author;
	}

}
?>
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
class Comment extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * the creation date
	 *
	 * @var \DateTime
	 */
	protected $crdate;

	/**
	 * Headline
	 *
	 * @var \string
	 */
	protected $headline;

	/**
	 * Main Content / Bodytext
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $content;

	/**
	 * Author
	 *
	 * @var \TYPO3\CMS\Extbase\Domain\Model\FrontendUser
	 */
	protected $author;

	/**
	 * Discussion that the comment belongs to
	 *
	 * @var \TYPO3\Amazingcomments\Domain\Model\Discussion
	 */
	protected $discussion;


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
	 * Returns the crdate
	 *
	 * @return \DateTime $crdate
	 */
	public function getCrdate() {
		return $this->crdate;
	}

	/**
	 * Sets the creation date
	 *
	 * @param \DateTime $creationdate
	 * @return void
	 */
	public function setCrdate($creationdate) {
		$this->crdate = $creationdate;
	}

	/**
	 * Returns the content
	 *
	 * @return \string $content
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Sets the content
	 *
	 * @param \string $content
	 * @return void
	 */
	public function setContent($content) {
		$content = strip_tags($content, '<b><i><em><strong><br /><br/><br>');
		$this->content = $content;
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

	/**
	 * Returns the discussion
	 *
	 * @return \TYPO3\Amazingcomments\Domain\Model\Discussion $discussion
	 */
	public function getDiscussion() {
		return $this->discussion;
	}

	/**
	 * Sets the discussion
	 *
	 * @param \TYPO3\Amazingcomments\Domain\Model\Discussion $discussion
	 * @return void
	 */
	public function setDiscussion(\TYPO3\Amazingcomments\Domain\Model\Discussion $discussion) {
		$this->discussion = $discussion;
	}

}
?>
<?php
namespace TYPO3\Amazingcomments\Domain\Repository;

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
class DiscussionRepository extends \TYPO3\CMS\Extbase\Persistence\Repository {
/*

	protected $defaultOrderings = array(
		'crdate' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING
	);
*/
	
	/**
	 * find latest discussions, used on the start page
	 *
	 * @param $limit limit to a certain amount
	 */
	public function findLatest($limit) {

		// find the latest comments
		$commentResults = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows(
			'discussion',
			'tx_amazingcomments_domain_model_comment',
			'deleted=0',
			'',
			'crdate DESC',
			'100'
		);
		foreach ($commentResults as $commentResult) {
			$discussionUids['DISC' . $commentResult['discussion']] = $commentResult['discussion'];
		}
		
		$discussionUids = array_slice($discussionUids, 0, 5);

		$query = $this->createQuery();
		$query->getQuerySettings()->setRespectStoragePage(FALSE);
		$query->setLimit($limit);
		$query->matching($query->in('uid', $discussionUids));

		// sort by the comments above
		$discussions = $query->execute();
		$finalDiscussions = array_flip($discussionUids);

		foreach ($discussions as $discussion) {
			$finalDiscussions[$discussion->getUid()] = $discussion;
		}
		return $finalDiscussions;
	}

}

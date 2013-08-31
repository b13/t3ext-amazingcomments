<?php

if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('TYPO3.' . $_EXTKEY,
	'LatestComments',
	array('Comment' => 'latest,add,create'),
	array('Comment' => 'latest,add,create') // non-cacheable actions
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('TYPO3.' . $_EXTKEY,
	'ListComments',
	array('Comment' => 'list,add,create'),
	array('Comment' => 'list,add,create') // non-cacheable actions
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin('TYPO3.' . $_EXTKEY,
	'LatestDiscussions',
	array('Discussion' => 'latest'),
	array('Discussion' => 'latest') // non-cacheable actions
);

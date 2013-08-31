<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY,
	'LatestComments', 'Amazing Comments: Show the latest comments'
);


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY,
	'ListComments', 'Amazing Comments: List comments'
);

// show the latest discussions
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin($_EXTKEY,
	'LatestDiscussions', 'Amazing Comments: Show the latest discussions'
);



\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Amazing Comments and Forum');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_amazingcomments_domain_model_comment', 'EXT:amazingcomments/Resources/Private/Language/locallang_csh_tx_amazingcomments_domain_model_comment.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_amazingcomments_domain_model_comment');
$TCA['tx_amazingcomments_domain_model_comment'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:amazingcomments/Resources/Private/Language/locallang_db.xlf:tx_amazingcomments_domain_model_comment',
		'label' => 'headline',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'headline,content,author,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Comment.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_amazingcomments_domain_model_comment.gif'
	),
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_amazingcomments_domain_model_discussion', 'EXT:amazingcomments/Resources/Private/Language/locallang_csh_tx_amazingcomments_domain_model_discussion.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_amazingcomments_domain_model_discussion');
$TCA['tx_amazingcomments_domain_model_discussion'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:amazingcomments/Resources/Private/Language/locallang_db.xlf:tx_amazingcomments_domain_model_discussion',
		'label' => 'headline',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,
		'origUid' => 't3_origuid',
		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'headline,comments,author,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Discussion.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_amazingcomments_domain_model_discussion.gif'
	),
);

	// Adding emailnotification field to pages TCA
$tmpCol = array(
	'tx_amazingcomments_emailnotification' => array(
		'exclude' => 1,
		'label' => 'LLL:EXT:amazingcomments/Resources/Private/Language/locallang_db.xlf:pages.tx_amazingcomments_emailnotification',
		'config' => Array (
			'type' => 'input',
			'size' => '30',
		)
	),
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTCAcolumns('pages', $tmpCol, 1);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes('pages', '--div--;Email Notifications, tx_amazingcomments_emailnotification');

?>
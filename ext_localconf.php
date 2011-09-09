<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

t3lib_extMgm::addPItoST43($_EXTKEY, 'Classes/Plugin.php', '_plugin', 'none', 1);

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['closure'] = 'EXT:closure/Classes/CacheHook.php:&tx_closure_cachehook->clearCachePostProc';
?>
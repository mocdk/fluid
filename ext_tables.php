<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

t3lib_extMgm::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'Fluid: (Optional) default ajax configuration');

if (TYPO3_MODE == 'BE') {

	// register the cache in BE so it will be cleared with "clear all caches"
	try {
		t3lib_cache::initializeCachingFramework();
			// Reflection cache
		if (!$GLOBALS['typo3CacheManager']->hasCache('fluid_template')) {
			$GLOBALS['typo3CacheFactory']->create(
				'fluid_template',
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template']['frontend'],
				$GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['fluid_template']['backend']
			);
		}
	} catch(t3lib_cache_exception_NoSuchCache $exception) {

	}

}
?>
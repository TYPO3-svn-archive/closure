<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
*  Big thanks to networkteam
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
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
 * Clear cache hook to clear Closure cache on clear all cache.
 *
 * @package closure
 */
class Tx_Closure_CacheHook {

	/**
	 *
	 * @param array $parameters
	 * @param t3lib_TCEmain $tcemain
	 * @return void
	 * @author Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
	 */
    public function clearCachePostProc($parameters, $tcemain) {
		if ($parameters['cacheCmd'] === 'all') {
			array_map('unlink', glob(PATH_site . 'typo3temp/closure/*'));
		}
	}
}
?>
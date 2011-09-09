<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2011 Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
 *  Big thanks to Christopher Hlubek from network Team
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
 * Closure Plugin
 *
 * @package closure
 */
class Tx_Closure_Plugin
{

	/**
	 * @var tslib_content
	 */
	public $cObj;

	/**
	 * Output compiled Javascripts
	 *
	 * @param string $content
	 * @param array $conf
	 * @return string
	 * @author Ingo Pfennigstorf <i.pfennigstorf@gmail.com>
	 */
	public function main($content, $conf) {
		$options = array();
		$options['cache'] = ($conf['cache'] == 1);
		$content = '';
		foreach ($conf['jsFiles.'] as $key => $jsFile) {
			if (!is_array($jsFile)) {
				$jsFileConf = is_array($conf['jsFiles.'][$key . '.']) ? $conf['jsFiles.'][$key . '.'] : array();
				$filename = $jsFile;
				if (isset($jsFileConf['filenameWrap.'])) {
					$filename = $this->cObj->stdWrap($filename, $jsFileConf['filenameWrap.']);
				}
				$filename = t3lib_div::getFileAbsFileName($filename);
				try {
					$closureLibFilename = t3lib_div::getFileAbsFileName('EXT:closure/Resources/Private/Libs/compiler.jar');

					if (isset($jsFileConf['output'])) {
						$outputFilename = $this->cObj->stdWrap($jsFileConf['output'], $jsFileConf['output.']);
					} else {
						$jsFilename = substr(basename($filename), 0, -5) . '_' . substr(md5($filename), 0, 6) . '.js';
						$outputFilename = 'typo3temp/closure/' . $jsFilename;
					}
					if (t3lib_div::isAllowedAbsPath(PATH_site . $outputFilename)) {
						if ($this->isCacheExpired(t3lib_div::getFileAbsFileName($outputFilename))) {
							// compile the java command to get the compiled JavaScript file
							$execCommand = 'java -jar ' . $closureLibFilename . ' --js ' . $filename . ' --js_output_file ' . t3lib_div::getFileAbsFileName($outputFilename) . '';
							exec($execCommand);
						}
					}
					else {
						throw new Exception('Output filename ' . $outputFilename . ' is not allowed', 1315570758);
					}
					$linkTag = '<script type="text/javascript" src="' . $outputFilename . '"></script>';
					if (isset($jsFileConf['linkWrap.'])) {
						$linkTag = $this->cObj->stdWrap($linkTag, $jsFileConf['linkWrap.']);
					}
					$content .= $linkTag . chr(10);

				} catch (Exception $exception) {
					if ($conf['showExceptions']) {
						throw $exception;
					} else {
						$content .= '<!-- ' . $exception->getMessage() . '-->';
					}
				}
			}
		}
		return $content;
	}

	/**
	 * Set default Cache Lifetime = 24h
	 *
	 * @param $fileName
	 * @return bool
	 * @todo customize Cachelifetime
	 */
	protected function isCacheExpired($fileName) {
		$return = FALSE;

		$maxLifeTime = 60 * 60 * 24;

		if (file_exists($fileName) && ((time() - filemtime($fileName)) > $maxLifeTime)) {
			$return = TRUE;
		}

		return $return;
	}
}

?>
<?php
	/**
	  * JSONArchiveApi reads a large file retrieved from the NYTimes Archive Api,
	  * and puts it into php array.  The contents of the file are json.
	 */
	class JSONArchiveApi
	{
		static public $m_phpContent = NULL;
		/**
		  * readContentsJSON opens a path name and applies json encoding to
		  * the contents.  These are stored internally in m_phpContent.
		 */
		static public function readContentsJSON($pathName)
		{
			$fileContent = file_get_contents($pathName);
			$this->m_phpContent = json_decode($contents, true);
		}
		/**
		  * checkStructure checkis the structure of the m_phpContents.  Does it
		  * have the correct keys at certain points in the structure.
		 */
		static public function checkStructure()
		{
			// First level: inspect for copyright and response.
			$topKeys = array_keys($this->m_phpContent);
			$copyrightPresent = in_array('copyright', $topKeys);
			$responsePresent = in_array('response', $topKeys);
			$copyrightPresent = $copyrightPresent?$copyrightPresent:(function(){throw new Exception('copyright not present');})();
			$responsePresent = $responsePresent?$responsePresent:(function(){throw new Exception('response not present');})();

			// Second level: inspect for response->meta and response->docs
			$responseKeys = array_keys($this->m_phpContent['response']);
			$metaPresent = in_array('meta', $responseKeys);
			$docsPresent = in_array('docs', $responseKeys);
			$metaPresent = $metaPresent?$metaPresent:(function(){throw new Exception('meta not present');})();
			$docsPresent = $docsPresent?$docsPresent:(function(){throw new Exception('docs not present');})();

			// Third level: inspect response->docs.
			$docsKeys = array_keys($this->m_phpContent['response']['docs']);
			if (count($docsKeys) == 0)
			{
				// @todo either return valid value or maybe return exception.
				// A valid value is probably better.
			}

			$zeroPresent = in_array(0, $docsKeys);
			$zeroPresent = $zeroPresent?$zeroPresent:(function(){throw new Exception('zero not present');})();

			// Fourth level: inspect response->docs->0	
			// @todo complete this.
		}
	}
?>

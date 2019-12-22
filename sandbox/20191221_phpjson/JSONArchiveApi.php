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
			$fileContent = ($fileContent===false)?(function(){throw new Exception('file_get_contents failed');})():$fileContent;
			self::$m_phpContent = json_decode($fileContent, true);
		}
		/**
		  * checkStructure checkis the structure of the m_phpContents.  Does it
		  * have the correct keys at certain points in the structure.
		 */
		static public function checkStructure()
		{
			// First level: inspect for copyright and response.
			$topKeys = array_keys(self::$m_phpContent);
			$copyrightPresent = in_array('copyright', $topKeys);
			$responsePresent = in_array('response', $topKeys);
			$copyrightPresent = $copyrightPresent?$copyrightPresent:(function(){throw new Exception('copyright not present');})();
			$responsePresent = $responsePresent?$responsePresent:(function(){throw new Exception('response not present');})();

			// Second level: inspect for response->meta and response->docs
			$responseKeys = array_keys(self::$m_phpContent['response']);
			$metaPresent = in_array('meta', $responseKeys);
			$docsPresent = in_array('docs', $responseKeys);
			$metaPresent = $metaPresent?$metaPresent:(function(){throw new Exception('meta not present');})();
			$docsPresent = $docsPresent?$docsPresent:(function(){throw new Exception('docs not present');})();

			// Third level: inspect response->docs.
			$docsKeys = array_keys(self::$m_phpContent['response']['docs']);
			if (count($docsKeys) == 0)
			{
				// @todo either return valid value or maybe return exception.
				// A valid value is probably better.
			}

			$zeroPresent = in_array(0, $docsKeys);
			$zeroPresent = $zeroPresent?$zeroPresent:(function(){throw new Exception('zero not present');})();

			// Fourth level: inspect response->docs->0	
			$zeroKeys = array_keys(self::$m_phpContent['response']['docs'][0]);
			$web_urlPresent = in_array('web_url', $zeroKeys);
			$snippetPresent = in_array('snippet', $zeroKeys);
			$lead_paragraphPresent = in_array('lead_paragraph', $zeroKeys);
			$abstractPresent = in_array('abstract', $zeroKeys);
			$web_urlPresent = $web_urlPresent?$web_urlPresent:(function(){throw new Exception('web_url not present');})();
			$snippetPresent = $snippetPresent?$snippetPresent:(function(){throw new Exception('snippet not present');})();
			$lead_paragraghPresent = $lead_paragraphPresent?$lead_paragraphPresent:(function(){throw new Exception('lead_paragraph not present');})();
			$abstractPresent = $abstractPresent?$abatractPresent:(function(){throw new Exception('abstract not present');})();
			return true;
			// @todo complete this.
		}
	}
?>

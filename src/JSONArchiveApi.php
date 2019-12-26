<?php
	namespace WhoIsAroundWho;
	require 'vendor/autoload.php';

	\Logger::configure('config/config.xml');

	/**
	  * JSONArchiveApi reads a large file retrieved from the NYTimes Archive Api,
	  * and puts it into php array.  The contents of the file are json.
	 */
	class JSONArchiveApi
	{
		static private $m_log = NULL;
		static public $m_docProperties = array(
			'web_url',
			'snippet',
			'lead_paragraph',
			'abstract',
			'blog',
			'source',
			'multimedia',
			'headline',
			'keywords',
			'pub_date',
			'document_type',
			'news_desk',
			'section_name',
			'subsection_name',
			'byline',
			'type_of_material',
			'_id',
			'word_count',
			'slideshow_credits'
		);
		static public $m_phpContent = NULL;
		/**
		  * readContentsJSON opens a path name and applies json encoding to
		  * the contents.  These are stored internally in m_phpContent.
		 */
		static public function readContentsJSON($pathName)
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('readContentsJSON: start');
			$log->info('... pathName='.$pathName);
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
			$abstractPresent = $abstractPresent?$abstractPresent:(function(){throw new Exception('abstract not present');})();
			return true;
			// @todo complete this.
		}

		/**
		  * find finds token in the key of each doc under response->docs.
		  * An array of doc numbers are returned.  key must be a legal key
		  * (such as web_url, snippet, abstract etc).
		  */
		public function find($token, $key)
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('find: start');
			$log->info('... token: .'.$token);
			$log->info('... key: '.$key);
			$docIndexes = array();
			if (!in_array($key, self::$m_docProperties))
			{
				throw new Exception('key '.$key.' not a recognized property of doc');
			}
			$docs = self::$m_phpContent['response']['docs'];
			foreach ($docs as $docKey=>$docValue)
			{
				$log->debug('... docValue(ve):'.var_export($docValue, TRUE));
				$log->debug('... docKey='.$docKey);
				$log->debug('... docValue[key]='.$docValue[$key]);
				$tokenCt = substr_count($docValue[$key], $token);
				if ($tokenCt > 0)
				{
					$docIndexes[] = $docKey;
				}
			}
			return $docIndexes;
		}

		/**
		  * getAvailabeJSONFiles: this determines what json data is available
		  * at the storage directory given by storageDir.  It is assumed
		  *
		  * 	* files are named YYYYMM.json
		  * 	* YYYY fully represents a year (e.g. 1932)
		  * 	* MM represents a month (e.g. 04 represents April).
		 */
		static public function getAvailableJSONFiles($storageDir)
		{
			$posLastForward = strrpos($storageDir, "/");
			$lenStorageDir = strlen($storageDir);
			$endsWithForward = $posLastForward == ($lenStorageDir-1)?TRUE:FALSE;

			$forwardSlashA = $endsWithForward?"":"/";
			$availFiles = glob($storageDir.$forwardSlashA."[12][89][0-9][0-9][0-9][0-9].json");
			$log = \Logger::getLogger("myAppender");
			$log->info('JSONArchiveApi::getAvailableJSONData: start');
			foreach ($availFiles as $someFile)
			{
				$log->info('... someFile='.$someFile);
			}
			return $availFiles;
		}
		
		/**
		  * getAvailableJSONYearsMonths returns years and months of the
		  * data available.
		 */
		static public function getAvailableJSONYearsMonths($storageDir)
		{
			$log = \Logger::getLogger("myAppender");
			$log->info('JSONArchiveApi::getAvailableJSONYearsMonths: start');
			$retArray = array();
			$jsonFiles = self::getAvailableJSONFiles($storageDir);
			foreach ($jsonFiles as $jsonFile)
			{
				$log->info('... jsonFile='.$jsonFile);
				$subJsonFile = explode("/", $jsonFile);;
				$statusExplode = count($subJsonFile) == 2?"ok":(function() { throw new \Exception('two components expected');})();
				$fileName = $subJsonFile[1];
				$subFileName = explode(".", $fileName);
				$log->info('... subFileName='.var_export($subFileName, TRUE));;
				$statusExplode = count($subFileName) == 2?"ok":(function() { throw new \Exception('two components expected');})();
				$yyyymm = $subFileName[0];
				$pm = preg_match('/^[0-9][0-9][0-9][0-9][0-9][0-9]$/', $yyyymm);
				$pm = $pm == TRUE?$pm:(function(){throw new \Exception('yyyymm format expected');})();

				$log->info('... yyyymm='.$yyyymm);
				$year = substr($yyyymm, 0, 4);
				$month = substr($yyyymm, 4, 2);
				$log->info('... year='.$year);
				$log->info('... month='.$month);
				if (!isset($retArray[$year]))
				{
					$retArray[$year] = array();
				}
				$retArray[$year][] = $month;
			}
			return $retArray;

		}


	}
?>

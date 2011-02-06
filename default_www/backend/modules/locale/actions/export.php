<?php

/**
 * This is the export-action, it will create a XML with locale items.
 *
 * @package		backend
 * @subpackage	locale
 *
 * @author		Dieter Vanden Eynde <dieter@netlash.com>
 * @since		2.0
 */
class BackendLocaleExport extends BackendBaseActionIndex
{
	/**
	 * Filter variables.
	 *
	 * @var	array
	 */
	private $filter;


	/**
	 * Locale items.
	 *
	 * @var	array
	 */
	private $locale;


	/**
	 * Builds the query for this datagrid.
	 *
	 * @return	array		An array with two arguments containing the query and its parameters.
	 */
	private function buildQuery()
	{
		// init var
		$parameters = array();

		// start of query
		$query = 'SELECT l.id, l.language, l.application, l.module, l.type, l.name, l.value
					FROM locale AS l
					WHERE 1';

		// add language
		if($this->filter['language'] !== null)
		{
			$query .= ' AND l.language = ?';
			$parameters[] = $this->filter['language'];
		}

		// add application
		if($this->filter['application'] !== null)
		{
			$query .= ' AND l.application = ?';
			$parameters[] = $this->filter['application'];
		}

		// add module
		if($this->filter['module'] !== null)
		{
			$query .= ' AND l.module = ?';
			$parameters[] = $this->filter['module'];
		}

		// add type
		if($this->filter['type'] !== null)
		{
			$query .= ' AND l.type = ?';
			$parameters[] = $this->filter['type'];
		}

		// add name
		if($this->filter['name'] !== null)
		{
			$query .= ' AND l.name LIKE ?';
			$parameters[] = '%'. $this->filter['name'] .'%';
		}

		// add value
		if($this->filter['value'] !== null)
		{
			$query .= ' AND l.value LIKE ?';
			$parameters[] = '%'. $this->filter['value'] .'%';
		}

		// end of query
		$query .= ' ORDER BY l.application, l.module, l.name ASC;';

		// cough up
		return array($query, $parameters);
	}


	/**
	 * Execute the action.
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// set filter
		$this->setFilter();

		// set items
		$this->setItems();

		// create XML
		$this->createXML();
	}


	/**
	 * Get full type name.
	 *
	 * @return	string
	 * @param	string $type
	 */
	private function getType($type)
	{
		// get full type name
		switch($type)
		{
			case 'act':
				$type = 'action';
				break;
			case 'err':
				$type = 'error';
				break;
			case 'lbl':
				$type = 'label';
				break;
			case 'msg':
				$type = 'message';
				break;
		}

		// cough up full name
		return $type;
	}


	/**
	 * Create the XML based on the locale items.
	 *
	 * @return	void
	 */
	private function createXML()
	{
		// init XML
		$xml = new DOMDocument('1.0', 'utf-8');

		// set some properties
		$xml->preserveWhiteSpace = false;
		$xml->formatOutput = true;

		// locale root element
		$root = $xml->createElement('locale');
		$xml->appendChild($root);

		// loop applications
		foreach($this->locale as $application => $modules)
		{
			// create application element
			$applicationElement = $xml->createElement($application);
			$root->appendChild($applicationElement);

			// loop modules
			foreach($modules as $module => $types)
			{
				// create application element
				$moduleElement = $xml->createElement($module);
				$applicationElement->appendChild($moduleElement);

				// loop types
				foreach($types as $type => $items)
				{
					// loop items
					foreach($items as $name => $translations)
					{
						// create application element
						$itemElement = $xml->createElement('item');
						$moduleElement->appendChild($itemElement);

						// attributes
						$itemElement->setAttribute('type', $this->getType($type));
						$itemElement->setAttribute('name', $name);

						// loop translations
						foreach($translations as $translation)
						{
							// create translation
							$translationElement = $xml->createElement('translation');
							$itemElement->appendChild($translationElement);

							// attributes
							$translationElement->setAttribute('language', $translation['language']);

							// set content
							$translationElement->appendChild(new DOMCdataSection($translation['value']));
						}
					}
				}
			}
		}

		// xml headers
		SpoonHTTP::setHeaders('content-type: text/xml;charset=utf-8');

		// output XML
		echo $xml->saveXML();

		// stop script
		exit;
	}


	/**
	 * Sets the filter based on the $_GET array.
	 *
	 * @return	void
	 */
	private function setFilter()
	{
		$this->filter['language'] = (isset($_GET['language'])) ? $this->getParameter('language') : BL::getWorkingLanguage();
		$this->filter['application'] = $this->getParameter('application');
		$this->filter['module'] = $this->getParameter('module');
		$this->filter['type'] = $this->getParameter('type');
		$this->filter['name'] = $this->getParameter('name');
		$this->filter['value'] = $this->getParameter('value');
	}


	/**
	 * Build items array and group all items by application, module, type and name.
	 *
	 * @return	void
	 */
	private function setItems()
	{
		// build our query
		list($query, $parameters) = $this->buildQuery();

		// get locale from the database
		$items = (array) BackendModel::getDB()->getRecords($query, $parameters);

		// init
		$this->locale = array();

		// group by application, module, type and name
		foreach($items as $item)
		{
			$this->locale[$item['application']][$item['module']][$item['type']][$item['name']][] = $item;
		}

		// no need to keep this around
		unset($items);
	}
}

?>
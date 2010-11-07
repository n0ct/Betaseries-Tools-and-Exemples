<?php

/**
 * 
 * XML/JSON abstraction class.
 * You can add an array of data and output it in XML or JSON.
 * Most functions are based on SimpleXML because of my original needs.
 * 
 * Author: Maxime VALETTE
 * Contact: maxime@maximevalette.com
 * 
 * @package XML_JSON
 * 
 */

class XML_JSON {

	/**
	 *
	 * Format variable.
	 * @access private
	 * var string
	 *
	 */

	var $format = 'xml';

	/**
	 *
	 * Class constructor.
	 *
	 * @param string $format 'xml' or 'json'.
	 * @return class
	 *
	 */

	function __construct($format) {

		if (in_array($format,array('xml','json'))) {
			$this->format = $format;
		}

	}

	/**
	 *
	 * Creates a new document.
	 *
	 * @return true
	 *
	 */

	function newDocument() {

		if ($this->format == 'xml') {

			$this->X = new XMLWriter();
			$this->X->openMemory();
			$this->X->setIndent(true);
			$this->X->startDocument('1.0', 'UTF-8');

		} elseif ($this->format == 'json') {

			$this->X = array();
			$this->current = &$this->X;
			$this->parents = array();

		}

		return true;

	}

	/**
	 *
	 * Starts a new element.
	 *
	 * @param string $el Name of the element.
	 * @return true
	 *
	 */

	function startElement($el) {

		if ($this->format == 'xml') {

			$this->X->startElement($el);

		} elseif ($this->format == 'json') {

			$this->parents[] = $el;
			$this->current[$el] = array();
			$this->current = &$this->current[$el];

		}

		return true;

	}

	/**
	 *
	 * Ends the current element.
	 *
	 * @return true
	 *
	 */

	function endElement() {

		if ($this->format == 'xml') {

			$this->X->endElement();

		} elseif ($this->format == 'json') {

			array_pop($this->parents);
			$this->current = &$this->X;
			foreach ($this->parents as $k) {
				$this->current = &$this->current[$k];
			}

		}

		return true;

	}

	/**
	 *
	 * Writes an array in the document.
	 * 
	 * @param string $title Used in the XML format to name each element's tag.
	 * @param array $array The data.
	 * @return true
	 * 
	 */

	function writeArray($title,$array) {

		if ($this->format == 'xml') {

			foreach ($array as $key => $value) {

				$this->X->startElement($title);

				foreach ($value as $k => $v) {

					if (!is_array($v)) {

						if (preg_match('/<[^>]+>/',$v)) {
							$this->X->startElement($k);
							$this->X->writeCData($v);
							$this->X->endElement();
						} else {
							$this->X->writeElement($k,$v);
						}

					} else {

						$this->X->startElement($k);

						foreach ($v as $v_k => $v_v) {

							if (!is_array($v_v)) {

								if (preg_match('/<[^>]+>/',$v_v)) {
									$this->X->startElement($v_k);
									$this->X->writeCData($v_v);
									$this->X->endElement();
								} else {
									$this->X->writeElement($v_k,$v_v);
								}

							} else {

								$this->X->startElement(substr($k,0,strlen($k)-1));

								foreach ($v_v as $v_v_k => $v_v_v) {

									$this->X->writeElement($v_v_k,$v_v_v);

								}

								$this->X->endElement();

							}

						}

						$this->X->endElement();

					}

				}

				$this->X->endElement();

			}

		} elseif ($this->format == 'json') {

			$this->current = $array;

		}

		return true;

	}

	/**
	 *
	 * Writes a single element.
	 *
	 * @param string $el Name of the element.
	 * @param string $val Value of the element.
	 * @return true
	 *
	 */

	function writeElement($el,$val) {

		if ($this->format == 'xml') {

			$this->X->writeElement($el,$val);

		} elseif ($this->format == 'json') {

			$this->current[$el] = $val;

		}

		return true;

	}

	/**
	 *
	 * Writes text into an element.
	 *
	 * @param string $val Text to write.
	 * @return true
	 *
	 */

	function text($val) {

		if ($this->format == 'xml') {

			$this->X->text($val);

		} elseif ($this->format == 'json') {

			if (is_array($this->current)) {
				$this->current['content'] = $val;
			} else {
				$this->current = $val;
			}

		}

		return true;

	}

	/**
	 *
	 * Writes CData into an element.
	 *
	 * @param string $val Value to write in CData.
	 * @return true
	 *
	 */

	function writeCData($val) {

		if ($this->format == 'xml') {

			$this->X->writeCData($val);

		} elseif ($this->format == 'json') {

			if (is_array($this->current)) {
				$this->current['content'] = $val;
			} else {
				$this->current = $val;
			}

		}

		return true;

	}

	/**
	 *
	 * Writes an attribute of the current element.
	 *
	 * @param string $el Name of the attribute.
	 * @param string $val Value of the attribute.
	 * @return true
	 *
	 */

	function writeAttribute($el,$val) {

		if ($this->format == 'xml') {

			$this->X->writeAttribute($el,$val);

		} elseif ($this->format == 'json') {

			$this->current[$el] = $val;

		}

		return true;

	}

	/**
	 *
	 * Flushes the output in XML or JSON.
	 *
	 * @return string Output.
	 *
	 */

	function flush() {

		if ($this->format == 'xml') {

			return $this->X->flush();

		} elseif ($this->format == 'json') {

			return json_encode($this->X);

		}

	}

}

?>

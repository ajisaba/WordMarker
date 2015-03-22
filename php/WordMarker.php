<?php
/**
 * This is a class for adding markers befor specified words and after thme.
 * It does not depend on order of specified words to add markers.
 */
class WordMarker {
	const INFO_CHAR = 0;
	const INFO_COUNT_START = 1;
	const INFO_COUNT_END = 2;

	/**
	 * List of key words for adding markers.
	 */
	private $listWord;

	/**
	 * Multiby endoding
	 */
	private $encoding;

	/**
	 * A start marker string
	 */
	private $markerStart;

	/**
	 * A end marker string
	 */
	private $markerEnd;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setEncoding(mb_internal_encoding());
	}

	/**
	 * Set a endcoding.
	 *
	 * @param string $encoding
	 */
	public function setEncoding($encoding) {
		$this->encoding = $encoding;
	}

	/**
	 * Set a start marker and a end markar.
	 *
	 * @param string $start
	 * @param string $end
	 */
	public function setMarker($start, $end) {
		$this->markerStart = $start;
		$this->markerEnd = $end;
	}

	/**
	 * Returns a list of keywords which are added markers.
	 *
	 * @return array
	 */
	public function getListWord() {
		return $this->listWord;
	}

	/**
	 * Returns a array of information for a single character.
	 *
	 * @param string $ch  a single word
	 * @param  int $posStart 
	 * @return array
	 */
	public function getCharInfo($char, $countStart, $countEnd) {
		$info = array();
		$info[self::INFO_CHAR] = $char;
		$info[self::INFO_COUNT_START] = $countStart;
		$info[self::INFO_COUNT_END] = $countEnd;
		return $info;
	}

	/**
	 * Returns a string that is added marker to specified keywords.
	 *
	 * @param string $str
	 * @param array $listWord
	 * @return string
	 */
	public function addMarker($str, $listWord = null) {
		// Check string.
		$length = mb_strlen($str);
		$strInfo = array();
		for ($index = 0; $index < $length; $index ++) {
			$ch = mb_substr($str, $index, 1);
			$strInfo[$index] = $this->getCharInfo($ch, 0, 0);
		}
		$strInfo[$length] = $this->getCharInfo('', 0, 0);


		// Check keywords.
		if (is_null($listWord)) {
			$listKey = $this->getListWord();
		} else {
			$listKey = $listWord;
		}
		foreach($listKey as $indexWord => $word) {
			$lenWord = mb_strlen($word);
			$pos = 0;
			while ($pos < $length &&
				($pos = mb_stripos($str, $word, $pos, $this->encoding)) !== false) {
				$strInfo[$pos][self::INFO_COUNT_START] ++;
				$strInfo[$pos + $lenWord][self::INFO_COUNT_END] ++;
				$pos ++;
			}	
		}

		// Get a string that is added markers to target words. 
		$isMatch = false;
		$result = '';
		$count = 0;
		foreach($strInfo as $index => $info) {
			if ($info[self::INFO_COUNT_START] > 0) {
				$count += $info[self::INFO_COUNT_START];
				if ($isMatch) {
					$result .= $info[self::INFO_CHAR];
					if ($info[self::INFO_COUNT_END] > 0) {
						// Match some words
						$count -= $info[self::INFO_COUNT_END];
					}
				} else {
					$isMatch = true;
					$result .= $this->markerStart .$info[self::INFO_CHAR];
				}
			} elseif ($info[self::INFO_COUNT_END] > 0) {
				if ($isMatch) {
					$count -= $info[self::INFO_COUNT_END];
					if ($count == 0) {
						$result .= $this->markerEnd .$info[self::INFO_CHAR];
						$isMatch = false;
					} else {
						// Match some words
						$result .= $info[self::INFO_CHAR];
					}
				} else {
					throw new Exception("Error unknown " .print_r($info, true));
				}
			} else {
				$result .= $info[self::INFO_CHAR];
			}
		}
		return $result;
	}
}

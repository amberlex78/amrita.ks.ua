<?php
/**
 * Class Validation_Template
 */
abstract class Validation_Template extends Kohana_Validation
{
	/**
	 * Creates a new Validation instance
	 *
	 * @param array $array Array to use for validation
	 * @return Validation|static
	 */
	public static function factory(array $array)
	{
		return new static($array);
	}

	/**
	 * Class constructor
	 *
	 * @param array $array Array to validate
	 */
	public function __construct(array $array)
	{
		parent::__construct($array);

		// Add labels
		$this->labels($this->_labels());

		// Add rules
		foreach($this->_rules() as $field => $rules)
		{
			$this->rules($field, $rules);
		}
	}

	/**
	 * Sets rules
	 *
	 * @return array
	 */
	protected function _rules()
	{
		return array();
	}

	/**
	 * Sets the label names for fields
	 *
	 * @return array
	 */
	protected function _labels()
	{
		return array();
	}
}
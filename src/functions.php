<?php
	/**
	 * Finds the value at the end of the hierarchical key path.
	 * @param array $array The array to dive into.
	 * @param array $keys The key path into the array.
	 * @return mixed
	 */
	function value_for_key_path(array $array, array $keys)
	{
		$value = $array;
		foreach($keys as $key)
			$value = $value[$key];
		return $value;
	}

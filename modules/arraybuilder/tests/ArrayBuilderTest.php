<?php

class ArrayBuilderTest extends Unittest_TestCase
{	
	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereGreaterOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereGreaterOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereGreaterOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereGreaterMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereLessOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 6);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereLessMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereLessOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereLessOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereNotEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5]
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOneWhereNotEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsOneWhereLikeOneResult()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann'],
			['name' => 'Lionel Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'MeSsI');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsOneWhereLikeMoreResult()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann'],
			['name' => 'Lionel Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'MaR');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}	

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsOneWhereLikeNoResult()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann'],
			['name' => 'Lionel Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'Ronaldo');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 9)->or_where('number', '=', 1);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 9)->or_where('number', '=', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}	

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereGreaterOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 12)->or_where('number', '>=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereGreaterOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 10)->or_where('number', '>=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereGreaterOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 10)->or_where('number', '>', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereGreaterMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 12)->or_where('number', '>', 4);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereLessOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 6)->or_where('number', '<', 3);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereLessMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 10)->or_where('number', '<', 8);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereLessOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 5)->or_where('number', '<=', 2);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereLessOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 10)->or_where('number', '<=', 8);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersOrWhereLessOrNotEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10)->or_where('number', '!=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsOrWhereLikeOneResult()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann'],
			['name' => 'Lionel Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'MeSsI')->or_where('name', 'LIKE', 'ronaldo');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsOrWhereLikeMoreResult()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann'],
			['name' => 'Lionel Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'MaR')->or_where('name', 'LIKE', 'mesS');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9'],
			['name' => 'Bognár Mariann', 'number' => 12],
			['name' => 'Lionel Messi', 'number' => 8],
			['name' => 'C. Ronaldo', 'number' => 7]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'MaR')->or_where('number', '=', '7');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('number', '=', 9)->or_where('number', '<=', 7);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'Bognár')->or_where('number', '<=', '8')->or_where('number', '=', 7);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);

		$this->assertFalse($expressions[0][2]);
		$this->assertFalse($expressions[1][2]);
		$this->assertFalse($expressions[2][2]);
		$this->assertTrue($expressions[3][2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereGreaterOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereGreaterOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereGreaterOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereGreaterMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 6);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereLessOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 6);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereLessMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 12);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereLessOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 6);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereLessOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereNotEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOneWhereNotEqualsEvenMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 2);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsStringsOneWhereLikeOneResult()
	{
		$data = [
			['name' => 'James Hetfiels'],
			['name' => 'Kirk Hammett'],
			['name' => 'Lars Urlich'],
			['name' => 'James Winstone'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'LArs');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertFalse($evaluates[3]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsStringsOneWhereLikeMoreResult()
	{
		$data = [
			['name' => 'James Hetfiels'],
			['name' => 'Kirk Hammett'],
			['name' => 'Lars Urlich'],
			['name' => 'James Winstone'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'James ');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 10)->or_where('number', '=', 1);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 10)->or_where('number', '=', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereGreaterOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 10)->or_where('number', '>=', 30);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereGreaterOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 9)->or_where('number', '>=', 8);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereGreaterOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 9)->or_where('number', '>', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereGreaterMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 8)->or_where('number', '>', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereLessOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 6)->or_where('number', '<', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereLessMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 9)->or_where('number', '<', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereLessOrEqualsOneResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 6)->or_where('number', '<=', 4);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereLessOrEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 9)->or_where('number', '<=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereNotEqualsMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10)->or_where('number', '!=', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsNumbersOrWhereNotEqualsEvenMoreResult()
	{
		$data = [
			['number' => 10],
			['number' => 5],
			['number' => 9],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 10)->or_where('number', '!=', 5);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsStringsOrWhereLikeOneResult()
	{
		$data = [
			['name' => 'James Hetfiels'],
			['name' => 'Kirk Hammett'],
			['name' => 'Lars Urlich'],
			['name' => 'James Winstone'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'LArs')->or_where('name', 'LIKE', 'KIRK');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertFalse($evaluates[3]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsStringsOrWhereLikeMoreResult()
	{
		$data = [
			['name' => 'James Hetfiels'],
			['name' => 'Kirk Hammett'],
			['name' => 'Lars Urlich'],
			['name' => 'James Winstone'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'James ')->or_where('name', 'LIKE', 'HammeT');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);
	}

	/**
	 * @group where
	 * @group evaluates
	 * @group bug.v21.1
	 */
	public function testEvaluateExpressionsOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9'],
			['name' => 'Bognár Mariann', 'number' => 12],
			['name' => 'Lionel Messi', 'number' => 8],
			['name' => 'C. Ronaldo', 'number' => 7]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'MaR')->or_where('number', '=', '7');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'Ron')->or_where('number', '<', '7')->or_where('number', '=', 7);		

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'Bognár')->or_where('number', '<=', '8')->or_where('number', '=', 7);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertTrue($evaluates[3]);
	}

	/**
	 * @group where
	 * @group expressions
	 * @group bug.v21.3
	 */
	public function testAddWhereExpressionsStringEquals()
	{
		$data = [
			['number' => 10, 'name' => 'Ten'],
			['number' => 5, 'name' => 'five'],
			['number' => 9, 'name' => 'joó'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', '=', 'JoÓ');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 * @group bug.v21.2
	 */
	public function testAddWhereExpressionsStringNotEquals()
	{
		$data = [
			['number' => 10, 'name' => 'Ten'],
			['number' => 5, 'name' => 'five'],
			['number' => 9, 'name' => 'joó'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', '!=', 'joó');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
	}

	/**
	 * @group where
	 * @group expressions
	 * @group bug.v21.2
	 * @group bug.v21.3
	 */
	public function testAddwhereExpressionsStringLikeCaseInsensitiveSpecialChars()
	{
		$data = [
			['name' => 'Joó Martin'],
			['name' => 'Bognár Mariann']
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'JoÓ');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'BOGNÁR');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('name', 'LIKE', 'Bognár')->or_where('name', 'LIKE', 'MARtiN');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[1][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 * @group bug.v21.2
	 */
	public function testAddWhereExpressionsNumbersAndWhereEqualsOneResult()
	{
		$data = [
			['number' => 10, 'name' => 'Ten'],
			['number' => 5, 'name' => 'five'],
			['number' => 9, 'name' => 'nine'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 9)->and_where('name', '=', 'NinE');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}	

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereEqualsMoreResult()
	{		
		$data = [
			['number' => 10, 'name' => 'Ten'],
			['number' => 9, 'name' => 'NINE'],
			['number' => 9, 'name' => 'nine'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '=', 9)->and_where('name', '=', 'nine');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}	

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereGreaterOrEqualsOneResult()
	{		
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 10)->and_where('value', '>=', 2);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereGreaterOrEqualsMoreResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>=', 9)->and_where('value', '>=', 2);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereGreaterOneResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 9)->and_where('value', '>', 10);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereGreaterMoreResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '>', 8)->and_where('value', '>', 1);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereLessOneResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 6)->and_where('value', '<', 30);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereLessMoreResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<', 20)->and_where('value', '<', 30);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereLessOrEqualsOneResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 5)->and_where('value', '<=', 14);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereLessOrEqualsMoreResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '<=', 40)->and_where('value', '<=', 40);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsNumbersAndWhereNotEqualsMoreResult()
	{
		$data = [
			['number' => 10, 'value' => 14],
			['number' => 5, 'value' => 23],
			['number' => 9, 'value' => 4],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('number', '!=', 5)->and_where('number', '!=', 9);

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsAndWhereLikeOneResult()
	{
		$data = [
			['first' => 'Joó', 'last' => 'Martin'],
			['first' => 'Bognár', 'last' => 'Mariann'],
			['first' => 'Lionel', 'last' => 'Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('last', 'LIKE', 'MeSsI')->and_where('first', 'LIKE', 'lionel');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsStringsAndWhereLikeMoreResult()
	{
		$data = [
			['first' => '', 'last' => 'Martin'],
			['first' => '', 'last' => 'Mariann'],
			['first' => 'Lionel', 'last' => 'Messi'],
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		$select->where('last', 'LIKE', 'MAR')->and_where('first', 'LIKE', '');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsAndWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'is_active' => 1],
			['name' => 'Bognár Mariann', 'number' => 12, 'is_active' => 1],
			['name' => 'Lionel Messi', 'number' => 8, 'is_active' => 0],
			['name' => 'C. Ronaldo', 'number' => 7, 'is_active' => 1]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'MaR')->and_where('is_active', '=', '1');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('number', '=', 9)->and_where('name', '=', 'JOÓ MArtin');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertFalse($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertFalse($expressions[3][1]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('name', 'LIKE', 'Bognár')->and_where('number', '<=', '8');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertFalse($expressions[0][1]);
		$this->assertFalse($expressions[1][1]);
		$this->assertTrue($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsAndOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'is_active' => 1, 'company_name' => 'Jmweb Zrt.'],
			['name' => 'Bognár Mariann', 'number' => 12, 'is_active' => 1, 'company_name' => 'Mariann Zrt.'],
			['name' => 'Lionel Messi', 'number' => 8, 'is_active' => 0, 'company_name' => ''],
			['name' => 'C. Ronaldo', 'number' => 7, 'is_active' => 1, 'company_name' => null]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('number', '>=', 8)->and_where('is_active', '=', '1')->or_where('name', 'LIKE', 'messi');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertTrue($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);

		$this->assertFalse($expressions[0][2]);
		$this->assertFalse($expressions[1][2]);
		$this->assertTrue($expressions[2][2]);
		$this->assertFalse($expressions[3][2]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('company_name', '!=', '')->and_where('is_active', '=', '1');

		$this->invokeMethod($select, 'addWhereExpressions', []);

		$expressions = $select->getExpressions();

		$this->assertTrue($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertFalse($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertTrue($expressions[3][1]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsAndOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'is_active' => 1, 'company_name' => 'Jmweb Zrt.'],
			['name' => 'Bognár Mariann', 'number' => 12, 'is_active' => 1, 'company_name' => 'Mariann Zrt.'],
			['name' => 'Lionel Messi', 'number' => 8, 'is_active' => 0, 'company_name' => ''],
			['name' => 'C. Ronaldo', 'number' => 7, 'is_active' => 1, 'company_name' => null]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('number', '>=', 8)->and_where('is_active', '=', '1')->or_where('name', 'LIKE', 'messi');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertFalse($evaluates[3]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('company_name', '!=', '')->and_where('is_active', '=', '1');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertFalse($evaluates[3]);
	}

	/**
	 * @group where
	 * @group expressions
	 */
	public function testAddWhereExpressionsFloatNumbersAndOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('float', '>=', 10.43)->and_where('float1', '<', '5.412')->or_where('float1', '=', 10.846);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertTrue($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertFalse($expressions[3][1]);

		$this->assertFalse($expressions[0][2]);
		$this->assertFalse($expressions[1][2]);
		$this->assertTrue($expressions[2][2]);
		$this->assertFalse($expressions[3][2]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('float', '>=', 12.4)->or_where('float1', '<=', '10');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$expressions = $select->getExpressions();

		$this->assertFalse($expressions[0][0]);
		$this->assertTrue($expressions[1][0]);
		$this->assertFalse($expressions[2][0]);
		$this->assertTrue($expressions[3][0]);

		$this->assertTrue($expressions[0][1]);
		$this->assertTrue($expressions[1][1]);
		$this->assertFalse($expressions[2][1]);
		$this->assertFalse($expressions[3][1]);
	}

	/**
	 * @group where
	 * @group evaluates
	 */
	public function testEvaluateExpressionsFloatNumbersAndOrWhereMixed()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('float', '>=', 10.43)->and_where('float1', '<', '5.412')->or_where('float1', '=', 10.846);

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertFalse($evaluates[3]);

		$select = new Array_Builder_Select();
		$select->from($data);

		$select->where('float', '>=', 12.4)->or_where('float1', '<=', '10');

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);
	}	
	
	/**
	 * @group where
	 * @group evaluates
	 * @group openclose
	 * @group bug
	 */
	public function testEvaulateExpressionsAndWhereOpenClose()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		$select
			->and_where_open()
				->where('float', '>=', 10.43)
				->and_where('float1', '<', '5.412')
			->and_where_close();

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();	

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertFalse($evaluates[3]);
		
		$select = new Array_Builder_Select();
		$select->from($data);

		$select
			->and_where_open()
				->where('name', 'LIKE', 'ronaldo')
				->and_where('number', '<=', 10)
				->and_where('float', '>=', 10.43)
				->and_where('float1', '<', '50.412')
			->and_where_close();

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);
		
		$select = new Array_Builder_Select();
		$select->from($data);

		$select
			->and_where_open()
				->where('name', 'LIKE', 'ronaldo')
				->and_where('number', '<=', 10)
				->and_where('float', '>=', 10.43)
				->and_where('float1', '<', '20.412')
			->and_where_close();

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertFalse($evaluates[3]);
	}
	
	/**
	 * @group where
	 * @group evaluates
	 * @group openclose
	 */
	public function testEvaulateExpressionsOrWhereOpenClose()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$select = new Array_Builder_Select();
		$select->from($data);

		/**
		 * WHERE name LIKE 'bognár' OR (float >= 20 OR float < 1)
		 */
		$select
			->where('name', 'LIKE', 'bognár')
			->or_where_open()
				->or_where('float', '>=', 20)
				->or_where('float', '<', 1)
			->or_where_close();

		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertTrue($evaluates[3]);				
	}
	
	/**
	 * @group where
	 * @group evaulats
	 * @group openclose
	 */
	public function testEvaulateExpressionsOrWhereAndWhereOpenClose()
	{
		$data = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];

		$select = new Array_Builder_Select();
		$select->from($data);
		
		/**
		 * WHERE number <= 7
		 * OR (float > 1 AND float1 >1)
		 */
		$select
			->where('number', '<=', 7)
			->or_where_open()
				->and_where('float', '>', 1)
				->and_where('float1', '>', '1')
			->or_where_close();
		
		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();		

		$this->assertTrue($evaluates[0]);
		$this->assertTrue($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);
				
		/*$select = new Array_Builder_Select();
		$select->from($data);*/
		
		/**
		 * WHERE number > 7
		 * AND (name LIKE 'MESSI' OR name LIKE 'ronaldo')
		 */
		/*$select
			->where('number', '>', 7)
			->and_where_open()
				->or_where('name', 'LIKE', 'MESSI')
				->or_where('name', 'LIKE', 'ronaldo')
			->and_where_close();
		
		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertFalse($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertTrue($evaluates[2]);
		$this->assertTrue($evaluates[3]);
		
		$data1 = [
			['name' => 'Joó Martin', 'number' => '9', 'float' => 1.34, 'float1' => 1.543],
			['name' => 'Bognár Mariann', 'number' => 12, 'float' => 12.4, 'float1' => 2.742],
			['name' => 'Lionel Messi', 'number' => 8, 'float' => 0.9, 'float1' => 10.846],
			['name' => 'C. Ronaldo', 'number' => 7, 'float' => 31.947, 'float1' => 40.453]
		];
		
		$select = new Array_Builder_Select();
		$select->from($data1);*/
		
		/**
		 * WHERE number > 7
		 * AND (name LIKE 'MESSI' OR name LIKE 'ronaldo')
		 * OR (float1 = 1.543 AND float != 2)
		 * 
		 * 1 && 1 || 0
		 * 
		 * Messi, Joó
		 */
		/*$select
			->where('number', '>', 7)
			->and_where_open()
				->or_where('name', 'LIKE', 'MESSI')
				->or_where('name', 'LIKE', 'ronaldo')
			->and_where_close()
			->or_where_open()
				->and_where('float1', '=', '1.543')
				->and_where('float', '!=', 2)
			->or_where_close();
		
		$this->invokeMethod($select, 'addWhereExpressions', []);
		$this->invokeMethod($select, 'evaluateExpressions', []);

		$evaluates = $select->getEvaluates();

		$this->assertTrue($evaluates[0]);
		$this->assertFalse($evaluates[1]);
		$this->assertFalse($evaluates[2]);
		$this->assertTrue($evaluates[3]);*/
	}
}
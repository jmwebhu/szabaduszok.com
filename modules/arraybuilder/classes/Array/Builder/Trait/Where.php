<?php

trait Array_Builder_Trait_Where
{	
	/**
	 * WHERE zaradek
	 *
	 * ['type', 'col', 'rel', 'val']
	 *
	 * @var array $_where
	 */
	protected $_where = [];
	
	/**
	 * A $_where ertekekbol osszeallitott logikai kifejezesek. Annyi eleme van, amennyi a $_from tombnek, es minden elem egy ujabb tomb,
	 * amiben annyi elem van, amennyi a $_where tombnek.
	 *
	 * Tehat minden bemeneti elemhez tartozik egy elem az $_expressions tombben, ami egy tovabbi tomb. Ebben a tombben pedig annyi elem van,
	 * amennyi a $_where tombben, ezek pedig bool ertekek.
	 *
	 * Pl.:
	 *
	 * $_from = [
	 * 		111 => ['id' => 1, 'name' => 'first', 'budget' => 2000],
	 * 		112 => ['id' => 2, 'name' => 'second', 'budget' => 3000],
	 * 		...
	 * ];
	 *
	 * $_where = [
	 * 		['type' => 'AND', 'col' => 'budget', 'rel' => '>', 'val' => 5000],
	 * 		['type' => 'OR', 'col' => 'id', 'rel' => '=', 'val' => 2],
	 * 		...
	 * ];
	 *
	 * Ebben az esetben az $_expressions tomb igy fog kinezni:
	 *
	 * $_expressions = [
	 * 		111 => [false, false],
	 * 		112 => [false, true],
	 * 		...
	 * ];
	 *
	 * A kiertekeleskor pedig ezek a bool ertekek lesznek vizsgalva a $_where -ben levo 'type' alapjan. Pl.:
	 *
	 * 	111 =>	false || false
	 * 	112 =>	false || true
	 *
	 * @var array $_expressions
	 */
	protected $_expressions = [];
	
	/**
	 * A beallitott $_expressions ertekeket ertekeli ki es meghataroz minden $_from sorhoz egy true vagy false erteket.
	 * A kiertelekes a $_where tomb ertekek alapjan tortenik.
	 *
	 * @var array $_evaluates
	 */
	protected $_evaluates = [];

	public function getExpressions() { return $this->_expressions; }
	public function getEvaluates() { return $this->_evaluates; }
	
	/**
	 * WHERE zaradek beallitasa
	 *
	 * @param string $col		Mezonev
	 * @param string $rel		Relacio
	 * @param string $val		Ertek
	 * @param string $type		Tipus (and, or). Nem kotelezo
	 *
	 * @return ArrayBuilder
	 */
	public function where($col, $rel, $val, $type = null)
	{
		$where = [
			'col'	=> $col,
			'rel'	=> $rel,
			'val'	=> $val,
			'type'	=> ($type) ? $type : self::WHERE_AND
		];
	
		$this->_where[] = $where;
	
		return $this;
	}
	
	/**
	 * 'AND WHERE' zaradek beallitasa
	 *
	 * @param string $col		Mezonev
	 * @param string $rel		Relacio
	 * @param string $val		Ertek
	 *
	 * @return ArrayBuilder
	 */
	public function and_where($col, $rel, $val)
	{
		return $this->where($col, $rel, $val, self::WHERE_AND);
	}
	
	/**
	 * 'OR WHERE' zaradek beallitasa
	 *
	 * @param string $col		Mezonev
	 * @param string $rel		Relacio
	 * @param string $val		Ertek
	 *
	 * @return ArrayBuilder
	 */
	public function or_where($col, $rel, $val)
	{
		return $this->where($col, $rel, $val, self::WHERE_OR);
	}
	
	/**
	 * Zarojelbe teszi a kovetkezo and_where_close() hivasig szereplo where() -ekben megadott felteteleket
	 *
	 * Pl.:
	 *
	 * 	->and_where_open()
	 * 		->where('id', '=', 1)
	 * 		->where('name', 'LIKE', 'name')
	 * 	->and_where_close()
	 * 	->or_where('budget', '>', 100000)
	 *
	 * 	Eredmeny:
	 * 		WHERE (id = 1 AND name LIKE 'name') OR budget > 100000
	 */
	public function and_where_open()
	{
		return $this->where(null, null, null, self::WHERE_AND_OPEN);
	}
	
	/**
	 * Lezarja az and_where_open() altal megnyitott zarojelet
	 */
	public function and_where_close()
	{
		return $this->where(null, null, null, self::WHERE_AND_CLOSE);
	}
	
	/**
	 * Zarojelbe teszi a kovetkezo or_where_close() hivasig szereplo where() -ekben megadott felteteleket
	 *
	 * Pl.:
	 *
	 * 	->or_where_open()
	 * 		->where('id', '=', 1)
	 * 		->where('name', 'LIKE', 'name')
	 * 	->or_where_close()
	 * 	->and_where('budget', '>', 100000)
	 *
	 * 	Eredmeny:
	 * 		WHERE (id = 1 OR name LIKE 'name') AND budget > 100000
	 */
	public function or_where_open()
	{
		return $this->where(null, null, null, self::WHERE_OR_OPEN);
	}
	
	/**
	 * Lezarja az or_where_open() altal megnyitott zarojelet
	 */
	public function or_where_close()
	{
		return $this->where(null, null, null, self::WHERE_OR_CLOSE);
	}
	
	/**
	 * Visszaad egy logikai kifejezest, amit a beallitott $_where ertekek alapjan allit ossze
	 */
	protected function addWhereExpressions()
	{
		// Vegmegy az bemeneti _from ertekeken
		foreach ($this->_from as $index => $fromItem)
		{
			// Vegmegy az osszes where erteken
			foreach ($this->_where as $whereItem)
			{
				$this->addWhereExpression($fromItem, $whereItem, $index);
			}
		}
	}
	
	/**
	 * Kiertelekeli a kapott $_where es $_from ertekek alapjan a relaciokat. Osszeallitja az $_expressions tombot
	 *
	 * @param array|ORM $fromItem		$_from egy eleme
	 * @param array		$whereItem		$_where egy eleme
	 * @param int	 	$index			$_from -on belul a $fromItem indexe. Ez az index lesz az $_expressions uj elemenek indexe is.
	 */
	protected function addWhereExpression($fromItem, array $whereItem, $index)
	{
		$isLast = ($index == 2);
		$fromItem = $this->getArrayFromObject($fromItem);
	
		// Ha meg nincs az adott indexhez elem
		if (empty($this->_expressions[$index]))
		{
			$this->_expressions[$index] = [];
		}
	
		$col 		= Arr::get($whereItem, 'col');
		$rel 		= Arr::get($whereItem, 'rel');
		$val 		= Arr::get($whereItem, 'val');
		$fromValue 	= Arr::get($fromItem, $col);
	
		// $_where -ben levo relacio alapjan osszehasonlitja a $_from es $_where ertekeket
		switch ($rel)
		{
			case self::REL_EQUALS: default:

				if (is_numeric($fromValue))
				{
					$this->_expressions[$index][] = $fromValue == $val;
				}
				else
				{
					/**
					 * bug.v21.2 es bug.v21.3 miatt kell strcoll, es mb_
					 * Igy case insensitive marad es utf8 biztos
					 */
					$cmp 							= Text::compareStringsUtf8SafeCaseInsensitive($fromValue, $val);									
					$this->_expressions[$index][] 	= $cmp == 0;
				}
				
				break;
	
			case self::REL_GRATER_OR_EQUALS:
				$this->_expressions[$index][] = $fromValue >= $val;
				break;
	
			case self::REL_GREATER:
				$this->_expressions[$index][] = $fromValue > $val;
				break;
	
			case self::REL_LESS:
				$this->_expressions[$index][] = $fromValue < $val;
				break;
	
			case self::REL_LESS_OR_EQUALS:
				$this->_expressions[$index][] = $fromValue <= $val;
				break;
					
			case self::REL_NOT_EQUALS:				
				if (is_numeric($fromValue))
				{
					$this->_expressions[$index][] = $fromValue != $val;
				}
				else
				{
					/**
					 * bug.v21.2 es bug.v21.3 miatt kell strcoll, es mb_
					 * Igy case insensitive marad es utf8 biztos
					 */
					$cmp 							= Text::compareStringsUtf8SafeCaseInsensitive($fromValue, $val);									
					$this->_expressions[$index][] 	= $cmp != 0;				
				}
				break;
	
			case self::REL_LIKE:
				$fromValueLower = mb_strtolower($fromValue);
				$valLower 		= mb_strtolower($val);

				if (empty($valLower))
				{
					$exp = true;					
				}
				else
				{
					$exp = (stripos($fromValueLower, $valLower) === false) ? false : true;
				}

				$this->_expressions[$index][] = $exp;
				break;
		}
	}	
	
	/**
	 * Kiertekeli az osszeallitott $_expressions tombot es beallitja az $_evaluates tomb ertekeit
	 */
	protected function evaluateExpressions()
	{
		foreach ($this->_from as $fromIndex => $fromItem)
		{
			$fromItem = $this->getArrayFromObject($fromItem);
				
			foreach ($this->_where as $whereIndex => $whereItem)
			{
				$type = Arr::get($whereItem, 'type');	
				$this->evaluate($type, $fromIndex, $whereIndex);
			}
		}
	}
	
	/**
	 * Kierteleki egy sor kifejezeseit. Az adott relacio alapjan kapott indexekhez tartozo kifejezeseket
	 *
	 * @param string	$rel			WHERE kifejezesek kozti relacio
	 * @param int 		$fromIndex		$_from index
	 * @param int 		$whereIndex		$_where index
	 *
	 * @return boolean
	 */
	protected function evaluate($rel, $fromIndex, $whereIndex)
	{
		// Csak egyetlen WHERE ertek van
		$onlyOneWhere = (count($this->_where) == 1) ? true : false;
	
		// Egy WHERE eseten ugyanaz lesz a kiertelekeles, mint a kifejezes. Elso WHERE eseten pedig initeli az _expressions -t
		if ($onlyOneWhere || $whereIndex == 0)
		{		
			$this->_evaluates[$fromIndex] = $this->_expressions[$fromIndex][$whereIndex];
				
			return false;
		}
	
		// Tobb WHERE ertek eseten relacio alapjan kiertekeli a kifejezeseket
		switch ($rel)
		{
			case self::WHERE_AND:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] && $this->_expressions[$fromIndex][$whereIndex];
				break;
	
			case self::WHERE_OR:				
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] || $this->_expressions[$fromIndex][$whereIndex];
				break;
			
			case self::WHERE_AND_OPEN:
				$this->_evaluates[$fromIndex] = $this->evaulateByOpen(self::WHERE_AND, $fromIndex, $whereIndex);
				break;
			
			case self::WHERE_OR_OPEN:
				var_dump('where_or_open');
				exit;
				$this->_evaluates[$fromIndex] = $this->evaulateByOpen(self::WHERE_OR, $fromIndex, $whereIndex);				
				break;			
			
			case self::WHERE_OR_CLOSE:
				break;
			
			case self::WHERE_AND_CLOSE:
				break;
		}
	}
	
	/**
	 * Kiertekeli a nyito kifejezest (_OPEN)
	 * 
	 * @param string $type		Relacio tipusa (OR, AND)
	 * @param int  $fromIndex	Aktualis index a _from tombben
	 * @param int  $whereIndex	Aktualis index a _where tombben
	 * 
	 * @return bool
	 */
	protected function evaulateByOpen($type, $fromIndex, $whereIndex)
	{
		var_dump('fuck');
		exit;
		$evaulate = $this->getNextEvaulateByOpen($type, $fromIndex, $whereIndex);
		
		for ($i = $whereIndex + 1; $i < count($this->_expressions[$fromIndex]); $i++)
		{
			// Az eredetileg ertekelt kifejezes AND, az aktualis pedig AND_CLOSE
			if ($type == self::WHERE_AND && $this->_where[$i]['type'] == self::WHERE_AND_CLOSE)
			{
				break;
			}
			
			// Az eredetileg ertekelt kifejezes OR, az aktualis pedig OR_CLOSE
			if ($type == self::WHERE_OR && $this->_where[$i]['type'] == self::WHERE_OR_CLOSE)
			{
				break;
			}
			
			$currentExpression = $this->_expressions[$fromIndex][$i];
			$evaulate = ($type == self::WHERE_AND) ? ($evaulate && $currentExpression) : ($evaulate || $currentExpression);
		}
		
		return $evaulate;
	}
	
	/**
	 * Visszaadja a kovetkezo kiertekelst az evaulateByOpen() -hez
	 * 
	 * @param string $type		Relacio tipusa (AND, OR)
	 * @param int $fromIndex	Aktualis _from index
	 * @param int $whereIndex	Aktualis _where index
	 * 
	 * @return bool
	 */
	protected function getNextEvaulateByOpen($type, $fromIndex, $whereIndex)
	{
		switch ($type)
		{
			case self::WHERE_OR:
				return $this->_evaluates[$fromIndex] || $this->_expressions[$fromIndex][$whereIndex];
			
			case self::WHERE_AND:
				return $this->_evaluates[$fromIndex] && $this->_expressions[$fromIndex][$whereIndex];
		}
	}
}
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
	 * amiben annyi elem van, amennyi a $_where tombben.
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
		//$this->_whereGroup[count($this->_where)] = ['type' => Array_Builder::WHERE_AND_OPEN];
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
	 * Visszaadja egy tombben a {type}_open {type}_close WHERE tipusokat
	 * Ezeket tobb helyen is figyelmen kivul kell hagyi a where kiertelekese soran
	 * 
	 * @return array
	 */
	protected function getOpenCloseIndexes()
	{
		return [Array_Builder::WHERE_AND_OPEN, Array_Builder::WHERE_OR_OPEN, 
			Array_Builder::WHERE_AND_CLOSE, Array_Builder::WHERE_OR_CLOSE];
	}
	
	/**
	 * Visszaad egy logikai kifejezest, amit a beallitott $_where ertekek alapjan allit ossze
	 */
	protected function addWhereExpressions()
	{
		// Vegmegy az bemeneti _from ertekeken
		foreach ($this->_from as $fromIndex => $fromItem)
		{
			// Vegmegy az osszes where erteken
			foreach ($this->_where as $whereIndex => $whereItem)
			{
				$this->addWhereExpression($fromItem, $whereItem, $fromIndex, $whereIndex);
			}
		}
	}
	
	/**
	 * Kiertelekeli a kapott $_where es $_from ertekek alapjan a relaciokat. Osszeallitja az $_expressions tombot
	 *
	 * @param array|ORM $fromItemTmp	$_from egy eleme
	 * @param array		$whereItem		$_where egy eleme
	 * @param int	 	$fromIndex		$_from -on belul a $fromItem indexe. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int		$whereIndex		Aktualis $whereItem indexe $_where -n belul
	 */
	protected function addWhereExpression($fromItemTmp, array $whereItem, $fromIndex, $whereIndex)
	{
		$fromItem = $this->getArrayFromObject($fromItemTmp);
	
		// Ha meg nincs az adott indexhez elem
		if (empty($this->_expressions[$fromIndex]))
		{
			$this->_expressions[$fromIndex] = [];
		}
		
		// _where -ben es _from -ban levo ertek, ezek lesznek osszehasonlitva
		$whereValue = $whereItem['val'];
		$fromValue 	= $fromItem[$whereItem['col']];
		
		// _expression hozzadasa relacio alapjan
		$this->addwhereExpressionByRelation($fromValue, $whereValue, $fromIndex, $whereIndex, $whereItem['rel']);
		
		// Az adott _where tipusa nyito, vagy zaro relacio
		if (in_array($whereItem['type'], $this->getOpenCloseIndexes()))
		{
			return false;
		}
	
		
	}	
	
	/**
	 * A kapott relacio alapjan vizsgalja a kapott _where es _from ertekeket, ez
	 * alapjan pedig kitolti az _expression tombot.
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $val -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param string $relation	Relacio. Array_Builder konstans
	 */
	protected function addwhereExpressionByRelation($fromValue, $whereValue, $fromIndex, $whereIndex, $relation)
	{
		// $_where -ben levo relacio alapjan osszehasonlitja a $_from es $_where ertekeket
		switch ($relation)
		{
			case self::REL_EQUALS: default:			
				$this->addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, Array_Builder::REL_EQUALS);
				break;
	
			case self::REL_GRATER_OR_EQUALS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue >= $whereValue;
				break;
	
			case self::REL_GREATER:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue > $whereValue;
				break;
	
			case self::REL_LESS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue < $whereValue;
				break;
	
			case self::REL_LESS_OR_EQUALS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue <= $whereValue;
				break;
					
			case self::REL_NOT_EQUALS:				
				$this->addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, Array_Builder::REL_NOT_EQUALS);
				break;
	
			case self::REL_LIKE:
				$this->addWhereExpressionLike($fromValue, $whereValue, $fromIndex, $whereIndex);
				break;
		}
	}
	
	/**
	 * _expression kitoltese '=', vagy '!=' relacio eseten
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $val -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param string $relation	Relacio. Array_Builder konstans
	 */
	protected function addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, $relation)
	{
		if (is_numeric($fromValue))
		{
			$this->_expressions[$fromIndex][$whereIndex] = $fromValue == $whereValue;
		}
		else
		{
			/**
			 * bug.v21.2 es bug.v21.3 miatt kell strcoll, es mb_
			 * Igy case insensitive marad es utf8 biztos
			 * 
			 * @see ArrayBuilderTest (group bug.v21.2, bug.v21.3)
			 */
			$cmp											= Text::compareStringsUtf8SafeCaseInsensitive($fromValue, $whereValue);											
			$this->_expressions[$fromIndex][$whereIndex]	= ($relation == Array_Builder::REL_EQUALS) ? $cmp == 0 : $cmp != 0;
		}
	}
	
	/**
	 * _expression kitoltese 'LIKE'
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $val -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 */
	protected function addWhereExpressionLike($fromValue, $whereValue, $fromIndex, $whereIndex)
	{
		$fromValueLower = mb_strtolower($fromValue);
		$valLower 		= mb_strtolower($whereValue);

		if (empty($valLower))
		{
			$exp = true;					
		}
		else
		{
			$exp = (stripos($fromValueLower, $valLower) === false) ? false : true;
		}

		$this->_expressions[$fromIndex][$whereIndex] = $exp;
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
	 * Visszaadja a $_where tombbol az elso indexet, de csak a tenyleges WHERE
	 * elemeket veszi figyelembe. Ezen kivul lehet meg AND_OPEN, OR_OPEN, azokat
	 * figyelmen kivul hagyja.
	 * 
	 * @return int
	 */
	public function getFirstRealWhereIndex()
	{
		$firstIndex = null;
		foreach ($this->_where as $index => $item)
		{
			if (!in_array($item['type'], $this->getOpenCloseIndexes()))
			{
				$firstIndex = $index;
				break;
			}
		}
		
		return $firstIndex;
	}
	
	/**
	 * Kierteleki egy sor kifejezeseit. Az adott relacio alapjan kapott indexekhez tartozo kifejezeseket
	 *
	 * @param string	$relation		WHERE kifejezesek kozti relacio
	 * @param int 		$fromIndex		$_from index
	 * @param int 		$whereIndex		$_where index
	 *
	 * @return boolean
	 */
	protected function evaluate($relation, $fromIndex, $whereIndex)
	{			
		// Csak egyetlen WHERE ertek van
		$onlyOneWhere			= (count($this->_where) == 1) ? true : false;
		
		// Elso olyan index a where zaradekbol, ami nem OPEN, vagy CLOSE, hanem igaz WHERE
		$firstRealWhereIndex	= $this->getFirstRealWhereIndex();
		
		// Egy WHERE eseten ugyanaz lesz a kiertelekeles, mint a kifejezes. Elso WHERE eseten pedig initeli az _evaluates -t
		if ($onlyOneWhere || $whereIndex == $firstRealWhereIndex)
		{		
			$this->_evaluates[$fromIndex] = array_values($this->_expressions[$fromIndex])[0];				
			return false;
		}
		
		/**
		 * Meg nincs fromIndex, ami azt jelenti, hogy tobb where van, es ez nem egy 'igazi' where, hanem open
		 * Ilyen eset, akkor fordul elo, ha pl ->and_where_open() hivassal kezdik a where zaradekot
		 * 
		 * Ebben az esetben inicializalni kell az _evaulates tomb adott indexet.
		 * 
		 * Ha AND_OPEN tipusu, akkor true -t kap, igy nem lesz befolyasa a (...) kozti reszhez,
		 * OR_OPEN eseten pedig false, a vegso kiertekeles igy fog kinezni:
		 * 
		 * AND_OPEN:		TRUE && (tenyleges feltetelek)		=> (TRUE && TRUE) = TRUE, (TRUE && FALSE) = FALSE
		 * OR_OPEN:		FALSE || (tenyleges feltetelek)		=> (FALSE || TRUE) = TRUE, (FALSE || FALSE) = FALSE
		 * 
		 * Tehat a kiertelekes minden esetben a tenyleges feltetelek eredmenyevel lesz egyenlo
		 * 
		 * @see ArrayBuilderTest::testEvaulateExpressionsAndWhereOpenClose
		 */
		if (!isset($this->_evaluates[$fromIndex]) && 
			in_array($this->_where[$whereIndex]['type'], $this->getOpenCloseIndexes()))
		{
			$this->_evaluates[$fromIndex] = ($relation == Array_Builder::WHERE_AND_OPEN) ? true : false;
		}
	
		$this->evaulateByRelation($relation, $fromIndex, $whereIndex);
	}
	
	protected function evaulateByRelation($relation, $fromIndex, $whereIndex)
	{
		// Relacio alapjan kiertekeli a kifejezeseket
		switch ($relation)
		{
			case self::WHERE_AND:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] && $this->_expressions[$fromIndex][$whereIndex];
				break;
	
			case self::WHERE_OR:				
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] || $this->_expressions[$fromIndex][$whereIndex];
				break;
			
			case self::WHERE_AND_OPEN:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] && $this->evaulateByOpen(self::WHERE_AND, $fromIndex, $whereIndex);
				break;
			
			case self::WHERE_OR_OPEN:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] || $this->evaulateByOpen(self::WHERE_OR, $fromIndex, $whereIndex);				
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
		$evaulate = null;			
		
		// A kovetkezo WHERE -tol megy egeszen a CLOSE hivasig
		for ($i = $whereIndex + 1; $i < count($this->_expressions[$fromIndex]); $i++)
		{
			if (!isset($this->_where[$i]))
			{
				continue;
			}			
			
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
			
			if (!$evaulate)
			{
				$evaulate = $currentExpression;
			}
			else
			{				
				$evaulate = ($this->_where[$i]['type'] == self::WHERE_AND) ? ($evaulate && $currentExpression) : ($evaulate || $currentExpression);
			}						
		}
		
		return $evaulate;
	}		
}
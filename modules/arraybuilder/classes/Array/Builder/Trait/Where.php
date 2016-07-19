<?php

trait Array_Builder_Trait_Where
{	
	/**
	 * WHERE zaradek
	 *
	 * ['type', 'column', 'relation', 'value']
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
	 * ```$_from = [
	 * 		111 => ['id' => 1, 'name' => 'first', 'budget' => 2000],
	 * 		112 => ['id' => 2, 'name' => 'second', 'budget' => 3000],
	 * 		...
	 * ];```
	 *
	 * ```$_where = [
	 * 		['type' => 'AND', 'column' => 'budget', 'relation' => '>', 'value' => 5000],
	 * 		['type' => 'OR', 'column' => 'id', 'relation' => '=', 'value' => 2],
	 * 		...
	 * ];```
	 *
	 * Ebben az esetben az $_expressions tomb igy fog kinezni:
	 *
	 * ```$_expressions = [
	 * 		111 => [false, false],
	 * 		112 => [false, true],
	 * 		...
	 * ];```
	 *
	 * A kiertekeleskor pedig ezek a bool ertekek lesznek vizsgalva a $_where -ben levo 'type' alapjan. Pl.:
	 *
	 * 	```111 =>	false || false
	 * 	112 =>	false || true```
	 *
	 * @var array $_expressions
	 */
	protected $_expressions = [];
	
	/**
	 * A beallitott $_expressions ertekeket ertekeli ki es meghataroz minden $_from sorhoz egy true vagy false erteket.
	 * A kiertelekes a $_where tomb ertekek alapjan tortenik.
	 * 
	 * Ez a tomb hatarozza, hogy egy adott $_from elem szerepel -e a visszateresi ertekben
	 * 
	 * Pl.:
	 * ```$_expressions = [
	 * 		111 => [false, false],
	 * 		112 => [false, true],
	 * 		...
	 * ];```
	 * 
	 * ```$_evaluates = [
	 *		111 => false,
	 *		112 => true,
	 *		...
	 * ];```
	 *
	 * @var array $_evaluates
	 */
	protected $_evaluates = [];

	/**
	 * Visszaadja a $_where alapjan alkotott kifejezeseket.
	 * Tobbnyire csak debug -hoz kell
	 * 
	 * @param void
	 * @return array
	 */
	public function getExpressions() { return $this->_expressions; }
	
	/**
	 * Visszaadja a $_where es $_expression alapjan alkotott kiertekeleseket.
	 * Tobbnyire csak debug -hoz kell
	 * 
	 * @param void
	 * @return array
	 */
	public function getEvaluates() { return $this->_evaluates; }
	
	/**
	 * WHERE zaradek beallitasa
	 *
	 * @param string $column		Mezonev
	 * @param string $relation		Relacio
	 * @param string $value			Ertek
	 * @param string $type			Tipus (and, or). Nem kotelezo
	 *
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::_where
	 */
	public function where($column, $relation, $value, $type = null)
	{
		$where = [
			'column'	=> $column,
			'relation'	=> $relation,
			'value'		=> $value,
			'type'		=> ($type) ? $type : Array_Builder::WHERE_AND
		];
	
		$this->_where[] = $where;
	
		return $this;
	}
	
	/**
	 * 'WHERE AND' zaradek beallitasa
	 *
	 * @param string $column		Mezonev
	 * @param string $relation		Relacio
	 * @param string $value			Ertek	 
	 *
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()
	 */
	public function and_where($column, $relation, $value)
	{
		return $this->where($column, $relation, $value, Array_Builder::WHERE_AND);
	}
	
	/**
	 * 'OR WHERE' zaradek beallitasa
	 *
	 * @param string $column		Mezonev
	 * @param string $relation		Relacio
	 * @param string $value			Ertek
	 *
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()	
	 */
	public function or_where($column, $relation, $value)
	{
		return $this->where($column, $relation, $value, Array_Builder::WHERE_OR);
	}
	
	/**
	 * Zarojelbe teszi a kovetkezo and_where_close() hivasig szereplo where() -ekben megadott felteteleket
	 *
	 * Pl.:
	 *
	 * 	``` ->and_where_open()
	 *			->where('id', '=', 1)
	 *			->where('name', 'LIKE', 'name')
	 *		->and_where_close()
	 *		->or_where('budget', '>', 100000)```
	 *
	 * 	Eredmeny:
	 * 	```WHERE (id = 1 AND name LIKE 'name') OR budget > 100000```
	 * 
	 * @param void
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()
	 */
	public function and_where_open()
	{
		return $this->where(null, null, null, Array_Builder::WHERE_AND_OPEN);
	}
	
	/**
	 * Lezarja az and_where_open() altal megnyitott zarojelet
	 * 
	 * @param void
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()
	 * @see Array_Builder_Trait_Where::and_where_open()
	 */
	public function and_where_close()
	{
		return $this->where(null, null, null, Array_Builder::WHERE_AND_CLOSE);
	}
	
	/**
	 * Zarojelbe teszi a kovetkezo or_where_close() hivasig szereplo where() -ekben megadott felteteleket
	 *
	 * Pl.:
	 *
	 * 	```	->or_where_open()
	 *			->where('id', '=', 1)
	 *			->where('name', 'LIKE', 'name')
	 *		->or_where_close()
	 *		->and_where('budget', '>', 100000)```
	 *
	 * 	Eredmeny:
	 * 	````WHERE (id = 1 OR name LIKE 'name') AND budget > 100000```
	 * 
	 * @param void
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()
	 */
	public function or_where_open()
	{
		return $this->where(null, null, null, Array_Builder::WHERE_OR_OPEN);
	}
	
	/**
	 * Lezarja az or_where_open() altal megnyitott zarojelet
	 * 
	 * @param void
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Trait_Where::where()
	 * @see Array_Builder_Trait_Where::or_where_open()
	 */
	public function or_where_close()
	{
		return $this->where(null, null, null, Array_Builder::WHERE_OR_CLOSE);
	}
	
	/**
	 * Visszaadja egy tombben a {type}_open {type}_close WHERE tipusokat
	 * Ezeket tobb helyen is figyelmen kivul kell hagyi a where kiertelekese soran
	 * 
	 * @param void
	 * @return array
	 */
	protected function getOpenCloseIndexes()
	{
		return [Array_Builder::WHERE_AND_OPEN, Array_Builder::WHERE_OR_OPEN, 
			Array_Builder::WHERE_AND_CLOSE, Array_Builder::WHERE_OR_CLOSE];
	}
	
	/**
	 * Visszaad egy logikai kifejezest, amit a beallitott $_where ertekek alapjan allit ossze
	 * 
	 * @param void	 
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::addWhereExpression()		$_expressions beallitasa
	 */
	protected function addWhereExpressions()
	{
		// Vegmegy az bemeneti $_from ertekeken
		foreach ($this->_from as $fromIndex => $fromItem)
		{
			// Vegmegy az osszes $_where erteken
			foreach ($this->_where as $whereIndex => $whereItem)
			{
				// Kitolti az $_expression ertekeket a $_from es $_where alapjan
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
	 * 
	 * @return mixed					Csak akkor van visszateres, ha nem kell tovabb futni a metodusnak
	 * 
	 * @uses Array_Builder_Trait_Where::addwhereExpressionByRelation()
	 * @uses Array_Builder_Trait_Where::getArrayFromObject()
	 * @uses Array_Builder_Trait_Where::_expressions
	 * @uses Array_Builder_Trait_Where::getOpenCloseIndexes()
	 */
	protected function addWhereExpression($fromItemTmp, array $whereItem, $fromIndex, $whereIndex)
	{
		// Objektumbol tomb
		$fromItem = $this->getArrayFromObject($fromItemTmp);
	
		// Ha meg nincs az adott indexhez elem
		if (empty($this->_expressions[$fromIndex]))
		{
			$this->_expressions[$fromIndex] = [];
		}
		
		// $_where -ben es $_from -ban levo ertek, ezek lesznek osszehasonlitva
		$whereValue = Arr::get($whereItem, 'value');
		$fromValue 	= Arr::get($fromItem, Arr::get($whereItem, 'column', ''));

		// Az adott $_where tipusa nyito, vagy zaro relacio
		if (in_array($whereItem['type'], $this->getOpenCloseIndexes()))
		{
			// Akkor nincs mit kiertekelni, ignoralni kell
			return false;
		}
		
		// $_expressions beallitasa relacio alapjan
		$this->addwhereExpressionByRelation($fromValue, $whereValue, $fromIndex, $whereIndex, Arr::get($whereItem, 'relation'));				
	}	
	
	/**
	 * A kapott relacio alapjan vizsgalja a kapott _where es _from ertekeket, ez
	 * alapjan pedig kitolti az _expression tombot.
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $whereValue -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param string $relation	Relacio. Array_Builder konstans
	 * 
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::addWhereExpressionEquals()
	 * @uses Array_Builder_Trait_Where::_expressions
	 */
	protected function addwhereExpressionByRelation($fromValue, $whereValue, $fromIndex, $whereIndex, $relation)
	{
		// $_where -ben levo relacio alapjan osszehasonlitja a $_from es $_where ertekeket
		switch ($relation)
		{
			case Array_Builder::REL_EQUALS: default:			
				$this->addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, Array_Builder::REL_EQUALS);
				break;
	
			case Array_Builder::REL_GRATER_OR_EQUALS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue >= $whereValue;
				break;
	
			case Array_Builder::REL_GREATER:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue > $whereValue;
				break;
	
			case Array_Builder::REL_LESS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue < $whereValue;
				break;
	
			case Array_Builder::REL_LESS_OR_EQUALS:
				$this->_expressions[$fromIndex][$whereIndex] = $fromValue <= $whereValue;
				break;
					
			case Array_Builder::REL_NOT_EQUALS:				
				$this->addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, Array_Builder::REL_NOT_EQUALS);
				break;
	
			case Array_Builder::REL_LIKE:
				$this->addWhereExpressionLike($fromValue, $whereValue, $fromIndex, $whereIndex);
				break;
		}
	}
	
	/**
	 * _expression kitoltese '=', vagy '!=' relacio eseten
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $whereValue -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param string $relation	Relacio. Array_Builder konstans
	 * 
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::_expressions
	 * @uses Text::compareStringsUtf8SafeCaseInsensitive
	 */
	protected function addWhereExpressionEquals($fromValue, $whereValue, $fromIndex, $whereIndex, $relation)
	{
		// Numerikus ertek, egyszeru osszehasonlito operatorok tipus relacio alapjan
		if (is_numeric($fromValue))
		{
			$this->_expressions[$fromIndex][$whereIndex] = 
				($relation == Array_Builder::REL_EQUALS) 
					? $fromValue == $whereValue 
					: $fromValue != $whereValue;
		}
		else	// Szoveges ertek
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
	 * _expression kitoltese 'LIKE' relacio eseten
	 * 
	 * @param mixed $fromValue	$_from egy elemenek erteke. Ez lesz osszehasonlitva a $whereValue -val
	 * @param mixed $whereValue	$_where egy elemenek erteke. Ez lesz osszehasonlitva a $fromValue -val
	 * @param int $fromIndex	$_from -on beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * @param int $whereIndex	$_where -en beluli index. Ez az index lesz az $_expressions uj elemenek indexe is.
	 * 
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::_expressions
	 */
	protected function addWhereExpressionLike($fromValue, $whereValue, $fromIndex, $whereIndex)
	{
		$fromValueLower		= mb_strtolower($fromValue);
		$whereValueLower	= mb_strtolower($whereValue);

		// Ures string, mindenkepp true
		if (empty($whereValueLower))
		{
			$exp = true;					
		}
		else
		{
			// case-insensitive strpos
			$exp = (stripos($fromValueLower, $whereValueLower) === false) ? false : true;
		}

		$this->_expressions[$fromIndex][$whereIndex] = $exp;
	}
	
	/**
	 * Kiertekeli az osszeallitott $_expressions tombot es beallitja az $_evaluates tomb ertekeit
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::getArrayFromObject()
	 * @uses Array_Builder_Trait_Where::evaluate()
	 * @uses Array_Builder_Trait_Where::_from
	 * @uses Array_Builder_Trait_Where::_where
	 */
	protected function evaluateExpressions()
	{
		// Vegmegy bemeneti $_from ertekeken
		foreach ($this->_from as $fromIndex => $fromItem)
		{
			// Objektumbol tomb
			$fromItem = $this->getArrayFromObject($fromItem);
				
			// Vegmegy $_where ertekeken
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
	 * @param string	$relation		WHERE kifejezesek kozti relacio
	 * @param int 		$fromIndex		$_from index
	 * @param int 		$whereIndex		$_where index
	 *
	 * @return mixed	Csak akkor ter vissza bool ertekkel, ha nem kell tovabb futni a metodusnak
	 * 
	 * @uses Array_Builder_Trait_Where::_where
	 * @uses Array_Builder_Trait_Where::getFirstRealWhereIndex()
	 * @uses Array_Builder_Trait_Where::_expressions
	 * @uses Array_Builder_Trait_Where::_evaluates
	 * @uses Array_Builder_Trait_Where::getOpenCloseIndexes()
	 * @uses Array_Builder_Trait_Where::evaulateByRelation()
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
	
	/**
	 * Kaporr relacio alapjan ertekeli az $_expressions -ben levo aktualis elemet
	 * es beallitja az $_evaluates tombot
	 * 
	 * @param string	$relation		Relacio
	 * @param int		$fromIndex		$_from aktualis elemenek indexe
	 * @param int		$whereIndex		$_where aktualis elemenek indexe
	 * 
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::_evaluates
	 * @uses Array_Builder_Trait_Where::_expressions
	 * @uses Array_Builder_Trait_Where::evaulateByOpen()
	 * @uses Array_Builder_Trait_Where::evaulateByOpen()
	 */
	protected function evaulateByRelation($relation, $fromIndex, $whereIndex)
	{
		// Relacio alapjan kiertekeli a kifejezeseket
		switch ($relation)
		{
			case Array_Builder::WHERE_AND:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] && $this->_expressions[$fromIndex][$whereIndex];
				break;
	
			case Array_Builder::WHERE_OR:				
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] || $this->_expressions[$fromIndex][$whereIndex];
				break;
			
			case Array_Builder::WHERE_AND_OPEN:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] && $this->evaulateByOpen(Array_Builder::WHERE_AND, $fromIndex, $whereIndex);
				break;
			
			case Array_Builder::WHERE_OR_OPEN:
				$this->_evaluates[$fromIndex] = $this->_evaluates[$fromIndex] || $this->evaulateByOpen(Array_Builder::WHERE_OR, $fromIndex, $whereIndex);				
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
	 * 
	 * @uses Array_Builder_Trait_Where::_expressions
	 * @uses Array_Builder_Trait_Where::_where
	 * @uses Array_Builder_Trait_Where::hasBreak
	 */
	protected function evaulateByOpen($type, $fromIndex, $whereIndex)
	{
		$evaulate = null;			
		
		// A kovetkezo WHERE -tol megy egeszen a CLOSE hivasig, amit egy break biztosit
		for ($i = $whereIndex + 1; $i < count($this->_expressions[$fromIndex]); $i++)
		{
			// Nincs ilyen indexu $_where elem. A $_where indexek nem szekvencionalisak, mert az OPEN, CLOSE zaradekok nem szerepelnek benn
			if (!isset($this->_where[$i]))
			{
				continue;
			}			
			
			if ($this->hasBreak($type, $i))
			{
				break;
			}
			
			$currentExpression = $this->_expressions[$fromIndex][$i];								
			
			// Ez az elso WHERE az OPEN -en belul, igy initeljuk, es johet a kovetkezo
			if (!$evaulate)
			{
				$evaulate = $currentExpression;
			}
			else	// Nem ez az elso WHERE 
			{				
				$evaulate = ($this->_where[$i]['type'] == Array_Builder::WHERE_AND) ? ($evaulate && $currentExpression) : ($evaulate || $currentExpression);
			}						
		}
		
		return $evaulate;
	}	
	
	/**
	 * Visszaadja a $_where tombbol az elso indexet, de csak a tenyleges WHERE
	 * elemeket veszi figyelembe. Ezen kivul lehet meg AND_OPEN, OR_OPEN, azokat
	 * figyelmen kivul hagyja.
	 * 
	 * @param void
	 * @return int
	 * 
	 * @uses Array_Builder_Trait_Where::_where
	 * @uses Array_Builder_Trait_Where::getOpenCloseIndexes
	 */
	public function getFirstRealWhereIndex()
	{
		$firstIndex = null;
		
		// Vegmegy az osszes $_where erteken
		foreach ($this->_where as $index => $item)
		{
			// Nem WHERE_OPEN vagy WHERE_CLOSE, tehat 'igaz' WHERE
			if (!in_array($item['type'], $this->getOpenCloseIndexes()))
			{
				$firstIndex = $index;
				break;
			}
		}
		
		return $firstIndex;
	}

	/**
	 * Visszaadja, hogy van -e szukseg break utasitasra az Array_Builder_Trait_Where::evaulateByOpen() hivaskor
	 * 
	 * @param string	$type		WHERE tipus
	 * @param int		$index		$_where aktualis elemenek indexe
	 * 
	 * @return boolean				Ha a kapott tipus es $_where kapott indexu eleme nyito es zaro par, akkor true
	 * 
	 * @uses Array_Builder_Trait_Where::_where
	 */
	protected function hasBreak($type, $index)
	{
		// Az eredetileg ertekelt kifejezes AND_OPEN, az aktualis pedig AND_CLOSE
		if ($type == Array_Builder::WHERE_AND && $this->_where[$index]['type'] == Array_Builder::WHERE_AND_CLOSE)
		{
			return true;
		}

		// Az eredetileg ertekelt kifejezes OR_OPEN, az aktualis pedig OR_CLOSE
		if ($type == Array_Builder::WHERE_OR && $this->_where[$index]['type'] == Array_Builder::WHERE_OR_CLOSE)
		{
			return true;
		}
		
		return false;
	}
}
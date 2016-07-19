<?php

/**
 * class Array_Builder_Select
 *
 * Array_Builder konkret Select tipusu alosztalya. Tombokon vegzett SELECT tipusu lekerdezeseket kezel.
 *
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * @see			Array_Builder
 * 
 * 2017.06.04
 *
 * Pelda:
 *
 * ```$projects = AB::select()
 *		->from('projects')
 *		->where('search_text', 'LIKE', 'web')
 *		->order_by('name')
 *		->limit(3)->offset(1)
 *		->execute()->as_array();```
 *
 * @todo IN operator
 * @todo JOIN
 */

class Array_Builder_Select extends Array_Builder
{
	// WHERE viselkedes
	use Array_Builder_Trait_Where;	
	
	/*
	 * SELECT ertekek
	 */
	const SELECT_EVERYTHING		= '*';
	
	/*
	 * ORDER_BY ertekek
	 */
	const ORDER_ASC 			= 'ASC';
	const ORDER_DESC			= 'DESC';		
	
	/**
	 * SELECT zaradek
	 * 
	 * @var string|array $_select
	 */
	protected $_select;
	
	/**
	 * ORDER_BY zaradek
	 *
	 * ['column', 'direction']
	 *
	 * @var array $_order_by
	 */
	protected $_order_by = [];
	
	/**
	 * @todo
	 * @var unknown
	 */
	protected $_group_by;
	
	/**
	 * LIMIT zaradek
	 * 
	 * @var null|int $_limit
	 */
	protected $_limit = null;
	
	/**
	 * OFFSET zaradek
	 * 
	 * @var null|int $_offset
	 */
	protected $_offset = null;
	
	/**
	 * Beallitja a $_select erteket
	 *
	 * @param array|string 	$columns		Lekerdezendo mezok array -kent megadva. Vagy string, lehet hasznalni a '*' jelet.
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Select::_select
	 */
	public function select($columns = null)
	{
		if (!$columns)
		{
			$this->_select = self::SELECT_EVERYTHING;
		}
		else
		{
			$this->_select = $columns;
		}
	
		return $this;
	}
	
	/**
	 * LIMIT zaradek beallitasa
	 *
	 * @param int $limit		LIMIT zaradek
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Select::_limit
	 */
	public function limit($limit)
	{
		$this->_limit = $limit;
	
		return $this;
	}
	
	/**
	 * OFFSET zaradek beallitasa
	 *
	 * @param int $offset	OFFSET zaradek
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Select::_offset
	 */
	public function offset($offset)
	{
		$this->_offset = $offset;
	
		return $this;
	}
	
	/**
	 * ORDER_BY beallitasa
	 *
	 * @param string $column		Mezonev
	 * @param string $direction		Irany. Alapertelmezetten novekvo, tehat ASC
	 * 
	 * @return Array_Builder
	 * @uses Array_Builder_Select::_order_by
	 */
	public function order_by($column, $direction = self::ORDER_ASC)
	{
		$this->_order_by['column'] = $column;
		$this->_order_by['direction'] = $direction;
	
		return $this;
	}
	
	/**
	 * @todo
	 */
	public function group_by($group_by)
	{
		$this->_group_by = $group_by;
	
		return $this;
	}
	
	/**
	 * Lekerdezes futtatasa
	 *
	 * @param void
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder_Select::validate()
	 * @uses Array_Builder_Trait_Where::addWhereExpressions()
	 * @uses Array_Builder_Trait_Where::validate()
	 * @uses Array_Builder_Select::setResult()
	 * @uses Array_Builder_Select::orderResult()
	 * @uses Array_Builder_Select::limitResult()
	 */
	public function execute()
	{
		$this->validate();
		$this->addWhereExpressions();
		$this->evaluateExpressions();
		$this->setResult();
		$this->orderResult();
		$this->limitResult();
	
		return $this;
	}

	/**
	 ÷ Ellenorzi a parameterek execute() elott
	 * 
	 * @param void
	 * @return void
	 * 
	 * @throws Exception_Array_Builder_Select	Helytelen $_from paramter eseten
	 * @uses Array_Builder::_from
	 */
	protected function validate()
	{		
		if (!is_array($this->_from) && !is_string($this->_from))
		{
			throw new Exception_Array_Builder_Select('_from is required, and is must be an array or a string');
		}
	}
	
	/**
	 * Visszaadja az osszes lekerdezett rekorbol a $_select -ben megadott mezoket.
	 * Mindenkepp tobb elemu tombot ad vissza.
	 * 
	 * Ha a $_select -ben olyan mezonev szerepel, ami nem letezik a $_result -ban, akkor NULL -t ad vissza ertekkent
	 *
	 * @param void
	 * @return array		Rekordok
	 * 
	 * @uses Array_Builder::_result
	 * @uses Array_Builder_Select::_select
	 * @uses Array_Builder::getArrayFromObject()
	 */
	public function as_array()
	{
		$result = [];
	
		foreach ($this->_result as $index => $item)
		{
			// Minden mezot vissza ad
			if ($this->_select == self::SELECT_EVERYTHING)
			{
				$result[] = $item;
			}
			else 	// Csak a $_select -ben megadott mezoket adja vissza
			{
				foreach ($this->_select as $col)
				{
					$array 					= $this->getArrayFromObject($item);
					$result[$index][$col] 	= Arr::get($array, $col);
				}
			}
		}
	
		$this->_result = $result;
		
		return $this->_result;
	}
	
	/**
	 * Visszaad egy rekordot.
	 *
	 * @param void
	 * @return array		Rekord
	 * 
	 * @uses Array_Builder_Select::as_array()
	 * @uses Array_Builder::_result
	 */
	public function current()
	{
		$this->as_array();
		$this->_result = Arr::get(array_values($this->_result), 0);
	
		return $this->_result;
	}
	
	/**
	 * Visszaadja egyetlen mezo erteket. Csak akkor hasznalhato, ha egy rekordot ad vissza a lekerdezes
	 *
	 * @param string $column	Mezonev
	 * @return mixed			Mezo ertek	
	 * 
	 * @uses Array_Builder::getArrayFromObject()
	 * @uses Array_Builder::_result
	 * @uses Array_Builder_Select::current()
	 */
	public function get($column)
	{
		$this->current();
		
		$array 			= $this->getArrayFromObject($this->_result);
		$this->_result 	= Arr::get($array, $column);
	
		return $this->_result;
	}
	
	/**
	 * Visszadja az eredmenyhalmaz darabszamat
	 * 
	 * @param void
	 * @return int $count	Darabszam
	 * 
	 * @uses Array_Builder::_result
	 */
	public function count()
	{
		return count($this->_result);
	}
	
	/**
	 * Eredmeny limitalasa LIMIT es OFFSET zaradekban megadott ertekeknek megfeleloen
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses Array_Builder::_result
	 * @uses Array_Builder::_offset
	 * @uses Array_Builder::_limit
	 */
	protected function limitResult()
	{
		if ($this->_limit)
		{
			$offset			= ($this->_offset) ? $this->_offset : 0;
			$this->_result 	= array_slice($this->_result, $offset, $this->_limit);
		}
	}
	
	/**
	 * Rendezi a teljes $_result tombot az $_order_by ertekek alapjan
	 * 
	 * @param void
	 * @return mixed	Csak akkor ter vissza false -val, ha nincs megadva rendezes
	 * 
	 * @uses Array_Builder_Select::_order_by
	 * @uses Array_Builder::_result
	 * @uses Array_Builder_Select::order()
	 */
	protected function orderResult()
	{
		$column = Arr::get($this->_order_by, 'column');
	
		if (!$column)
		{
			return false;
		}
	
		usort($this->_result, ['Array_Builder_Select', 'order']);
	}
	
	/**
	 * Ket elemet  hasonlit ossze $_order_by szerint
	 *
	 * @param array|ORM $a  Egyik elem
	 * @param array|ORM $b  Masik elem
	 *
	 * @return int          1, 0, -1
	 * 
	 * @uses Array_Builder::getArrayFromObject()
	 * @uses Array_Builder_Select::_order_by
	 */
	protected function order($a, $b)
	{
		// Konvertalas tombokke
		$arrayA	 	= $this->getArrayFromObject($a);
		$arrayB	 	= $this->getArrayFromObject($b);
	
		// $_order_by ertekek
		$direction 	= Arr::get($this->_order_by, 'direction');
		$column 	= Arr::get($this->_order_by, 'column');
	
		// Osszahasonlitando ertekek
		$aValue		= Arr::get($arrayA, $column);
		$bValue		= Arr::get($arrayB, $column);
	
		// Szam ertekek eseten egyszeru operatorok
		if (is_numeric($aValue) && is_numeric($bValue))
		{		
			$aValue = floatval($aValue);
			$bValue = floatval($bValue);
					
			if ($aValue < $bValue)
			{
				return ($direction == self::ORDER_ASC) ? -1 : 1;
			}
			elseif ($aValue > $bValue)
			{
				return ($direction == self::ORDER_ASC) ? 1 : -1;
			}
		}
		else	// String ertekek eseten case-insensitive strcasecmp
		{
			$cmp = Text::compareStringsUtf8SafeCaseInsensitive($aValue, $bValue);		
			return ($direction == self::ORDER_ASC) ? $cmp : (-1 * $cmp);
		}
	
		return 0;
	}
	
	/**
	 * Beallitja a $_result erteket a lekerdezes eredmenyere. Az as_array(), current() es get() ebbol fog dolgozni
	 * 
	 * @param void
	 * @return void
	 * 
	 * @uses Array_Builder_Trait_Where::_where
	 * @uses Array_Builder::_result
	 * @uses Array_Builder_Trait_Where::_evaluates
	 * @uses Array_Builder::_from
	 */
	protected function setResult()
	{
		// Nincs WHERE, minden eredmeny maradhat
		if (count($this->_where) == 0)
		{
			$this->_result = $this->_from;
		}

		foreach ($this->_from as $index => $item)
		{
			if (Arr::get($this->_evaluates, $index))
			{
				$this->_result[] = $item;
			}
		}
	}
}
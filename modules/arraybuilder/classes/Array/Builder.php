<?php

/**
 * class Array_Builder
 * 
 * Array_Builder osztaly, ami ugyanugy mukodik, mint a klasszikus QueryBuilder, de nem adatbazisbol, hanem tombokbol dolgozik.
 * Cache miatt van ra szukseg.
 * 
 * Ez az absztakt ososztaly. Minden konkret lekerdezes tipus ebbol orokol:
 * 	- Array_Builder_Select
 *  - Array_Builder_Insert
 *  - Array_Builder_Update
 *  - Array_Builder_Delete
 * 
 * @author 		Joó Martin <joomartin@jmweb.hu>
 * @package		Core
 * @copyright	(c) 2016 Szabaduszok.com
 * @date		2017.06.04
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * @see			Array_Builder_Select
 * @see			Array_Builder_Insert
 * @see			Array_Builder_Update
 * @see			Array_Builder_Delete
 *  
 * Pelda:
 * 
 * $projects = AB::select()
 *		->from('projects')
 *		->where('search_text', 'LIKE', 'web')
 *		->order_by('name')
 *		->limit(3)->offset(1)
 *		->execute()->as_array();
 *
 * @todo IN operator
 * @todo JOIN
 */

abstract class Array_Builder
{		
	/*
	 * WHERE relaciok
	 */
	const WHERE				= 'WHERE';
	const WHERE_AND			= 'AND';
	const WHERE_OR			= 'OR';
	const WHERE_AND_OPEN	= 'AND_OPEN';
	const WHERE_OR_OPEN		= 'OR_OPEN';
	const WHERE_AND_CLOSE	= 'AND_CLOSE';
	const WHERE_OR_CLOSE	= 'OR_CLOSE';
	
	/*
	 * Logikai relaciok
	 */
	const REL_EQUALS 			= '=';
	const REL_LESS 				= '<';
	const REL_GREATER 			= '>';
	const REL_LESS_OR_EQUALS  	= '<=';
	const REL_GRATER_OR_EQUALS	= '>=';
	const REL_NOT_EQUALS		= '!=';
	const REL_LIKE				= 'LIKE';
	
	protected $_from = [];		
	
	/**
	 * A lekerdezes eredmenye
	 * 
	 * @var array $_result
	 */
	protected $_result = [];
	
	/**
	 * Lekerdezes futtatasa. A konkret osztaly definialja
	 */
	public abstract function execute();
		
	/**
	 * Beallitja a from erteket
	 * 
	 * @param array|string $from		Vagy a lekerdezni kivant tomb, vagy egy index a cache -ben
	 */
	public function from($from)
	{
		// Nem array -t kap, csak egy indexet. Kiszedi cache -bol
		if (!is_array($from) && is_string($from))
		{
			$cache = Cache::instance();	
			$from = Cache::instance()->get($from);			
		}
		
		$this->_from = $from;
		
		return $this;
	}		
	
	/**
	 * Ha a kapott elem ORM peldany, akkor visszaadja az _object reszt, igy array -kent lehet hasznalni
	 * 
	 * @param array|ORM $fromItem
	 * @return array
	 */
	protected function getArrayFromObject($fromItem)
	{
		// Ha az adott item ORM peldany, akkor kiszedi az _object reszt
		if (is_object($fromItem) && $fromItem instanceof ORM)
		{
			$fromItem = $fromItem->object();
		}
		
		return $fromItem;
	}
}
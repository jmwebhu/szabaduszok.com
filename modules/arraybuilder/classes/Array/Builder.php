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
 * @version		1.0
 * @link		https://www.dropbox.com/s/7d49u7sl7fu9cs9/Fejleszt%C3%A9si%20le%C3%ADr%C3%A1s%2C%20rendszerterv.pages?dl=0
 * @see			Array_Builder_Select
 * @see			Array_Builder_Insert
 * @see			Array_Builder_Update
 * @see			Array_Builder_Delete
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
 * SQL megfelelo:
 * ```SELECT * FROM projects WHERE search_text LIKE "web" ORDER_BY name LIMIT 3 OFFSET 1```
 * 
 * _open hasznalata:
 * ```AB::select()
 *		->from($data)
 *		->where('budget', '>', 1000)
 *		->or_where_open()
 *			->and_where('salary_type', '=', 1)
 *			->and_where('skill', '=', 10)
 *		->or_where_close()
 *		->execute()->as_array();```
 * 
 * SQL megfelelo:
 * ```SELECT * projects WHERE budget > 1000 OR (salary_type = 1 AND skill = 10)```
 * 
 * Egy elem lekerdezese:
 * ```AB::select()->from($data)->where('slug', '=', $slug)->execute()->current();```
 *
 * @todo IN operator
 * @todo JOIN
 * @todo group_by
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
	
	/**
	 * ->from() hivaskor kapott adathalmaz, vagy string
	 * 
	 * Lehet egy array, ami tartalamaz adatokat, vagy lehet egy string, ami 
	 * egy cache -ben levo indexet jelol.
	 * 
	 * Pelda:
	 * ```AB::select()->from($data)```
	 * A $data eleve egy tomb, amibol lehet lekerdezni elemeket
	 * 
	 * ```AB::select()->from('projects')```
	 * Cache::get('projects') indexnek letezni kell, onnan kerdezi le az adatokat
	 * 
	 * @var array|string $_from
	 */
	protected $_from;		
	
	/**
	 * A lekerdezes eredmenye. Ugyanolyan strukturaju tomb, mint a $_from
	 * 
	 * @var array $_result
	 */
	protected $_result = [];
	
	/**
	 * Lekerdezes futtatasa. A konkret osztaly definialja
	 * 
	 * @param void
	 * @return Array_Builder
	 */
	public abstract function execute();
		
	/**
	 * Beallitja a $_from erteket
	 * 
	 * Pelda:
	 * ```AB::select()->from($data)```
	 * A $data eleve egy tomb, amibol lehet lekerdezni elemeket
	 * 
	 * ```AB::select()->from('projects')```
	 * Cache::get('projects') indexnek letezni kell, onnan kerdezi le az adatokat
	 * 
	 * @param array|ORM $from		Vagy a lekerdezni kivant tomb, vagy az entitas ORM peldanya
	 * @return Array_Builder
	 * 
	 * @uses Array_Builder::_from
	 */
	public function from($from)
	{
		// Nem array -t kap. Kiszedi cache -bol
		if (!is_array($from) && is_object($from))
		{
		    $from = $from->getAll();
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
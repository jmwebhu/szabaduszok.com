<?php defined('SYSPATH') OR die('No direct script access.');

class ORM extends Kohana_ORM 
{
    public function setDb($db)
    {
        if (is_string($db))
        {            
            $this->_db_group = $db;
        }                                      
    }
    
    /**
     * Torol minden adatot a tablabol
     */
    public function truncate()
    {
        $models = $this->find_all();        
        
        foreach ($models as $model)
        {
            DB::delete($this->_table_name)->where($this->_primary_key, '=', $model->pk())->execute();
            Cache::instance()->delete($this->_table_name);
        }
    }    
    
    public function findByPk($id)
    {
        return $this->where($this->_primary_key, '=', $id)->find();
    }
    
    /**
     * Letrehoz, vagy visszaad egy modelt. Ha a $value egy letezo ID,
     * akkor lekerdezi, es visszaadja. Ha egy nev, akkor letrehozza es visszaadja.
     * 
     * @param int|string $value         Kapcsolat ID, vagy nev
     * @param ORM $emptyModel           Ures model, a megfelelo osztalybol
     * 
     * @return ORM                      A letrehozott, vagy lekerdezett kapcsolat ORM
     */
    protected function getOrCreate($value, ORM $emptyModel)
    {                             
        $singular   = $emptyModel->object_name();
        $className  = 'Model_' . ucfirst($singular);
        $modelTemp  = new $className();
        
        // Azonosito, tehat letezik
        if (Text::isId($value))
        {
            $model = $modelTemp->findByPk($value);                
        }
        else	// Uj elem
        {                        
			$modelTemp->name    = mb_strtolower($value);
            $model              = $modelTemp->save();

            // Slug generalas, hozzadas cache gyujtemenyhez           
            $model->saveSlug();                       
            $model->cacheToCollection();
        }        
        
        return $model;
    }
    
    /**
     * Hozzaadja a modelhez a kapott tipusu kapcsolatot
     * 
     * @param array $post               _POST adatok. Tartalmazza a konkret kapcsolat ID -kat
     * @param ORM $relationModel        Egy ures ORM objektum, a kapcsolat tipusabol. Pl Model_Project_Skill
     * @param ORM $relationEndModel     Egy ures ORM objektum, a kapcsolat vegpont tipusabol. Pl Model_Skill
     * 
     * @return void
     */
    protected function addRelation(array $post, ORM $relationModel, ORM $relationEndModel)
    {                 	
        $relationIds                    = [];    			
		$cache                          = Cache::instance();   
		$cacheRelations                 = $relationModel->getAll();    	
		$cacheRelations[$this->pk()]    = [];      
        
    	// _POST kapcsolatok
		$postData = Arr::get($post, $relationEndModel->table_name(), []);
		
		if (!empty($postData))
		{
			foreach ($postData as $value)
			{            
				$relation       = $this->getOrCreate($value, $relationEndModel);
				$relationIds[]  = $relation->pk();

				$cacheRelations[$this->pk()][] = $relation;
			}                

			$this->add($relationEndModel->object_plural(), $relationIds);       
			$cache->set($relationModel->table_name(), $cacheRelations);
		}        
    }
	
	public function relationAutocomplete($term)
	{
		$items = $this->getAll();
		$result = [];

		/**
		 * @todo REFACT ALIAS
		 */

		$tmp = AB::select(['skill_id', 'name'])->from($items)->where('name', 'LIKE', $term)->order_by('name')->execute()->as_array();
		foreach ($tmp as $item)
		{
			$firstChar = substr($term, 0, 1);
			$isLower = ctype_lower($firstChar);			
			
			$text = ($isLower) ? mb_strtolower(Arr::get($item, 'name')) : ucfirst(Arr::get($item, 'name'));
			
			$result[] = [
				'text'	=> $text,
				'id'  	=> Arr::get($item, $this->primary_key())
			];  
		}			
		
		return $result;
	}
}

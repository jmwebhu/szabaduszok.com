<?php defined('SYSPATH') OR die('No direct script access.');

class ORM extends Kohana_ORM 
{
    /**
     * Torol minden adatot a tablabol
     */
    public function truncate()
    {
        $models = $this->find_all();        
        
        foreach ($models as $model) {
            $model->delete();
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
        if (is_numeric($value)) {
            $model = $modelTemp->findByPk($value);                
        } else {    // Uj elem
			$byName				= new $className();
			$byNameModel		= $byName->where('name', '=', mb_strtolower($value))->find(); 
			
			if ($byNameModel->loaded()) {
				return $byNameModel;
			}
			
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
        $thisPk                             = $this->primary_key();
        $relationIds                        = [];
		$cache                              = Cache::instance();
		$cacheRelations                     = $relationModel->getAll();
		$cacheRelations[$this->{$thisPk}]   = [];

    	// _POST kapcsolatok
		$postData = Arr::get($post, $relationEndModel->table_name(), []);
        $postData = Arr::uniqueString($postData);
		
		if (!empty($postData)) {
			foreach ($postData as $value) {
				$relation       = $this->getOrCreate($value, $relationEndModel);
				$relationPk     = $relation->primary_key();

				if (!$this->has($relationEndModel->object_plural(), $relation->{$relationPk})) {
					$relationIds[]  = $relation->{$relationPk};
				}				

				$cacheRelations[$this->{$thisPk}][] = $relation;
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

		$tmp = AB::select([$this->primary_key(), 'name'])->from($items)->where('name', 'LIKE', $term)->order_by('name')->execute()->as_array();
		foreach ($tmp as $item) {
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

    /**
     * @return Array_Builder
     */
    public function baseSelect()
    {
        return AB::select()->from($this);
    }

    public function getModelsByIds(array $ids)
    {
        $models = [];

        foreach ($ids as $id) {
            $models[] = $this->getById($id);
        }

        return $models;
    }

    public function clearCache()
    {
        AB::delete($this->_table_name, $this->pk())->execute();
    }

    /**
     * @param string $relationName
     * @return string
     */
    public function getRelationString($relationName)
    {
        $items  = $this->{$relationName}->find_all();
        $sb     = SB::create();

        foreach ($items as $i => $item) {
            $sb->append($item->name);

            if ($i == count($items) - 1) {
                $sb->append(', ');
            }
        }

        return $sb->get('');
    }

    public function getJson()
    {
        return json_encode($this->object());
    }

    public function getRelationBy($name)
    {
        if ($this->isRelationHasMany($name)) {
            $through = $this->getHasManyThrough($name);

            if ($through) {
                $cache = Cache::instance()->get($through);

                if (empty($cache)) {
                    return $this->{$name}->find_all();
                }

                return Arr::get($cache, $this->{$this->_primary_key});

            } else {
                return $this->{$name}->find_all();
            }

        } else {
            return $this->{$name};
        }
    }

    protected function isRelationHasMany($relation)
    {
        if (Arr::get($this->has_many(), $relation)) {
            return true;
        }

        return false;
    }

    protected function getHasManyThrough($relation)
    {
        $hasMany = Arr::get($this->has_many(), $relation);
        return Arr::get($hasMany, 'through');
    }
}

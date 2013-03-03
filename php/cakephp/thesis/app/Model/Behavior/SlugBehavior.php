<?php
class SlugBehavior extends ModelBehavior{
	public $settings;
	
	/*
	 * Przekazujemy informacje o budowie modelu
	 * 
	 * @param mixed $model Obiekt modelu który coś zapisywał
	 * @param array $settings Tablica definująca pod jakimi polami zapisane są kolejno: title, leed, content i aditional. 
	 */
	function setup(&$model, $settings = array(null, null, null, null)) {
        $this->settings[$model->alias] = am($model->alias, (is_array($settings)? $settings : array()));
    } 
    
    
    public function beforeSave( &$model, $created) {
    	extract($this->settings[$model->alias]);
    	
    	//$slugFieldSource 
    	//$slugFieldTarget
    	
    	if ( !isset($model->data[$model->alias][$slugFieldSource]) ) {
    		return;
    	}
    	
    	$slugText = $model->data[$model->alias][$slugFieldSource];
    	$slugText = Inflector::slug($slugText, '-');
    	$slugText = mb_strtolower($slugText, 'UTF-8');
    	
    	$failCount = 1;
    	
    	//gdyby to byl update przez model->id przepisujemy dane
    	if (isset($model->id)) {
    		$model->data[$model->alias]['id'] = $model->id;
    	}
    	
    	if ( !isset($model->data[$model->alias]['id']) ) {
	    	while ($model->find('count', array('recursive' => -1, 'conditions' => array( $slugFieldTarget => $slugText . (($failCount>1)?"-{$failCount}":'')) ))) {
	    		$failCount++;
	    	}
    	} else {
    		while (
    			$model->find('count', array(
    				'recursive' => -1, 
    				'conditions' => array( 
    					'id !=' => $model->data[$model->alias]['id'],  
    					$slugFieldTarget => $slugText . (($failCount>1)?"-{$failCount}":'')
    				) 
    			))) {
	    		$failCount++;
	    	}
    	}
    	$slugText .= ($failCount>1)?"-{$failCount}":'';
    	
    	$model->data[$model->alias][$slugFieldTarget] = $slugText;
    }
    
}
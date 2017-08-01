<?php
namespace Generic;

use \Auth;

Trait GenericObserverCreatingTrait {
	
	public function genericCreating($new_obj)
	{
		$primaryKey = $new_obj->getPrimaryKey();
		$new_obj->$primaryKey = $new_obj->getNexVal();
		if (Auth::check())
		{
			$new_obj->id_user_create = Auth::id();
		}
	}
}
<?php
namespace Generic;

use \Auth;

Trait GenericObserverUpdatingTrait {

	public function genericUpdating ($new_obj)
	{
		if (Auth::check())
		{
			$new_obj->id_user_update = Auth::id();
		}
	}
}
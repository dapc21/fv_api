<?php
namespace Generic;

use \Auth;

Trait GenericObserverDeletingTrait {

	public function genericdeleting ($new_obj)
	{
		if (Auth::check())
		{
			$new_obj->id_user_update = Auth::id();
		}
	}
}
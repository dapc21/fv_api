<?php
namespace Generic;

use \Auth;

use \Carbon\Carbon;

Trait GenericObserverCreatedTrait {

	public function genericCreated ($new_obj)
	{
		if (Auth::check())
		{
			$description = '';
			//Atributos del objeto
			$mapeo_columnas = $new_obj->getMapeoColumnas();
			$attributes_array = array_keys($mapeo_columnas);
			$attribute_titles = $new_obj->getColumsTitle();
			foreach ($attributes_array as $key => $attribute)
			{
				if(strlen($new_obj->$attribute)>0)
				{	
					if(array_key_exists($attribute, $attribute_titles))
					{
						if($mapeo_columnas[$attribute]['type'] == 'boolean')
						{
							if($new_obj->$attribute == 1)
							{
								$description .= $attribute_titles[$attribute].": 'SI'.<br>";
							}
							else {
								$description .= $attribute_titles[$attribute].": 'NO'.<br>";
							}
						}
						else
						{
							$description .= $attribute_titles[$attribute].": '".$new_obj->$attribute."'.<br>";
						}
					}
				}
			}
			
			//Relaciones
			$map_relations = $new_obj->getMapeoRelaciones();
			$relations = $new_obj->getWhiteWith();
			for ($i = 0; $i < count($relations); $i++) {
				if(array_key_exists($relations[$i], $attribute_titles))
				{
					$tipo_relacion = $map_relations[$relations[$i]]['relation'];
					
					if($tipo_relacion == 'onetoone')
					{
						$related = $new_obj->$relations[$i];
						if($related)
						{
							foreach ($attribute_titles[$relations[$i]] as $key=>$value)
							{
								if(strlen($related->$key)>0)
								{
									$description .= $value.": '".$related->$key."'.<br>";
								}
							}
						}
					}	
				}
			}
			
			//Insertar en base de datos
			if(strlen($description)>0)
			{
				$login = Auth::user()->login;
		
				$description = "El usuario '".$login."' ha creado un ".$this->section_name." con la siguiente información:<br>".$description;
		
				$audit = new \audits\Audit;
				$audit->id_user = Auth::id();
				$audit->id_audit_section = $this->id_section;
				$audit->id_audit_action = 1; //Id de la accion CREAR en la tabla AUDITS.AUDIT_ACTION
				$audit->description = $description;
				$audit->ts_action = Carbon::now()->toDateTimeString();
					
				$audit->save();
			}
		}
	}
}
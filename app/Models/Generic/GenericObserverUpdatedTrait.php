<?php
namespace Generic;

use \Auth;

use \Carbon\Carbon;

Trait GenericObserverUpdatedTrait {

	public function genericUpdated ($new_obj)
	{
		if (Auth::check())
		{
			$old_obj = $new_obj->getOriginal();
			
			$description = '';
		
			//Atributos del objeto
			$mapeo_columnas = $new_obj->getMapeoColumnas();
			$attributes_array = array_keys($mapeo_columnas);
			$attribute_titles = $new_obj->getColumsTitle();
			foreach ($attributes_array as $key => $attribute)
			{
				if(array_key_exists($attribute, $old_obj))
				{
					if($old_obj[$attribute] != $new_obj->$attribute)
					{
						if($mapeo_columnas[$attribute]['type'] == 'boolean')
						{
							if($new_obj->$attribute == 1)
							{
								$new = "SI";
							}
							else {
								$new = "NO";
							}
							
							if($old_obj[$attribute] == 1)
							{
								$old = "SI";
							}
							else {
								$old = "NO";
							}
						}
						else
						{
							$new = $new_obj->$attribute;
							$old = $old_obj[$attribute];
						}

						if(array_key_exists($attribute, $attribute_titles))
						{
							$description .= "Ha cambiado ".$attribute_titles[$attribute].": '".$old."' por '".$new."'.<br>";
						}						
					}
				}
			}

			//Relaciones
			$relations = $new_obj->getWhiteWith();
			$map_relations = $new_obj->getMapeoRelaciones();
			for ($i = 0; $i < count($relations); $i++) {
				if(array_key_exists($relations[$i], $map_relations))
				{
					if(array_key_exists('id_pivot_relacion',$map_relations[$relations[$i]]))
					{
						if(array_key_exists('model_reference',$map_relations[$relations[$i]]))
						{
							$tipo_relacion = $map_relations[$relations[$i]]['relation'];
								
							if($tipo_relacion == 'onetoone')
							{							
								$modelo = $map_relations[$relations[$i]]['model_reference'];
								$id_modelo = $map_relations[$relations[$i]]['id_pivot_relacion'];
								
								if(array_key_exists($id_modelo,$old_obj))
								{
									$obj = $modelo::find($old_obj[$id_modelo]);
									
									if(!is_null($obj))
									{
										$related = $new_obj->$relations[$i];
										
										if(array_key_exists($relations[$i],$attribute_titles))
										{
											foreach ($attribute_titles[$relations[$i]] as $key=>$value)
											{
												if($related->$key != $obj->$key)
												{
													$description .= "Ha cambiado ".$value.": '".$obj->$key."' por '".$related->$key ."'.<br>";
												}									
											}
										}
									}
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
				
				$final = "El usuario '".$login."' ha modificado un(a) ".$this->section_name.": ";
				
				$colums_identify = $new_obj->getColumsIdentify();				
				foreach($colums_identify as $key => $value)
				{
					$final .= " '".$old_obj[$key]."' ";
				}
				$final .= ".<br>".$description;
				
				$audit = new \audits\Audit;
				$audit->id_user = Auth::id();
				$audit->id_audit_section = $this->id_section;
				$audit->id_audit_action = 2; //Id de la accion EDITAR en la tabla AUDITS.AUDIT_ACTION
				$audit->description = $final;
				$audit->ts_action = Carbon::now()->toDateTimeString();
			
				$audit->save();
			}
		}
	}
}
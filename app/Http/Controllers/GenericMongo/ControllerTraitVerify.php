<?php
namespace \App\Http\Controllers\GenericMongo;

use \Illuminate\Support\Facades\Log;
use \Exception;

trait ControllerTraitVerify {
	private function verifyModel() {
		// Verificar que exista el modelo
		if (! class_exists ( $this->modelo )) {
			$mynameis = get_class ( $this );
			Log::error ( "No se puede cargar la clase {$this->modelo} desde {$mynameis}" );
			throw new Exception ( " No se puede cargar la clase '{$this->modelo}' desde '{$mynameis}' " );
		}
		$modelo = new $this->modelo ();
		// Verificar que el modelo implemenete GenericTrait
		$traitimpl = class_uses ( $modelo );
		$trait = 'GenericModelTrait';
		if (! in_array ( $trait, $traitimpl )) {
			Log::error ( "falta implementar trait {$this->modelo} en {$trait}" );
			throw new Exception ( "Modelo no implementa  trait en el modelo {$this->modelo}" );
		}
		// Verificar que el modelo haya definido la variable mapeo_columnas
		$propiedad = 'mapeo_columnas';
		if (! property_exists ( $modelo, $propiedad )) {
			Log::error ( "falta inicializar la propiedad {$propiedad} en el modelo {$this->modelo}" );
			throw new Exception ( "falta inicializar la propiedad {$propiedad} en el modelo {$this->modelo}" );
		}
		// Verificar que el modelo haya definido la variable mapeo_relaciones
		$propiedad = 'mapeo_relaciones';
		if (! property_exists ( $modelo, $propiedad )) {
			Log::error ( "falta inicializar la propiedad {$propiedad} en el modelo {$this->modelo}" );
			throw new Exception ( "falta inicializar la propiedad {$propiedad} en el modelo {$this->modelo}" );
		}
	}	

}
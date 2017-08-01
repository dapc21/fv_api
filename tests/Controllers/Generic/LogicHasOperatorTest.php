<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Test\Vehiculo;

class LogicHasOperatorTest extends TestCase{
    
    use DatabaseMigrations;
    
    public function testDePrueba()
    {   
        /*
        select "placa" from "public"."test_vehiculo" 
        where "id_empresa" = ? 
        and (
        (
        select count(*) from "public"."test_conductor" inner join "public"."test_conductor_vehiculo" on "public"."test_conductor"."id_conductor" = "public"."test_conductor_vehiculo"."id_conductor" where "public"."test_conductor_vehiculo"."id_vehiculo" = "public"."test_vehiculo"."id_vehiculo" and "num_documento" = ? and "public"."test_conductor_vehiculo"."deleted_at" is null
        ) >= 1 
        or
        (
        select count(*) from "public"."test_conductor" inner join "public"."test_conductor_vehiculo" on "public"."test_conductor"."id_conductor" = "public"."test_conductor_vehiculo"."id_conductor" where "public"."test_conductor_vehiculo"."id_vehiculo" = "public"."test_vehiculo"."id_vehiculo" and "num_documento" = ? and "public"."test_conductor_vehiculo"."deleted_at" is null
        ) >= 1
        )  
         */
        /*$vehiculos = Vehiculo::where('id_empresa','=',864037)
                        ->where(function($query){
                            $query->whereHas('conductores',function ($query) {
                                $query->where('num_documento', '=', '12345667');
                            });
                            $query->orWhereHas('conductores',function ($query) {
                                $query->where('num_documento', '=', '79481786');
                            });
                        })
                    ->get(['placa']);
        dump($vehiculos->toArray());   */
        
        /**
        select "placa" 
        from "public"."test_vehiculo" 
        where "id_empresa" = ? and (
        select count(*) 
        from "public"."test_conductor" 
        inner join "public"."test_conductor_vehiculo" on "public"."test_conductor"."id_conductor" = "public"."test_conductor_vehiculo"."id_conductor" 
        where "public"."test_conductor_vehiculo"."id_vehiculo" = "public"."test_vehiculo"."id_vehiculo" 
        and ("num_documento" = ? or "num_documento" = ?) 
        and "public"."test_conductor_vehiculo"."deleted_at" is null) >= 1  
         */
        /*$vehiculos = Vehiculo::where('id_empresa','=',864037)
                        ->whereHas('conductores',function ($query) {
                            $query->where(function($query){
                                    $query->orWhere('num_documento', '=', '12345667');
                                    $query->orWhere('num_documento', '=', '79481786');
                                });
                            })
                    ->get(['placa']);
        dump($vehiculos->toArray());*/
        
        $json = '{
                    "and":[
                      {
                        "field":"id_empresa",
                        "comparison":"=",
                        "value":864037
                      },
                      {
                        "or":[
                          {
                            "field":"conductores.num_documento",
                            "comparison":"=",
                            "value":"12345667"
                          },
                          {
                            "field":"conductores.num_documento",
                            "comparison":"=",
                            "value":"79481786"
                          }
                        ]
                      }
                    ]
                }';        
        $filters = json_decode($json, true);

        if(isset($filters['and']))
        {
            $query =$this->procesarFiltro($filters['and'], 'and');
        }
        else
        {
            $query =$this->procesarFiltro($filters['or'], 'or');
        }
       
        
        $builder = Vehiculo::query();
        $builder->setQuery($query->getQuery());
        
        $vehiculo = new Vehiculo();
        $consultaFinal = $vehiculo->applyGlobalScopes($builder);
        $vehiculos = $consultaFinal->get(['placa']); 
        
        dump($vehiculos->toArray());
        
       $this->assertTrue(true);
    }
    
    private function procesarFiltro($filters, $logic)
    {
        $conductor = new Vehiculo();
        $query = $conductor->newQueryWithoutScopes();
        foreach ($filters as $operator => $filtro) {      
            if(isset($filtro['and']))
            {
                $subquery = $this->procesarFiltro($filtro['and'], 'and');
                $subquery = $subquery->getQuery();
                $query->addNestedWhereQuery($subquery, $logic);
            }
            else if(isset($filtro['or']))
            {
                $subquery = $this->procesarFiltro($filtro['or'], 'or');
                $subquery = $subquery->getQuery();
                $query->addNestedWhereQuery($subquery, $logic);
            }
            else
            {
                $signoletras = "=";//isset($filtro['comparison'])?$filtro['comparison']:"eq";
                $campo = $filtro['field'];
                $valor = isset($filtro['value'])?$filtro['value']:null;   
                
                
                if($this->isRelation($campo))
                {
                    $components = explode('.', $campo);
                    $relation = $components[0];
                    $field = $components[1];
                    
                    if($logic == 'and')
                    {
                        $query->whereHas($relation,function ($query) use ($field,$signoletras,$valor) {
                            $query->where($field, $signoletras, $valor);
                        });
                    }
                    else
                    {
                        $query->orWhereHas($relation,function ($query) use ($field,$signoletras,$valor) {
                            $query->orWhere($field, $signoletras, $valor);
                        });
                    }
                }
                else
                {
                    if($logic == 'and')
                    {
                        $query->where($campo, $signoletras, $valor);
                    }
                    else
                    {
                        $query->orWhere($campo, $signoletras, $valor);
                    }
                }
            }
        }
        
        return $query;
    } 
    
    private function isRelation($campo)
    {
        $vehiculo = new Vehiculo();
        $mapeoRelaciones = $vehiculo->getRelationshipMap(); //mapeo de ralaciones 
        $aOrd = explode(".", $campo);
        $relation = $aOrd[0];
        return array_key_exists($relation, $mapeoRelaciones);
    }    
}
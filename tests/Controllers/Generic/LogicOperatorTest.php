<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Models\Test\Conductor;

class LogicOperatorTest extends TestCase{
    
    use DatabaseMigrations;
    
    public function testDePrueba()
    {   
        
        $json = '{
                    "or":[
                      {
                        "field":"titulo",
                        "comparison":"lk",
                        "value":"prueba"
                      },
                      {
                        "field":"tema",
                        "comparison":"lk",
                        "value":"prueba"
                      },
                      {
                        "field":"objectio",
                        "comparison":"lk",
                        "value":"prueba"
                      },
                    ]
                }';
        /*$json = '{
                    "or":[
                      {
                        "field":"num_documento",
                        "comparison":"=",
                        "value":"12345667"
                      },
                      {
                        "field":"num_documento",
                        "comparison":"=",
                        "value":"79481786"
                      }
                    ]
                }';*/
        
        $filters = json_decode($json, true);

        if(isset($filters['and']))
        {
            $query =$this->procesarFiltro($filters['and'], 'and');
        }
        else
        {
            $query =$this->procesarFiltro($filters['or'], 'or');
        }
       
        
        $builder = Conductor::query();
        $builder->setQuery($query->getQuery());
        
        $conductor = new Conductor();
        $consultaFinal = $conductor->applyGlobalScopes($builder);
        $conductores = $consultaFinal->get(); 
        
        dump($conductores->count()); 
        
       $this->assertTrue(true);
    }
    
    private function procesarFiltro($filters, $logic)
    {
        $conductor = new Conductor();
        $query = $conductor->newQueryWithoutScopes();//->getQuery();
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
        
        return $query;
    }        
}
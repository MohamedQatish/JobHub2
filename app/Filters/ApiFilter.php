<?php
namespace App\Filters;

class ApiFilter{
    protected $allowdParams = [];
    protected $operatorsMap = [];
    protected $columnsMap = [];


    public function transform($request){
        $eloQuery = [];
        foreach($this->allowdParams as $param => $operators){
            $query = $request->query($param);
            if(!isset($query)){
                continue;
            }
            $column = $this->columnsMap[$param] ?? $param;
            foreach($operators as $operator){
                if(!isset($query[$operator])){
                    continue;
                }
                $eloQuery[] = [$column,$this->operatorsMap[$operator],$query[$operator]];
            }
        }
        return $eloQuery;
    }
}
?>

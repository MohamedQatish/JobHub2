<?php
namespace App\Filters;

class JobFilter extends ApiFilter{
    protected $allowdParams = [
        'category' => ['eq'],
        'experienceLevel' => ['eq'],
        'fixedPrice' => ['eq','gt','gte','lt','lte'],
        'hourlyPayment' => ['eq','gt','gte','lt','lte'],
        'applicants' => ['eq','gt','gte','lt','lte'],
        'scope' => ['eq'],
    ];
    protected $operatorsMap = [
        'eq'=>'=',
        'gt'=>'>',
        'gte'=>'>=',
        'lt'=>'<',
        'lte'=>'<=',
        'ne'=>'!='
    ];
    protected $columnsMap = [
        'category' => 'category_id',
        'experienceLevel' => 'experience_lvl',
        'fixedPrice' => 'fixed_price',
        'hourlyPayment' => 'hourly_payment',
        'applicants' => 'applicants_count'
    ];
}
?>
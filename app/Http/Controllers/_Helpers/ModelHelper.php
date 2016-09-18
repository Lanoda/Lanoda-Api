<?php

namespace App\Http\Controllers\Helpers;


class ModelHelper 
{
    public function __construct() 
    { 

    }

    /**
     * Apply Filters to an eloquent set.
     *
     */
    public static function ApplyFilters(&$set, $filterString)
    {
        if ($filterString == null) return;

        $filterVals = explode("|", $filterString);
        $filters = array_map("App\Http\Controllers\Helpers\Filter::CreateFromString", $filterVals);

        if ($filters != null)
        {
            foreach ($filters as $filter)
            {
                $values = array($filter->Value);
                $valueArray = explode(",", $filter->Value);
                if (count($valueArray) > 1)
                {
                    $values = $valueArray;
                }

                switch ($filter->Operator)
                {
                    // Equal-To
                    case "eq":
                        $set = $set->where($filter->Field, $values);
                        break;

                    // Greater-Than
                    case "gt":
                        $set = $set->where($filter->Field, '>', $values);
                        break;

                    // Less-Than
                    case "lt":
                        $set = $set->where($filter->Field, '<', $values);
                        break;

                    // Postgresql extension, case-insensitive 'LIKE'
                    case "il":
                        for($i = 0; $i < count($values); $i++)
                        {
                            $values[$i] = '%' . $values[$i] . '%';
                            if ($i == 0) 
                            {
                                $set = $set->where($filter->Field, "ilike", $values[$i]);
                            }
                            else 
                            {
                                $set = $set->orWhere($filter->Field, "ilike", $values[$i]);
                            }
                        }
                        break;

                    // Like (or 'contains')
                    case "lk":
                        for($i = 0; $i < count($values); $i++)
                        {
                            $values[$i] = '%' . $values[$i] . '%';
                            if ($i == 0) 
                            {
                                $set = $set->where($filter->Field, "like", $values[$i]);
                            }
                            else 
                            {
                                $set = $set->orWhere($filter->Field, "like", $values[$i]);
                            }
                        }
                        break;

                    // Not Equal
                    case "ne":
                        $set = $set->where($filter->Field, "!=", $values)->orWhereNull($filter->Field);
                        break;
                }
            }
        }
    }
    
    /**
     * Apply Sorts to an eloquent set.
     *
     */
    public static function ApplySorts(&$set, $sortString, $primaryKey)
    {
        if ($sortString == null) return;

        $sortVals = explode("|", $sortString);
        $sorts = array_map("App\Http\Controllers\Helpers\Sort::CreateFromString", $sortVals);

        foreach ($sorts as $sort)
        {
            $set = $set->groupBy($primaryKey)->orderBy($sort->Field, $sort->Direction);
        }
    }

    /**
     * Apply Search to an eloquent set.$_COOKIE
     *
     */
    public static function ApplySearch(&$set, $search) 
    {
        
    }

    /**
     * Apply pagination
     */
     public static function ApplyPagination(&$set, &$pageIndex, &$pageSize) 
     {
        $pi = 0;
        $ps = 50;
        if ($pageIndex != null) {
            $pi = $pageIndex;
        }
        if ($pageSize) {
            $ps = $pageSize;
        }

        if ($pi !== -1 && $ps !== -1) {
            $set = $set->skip($pi * $ps)->take($ps);
        } else {
            $set = $set;
        }

        $pageIndex = $pi;
        $pageSize = $ps;
     }


    /**
     * Apply commonly-used methods and create result
     */
     public function GetListResult($set, array $requestVars, $listTitle, $primaryKey, $transformFunc) 
     {
        $pageIndex = $requestVars['pageIndex'] ?? 0;
        $pageSize = $requestVars['pageSize'] ?? 48;

        if ($set == null) {
            return [
                $listTitle => array(), 
                'TotalResults' => 0, 
                'PageIndex' => $pageIndex, 
                'PageSize' => $pageSize
            ];
        }

        ModelHelper::ApplyFilters($set, $requestVars['filters'] ?? null);
        ModelHelper::ApplySearch($set, $requestVars['search'] ?? null);
        $totalResults = $set->count();
        ModelHelper::ApplySorts($set, $requestVars['sorts'] ?? null, $primaryKey);
        ModelHelper::ApplyPagination($set, $pageIndex, $pageSize);

        $content = [
            $listTitle => $transformFunc($set->get()),
            'TotalResults' => $totalResults,
            'PageIndex' => $pageIndex,
            'PageSize' => $pageSize
        ];

        return $content;
     }
}

class Filter
{
    public $Field;
    public $Operator;
    public $Value;

    public function __construct($field, $operator, $value)
    {
        $this->Field = $field;
        $this->Operator = $operator;
        $this->Value = $value;
    }

    public static function CreateFromString($filterString)
    {
        if ($filterString == null) return null;

        $filterProps = explode("~", $filterString);
        if (count($filterProps) < 3) return "Incorrect format for FilterString.";

        return new Filter($filterProps[0], $filterProps[1], $filterProps[2]);
    }
}

class Sort
{
    public $Field;
    public $Direction;

    public function __construct($field, $direction)
    {
        $this->Field = $field;
        $this->Direction = $direction;
    }

    public static function CreateFromString($sortString)
    {
        if ($sortString == null) return null;

        $sortProps = explode("~", $sortString);
        if (count($sortProps) < 2) return "Incorrect format for SortString.";

        return new Sort($sortProps[0], $sortProps[1]);
    }
}


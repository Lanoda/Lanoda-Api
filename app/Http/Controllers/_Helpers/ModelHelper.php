<?php

namespace App\Http\Controllers\Helpers;

class ModelHelper 
{
    /**
     * Apply Filters to an eloquent set.
     *
     */
    public function ApplyFilters($set, array $filters)
    {
        $returnSet = $set;
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
                        $returnSet = $set->where($filter->Field, $values);
                        break;

                    // Greater-Than
                    case "gt":
                        $returnSet = $set->where($filter->Field, '>', $values);
                        break;

                    // Less-Than
                    case "lt":
                        $returnSet = $set->where($filter->Field, '<', $values);
                        break;

                    // Like (or 'contains')
                    case "lk":
                        for($i = 0; $i < count($values); $i++)
                        {
                            $values[$i] = '%' . $values[$i] . '%';
                        }
                        $returnSet = $set->where($filter->Field, "LIKE", $values);
                        break;

                    // Not
                    case "nt":
                        $returnSet = $set->where($filter->Field, "!=", $values)->orWhereNull($filter->Field);
                        break;
                }
            }
        }

        return $returnSet;
    }
    
    /**
     * Apply Sorts to an eloquent set.
     *
     */
    public function ApplySorts($set, array $sorts)
    {
        $returnSet = $set;
        foreach ($sorts as $sort)
        {
            $returnSet = $returnSet->orderBy($sort->Field, $sort->Direction);
        }

        return $returnSet;
    }

    /**
     * Apply Search to an eloquent set.$_COOKIE
     *
     */
    public function ApplySearch($set, array $search) 
    {
        
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


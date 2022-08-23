<?php

namespace Tailslide;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class Builder
{
    public static function register()
    {
        EloquentBuilder::macro('percentile', function ($column, $percentile) {
            if (!is_numeric($percentile)) {
                throw new \InvalidArgumentException('percentile is not numeric');
            }

            if ($percentile < 0 || $percentile > 1) {
                throw new \InvalidArgumentException('percentile is not between 0 and 1');
            }

            $quotedColumn = $this->getGrammar()->wrap($column);

            $driverName = $this->getConnection()->getDriverName();
            if ($driverName == 'pgsql') {
                $relation = $this->selectRaw("percentile_cont(?) within group (order by $quotedColumn)", [$percentile]);
            } elseif ($driverName == 'mysql') {
                if ($this->getConnection()->isMaria()) {
                    $relation = $this->selectRaw("percentile_cont(?) within group (order by $quotedColumn) over ()", [$percentile]);
                } else {
                    $relation = $this->selectRaw("percentile_cont($quotedColumn, ?)", [$percentile]);
                }
            } elseif ($driverName == 'sqlsrv') {
                $relation = $this->selectRaw("percentile_cont(?) within group (order by $quotedColumn) over ()", [$percentile]);
            } else {
                throw new \Exception("Driver not supported: $driverName");
            }

            $rows = $relation->get()->toArray();

            // for SQL Server
            if (count($rows) == 0) {
                return null;
            }

            $result = array_values($rows[0])[0];

            // cast to float, even though average doesn't
            // https://github.com/laravel/framework/issues/36391
            // possibly add option to disable in the future
            if (!is_null($result)) {
                $result = floatval($result);
            }

            return $result;
        });

        EloquentBuilder::macro('median', function ($column) {
            return $this->percentile($column, 0.5);
        });
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Task extends Model
{
    use HasFactory;

    public function getEnumValues($columnName)
    {
        $tableName = $this->getTable();

        $enumValues = DB::select("SHOW COLUMNS FROM $tableName WHERE Field = '$columnName'")[0]->Type;

        preg_match('/^enum\((.*)\)$/', $enumValues, $matches);

        $enumValues = [];

        if (isset($matches[1])) {
            $enumValues = explode(',', $matches[1]);
            $enumValues = array_map(function($value) {
                return trim($value, "'");
            }, $enumValues);
        }

        return $enumValues;
    }
}

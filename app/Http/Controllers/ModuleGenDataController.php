<?php

namespace App\Http\Controllers;

use App\Models\ModuleGenDataModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ModuleGenDataController extends Controller
{

    public function getData()
    {
        $data = ModuleGenDataModel::select();

        $data = $data->get();

        return $data;
    }

    public function index()
    {
        $data = $this->getData();

        $sent = [
            'data' => $data
        ];
        return view('modulegendata.index', $sent);
    }

    public function updateTable()
    {
        $databaseName = DB::connection()->getDatabaseName();

        $dummy = DB::select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'users' and table_schema = '$databaseName'");
        // return $dummy;
        $exclude = "'failed_jobs', 'migrations', 'password_resets', 'personal_access_tokens', 'module_gen_data'";
        $data = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = '$databaseName' and table_name not in($exclude);");

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->query = $this->generateQuery($data[$i]->table_name);
        }

        for ($i = 0; $i < count($data); $i++) {
            $temp = ModuleGenDataModel::where(['table_name' => $data[$i]->table_name])->first();

            if ($temp == null) {
                $temp = new ModuleGenDataModel();
                $temp->spesial_conditions = "";
            }

            $temp->table_name = $data[$i]->table_name;
            $temp->query = $data[$i]->query;
            $temp->save();
        }
        return redirect('/modulegendata')->with('sukses', 'Success Generated Table');
    }

    public function generateQuery($table_name)
    {
        $databaseName = DB::connection()->getDatabaseName();

        $table = DB::select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' and table_schema = '$databaseName'");

        $query = "CREATE TABLE $table_name (";
        for ($i = 0; $i < count($table); $i++) {
            $c = $table[$i];
            if ($c->DATA_TYPE == "varchar" || $c->DATA_TYPE == "timestamp") {
                $c->DATA_TYPE = " TEXT";
            } else if ($c->DATA_TYPE == "int" || $c->DATA_TYPE == "bigint") {
                $c->DATA_TYPE = " INTEGER";
            }

            if ($c->COLUMN_KEY == "PRI") {
                $c->COLUMN_KEY = " PRIMARY KEY";
            } else {
                $c->COLUMN_KEY = "";
            }

            if ($c->IS_NULLABLE == "NO") {
                $c->IS_NULLABLE = " NOT NULL";
            } else if ($c->IS_NULLABLE == "YES") {
                $c->IS_NULLABLE = " DEFAULT NULL";
            }

            if ($c->EXTRA == "auto_increment") {
                $c->EXTRA = " AUTOINCREMENT";
            } else {
                $c->EXTRA = "";
            }
            $query .= $c->COLUMN_NAME . $c->DATA_TYPE . $c->COLUMN_KEY . $c->IS_NULLABLE . $c->EXTRA . ", ";
        }
        $query = rtrim($query, ', ') . ");";

        return $query;
    }

    public function getDataById($id)
    {
        $find = ModuleGenDataModel::where(["id" => $id])->first();
        return json_encode($find);
    }

    public function updateShow($id)
    {
        return view('modulegendata.edit');
    }

    public function updatePerform(Request $r)
    {
        $data = ModuleGenDataModel::find($r->id);
        $data->update($r->all());
        return redirect('/modulegendata')->with('sukses', 'Success Update Data');
    }

    public function generateFile()
    {
    }
    /*
CREATE TABLE `examples` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `flag_active` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    */
}

/*
CREATE TABLE contacts (
	contact_id INTEGER PRIMARY KEY,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	email TEXT NOT NULL UNIQUE,
	phone TEXT NOT NULL UNIQUE
);
*/

/*
[
  {
    "TABLE_CATALOG": "def",
    "TABLE_SCHEMA": "laravel_module_gen_data",
    "TABLE_NAME": "examples",
    "COLUMN_NAME": "id",
    "ORDINAL_POSITION": 1,
    "COLUMN_DEFAULT": null,
    "IS_NULLABLE": "NO",
    "DATA_TYPE": "int",
    "CHARACTER_MAXIMUM_LENGTH": null,
    "CHARACTER_OCTET_LENGTH": null,
    "NUMERIC_PRECISION": 10,
    "NUMERIC_SCALE": 0,
    "DATETIME_PRECISION": null,
    "CHARACTER_SET_NAME": null,
    "COLLATION_NAME": null,
    "COLUMN_TYPE": "int(11)",
    "COLUMN_KEY": "PRI",
    "EXTRA": "auto_increment",
    "PRIVILEGES": "select,insert,update,references",
    "COLUMN_COMMENT": "",
    "IS_GENERATED": "NEVER",
    "GENERATION_EXPRESSION": null
  },
  {
    "TABLE_CATALOG": "def",
    "TABLE_SCHEMA": "laravel_module_gen_data",
    "TABLE_NAME": "examples",
    "COLUMN_NAME": "name",
    "ORDINAL_POSITION": 2,
    "COLUMN_DEFAULT": null,
    "IS_NULLABLE": "NO",
    "DATA_TYPE": "varchar",
    "CHARACTER_MAXIMUM_LENGTH": 255,
    "CHARACTER_OCTET_LENGTH": 1020,
    "NUMERIC_PRECISION": null,
    "NUMERIC_SCALE": null,
    "DATETIME_PRECISION": null,
    "CHARACTER_SET_NAME": "utf8mb4",
    "COLLATION_NAME": "utf8mb4_general_ci",
    "COLUMN_TYPE": "varchar(255)",
    "COLUMN_KEY": "",
    "EXTRA": "",
    "PRIVILEGES": "select,insert,update,references",
    "COLUMN_COMMENT": "",
    "IS_GENERATED": "NEVER",
    "GENERATION_EXPRESSION": null
  },
  {
    "TABLE_CATALOG": "def",
    "TABLE_SCHEMA": "laravel_module_gen_data",
    "TABLE_NAME": "examples",
    "COLUMN_NAME": "flag_active",
    "ORDINAL_POSITION": 3,
    "COLUMN_DEFAULT": "1",
    "IS_NULLABLE": "NO",
    "DATA_TYPE": "int",
    "CHARACTER_MAXIMUM_LENGTH": null,
    "CHARACTER_OCTET_LENGTH": null,
    "NUMERIC_PRECISION": 10,
    "NUMERIC_SCALE": 0,
    "DATETIME_PRECISION": null,
    "CHARACTER_SET_NAME": null,
    "COLLATION_NAME": null,
    "COLUMN_TYPE": "int(11)",
    "COLUMN_KEY": "",
    "EXTRA": "",
    "PRIVILEGES": "select,insert,update,references",
    "COLUMN_COMMENT": "",
    "IS_GENERATED": "NEVER",
    "GENERATION_EXPRESSION": null
  },
  {
    "TABLE_CATALOG": "def",
    "TABLE_SCHEMA": "laravel_module_gen_data",
    "TABLE_NAME": "examples",
    "COLUMN_NAME": "created_at",
    "ORDINAL_POSITION": 4,
    "COLUMN_DEFAULT": "current_timestamp()",
    "IS_NULLABLE": "NO",
    "DATA_TYPE": "timestamp",
    "CHARACTER_MAXIMUM_LENGTH": null,
    "CHARACTER_OCTET_LENGTH": null,
    "NUMERIC_PRECISION": null,
    "NUMERIC_SCALE": null,
    "DATETIME_PRECISION": 0,
    "CHARACTER_SET_NAME": null,
    "COLLATION_NAME": null,
    "COLUMN_TYPE": "timestamp",
    "COLUMN_KEY": "",
    "EXTRA": "",
    "PRIVILEGES": "select,insert,update,references",
    "COLUMN_COMMENT": "",
    "IS_GENERATED": "NEVER",
    "GENERATION_EXPRESSION": null
  },
  {
    "TABLE_CATALOG": "def",
    "TABLE_SCHEMA": "laravel_module_gen_data",
    "TABLE_NAME": "examples",
    "COLUMN_NAME": "updated_at",
    "ORDINAL_POSITION": 5,
    "COLUMN_DEFAULT": "NULL",
    "IS_NULLABLE": "YES",
    "DATA_TYPE": "timestamp",
    "CHARACTER_MAXIMUM_LENGTH": null,
    "CHARACTER_OCTET_LENGTH": null,
    "NUMERIC_PRECISION": null,
    "NUMERIC_SCALE": null,
    "DATETIME_PRECISION": 0,
    "CHARACTER_SET_NAME": null,
    "COLLATION_NAME": null,
    "COLUMN_TYPE": "timestamp",
    "COLUMN_KEY": "",
    "EXTRA": "on update current_timestamp()",
    "PRIVILEGES": "select,insert,update,references",
    "COLUMN_COMMENT": "",
    "IS_GENERATED": "NEVER",
    "GENERATION_EXPRESSION": null
  }
]
*/
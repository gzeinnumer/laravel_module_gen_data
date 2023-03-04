<?php

namespace App\Http\Controllers;

use App\Models\ModuleGenDataModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

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
                $temp->table_name = $data[$i]->table_name;
                $temp->query = $data[$i]->query;
                $temp->save();
            }
        }
        return redirect('/modulegendata')->with('sukses', 'Success Generated Table');
    }

    public function regenerateAll()
    {
        $databaseName = DB::connection()->getDatabaseName();

        $exclude = "'failed_jobs', 'migrations', 'password_resets', 'personal_access_tokens', 'module_gen_data'";
        $data = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = '$databaseName' and table_name not in($exclude);");

        for ($i = 0; $i < count($data); $i++) {
            $data[$i]->query = $this->generateQuery($data[$i]->table_name);
        }

        for ($i = 0; $i < count($data); $i++) {
            $temp = ModuleGenDataModel::where(['table_name' => $data[$i]->table_name])->first();

            if ($temp != null) {
                $temp->table_name = $data[$i]->table_name;
                $temp->query = $data[$i]->query;
                $temp->save();
            }
        }
        return redirect('/modulegendata')->with('sukses', 'Success Re-Generated Table');
    }

    public function generateQuery($table_name)
    {
        $databaseName = DB::connection()->getDatabaseName();

        $table = DB::select("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table_name' and table_schema = '$databaseName'");

        $query = "CREATE TABLE $table_name (";
        for ($i = 0; $i < count($table); $i++) {
            $c = $table[$i];
            if ($c->DATA_TYPE == "varchar" || $c->DATA_TYPE == "timestamp" || $c->DATA_TYPE == "text") {
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
            $query .= "\n" . $c->COLUMN_NAME . $c->DATA_TYPE . $c->COLUMN_KEY . $c->IS_NULLABLE . $c->EXTRA . ", ";
        }
        $query = rtrim($query, ", ") . "\n);";

        return $query;
    }

    public function getDataById($id)
    {
        $find = ModuleGenDataModel::where(["id" => $id])->first();
        return json_encode($find);
    }

    public function updateShow($id)
    {
        return view('modulegendata.edit', ['id' => $id]);
    }

    public function updatePerform(Request $r)
    {
        $data = ModuleGenDataModel::find($r->id);
        $data->update($r->all());
        return redirect('/modulegendata')->with('sukses', 'Success Update Data');
    }

    public function regenerateqQuery($id)
    {
        $data = ModuleGenDataModel::find($id);
        $data->query = $this->generateQuery($data->table_name);

        return response([
            'data' => $data->query,
        ]);
    }

    public function generateFile()
    {
        $date = date('Ymd');
        $path = public_path() . '/generate/' . $date;
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }

        $users = User::all();

        for ($i = 0; $i < count($users); $i++) {
            $p = $path . "/" . $users[$i]->id;
            if (!File::exists($p)) {
                File::makeDirectory($p);
            }

            $output = $this->generateSql($users[$i]->id);

            $myfile = fopen($p . "/db.db", "w") or die("Unable to open file!");
            fwrite($myfile, $output);
            fclose($myfile);

            $rootPath = $p;

            $zip = new ZipArchive();
            $zip->open($p . '/db.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
            //delete file
            unlink($p . "/db.db");
        }
        return response(["data" => "success"]);
    }

    public function generateSql($id)
    {
        $res = "CREATE DATABASE db;\n\n";

        $databaseName = DB::connection()->getDatabaseName();
        $exclude = "'failed_jobs', 'migrations', 'password_resets', 'personal_access_tokens', 'module_gen_data'";
        $data = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = '$databaseName' and table_name not in($exclude);");

        for ($i = 0; $i < count($data); $i++) {
            $d = ModuleGenDataModel::where('table_name', $data[$i]->table_name)->first();
            $res .= "" . $d->query . "\n\n";

            $res .= "INSERT INTO $d->table_name VALUES \n";

            $c = DB::select("SELECT * FROM $d->table_name $d->spesial_conditions");
            for ($j = 0; $j < count($c); $j++) {
                $res .= "(";
                foreach ($c[$j] as $c1) {
                    $res .= "'" . $c1 . "',";
                }
                $res = rtrim($res, ",");
                $res .= "),\n";
            }
            $res = rtrim($res, ",\n");
            $res .= ";\n\n\n";
        }

        return $res;
    }
}

/*
CREATE TABLE examples (
    id int(11) NOT NULL,
    name varchar(255) NOT NULL,
    flag_active int(11) NOT NULL DEFAULT 1,
    created_at timestamp NOT NULL DEFAULT current_timestamp(),
    updated_at timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
*/

/*
CREATE TABLE contacts (
	contact_id INTEGER PRIMARY KEY,
	first_name TEXT NOT NULL,
	last_name TEXT NOT NULL,
	email TEXT NOT NULL UNIQUE,
	phone TEXT NOT NULL UNIQUE
);
*/

<?php
Route::get("db_seeder",function (){
    $table_list = DB::select('SHOW TABLES');
    $tables = [];
    foreach ($table_list as $table) {
        $tables[] = $table->Tables_in_orbisya;
    }

    $db_data = [];
    foreach ($tables as $table) {
        $db_data[$table] = DB::table($table)->get();
    }
    $db_data = json_decode(json_encode($db_data), true);
    Artisan::call("migrate:fresh");

    $report_tables = [];
    $row_count = 0;
    foreach ($db_data as $key => $value) {
        if ($key != "migrations"){
            $report_tables[$key] = count($value);
            $row_count += count($value);
            DB::table($key)->insert($value);
        }
    }
    $report = "";
    $report .= "Total number of tables ".count($report_tables).". Total number of rows ".$row_count.".<br>";
    foreach ($report_tables as $key => $value) {
        $report .= $key." = ".$value."<br>";
    }
    echo $report;
});
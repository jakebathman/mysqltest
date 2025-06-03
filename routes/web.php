<?php

use App\Models\Record;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    DB::select('ANALYZE TABLE records');

    return view('welcome', [
        'count' => Record::count(),
        'latest' => Record::latest()->first()?->created_at ?: '—',
        'size' => DB::select('SELECT
            table_name AS `Table`,
            round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB`
            FROM information_schema.TABLES
            WHERE table_schema = "mysqltest"
            AND table_name = "records"')[0]->{'Size in MB'},
    ]);
})->name('home');

Route::get('backup', function () {
    $password = config('database.connections.mysql.password');
    $filename = 'backup_' . now()->format('Y-m-d_H-i-s') . '.sql';

    Storage::disk('public')->makeDirectory('backups');

    $process = Process::run([
        '/Users/jakebathman/Library/Application Support/Herd/bin//mysqldump',
        '-h', config('database.connections.mysql.host'),
        '-u', config('database.connections.mysql.username'),
        "--password={$password}",
        config('database.connections.mysql.database'),
        '--result-file=' . storage_path("app/public/backups/{$filename}"),
    ]);

    $size = Storage::disk('public')->size("backups/{$filename}");
    $sizeInMB = round($size / 1024 / 1024, 2);

    return response()->json([
        'exitCode' => $process->exitCode(),
        'output' => $process->output(),
        'errorOutput' => $process->errorOutput(),
        'filename' => $filename,
        'path' => Storage::disk('public')->path("backups/{$filename}"),
        'size' => $sizeInMB,
    ]);
})->name('backup');

Route::get('truncate', function () {
    return redirect(route('home'))->with([
        'success' => Record::truncate() ? 'Records truncated successfully.' : 'Failed to truncate records.',
    ]);
})->name('truncate');

Route::get('generate', function () {
    $countDefault = 30_000;
    $records = Record::factory()
        ->count(request()->get('count', $countDefault))
        ->create();

    DB::select('ANALYZE TABLE records');

    return response()->json([
        'count' => Record::count(),
        'latest' => Record::latest()->first()?->created_at ?: '—',
        'size' => DB::select('SELECT
            table_name AS `Table`,
            round(((data_length + index_length) / 1024 / 1024), 2) `Size in MB`
            FROM information_schema.TABLES
            WHERE table_schema = "mysqltest"
            AND table_name = "records"')[0]->{'Size in MB'},
    ]);
})->name('generate');

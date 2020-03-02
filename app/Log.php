<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;


class Log extends Model
{
    protected $table = "logs";

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id', 'action'
    ];

    public $timestamps = true;

    //Get all logs
    public function getLogs()
    {
        $logs = DB::table('logs')
            ->selectRaw('users.email, users.access_level, logs.action, logs.created_at')
            ->leftJoin('users', function ($query) {
                $query->on('users.id', 'logs.user_id');
            })
            ->limit(30);

        return $logs->get()->sortByDesc('created_at');
    }

    //Store Logs
    public function storeLog($log_details)
    {
        $store_log = Log::create($log_details);

        return $store_log;
    }
}

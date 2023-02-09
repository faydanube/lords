<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analysis extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function day()
    {
        return Lord::where('expired_at', now()->addDay()->startOfDay())->count();
    }

    public static function month()
    {
        $start_of_month = now()->startOfMonth();

        $lord_list = Lord::select(['id', 'is_sync', 'expired_at'])
            ->where('is_sync', 0)
            ->where('expired_at', '>=', $start_of_month);

        self::sync($lord_list);

        return intval(self::where('expired_at', '>=', $start_of_month)->sum('score'));
    }

    public static function total()
    {
        $lord_list = Lord::select(['id', 'is_sync', 'expired_at'])
            ->where('is_sync', 0);

        self::sync($lord_list);

        return intval(self::sum('score'));
    }

    public static function byMonth($month)
    {
        $start_of_month = $month . '-01';
        $end_of_month   = Carbon::parse($start_of_month)->endOfMonth();

        $lord_list = Lord::select(['id', 'is_sync', 'expired_at'])
            ->where('is_sync', 0)
            ->where('expired_at', '>=', $start_of_month)
            ->where('expired_at', '<=', $end_of_month);

        self::sync($lord_list);

        $analysis_list = self::where('expired_at', '>=', $start_of_month)
            ->where('expired_at', '<=', $end_of_month)
            ->pluck('score', 'expired_at');

        $list = [];
        for ($i=1; $i < 10; $i++) {
            if ($analysis_list->has($month . '-0' . $i)) {
                $list[$i] = $analysis_list[$month . '-0' . $i];
            } else {
                $list[$i] = 0;
            }
        }

        $days = cal_days_in_month(CAL_GREGORIAN, substr($month, -2), substr($month, 0, 4));
        for ($i=10; $i <= $days; $i++) {
            if ($analysis_list->has($month . '-' . $i)) {
                $list[$i] = $analysis_list[$month . '-' . $i];
            } else {
                $list[$i] = 0;
            }
        }

        return $list;
    }

    private static function sync($eloquent)
    {
        if ($eloquent->exists()) {
            $eloquent->chunkById(10000, function ($eloquent) {
                $arr = [];
                foreach ($eloquent as $lord) {
                    $arr[$lord->expired_at][] = $lord;
                }

                foreach($arr as $date => $item) {
                    Analysis::create([
                        'score'      => count($item),
                        'expired_at' => $date,
                    ]);
                }
            });

            $eloquent->update(['is_sync' => 1]);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use DB;
use Auth;
use Response;
use Helper;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class ActivityController extends Controller
{
    public static function log($user, $action, $title, $note, $oldnote, $date)
    {
        DB::beginTransaction();
        try{
            $activity = new Activity();
            $activity->iduser = $user;
            $activity->action = $action;
            $activity->title = $title;
            $activity->note = $note;
            $activity->oldnote = $oldnote;
            $activity->date = $date;
            $activity->save();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function historyAktivitas()
    {
        $date = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
            ->orderBy('id', 'desc')
            ->distinct()
            ->limit(10)
            ->get();

        $data = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                'users.name as user',
                'activity.action',
                'activity.title',
                'activity.note',
                'activity.oldnote',
                'activity.created_at',
                'activity.id',
                DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
            ->join('users', 'activity.iduser', '=', 'users.id')
            ->orderBy('activity.id', 'desc')
            ->get();

        return view('member.history.aktivitas')->with(compact('data', 'date'));
    }

    public function historyAktivitasAdmin()
    {
        $date = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
            ->orderBy('id', 'desc')
            ->distinct()
            ->limit(10)
            ->get();

        $data = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                'users.name as user',
                'activity.action',
                'activity.title',
                'activity.note',
                'activity.oldnote',
                'activity.created_at',
                'activity.id',
                DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
            ->join('users', 'activity.iduser', '=', 'users.id')
            ->orderBy('activity.id', 'desc')
            ->get();

        return view('admin.history.aktivitas')->with(compact('data', 'date'));
    }

    public function filterHistoryAktivitas($tanggal)
    {
        if ($tanggal != "null") {
            $tgl_explode = explode(" ", $tanggal);
            $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
            $tanggal = date('Y-m-d', strtotime($tgl));
            $date = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->where(DB::raw('substr(date, 1, 10)'), '=', $tanggal)
                ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
                ->orderBy('id', 'desc')
                ->distinct()
                ->get();

            $data = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->where(DB::raw('substr(date, 1, 10)'), '=', $tanggal)
                ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                    'users.name as user',
                    'activity.action',
                    'activity.title',
                    'activity.note',
                    'activity.oldnote',
                    'activity.created_at',
                    'activity.id',
                    DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
                ->join('users', 'activity.iduser', '=', 'users.id')
                ->orderBy('activity.id', 'desc')
                ->get();
        } else {
            $date = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
                ->orderBy('id', 'desc')
                ->distinct()
                ->limit(10)
                ->get();

            $data = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                    'users.name as user',
                    'activity.action',
                    'activity.title',
                    'activity.note',
                    'activity.oldnote',
                    'activity.created_at',
                    'activity.id',
                    DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
                ->join('users', 'activity.iduser', '=', 'users.id')
                ->orderBy('activity.id', 'desc')
                ->get();
        }


        $result = array();
        foreach ($data as $key => $dt){
            $result[] = [
                'date' => $dt->date,
                'user' => $dt->user,
                'action' => $dt->action,
                'title' => $dt->title,
                'note' => $dt->note,
                'oldnote' => $dt->oldnote,
                'created_at' => $dt->created_at,
                'tgl' => $dt->tgl,
                'times' => Carbon::parse($dt->created_at)->diffForHumans()
            ];
        }

        return Response::json(['tanggal' => $date, 'data' => $result]);
    }
}
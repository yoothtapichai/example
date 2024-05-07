<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Leave_type;
use App\Models\Log_login;
use App\Models\Notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Validator;

class LeaveController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $user =  Auth::user();

        if (empty($user->created_by)) {
            $user->created_by = 0;
        }

        if (!file_exists($user->avatar) && ($user->provider == 'email')) {
            $user->avatar = '';
        }
        $leaveTypes  = Leave_type::all();

        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->where('user_id', Auth::user()->id)
            ->select('leaves.*', 'leave_types.leave_type_name')
            ->orderBy('created_at', 'desc') // 
            ->get();

        foreach ($leaves as $key => $value) {
            $start_date = Carbon::parse($value['start_date']);
            $end_date = Carbon::parse($value['end_date']);

            $days = 0; // เริ่มต้นจำนวนวันลาเป็น 0

            if ($start_date->eq($end_date)) {
                // $start_date เป็นวันเดียวกับ $end_date
                $days = 1; // กำหนดค่า 1 วัน
            } else {
                // $start_date ไม่ได้เป็นวันเดียวกับ $end_date
                $days = $end_date->diffInDays($start_date) + 1; // คำนวณจำนวนวัน 
            }
            $leaves[$key]->ifdate = Carbon::now()->lt($value['start_date']);
            // อัปเดตจำนวนวันลาในแต่ละใบลา
            $leaves[$key]->days = $days;
        }


        $startDate = now()->format('Y-m-d\TH:i');
        $endDate = now()->addMinutes(1)->format('Y-m-d\TH:i');
        $pendingLeaves = $this->countLeaveStatus(1);
        $approvedLeaves = $this->countLeaveStatus(2);
        $rejectedLeaves = $this->countLeaveStatus(3);

        $remainingLeave = $pendingLeaves['days_left'] + $approvedLeaves['days_left'] + $rejectedLeaves['days_left'];
        $pending = !empty($pendingLeaves['count']) ? $pendingLeaves['count'] : 0;
        $approved = !empty($approvedLeaves['count']) ? $approvedLeaves['count'] : 0;
        $rejected = !empty($rejectedLeaves['count']) ? $rejectedLeaves['count'] : 0;
        // dd($rejected);
        $modal = array();

        foreach ($leaveTypes as $key => $value) {

            $leaveTypeId = $leaveTypes[$key]['id'];
            $leaveTypeName = $this->leave_types_name($leaveTypeId);

            $leaveStatusCount = DB::table('leaves')
                ->where('user_id', Auth::user()->id)
                ->where('leave_type_id', $leaveTypeId)
                ->where('leave_status', 2) // APPROVED
                ->whereYear('leaves.start_date', Carbon::now()->year)
                ->select(DB::raw('SUM(leaves.date) AS count'))
                ->first()->count;


            $leaveLimit = Leave_type::where('id', $leaveTypeId)->first()->leave_limit;
            $daysLeft = $leaveLimit - $leaveStatusCount;
            $modal[$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .  $daysLeft .   ' วัน';
        }


        return view('leave.index', compact('user', 'leaves', 'pending', 'approved', 'rejected', 'remainingLeave', 'modal', 'leaveTypes', 'startDate', 'endDate'));
    }
    public function request()
    {
        $user =  Auth::user();
        $leaveTypes  = Leave_type::all();
        $startDate = now()->format('Y-m-d\TH:i');
        $endDate = now()->addMinutes(1)->format('Y-m-d\TH:i');



        $pendingLeaves = $this->countLeaveStatus(1);
        $approvedLeaves = $this->countLeaveStatus(2);
        $rejectedLeaves = $this->countLeaveStatus(3);
        $remainingLeave = $pendingLeaves['days_left'] + $approvedLeaves['days_left'] + $rejectedLeaves['days_left'];
        $pending = !empty($pendingLeaves['count']) ? $pendingLeaves['count'] : 0;
        $approved = !empty($approvedLeaves['count']) ? $approvedLeaves['count'] : 0;
        $rejected = !empty($rejectedLeaves['count']) ? $rejectedLeaves['count'] : 0;

        $modal = array();

        foreach ($leaveTypes as $key => $value) {

            $leaveTypeId = $leaveTypes[$key]['id'];
            $leaveTypeName = $this->leave_types_name($leaveTypeId);

            // $leaveStatusCount = DB::table('leaves')
            //     ->where('leave_type_id', $leaveTypeId)
            //     ->where('leave_status', 2) // APPROVED
            //     ->count();

            $leaveStatusCount = DB::table('leaves')
                ->where('user_id', Auth::user()->id)
                ->where('leave_type_id', $leaveTypeId)
                ->where('leave_status', 2) // APPROVED
                ->whereYear('leaves.start_date', Carbon::now()->year)
                ->select(DB::raw('SUM(leaves.date) AS count'))
                ->first()->count;

            $leaveLimit = Leave_type::where('id', $leaveTypeId)->first()->leave_limit;
            $daysLeft = $leaveLimit - $leaveStatusCount;
            $modal[$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .  $daysLeft .   ' วัน';
        }



        if (empty($user->created_by)) {
            $user->created_by = 0;
        }

        if (!file_exists($user->avatar) && ($user->provider == 'email')) {
            $user->avatar = '';
        }

        return view('leave.request', compact('user', 'leaveTypes', 'startDate', 'endDate', 'pending', 'approved', 'rejected', 'remainingLeave', 'modal'));
    }
    public function adddata(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'leave_period' => 'required|numeric|min:1',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'phone_number' => 'required|regex:/\d{10}/',
            'leave_reason' => 'required|string|min:10',
        ]);


        //เช็คลาล่วงหน้า
        $return =  $this->check_date($request->start_date, $request->leave_type_id);

        if ($return['success'] != true) {

            return response()->json(['success' => false, 'msg' => $return['message']]);
        }
        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $limit = $this->countLeaveStatus($request->leave_type_id);
                if ($limit['days_left'] == 0) {
                    $_name = $this->leave_types_name($request->leave_type_id);
                    return response()->json(['success' => false, 'msg' => "จำนวนประเภทการลา " . $_name . " ของคุณครบแล้ว"]);
                }

                $addLeave = new Leave();
                $addLeave->leave_type_id = $request->leave_type_id;
                $addLeave->user_id = Auth::user()->id;
                $addLeave->leave_period = $request->leave_period;
                $addLeave->start_date = $request->start_date;
                $addLeave->end_date = $request->end_date;

                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $numberOfDays = $endDate->diffInDays($startDate);
                $addLeave->date = $numberOfDays + 1;
                $addLeave->phone_number = $request->phone_number;
                $addLeave->leave_reason = $request->leave_reason;
                $addLeave->leave_status = 1;

                $addLeave->created_at     = date("Y-m-d H:i:s");
                $addLeave->updated_at     = date("Y-m-d H:i:s");
                $addLeave->save();

                $noti =  new Notifications();
                $noti->user_id = Auth::user()->id;
                $noti->user_name = Auth::user()->name;
                $noti->user_type = (Auth::user()->type == 'admin') ? 1 : 0;
                $noti->leave_id = $request->leave_type_id;
                $noti->leave_name = $this->leave_types_name($request->leave_type_id);
                $noti->created_at     = date("Y-m-d H:i:s");
                $noti->updated_at     = date("Y-m-d H:i:s");
                $noti->save();

                return response()->json(['success' => true, 'msg' => 'Add a leave letter']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }
    public function editdata(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'leave_type_id' => 'required|integer|exists:leave_types,id',
            'leave_period' => 'required|numeric|min:1',
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'phone_number' => 'required|regex:/\d{10}/',
            'leave_reason' => 'required|string|min:10',
        ]);




        if ($validator->fails()) {

            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $limit = $this->countLeaveStatus($request->leave_type_id);
                if ($limit['days_left'] == 0) {
                    $_name = $this->leave_types_name($request->leave_type_id);
                    return response()->json(['success' => false, 'msg' => "จำนวนประเภทการลา " . $_name . " ของคุณครบแล้ว"]);
                }

                $startDate = Carbon::parse($request->start_date);
                $endDate = Carbon::parse($request->end_date);
                $numberOfDays = $endDate->diffInDays($startDate);
                $date__ = $numberOfDays + 1;

                $editLeave = Leave::where('id', $request->id)->update([
                    'leave_type_id' => $request->leave_type_id,
                    'leave_period' => $request->leave_period,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'date' =>   $date__,
                    'phone_number' => $request->phone_number,
                    'leave_reason' => $request->leave_reason,
                    'leave_status' => 1,
                    'updated_at' => date("Y-m-d H:i:s"),

                ]);

                $noti =  new Notifications();
                $noti->user_id = Auth::user()->id;
                $noti->user_name = Auth::user()->name;
                $noti->user_type = (Auth::user()->type == 'admin') ? 1 : 0;
                $noti->leave_id = $request->leave_type_id;
                $noti->leave_name = $this->leave_types_name($request->leave_type_id);
                $noti->created_at     = date("Y-m-d H:i:s");
                $noti->updated_at     = date("Y-m-d H:i:s");
                $noti->save();



                return response()->json(['success' => true, 'msg' => 'Updated a leave letter']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }
    }


    public function listdata()
    {
        try {

            $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
                ->join('users', 'users.id', '=', 'leaves.user_id')
                ->select('leaves.*', 'leave_types.leave_type_name', 'users.name', 'users.email')
                ->orderBy('created_at', 'desc')
                ->get();



            $leaveTypes  = Leave_type::all();

            $modal = array();
            foreach ($leaves as $k => $v) {
                // dd($v->user_id);
                if (empty($modal[$v->user_id])) {
                    foreach ($leaveTypes as $key => $value) {

                        $leaveTypeId = $leaveTypes[$key]['id'];
                        $leaveTypeName = $this->leave_types_name($leaveTypeId);

                        // $leaveStatusCount = DB::table('leaves')
                        //     ->where('user_id', $v->user_id)
                        //     ->where('leave_type_id', $leaveTypeId)
                        //     ->where('leave_status', 2) // APPROVED
                        //     ->count();

                        $leaveStatusCount = DB::table('leaves')
                            ->where('user_id', $v->user_id)
                            ->where('leave_type_id', $leaveTypeId)
                            ->where('leave_status', 2) // APPROVED
                            ->whereYear('leaves.start_date', Carbon::now()->year)
                            ->select(DB::raw('SUM(leaves.date) AS count'))
                            ->first()->count;


                        // $leaveStatusCount = DB::table('leaves')
                        //     ->where('leave_type_id', $leaveTypeId)
                        //     ->where('leave_status', 2) // APPROVED


                        $leaveLimit = Leave_type::where('id', $leaveTypeId)->first()->leave_limit;
                        $daysLeft = $leaveLimit - $leaveStatusCount;
                        $modal[$v->user_id][$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .  $daysLeft .   ' วัน';
                    }
                }
            }
            // dd( $modal);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }


        return view('leave.listdata', compact('leaves', 'modal'));
    }
    public function approved(Request $request)
    {

        try {

            $leave = Leave::where('id',  $request->id)
                ->first();

            // dd($leave->leave_status);
            if ($leave->leave_status == "1") {

                Leave::where('id', $request->id)->update([
                    'leave_status' => 2,
                    'updated_at' => date("Y-m-d H:i:s"),

                ]);
            }

            $noti =  new Notifications();
            $noti->user_id = Auth::user()->id;
            $noti->user_name = Auth::user()->name;
            $noti->user_type = (Auth::user()->type == 'admin') ? 1 : 0;
            $noti->leave_id = $request->id;
            $noti->leave_name = $this->leave_name($request->id);
            $noti->created_at     = date("Y-m-d H:i:s");
            $noti->updated_at     = date("Y-m-d H:i:s");
            $noti->save();


            return response()->json(['success' => true, 'msg' => 'Leaves status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }


        // return view('leave.listdata', compact('leaves', 'modal'));
    }
    public function admin_comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admin_comment' => 'required|string|min:10',
        ]);

        // dd($request->all());
        if ($validator->fails()) {
            return response()->json(['msg' => $validator->errors()->toArray()]);
        } else {
            try {

                $leave = Leave::where('id',  $request->id)
                    ->first();

                // dd($leave->leave_status);
                if ($leave->leave_status == "1") {

                    Leave::where('id', $request->id)->update([
                        'leave_status' => 3,
                        'admin_comment' => $request->admin_comment,
                        'updated_at' => date("Y-m-d H:i:s"),

                    ]);
                }

                $noti =  new Notifications();
                $noti->user_id = Auth::user()->id;
                $noti->user_name = Auth::user()->name;
                $noti->user_type = (Auth::user()->type == 'admin') ? 1 : 0;
                $noti->leave_id = $request->id;
                $noti->leave_name = $this->leave_name($request->id);
                $noti->created_at     = date("Y-m-d H:i:s");
                $noti->updated_at     = date("Y-m-d H:i:s");
                $noti->save();


                return response()->json(['success' => true, 'msg' => 'Leaves status updated successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()]);
            }
        }


        // return view('leave.listdata', compact('leaves', 'modal'));
    }
    public function admin_delete_leaves(Request $request)
    {
        try {


            Leave::where('id', $request->id)->delete();

            return response()->json(['success' => true, 'msg' => 'Leaves delete successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function user_delete_leaves(Request $request)
    {
        try {
            $leaveId = $request->id;


            $leave = Leave::where('id', $leaveId)
                ->where('user_id', Auth::user()->id)
                ->first();

            if ($leave) {
                $leave->delete();
                return response()->json(['success' => true, 'msg' => 'Leave deleted successfully']);
            } else {
                return response()->json(['success' => false, 'msg' => 'Unauthorized deletion attempt']);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function noti(Request $request)
    {

        try {

            $type = (Auth::user()->type == 'admin') ? 0 : 1;

            if ($type  == 0) {
                $Notifi = Notifications::where('user_type', $type)
                    ->orderBy('created_at', 'desc') // Order by created_at descending
                    ->get();
            } else {
                $userId = Auth::user()->id;
                $Notifi = Notifications::selectRaw('notifications.*, leaves.user_id as leave_user_id, leaves.leave_status')
                    ->join('leaves', 'notifications.leave_id', '=', 'leaves.id')
                    ->where('leaves.user_id', $userId) // Filter by current user's ID
                    ->where('notifications.user_id', '!=', 'leaves.user_id') // Filter where user_id in notifications is different from leaves
                    ->where('user_type', '!=', 0) // Filter where user_type is not 0
                    ->orderBy('created_at', 'desc')
                    ->get();
                // Auth::user()->id
                // dd($Notifi );
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        if ($type  == 0) {
            return view('leave.noti_admin', compact('Notifi'));
        } else {
            return view('leave.noti_user', compact('Notifi'));
        }
    }
    public function seen_noti(Request $request)
    {


        try {
            $type = (Auth::user()->type == 'admin') ? 0 : 1;

            $Notifi = Notifications::where('user_type', $type)
                ->orderBy('created_at', 'desc') // 
                ->get();

           
            $unreadCount = $Notifi->where('seen', 0)->count();

       
            $data = [
                'unread_count' => $unreadCount,
                'notifications' => $Notifi,
            ];

            return response()->json(['success' => true, 'msg' => 'Notifications retrieved successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function seen_noti_user(Request $request)
    {

        try {
            $type = (Auth::user()->type == 'admin') ? 0 : 1;


            $userId = Auth::user()->id;
            $Notifi = Notifications::selectRaw('notifications.*, leaves.user_id as leave_user_id, leaves.leave_status')
                ->join('leaves', 'notifications.leave_id', '=', 'leaves.id')
                ->where('leaves.user_id', $userId) // Filter by current user's ID
                ->where('notifications.user_id', '!=', 'leaves.user_id') // Filter where user_id in notifications is different from leaves
                ->where('user_type', '!=', 0) // Filter where user_type is not 0
                ->orderBy('created_at', 'desc')
                ->get();
            // $Notifi = Notifications::where('user_type', $type)
            //     ->orderBy('created_at', 'desc') // Order by created_at descending
            //     ->get();

            // Count unread notifications
            $unreadCount = $Notifi->where('seen', 0)->count();

            // Prepare response data
            $data = [
                'unread_count' => $unreadCount,
                'notifications' => $Notifi,
            ];

            return response()->json(['success' => true, 'msg' => 'Notifications retrieved successfully', 'data' => $data]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function seen_admin($id)
    {


        try {
            // ดึงข้อมูลผู้ใช้จาก ID ที่ส่งเข้ามา

            $notif = Notifications::where('id', $id)
                // ->where('user_id', Auth::user()->id)
                ->first();

            // ตรวจสอบว่ามีผู้ใช้หรือไม่
            if (Auth::user()->type != 'admin') {
                return response()->json(['success' => false, 'msg' => 'User not found or is Admin']);
            }
            // dd($notif);
            if ($notif->seen == 0) {
                $notif->seen = 1;
                $notif->save();
            }



            return response()->json(['success' => true, 'msg' => 'User status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function seen_user($id)
    {


        try {
            // ดึงข้อมูลผู้ใช้จาก ID ที่ส่งเข้ามา

            $notif = Notifications::where('id', $id)
                // ->where('user_id', Auth::user()->id)
                ->first();

            // ตรวจสอบว่ามีผู้ใช้หรือไม่
            if (Auth::user()->id == $notif->user_id) {
                return response()->json(['success' => false, 'msg' => 'User not found ']);
            }
            // dd($notif);
            if ($notif->seen == 0) {
                $notif->seen = 1;
                $notif->save();
            }



            return response()->json(['success' => true, 'msg' => 'User status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function leave_type()
    {


        try {

            $leaveTypes  = Leave_type::all();

            return view('leave.leave_type', compact('leaveTypes'));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }
    public function log_login(Request $request)
    {


        try {

            $log_login  =  DB::table('log_login')
            ->orderBy('login_time', 'desc')
            ->get();

            return view('leave.log_login', compact('log_login'));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    function countLeaveStatus($leaveStatus)
    {
        try {

            $leaveStatusCount = DB::table('leaves')
                ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
                ->where('user_id', Auth::user()->id)
                ->where('leave_status', $leaveStatus)
                ->whereYear('leaves.start_date', Carbon::now()->year)
                ->select(DB::raw('SUM(leaves.date) AS count'))
                ->first()->count;

            $leaveLimit = Leave_type::where('id', $leaveStatus)->first()->leave_limit;



            $daysLeft = $leaveLimit - $leaveStatusCount;
            if ($leaveStatus === 2) { // Check if leave_status is APPROVED (2)
                $daysLeft = $leaveLimit - $leaveStatusCount;
            } else {
                // dd( $leaveStatusCount);
                $daysLeft =  $leaveLimit;
            }
            //    = $leaveLimit - $leaveStatusCount;

            return [
                'count' => $leaveStatusCount,
                'leave_limit' => $leaveLimit,
                'days_left' => $daysLeft
            ];
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
    }


    function leave_types_name($leaveStatus)
    {
        try {
            $leave = DB::table('leave_types')
                ->where('id', $leaveStatus)
                ->first();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }
        return isset($leave->leave_type_name) ? $leave->leave_type_name : '';
    }
    function leave_name($id)
    {
        try {
            $leave = DB::table('leaves')
                ->join('leave_types', 'leaves.leave_type_id', '=', 'leave_types.id')
                ->where('leaves.id', $id) // Use $id instead of $$id
                ->first();
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()]);
        }

        return isset($leave->leave_type_name) ? $leave->leave_type_name : '';
    }
    // function check_date($start_date, $id)
    // {

    //     $advanceDays = Leave_type::where('id', $id)->first()->advance_days;


    //     $startDate = Carbon::parse($start_date);

    //     $return = $startDate->diff(Carbon::now()->addDays((int)$advanceDays));

    //     dd($return);
    // }

    function check_date($start_date, $id)
    {
        try {
            // Retrieve advance_days based on leave_type_id
            $advanceDays = Leave_type::where('id', $id)->first()->advance_days;

            if ($advanceDays == 0) {
                return ['success' => true];
            }
            if (is_null($advanceDays)) {

                return ['success' => false, 'message' => 'ไม่มีข้อมูล'];
            }


            $validStartDate = Carbon::parse($start_date);


            if ($validStartDate->isBefore(Carbon::now())) {
                return ['success' => false, 'message' => 'วันลาเริ่มต้องต้องอย่างน้อย ' . $advanceDays . ' วัน'];
            }


            $daysRemaining = $validStartDate->diffInDays(Carbon::now()->addDays($advanceDays));


            if ($daysRemaining > 0) {
                return ['success' => true, 'days_remaining' => $daysRemaining];
            } else {
                return ['success' => false, 'message' => 'ไม่มีวันเหลือสำหรับการขอลา'];
            }
        } catch (\Exception $e) {

            return ['success' => false, 'message' => 'Error checking date: ' . $e->getMessage()];
        }
    }
}

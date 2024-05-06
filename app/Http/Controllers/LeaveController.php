<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Leave_type;
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
        $pendingLeaves = $this->countLeaveStatus(1);
        $approvedLeaves = $this->countLeaveStatus(2);
        $rejectedLeaves = $this->countLeaveStatus(3);
        $remainingLeave = $pendingLeaves['days_left'] + $approvedLeaves['days_left'] + $rejectedLeaves['days_left'];
        $pending = $pendingLeaves['count'];
        $approved = $approvedLeaves['count'];
        $rejected = $approvedLeaves['count'];

        $modal = array();

        foreach ($leaveTypes as $key => $value) {

            $modal[$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .   $this->countLeaveStatus($leaveTypes[$key]['id'])['days_left'] . ' วัน';
        }




        $leaves = Leave::join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
            ->select('leaves.*', 'leave_types.leave_type_name')
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

            // อัปเดตจำนวนวันลาในแต่ละใบลา
            $leaves[$key]->days = $days;
        }


        $startDate = now()->format('Y-m-d\TH:i');
        $endDate = now()->addMinutes(1)->format('Y-m-d\TH:i');
        $pendingLeaves = $this->countLeaveStatus(1);
        $approvedLeaves = $this->countLeaveStatus(2);
        $rejectedLeaves = $this->countLeaveStatus(3);
        $remainingLeave = $pendingLeaves['days_left'] + $approvedLeaves['days_left'] + $rejectedLeaves['days_left'];
        $pending = $pendingLeaves['count'];
        $approved = $approvedLeaves['count'];
        $rejected = $approvedLeaves['count'];



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
        $pending = $pendingLeaves['count'];
        $approved = $approvedLeaves['count'];
        $rejected = $approvedLeaves['count'];

        $modal = array();

        foreach ($leaveTypes as $key => $value) {

            $modal[$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .   $this->countLeaveStatus($leaveTypes[$key]['id'])['days_left'] . ' วัน';
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
                $addLeave->phone_number = $request->phone_number;
                $addLeave->leave_reason = $request->leave_reason;
                $addLeave->leave_status = 1;

                $addLeave->created_at     = date("Y-m-d H:i:s");
                $addLeave->updated_at     = date("Y-m-d H:i:s");


                $addLeave->save();
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

                $editLeave = Leave::where('id', $request->id)->update([
                    'leave_type_id' => $request->leave_type_id,
                    'leave_period' => $request->leave_period,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'phone_number' => $request->phone_number,
                    'leave_reason' => $request->leave_reason,
                    'leave_status' => 1,
                    'updated_at' => date("Y-m-d H:i:s"),

                ]);


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
                ->get();



            $leaveTypes  = Leave_type::all();

            $modal = array();

            foreach ($leaveTypes as $key => $value) {
                // dd($leaveTypes[$key]);

                $modal[$leaveTypes[$key]['id']] = ' จำนวนวันที่ ' . $this->leave_types_name($leaveTypes[$key]['id']) . ' เหลือ ' .   $this->countLeaveStatus($leaveTypes[$key]['id'])['days_left'] . ' วัน';
            }
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



        // return view('leave.listdata', compact('leaves', 'modal'));
    }


    function countLeaveStatus($leaveStatus)
    {
        try {
            $leaveStatusCount = DB::table('leaves')
                ->join('leave_types', 'leave_types.id', '=', 'leaves.leave_type_id')
                ->where('leave_status', $leaveStatus)
                ->select(DB::raw('COUNT(DISTINCT leaves.id) AS count'))
                ->first()->count;

            $leaveLimit = Leave_type::where('id', $leaveStatus)->first()->leave_limit;

            if ($leaveStatus === 2) { // Check if leave_status is APPROVED (2)
                $daysLeft = $leaveLimit - $leaveStatusCount;
            } else {
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
}

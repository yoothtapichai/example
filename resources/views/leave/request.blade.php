@extends('layouts.backend')
@section('title')
    ยื่นเรื่องขอลา - ระบบลาออนไลน์
@endsection
@section('css')
    <link href="{{ asset('public/backend/css/upload.css') }}" rel="stylesheet" />
@endsection
@section('content')
    <div class="container">
        <div class="mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-4">
                        @if ($user->avatar)
                            <img src="{{ asset($user->avatar) }}" class="card-img-top" alt="Profile Picture">
                        @else
                            <img src="https://picsum.photos/200" class="card-img-top" alt="Profile Picture">
                        @endif

                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $user->name }}</h5>
                            <p class="card-text">{{ $user->email }}</p>
                            <div class="d-flex justify-content-center">

                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-9">

                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-primary text-white mb-4">
                                <div class="card-body"> {{ $remainingLeave }} วันลาที่เหลือ</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">
                                    <a class="small text-white stretched-link" data-bs-toggle="modal"
                                        data-bs-target="#modalRequest">รายละเอียด</a>
                                    <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-warning text-white mb-4">
                                <div class="card-body">{{ $pending }} วันลาที่รออนุมัติ</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-success text-white mb-4">
                                <div class="card-body">{{ $approved }} วันลาที่ลาแล้ว</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card bg-danger text-white mb-4">
                                <div class="card-body">{{ $rejected }} วันลาที่ไม่อนุมัติ</div>
                                <div class="card-footer d-flex align-items-center justify-content-between">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">ยื่นเรื่องขอลา</h6>
                        </div>
                        <div class="card-body">
                            <form action="" method="post" id="addRequest">

                                <div class=" mb-3">
                                    <label for="floatingSelect">ประเภทการลา</label>
                                    <select class="form-select" id="leaveType" aria-label="Floating label select example"
                                        name="leaveType">
                                        @if (count($modal) > 0)
                                            @foreach ($leaveTypes as $leaveType)
                                                <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type_name }}
                                                </option>
                                            @endforeach
                                        @else
                                            ไม่มีข้อมูล
                                        @endif

                                    </select>
                                    <span id="leave_type_id_error" class="text-danger"></span>
                                </div>

                                <div class=" mb-3">
                                    <label for="floatingSelect">ประเภทการลา</label>
                                    <select class="form-select" id="leave_period" aria-label="Floating label select example"
                                        name="leave_period">
                                        <option value="1">ช่วงเช้า</option>
                                        <option value="2">ช่วงบ่าย</option>
                                        <option value="3">เต็มวัน</option>
                                    </select>
                                    <span id="leave_period_error" class="text-danger"></span>
                                </div>
                                <div class=" mb-3">
                                    <label for="floatingInput" class="form-label">วันที่ลา</label>
                                    <input class="form-control" type="datetime-local" id="start_date" name="meeting-time"
                                        min="{{ $startDate }}" value="{{ $startDate }}" />
                                    <span id="start_date_error" class="text-danger"></span>

                                </div>

                                <div class=" mb-3">
                                    <label for="floatingInput" class="form-label">ถึง</label>
                                    <input class="form-control" type="datetime-local" id="end_date" name="meeting-time"
                                        min="{{ $endDate }}" value="{{ $endDate }}" />
                                    <span id="end_date_error" class="text-danger"></span>

                                </div>
                                <div class=" mb-3">
                                    <label for="floatingInput">เบอร์ติดต่อ</label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        required>
                                    <span id="phone_number_error" class="text-danger"></span>
                                </div>
                                <div class=" mb-3">
                                    <label for="floatingInput" class="form-label">สาเหตุการลา</label>
                                    <textarea class="form-control" id="leave_reason" rows="5" name="leave_reason" required></textarea>
                                    <span id="leave_reason_error" class="text-danger"></span>

                                </div>
                                <div class=" mb-3 text-end">
                                    <button type="submit" class="btn btn-success addRequest">บันทึก</button>

                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal modalRequest -->
    <div class="modal fade " id="modalRequest" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="adduserlabell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="adduserlabell">รายละเอียดวันลาที่เหลือ</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @if (count($modal) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach ($modal as $m)
                                <li class="list-group-item"> {{ $m }}</li>
                            @endforeach
                        </ul>
                    @else
                    @endif

                </div>
                <div class="modal-footer">


                </div>


            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#addRequest').submit(function(e) {
                e.preventDefault();
                // let formData = $(this).serialize();//get
                let formData = new FormData(); // สร้าง FormData object

                // check ว่ามีค่าไหม

                // เพิ่มข้อมูลที่ต้องการส่งผ่าน AJAX เข้าไปใน formData
                formData.append('leave_type_id', $('#leaveType').val());
                formData.append('leave_period', $('#leave_period').val());
                formData.append('start_date', $('#start_date').val());
                formData.append('end_date', $('#end_date').val());
                formData.append('phone_number', $('#phone_number').val());
                formData.append('leave_reason', $('#leave_reason').val());

                let csrfToken = $('meta[name="csrf-token"]').attr('content'); // เก็บค่า CSRF token
                formData.append('_token', csrfToken);
                $.ajax({
                    url: '{{ route('leaveAddData') }}',
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.addRequest').prop('disable', true);
                    },
                    complete: function() {
                        $('.addRequest').prop('disable', false);
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.success == true) {


                            Swal.fire({
                                title: "เพิ่มข้อมูลสำเร็จ",
                                text: "กดปุ่มเพื่อปิดการแสดงผลป๊อปอัพนี้",
                                icon: "success",
                                confirmButtonText: "ปิด",
                                allowOutsideClick: false // ปิดป๊อปอัพเมื่อกดปุ่ม "ปิด"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload(true);
                                }
                            });


                        } else if (data.success == false) {
                            printErrorMsg(data.msg);
                        } else {
                            printValidationErrorMsg(data.msg);
                        }

                    }

                });

                return false;


            });





            function printErrorMsg(str) {
                Swal.fire({
                    icon: "error",
                    title: "แจ้งเตือน",
                    text: str,
                });
            }

            function printValidationErrorMsg(msg) {
                $.each(msg, function(field_name, error) {
                    $(document).find('#' + field_name + '_error').text(error);

                });
            }

            function printValidationErrorMsg_edit(msg) {
                $.each(msg, function(field_name, error) {
                    $(document).find('#' + field_name + '_editerror').text(error);

                });
            }

        });
    </script>
@endsection

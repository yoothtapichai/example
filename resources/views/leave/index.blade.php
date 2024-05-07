@extends('layouts.backend')
@section('title')
    การลาของฉัน - ระบบลาออนไลน์
@endsection

@section('content')
    <div class="container">
        <div class="mt-4">
            <div class="row">
                <div class="col-md-3">
                    <div class="card mb-4">
                        @if ($user->avatar)
                            <img src="{{ asset($user->avatar) }}" class="card-img-top" alt="Profile Picture"
                                onerror="this.src='{{ asset('public/backend/default.jpg') }}'">
                        @else
                            <img src="https://picsum.photos/200" class="card-img-top" alt="Profile Picture" width=""
                                onerror="this.src='{{ asset('public/backend/default.jpg') }}'">
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
                                        data-bs-target="#modalIndex">รายละเอียด</a>
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
                                <div class="card-body">{{ $approved }} วันลาที่อนุมัติแล้ว</div>
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
                            <h6 class="card-title">ข้อมูลส่วนตัว</h6>
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ประเภทการลา</th>
                                        <th>วันที่ลา</th>
                                        <th>จำนวนวัน</th>
                                        <th>สถานะ</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($leaves as $leave)
                                        <tr>
                                            <td>{{ $leave->leave_type_name }}</td>
                                            <td>{{ $leave->start_date }} - {{ $leave->end_date }}</td>
                                            <td>{{ $leave->days }}</td>
                                            <td>

                                                @if ($leave->leave_status == 1)
                                                    <button type="button" class="btn btn-warning modalEdit"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                        data-id="{{ $leave->id }}"
                                                        data-leave_type_id="{{ $leave->leave_type_id }}"
                                                        data-start_date="{{ $leave->start_date }}"
                                                        data-end_date="{{ $leave->end_date }}"
                                                        data-leave_period="{{ $leave->leave_period }}"
                                                        data-phone_number="{{ $leave->phone_number }}"
                                                        data-leave_reason="{{ $leave->leave_reason }}">รออนุมัติ</button>
                                                    <button type="button" class="btn btn-outline-danger deleteRequest"
                                                        data-id="{{ $leave->id }}">ลบ</button>
                                                @elseif($leave->leave_status == 2)
                                                    <button type="button" class="btn btn-success" disabled>อนุมัติ</button>
                                                    @if ($leave->ifdate)
                                                        <button type="button" class="btn btn-outline-danger deleteRequest"
                                                            data-id="{{ $leave->id }}">ลบ</button>
                                                    @endif
                                                @elseif($leave->leave_status == 3)
                                                    <button type="button" class="btn btn-danger modalEdit"
                                                        data-bs-toggle="modal" data-bs-target="#modalEdit"
                                                        data-id="{{ $leave->id }}"
                                                        data-leave_type_id="{{ $leave->leave_type_id }}"
                                                        data-start_date="{{ $leave->start_date }}"
                                                        data-end_date="{{ $leave->end_date }}"
                                                        data-leave_period="{{ $leave->leave_period }}"
                                                        data-phone_number="{{ $leave->phone_number }}"
                                                        data-leave_reason="{{ $leave->leave_reason }}"
                                                        data-leave_status="{{ $leave->leave_status }}"
                                                        data-admin_comment="{{ $leave->admin_comment }}">ไม่อนุมัติ</button>
                                                    <button type="button" class="btn btn-outline-danger deleteRequest"
                                                        data-id="{{ $leave->id }}">ลบ</button>
                                                @endif

                                            </td>

                                        </tr>
                                    @endforeach



                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <!-- Modal index -->
    <div class="modal fade " id="modalIndex" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
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
                        ไม่มีข้อมูล
                    @endif

                </div>
                <div class="modal-footer">


                </div>


            </div>
        </div>
    </div>


    <!-- Modal แก้ไขชื่อผู้ใช้ -->
    <div class="modal fade " id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalEdit_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEdit_labell">แก้ไขชื่อผู้ใช้</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="" id="editRequest">

                        <div class=" mb-3" hidden id="div_admin_comment">
                            <label for="floatingInput" class="form-label">ตอบกลับ</label>
                            <textarea class="form-control" id="admin_comment" rows="5" disabled></textarea>


                        </div>
                        <hr>
                        <div class=" mb-3">
                            <label for="floatingSelect">ประเภทการลา</label>
                            <select class="form-select" id="leaveType" aria-label="Floating label select example"
                                name="leaveType">
                                @if (count($modal) > 0)
                                    @foreach ($leaveTypes as $leaveType)
                                        <option value="{{ $leaveType->id }}">{{ $leaveType->leave_type_name }}</option>
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
                                min="{{ $startDate }}" value="" />
                            <span id="start_date_error" class="text-danger"></span>

                        </div>

                        <div class=" mb-3">
                            <label for="floatingInput" class="form-label">ถึง</label>
                            <input class="form-control" type="datetime-local" id="end_date" name="meeting-time"
                                min="{{ $endDate }}" value="" />
                            <span id="end_date_error" class="text-danger"></span>

                        </div>
                        <div class=" mb-3">
                            <label for="floatingInput">เบอร์ติดต่อ</label>
                            <input type="text" class="form-control" id="phone_number" name="phone_number" required>
                            <span id="phone_number_error" class="text-danger"></span>
                        </div>
                        <div class=" mb-3">
                            <label for="floatingInput" class="form-label">สาเหตุการลา</label>
                            <textarea class="form-control" id="leave_reason" rows="5" name="leave_reason" required></textarea>
                            <span id="leave_reason_error" class="text-danger"></span>

                        </div>
                        <div class=" mb-3 text-end">
                            <button type="submit" class="btn btn-primary editRequest">แก้ไข</button>



                        </div>

                    </form>
                </div>



            </div>
        </div>
    </div>

    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

    <script>
        $(document).ready(function() {

            $('.modalEdit').on('click', function() {

                $('#editRequest').trigger('reset');
                var leave_status = $(this).attr('data-leave_status');
                var admin_comment = $(this).attr('data-admin_comment');

                if (leave_status == 3 || leave_status == '3') {
                    $('#div_admin_comment').removeAttr("hidden");
                    $('#admin_comment').val(admin_comment);
                }


                var leave_id = $(this).attr('data-id');
                var leave_type_id = $(this).attr('data-leave_type_id');
                var start_date = $(this).attr('data-start_date');
                var end_date = $(this).attr('data-end_date');
                var leave_period = $(this).attr('data-leave_period');
                var phone_number = $(this).attr('data-phone_number');
                var leave_reason = $(this).attr('data-leave_reason');

                $('#leaveType').val(leave_type_id);

                $('#start_date').val(start_date);
                // $('#start_date').prop('min', start_date);

                $('#end_date').val(end_date);
                // $('#end_date').prop('min', end_date);

                $('#leave_period').val(leave_period);
                $('#phone_number').val(phone_number);
                $('#leave_reason').val(leave_reason);


                $('#editRequest').submit(function(e) {
                    e.preventDefault();
                    // let formData = $(this).serialize();//get
                    let formData = new FormData(); // สร้าง FormData object

                    // check ว่ามีค่าไหม

                    // เพิ่มข้อมูลที่ต้องการส่งผ่าน AJAX เข้าไปใน formData
                    formData.append('id', leave_id);
                    formData.append('leave_type_id', $('#leaveType').val());
                    formData.append('leave_period', $('#leave_period').val());
                    formData.append('start_date', $('#start_date').val());
                    formData.append('end_date', $('#end_date').val());
                    formData.append('phone_number', $('#phone_number').val());
                    formData.append('leave_reason', $('#leave_reason').val());

                    let csrfToken = $('meta[name="csrf-token"]').attr(
                        'content'); // เก็บค่า CSRF token
                    formData.append('_token', csrfToken);
                    $.ajax({
                        url: '{{ route('leaveEditData') }}',
                        method: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.editRequest').prop('disabled', true);
                            $('.deleteRequest').prop('disabled', true);
                        },
                        complete: function() {
                            $('.editRequest').prop('disabled', false);
                            $('.deleteRequest').prop('disabled', false);
                        },
                        success: function(data) {
                            // console.log(data);
                            if (data.success == true) {


                                Swal.fire({
                                    title: "แก้ไขข้อมูลสำเร็จ",
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


            })
            $(".deleteRequest").on('click', function() {
                Swal.fire({
                    title: "คุณต้องการลบข้อมูลเหรือไม่ ? ",
                    text: "กดปุ่มเพื่อตกลงเพื่อยืนยันการลบ",
                    icon: "question",
                    confirmButtonText: "ตกลง",
                    allowOutsideClick: false // ปิดป๊อปอัพเมื่อกดปุ่ม "ปิด"
                }).then((result) => {
                    if (result.isConfirmed) {
                        var leave_id = $(this).attr('data-id');
                        let formData = new FormData();
                        let csrfToken = $('meta[name="csrf-token"]').attr('content');
                        formData.append('id', leave_id);
                        formData.append('_token', csrfToken);
                        $.ajax({
                            url: '{{ route('userDeleteLeaves') }}',
                            method: "POST",
                            data: formData,
                            headers: {
                                'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                            },
                            contentType: false,
                            processData: false,
                            beforeSend: function() {

                            },
                            complete: function() {

                            },
                            success: function(data) {
                                // console.log(data);
                                if (data.success == true) {

                                    location.reload(true);


                                } else if (data.success == false) {
                                    printErrorMsg(data.msg);
                                } else {
                                    printValidationErrorMsg(data.msg);
                                }

                            }

                        });

                    }
                });
            })




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

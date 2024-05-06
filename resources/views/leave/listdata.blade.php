@extends('layouts.backend')
@section('title')
    อนุมัติการลา - ระบบลาออนไลน์
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">อนุมัติการลา</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">หน้าอนุมัติการลา มีไว้จัดการ อนุมัติ หรือปฏิเสธ </li>
        </ol>

        <div class="mb-2 text-end">
            {{-- <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adduser"><i
                    class="fa-solid fa-plus"></i></button> --}}

        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
               รายการ

            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ชื่อ</th>
                            <th>ประเภทการลา</th>
                            <th>เบอร์ติดต่อ</th>
                            <th>รายละเอียด</th>
                            <th>วันที่ลา</th>
                            <th>จำนวนวันลาที่เหลือ</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @if (count($leaves) > 0)
                            @foreach ($leaves as $leave)
                                <tr>
                                    <th>{{ $leave->name }}</th>
                                    <th>{{ $leave->leave_type_name }}</th>
                                    <th>{{ $leave->phone_number }}</th>
                                    <th>{{ $leave->leave_reason }}</th>
                                    <th>{{ $leave->start_date }} - {{ $leave->end_date }}</th>
                                    <th>
                                        @if (count($modal) > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($modal as $m)
                                                    <li class="list-group-item"> {{ $m }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            ไม่มีข้อมูล
                                        @endif
                                    </th>
                                    <th>
                                        @if ($leave->leave_status == 2)
                                            <button type="button" class="btn btn-outline-success mb-2"
                                                disabled>อนุมัติแล้ว</button><br>
                                        @elseif($leave->leave_status == 3)
                                            <button type="button" class="btn btn-outline-info mb-2"
                                                disabled>ไม่อนุมัติ</button><br>
                                            <button type="button"
                                                class="btn btn-outline-danger mb-2 btn_delete">ลบ</button><br>
                                        @else
                                            <button type="button" class="btn btn-outline-warning mb-2 btn_pending"
                                                data-id="{{ $leave->id }}">รออนุมัติ</button><br>
                                            <button type="button" class="btn btn-outline-info mb-2 btn_reply"
                                                data-bs-toggle="modal" data-bs-target="#modalreply"
                                                data-id="{{ $leave->id }}">ตอบกลับ</button><br>
                                            <button type="button" class="btn btn-outline-danger mb-2 btn_delete"
                                                data-id="{{ $leave->id }}">ลบ</button><br>
                                        @endif

                                    </th>
                                </tr>
                            @endforeach
                        @else
                        @endif





                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <div class="modal fade " id="modalreply" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalreply_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalreply_labell">ไม่อนุมัติ</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" id="replyForm">

                        <div class=" mb-3">
                            <label for="floatingInput" class="form-label">ตอบกลับ</label>
                            <textarea class="form-control" id="admin_comment" rows="5" name="admin_comment" required></textarea>
                            <span id="admin_comment_error" class="text-danger"></span>

                        </div>
                        <div class=" mb-3 text-end">
                            <button type="submit" class="btn btn-primary ">ส่ง</button>



                        </div>

                    </form>
                </div>



            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.btn_pending').on('click', function() {

                var leave_id = $(this).attr('data-id');
                var formData = new FormData(); // สร้าง FormData object
                let csrfToken = $('meta[name="csrf-token"]').attr(
                    'content'); // เก็บค่า CSRF token
                formData.append('_token', csrfToken);
                formData.append('id', leave_id);
                $.ajax({
                    url: '{{ route('adminLeavesApproved') }}',
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn_pending').prop('disabled', true);
                        $('.btn_reply').prop('disabled', true);
                        $('.btn_delete').prop('disabled', true);
                    },
                    complete: function() {
                        $('.btn_pending').prop('disabled', false);
                        $('.btn_reply').prop('disabled', false);
                        $('.btn_delete').prop('disabled', false);
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.success == true) {


                            Swal.fire({
                                title: "อนุมัติวันลาสำเร็จ",
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

            $('.btn_reply').on('click', function() {
                var leave_id = $(this).attr('data-id');
                $('#replyForm').trigger('reset');
                $('#replyForm').submit(function(e) {




                    var formData = new FormData(); // สร้าง FormData object
                    let csrfToken = $('meta[name="csrf-token"]').attr(
                        'content'); // เก็บค่า CSRF token
                    formData.append('_token', csrfToken);
                    formData.append('id', leave_id);
                    formData.append('admin_comment', $('#admin_comment').val());
                    console.log($('#admin_comment').val(), leave_id);
                    $.ajax({
                        url: '{{ route('adminComment') }}',
                        method: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.btn_pending').prop('disabled', true);
                            $('.btn_reply').prop('disabled', true);
                            $('.btn_delete').prop('disabled', true);
                        },
                        complete: function() {
                            $('.btn_pending').prop('disabled', false);
                            $('.btn_reply').prop('disabled', false);
                            $('.btn_delete').prop('disabled', false);
                        },
                        success: function(data) {
                            // console.log(data);
                            if (data.success == true) {


                                Swal.fire({
                                    title: "ตอบกลับสำเร็จ",
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

            $('.btn_delete').on('click', function() {

                var leave_id = $(this).attr('data-id');
                var formData = new FormData(); // สร้าง FormData object
                let csrfToken = $('meta[name="csrf-token"]').attr(
                    'content'); // เก็บค่า CSRF token
                formData.append('_token', csrfToken);
                formData.append('id', leave_id);
                $.ajax({
                    url: '{{ route('adminDeleteLeaves') }}',
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.btn_pending').prop('disabled', true);
                        $('.btn_reply').prop('disabled', true);
                        $('.btn_delete').prop('disabled', true);
                    },
                    complete: function() {
                        $('.btn_pending').prop('disabled', false);
                        $('.btn_reply').prop('disabled', false);
                        $('.btn_delete').prop('disabled', false);
                    },
                    success: function(data) {
                        // console.log(data);
                        if (data.success == true) {


                            Swal.fire({
                                title: "ลบข้อมูลสำเร็จ",
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


        })
    </script>
@endsection

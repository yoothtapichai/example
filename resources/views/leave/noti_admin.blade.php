@extends('layouts.backend')
@section('title')
    แจ้งเตือน - ระบบลาออนไลน์
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">แจ้งเตือน</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">แสดงแจ้งเตือนทั้งหมด </li>
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
                            <th>วันที่ลาแจ้งเตือน</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @if (count($Notifi) > 0)
                            @foreach ($Notifi as $Noti)
                                <tr>
                                    <th>{{ $Noti->user_name }}</th>
                                    <th>{{ $Noti->leave_name }}</th>
                                    <th>{{ $Noti->created_at }}</th>

                                    <th>
                                        @if ($Noti->seen == 1)
                                            <button type="button" class="btn btn-success status-eye" disabled>

                                                <i class="eye-switch fa-solid fa-eye">

                                                </i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-warning status-eye"
                                                data-id="{{ $Noti->id }}">

                                                <i class="eye-switch fa-solid fa-eye">

                                                </i>
                                            </button>
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
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script> --}}

    <script>
        $(document).ready(function() {

            $('.status-eye').on('click', function() {
                var status_eye = $(this);
                var id = $(this).attr('data-id');
                var url = "{{ route('seenAdmin', 'l_id') }}";
                url = url.replace('l_id', id);
                let csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: url,
                    method: 'POST',
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    beforeSend: function() {

                    },
                    complete: function() {

                    },
                    success: function(data) {


                        if (data.success == true) {
                            status_eye.prop('disabled', true)
                            status_eye.removeClass("btn-warning").addClass("btn-success");
                            noti();
                            // var data_status = status_eye.attr('data-status');
                            // console.log(data_status);
                            // if (data_status == 1) {
                            //     status_eye.attr('data-status', '0')
                            //     status_eye.html(
                            //         '<i class="eye-switch fa-solid fa-eye-slash"></i>')
                            // } else {
                            //     status_eye.attr('data-status', '1')
                            //     status_eye.html('<i class="eye-switch fa-solid fa-eye"></i>')
                            // }

                        } else {
                            printErrorMsg(data.msg);
                        }
                    }

                });


                function printErrorMsg(str) {
                    Swal.fire({
                        icon: "error",
                        title: "แจ้งเตือน",
                        text: str,
                    });
                }

                function noti() {
                    let csrfToken = $('meta[name="csrf-token"]').attr('content'); // เก็บค่า CSRF token

                    $.ajax({
                        url: '{{ route('seenNoti') }}',
                        method: "GET",
                        // data: formData,
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
                                // data.res['unread_count'];

                                if (data.data.unread_count > 0) {
                                    $('#noti_span').html(' (' + data.data.unread_count + ') ')
                                    $("#iconnoti").css("color", "yellow");
                                }else{
                                    $('#noti_span').html('');
                                    $("#iconnoti").css("color", "");
                                }



                            } else if (data.success == false) {
                                printErrorMsg(data.msg);
                            } else {
                                printValidationErrorMsg(data.msg);
                            }

                        }

                    });
                }

            });
        });
    </script>
@endsection

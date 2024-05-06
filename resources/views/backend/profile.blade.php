@extends('layouts.backend')
@section('title')
    Profile - SB Admin
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
                                <button type="button" class="btn btn-primary editBtn" data-bs-toggle="modal"
                                    data-bs-target="#edit_img_profile">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>&nbsp;
                                <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                    data-bs-target="#delete_img_profile"><i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-md-9">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">ข้อมูลส่วนตัว</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">ชื่อ : {{ $user->name }}</li>
                                <li class="list-group-item">Email : {{ $user->email }}</li>
                                <li class="list-group-item">วันที่สมัคร : {{ $user->created_at }}</li>
                                <li class="list-group-item">แก้ไขข้อมูลล่าสุด : {{ $user->updated_at }}</li>
                                <li class="list-group-item">สิทธิ์ : {{ $user->type }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title">ข้อมูลอื่นๆ</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-outline-primary edit_name" data-bs-toggle="modal"
                                        data-bs-target="#edit_name" data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}">แก้ไขชื่อผู้ใช้</button>
                                </li>
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-outline-primary edit_pass" data-bs-toggle="modal"
                                        data-bs-target="#edit_pass" data-id="{{ $user->id }}">เปลี่ยนรหัสผ่าน</button>
                                </li>
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-outline-primary admin_detail"
                                        data-bs-toggle="modal" data-bs-target="#admin_detail" data-id="{{ $user->id }}"
                                        data-createdby="{{ $user->created_by }}"
                                        data-createdat="{{ $user->created_at }}">ดูรายละเอียดการสมัคร</button>
                                </li>
                                <li class="list-group-item">
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#delete_user" data-id="{{ $user->id }}">ลบข้อมูลผู้ใช้</button>
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal แก้ไขชื่อผู้ใช้ -->
    <div class="modal fade " id="edit_name" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="edit_name_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit_name_labell">แก้ไขชื่อผู้ใช้</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="editNameForm">

                    <div class="modal-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="edit_name_input" name="name">
                            <label for="floatingInput">ชื่อ</label>
                            <span id="name_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary edit_name_btn">แก้ไขข้อมูล</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Modal เปลี่ยนรหัสผ่าน -->
    <div class="modal fade " id="edit_pass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="edit_pass_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edit_pass_labell">เปลี่ยนรหัสผ่าน</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="editPassForm">
                    <div class="modal-body">

                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password">
                            <label for="floatingInput">รหัสผ่าน</label>
                            <span id="password_error" class="text-danger"></span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            <label for="floatingInput">ยืนยันรหัสผ่าน</label>
                            <span id="password_confirmation_error" class="text-danger"></span>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary edit_pass_Btn">แก้ไขข้อมูล</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>


            </div>
        </div>
    </div>
    <!-- Modal ดูรายละเอียดการสมัคร -->
    <div class="modal fade " id="admin_detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="admin_detail_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="admin_detail_labell">ดูรายละเอียดการสมัคร</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="card-body">
                        <ul class="list-group list-group-flush admin_detail_body">

                        </ul>
                    </div>


                </div>
                <div class="modal-footer">

                </div>


            </div>
        </div>
    </div>
    <!-- Modal ลบข้อมูลผู้ใช้ -->
    <div class="modal fade " id="delete_user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="delete_user_labell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="delete_user_labell">ลบข้อมูลผู้ใช้</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="delete_user_profile">
                    <div class="modal-body">

                        คุณต้องการลบข้อมูลชื่อผู้ใช้งานใช่หรือไม่

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger  delete_user_Btn">ยืนยันลบผู้ใช้งาน</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal เพิ่มไฟล์ -->
    <div class="modal fade " id="edit_img_profile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="upfilelabell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="upfilelabell">แก้ไขภาพโปรไฟล์</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadForm" enctype="multipart/form-data">
                    <div class="modal-body">


                        <div class="mb-3">
                            <label for="uploadFile" class="form-label">Select File</label>
                            <input class="form-control" type="file" id="uploadFile" name="uploadFile">
                            <span id="uploadFile_error" class="text-danger"></span>
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary upfileBtn ">อัพโหลดภาพ</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- Modal ลบ -->
    <div class="modal fade " id="delete_img_profile" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deletemodallabell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deletemodallabell">ลบรูปโปรไฟล์</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    คุณต้องการลบรูปภาพโปรไฟล์หรือใม่


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger delete_img_Btn ">ยืนยันลบรูปภาพ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                </div>


            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <script>
        $(document).ready(function() {




            $('.edit_name').on('click', function() {

                $('#editNameForm').trigger('reset');
                var user_id = $(this).attr('data-id');
                var user_name = $(this).attr('data-name');

                $("#edit_name_input").val(user_name)

                $('#editNameForm').submit(function(e) {

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    e.preventDefault();
                    var formData = new FormData(); // สร้าง FormData object
                    formData.append('_token', csrfToken);
                    formData.append('id', user_id);
                    formData.append('name', $('#edit_name_input').val());

                    $.ajax({
                        url: '{{ route('editNameProfile') }}',
                        method: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.edit_name_btn').prop('disable', true);
                        },
                        complete: function() {
                            $('.edit_name_btn').prop('disable', false);
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.success == true) {
                                Swal.fire({
                                    title: "แก้ไขข้อมูลผู้ใช้งานสำเร็จ",
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
                });







            });
            $('.edit_pass').on('click', function() {


                $('#editPassForm').trigger('reset');
                var user_id = $(this).attr('data-id');




                $('#editPassForm').submit(function(e) {
                    e.preventDefault();
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    var formData = new FormData(); // สร้าง FormData object
                    formData.append('_token', csrfToken);
                    formData.append('id', user_id);
                    formData.append('password', $('#password').val());
                    formData.append('password_confirmation', $('#password_confirmation').val());

                    $.ajax({
                        url: '{{ route('editPassProfile') }}',
                        method: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.edit_pass_Btn').prop('disable', true);
                        },
                        complete: function() {
                            $('.edit_pass_Btn').prop('disable', false);
                        },
                        success: function(data) {
                            console.log(data);
                            if (data.success == true) {
                                Swal.fire({
                                    title: "เปลี่ยนรหัสผ่านผู้ใช้งานสำเร็จ",
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
                });







            });
            $('.admin_detail').on('click', function() {



                var user_id = $(this).attr('data-id');
                var createdby = $(this).attr('data-createdby');
                var createdat = $(this).attr('data-createdat');


                if (createdby == 0) {

                    $('.admin_detail_body').html(
                        '<li class="list-group-item">คุณสมัครด้วย Email เพื่อใช้งาน เมื่อวันที่ ' +
                        createdat + '</li>')


                } else {
                    var csrfToken = $('meta[name="csrf-token"]').attr('content');
                    var formData = new FormData(); // สร้าง FormData object
                    formData.append('_token', csrfToken);
                    formData.append('id', user_id);

                    $.ajax({
                        url: '{{ route('editDetailProfile') }}',
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
                            console.log(data);
                            if (data.success == true) {
                                console.log(data.data.name);
                                $('.admin_detail_body').html(
                                    '<li class="list-group-item">เพิ่ม user นี้ด้วย : ' +
                                    data
                                    .data.name +
                                    '</li></li><li class="list-group-item">  จาก Email : ' +
                                    data.data.email +
                                    '</li><li class="list-group-item"> เมื่อวันที่ ' +
                                    createdat + '</li>')


                            } else {
                                printValidationErrorMsg(data.msg);
                            }

                        }

                    });
                }




            });




            $('#delete_user_profile').submit(function(e) {

                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                e.preventDefault();
                var formData = new FormData(); // สร้าง FormData object
                formData.append('_token', csrfToken);


                $.ajax({
                    url: '{{ route('deleteUserProfile') }}',
                    method: "DELETE",
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
                            Swal.fire({
                                title: "ลบผู้ใช้งานสำเร็จ",
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
            });


            $('#uploadForm').submit(function(event) {
                event.preventDefault();

                var formData = new FormData($(this)[0]);
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    url: '{{ route('editImgProfile') }}', // Replace with your server-side script URL
                    method: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.editBtn').prop('disable', true);
                    },
                    complete: function() {
                        $('.editBtn').prop('disable', false);
                    },
                    success: function(data) {
                        console.log('File uploaded successfully:', data);
                        console.log(data);
                        if (data.success == true) {
                            Swal.fire({
                                title: "อัพโหลดภาพผู้ใช้งานสำเร็จ",
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
                    },
                    error: function(xhr, status, error) {
                        console.error('Upload failed:', status, error);

                    }
                });
            });

            $(".delete_img_Btn").on('click',function(){
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                $.ajax({
                    url: '{{ route('deleteImgProfile') }}',
                    method: "DELETE",
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
                            Swal.fire({
                                title: "ลบภาพผู้ใช้งานสำเร็จ",
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
            });










            function printErrorMsg(str) {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
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

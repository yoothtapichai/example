@extends('layouts.backend')
@section('title')
    User management - SB Admin
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">User management</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">User management</li>
        </ol>

        <div class="mb-2 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#adduser"><i
                    class="fa-solid fa-plus"></i></button>

        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                DataTable Example

            </div>
            <div class="card-body">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>ชื่อ</th>
                            <th>อีเมล</th>
                            <th>สิทธิ์</th>
                            <th>วันที่สร้าง</th>
                            <th>วันที่แก้ไข</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ชื่อ</th>
                            <th>อีเมล</th>
                            <th>สิทธิ์</th>
                            <th>วันที่สร้าง</th>
                            <th>วันที่แก้ไข</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>

                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->type }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->updated_at }}</td>
                                    <td>

                                        @if ($user->type != 'admin')
                                            <button type="button" class="btn btn-warning status-eye"
                                                data-id="{{ $user->id }}" data-status="{{ $user->status }}">
                                                @if ($user->status == 1)
                                                    <i class="eye-switch fa-solid fa-eye"></i>
                                                @else
                                                    <i class="eye-switch fa-solid fa-eye-slash"></i>
                                                @endif



                                            </button>
                                        @endif

                                        <button type="button" class="btn btn-primary editBtn" data-bs-toggle="modal"
                                            data-bs-target="#edituser" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                                            data-type="{{ $user->type }}"><i
                                                class="fa-solid fa-pen-to-square"></i></button>
                                        <button type="button" class="btn btn-danger deleteBtn" data-bs-toggle="modal"
                                            data-bs-target="#deleteuser" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"><i class="fa-solid fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">No Data Found</td>
                            </tr>
                        @endif





                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal add -->
    <div class="modal fade " id="adduser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="adduserlabell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="adduserlabell">เพิ่มผู้ใช้</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="addUserForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="name" name="name">
                            <label for="floatingInput">ชื่อ</label>
                            <span id="name_error" class="text-danger"></span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email">
                            <label for="floatingInput">อีเมล</label>
                            <span id="email_error" class="text-danger"></span>
                        </div>
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

                        <div class="form-floating">
                            <select class="form-select" id="floatingSelect" aria-label="Floating label select example"
                                name="type">
                                <option value="1">admin</option>
                                <option value="0">user</option>
                            </select>
                            <label for="floatingSelect">กำหนดสิทธิ์</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary addUserBtn ">เพิ่ม</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Modal edit -->
    <div class="modal fade " id="edituser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="edituserlabell" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="edituserlabell">แก้ไขข้อมูล</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" id="editUserForm">
                    <div class="modal-body">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="edit_name" name="name">
                            <label for="floatingInput">ชื่อ</label>
                            <span id="name_editerror" class="text-danger"></span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="edit_email" name="email"
                                disabled="disabled">
                            <label for="floatingInput">อีเมล</label>
                            <span id="email_editerror" class="text-danger"></span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="edit_password" name="password">
                            <label for="floatingInput">รหัสผ่าน</label>
                            <span id="password_editerror" class="text-danger"></span>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="edit_password_confirmation"
                                name="password_confirmation">
                            <label for="floatingInput">ยืนยันรหัสผ่าน</label>
                            <span id="password_confirmation_editerror" class="text-danger"></span>
                        </div>

                        <div class="form-floating">
                            <select class="form-select" id="editSelect" aria-label="Floating label select example"
                                name="type">

                            </select>
                            <label for="floatingSelect">กำหนดสิทธิ์</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary editUserBtn ">แก้ไข</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal delete -->
    <div class="modal fade " id="deleteuser" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="deleteuserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteuserLabel">ลบข้อมูลผู้ใช้</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="text-delete"></div>

                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-danger" id="delete_user">ลบ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    {{-- <input type="hidden" id="table-user-manage" value="{{ route('table-user-manage') }}"> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#addUserForm').submit(function(e) {
                e.preventDefault();
                // let formData = $(this).serialize();//get
                let formData = new FormData(); // สร้าง FormData object

                // เพิ่มข้อมูลที่ต้องการส่งผ่าน AJAX เข้าไปใน formData
                formData.append('name', $('#name').val());
                formData.append('email', $('#email').val());
                formData.append('password', $('#password').val());
                formData.append('password_confirmation', $('#password_confirmation').val());
                formData.append('type', $('#floatingSelect[name="type"]').val());

                let csrfToken = $('meta[name="csrf-token"]').attr('content'); // เก็บค่า CSRF token
                formData.append('_token', csrfToken);
                $.ajax({
                    url: '{{ route('addUser') }}',
                    method: "POST",
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                    },
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $('.addUserBtn').prop('disable', true);
                    },
                    complete: function() {
                        $('.addUserBtn').prop('disable', false);
                    },
                    success: function(data) {
                        console.log(data);
                        if (data.success == true) {
                            $('#addModal').modal('hide');

                            Swal.fire({
                                title: "เพิ่มผู้ใช้งานสำเร็จ",
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



            $('.editBtn').on('click', function() {

                $('#editUserForm').trigger('reset');

                let user_id = $(this).attr('data-id');
                let user_name = $(this).attr('data-name');
                let user_email = $(this).attr('data-email');
                let user_type = $(this).attr('data-type');

                $('#edit_name').val(user_name);
                $('#edit_email').val(user_email);
                if (user_type == 'admin') {
                    $('#editSelect').html(
                        '<option value="1">admin</option><option value="0">user</option>');
                } else {
                    $('#editSelect').html(
                        '<option value="0">user</option><option value="1">admin</option>');
                }

                $('#editUserForm').submit(function(e) {
                    e.preventDefault();
                    // let formData = $(this).serialize();//get
                    let formData = new FormData(); // สร้าง FormData object

                    // เพิ่มข้อมูลที่ต้องการส่งผ่าน AJAX เข้าไปใน formData
                    formData.append('id', user_id);
                    formData.append('name', $('#edit_name').val());
                    // formData.append('email', $('#edit_email').val());
                    formData.append('password', $('#edit_password').val());
                    formData.append('password_confirmation', $('#edit_password_confirmation')
                        .val());
                    formData.append('type', $('#editSelect[name="type"]').val());

                    let csrfToken = $('meta[name="csrf-token"]').attr(
                        'content'); // เก็บค่า CSRF token
                    formData.append('_token', csrfToken);
                    $.ajax({
                        url: '{{ route('editUser') }}',
                        method: "POST",
                        data: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        contentType: false,
                        processData: false,
                        beforeSend: function() {
                            $('.editUserBtn').prop('disable', true);
                        },
                        complete: function() {
                            $('.editUserBtn').prop('disable', false);
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
                                printValidationErrorMsg_edit(data.msg);
                            }

                        }

                    });

                    return false;


                });





            });





            $('.deleteBtn').on('click', function() {

                var user_id = $(this).attr('data-id');
                var user_name = $(this).attr('data-name');
                console.log(user_name);
                $('#text-delete').html('');
                $('#text-delete').html("<p>คุณต้องการลบข้อมูลชื่อของชื่อผู้ใช้งาน " + user_name +
                    " หรือไม่</p>");

                $('#delete_user').on('click', function() {
                    var url = "{{ route('deleteUser', 'user_id') }}";
                    url = url.replace('user_id', user_id);
                    let csrfToken = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: url,
                        method: 'DELETE',
                        contentType: false,
                        processData: false,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken // เพิ่ม CSRF token เข้าไปใน header
                        },
                        beforeSend: function() {
                            $('delete_user').prop('disable', true);
                        },
                        complete: function() {
                            $('delete_user').prop('disable', false);
                        },
                        success: function(data) {
                            if (data.success == true) {
                                Swal.fire({
                                    title: "ลบข้อมูลผู้ใช้งานสำเร็จ",
                                    text: "กดปุ่มเพื่อปิดการแสดงผลป๊อปอัพนี้",
                                    icon: "success",
                                    confirmButtonText: "ปิด",
                                    allowOutsideClick: false // ปิดป๊อปอัพเมื่อกดปุ่ม "ปิด"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload(true);
                                    }
                                });


                            } else {
                                printErrorMsg(data.msg);
                            }
                        }

                    });


                });

            });


            $('.status-eye').on('click', function() {
                var status_eye = $(this);
                var user_id = $(this).attr('data-id');
                var url = "{{ route('statusUser', 'user_id') }}";
                url = url.replace('user_id', user_id);
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
                            var data_status = status_eye.attr('data-status');
                            console.log(data_status);
                            if (data_status == 1) {
                                status_eye.attr('data-status', '0')
                                status_eye.html(
                                    '<i class="eye-switch fa-solid fa-eye-slash"></i>')
                            } else {
                                status_eye.attr('data-status', '1')
                                status_eye.html('<i class="eye-switch fa-solid fa-eye"></i>')
                            }

                        } else {
                            printErrorMsg(data.msg);
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

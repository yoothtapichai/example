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
                                        <button type="button" class="btn btn-warning status-eye"
                                            data-id="{{ $Noti->id }}"> <i class="eye-switch fa-solid fa-eye"></i>
                                        </button>
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


@endsection

@extends('layouts.backend')
@section('title')
    ประเภทการลา- ระบบลาออนไลน์
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">ประเภทการลา</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">ประเภทการลา</li>
        </ol>


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
                            <th>วันที่เพิ่ม</th>
                            <th>วันลาสูงสุด</th>
                        </tr>
                    </thead>

                    <tbody>

                        @if (count($leaveTypes)>0)
                        @foreach ($leaveTypes as $type)
                        <tr>
                            <th>{{$type->leave_type_name}}</th>
                            <th>{{$type->created_at}}</th>
                            <th>{{$type->leave_limit}}</th>
                           
                        </tr>  
                        @endforeach
                 

                        @else
                            
                        @endif
                       





                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

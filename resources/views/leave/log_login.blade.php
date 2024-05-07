@extends('layouts.backend')
@section('title')
    Log login - ระบบลาออนไลน์
@endsection
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Log login</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Log login </li>
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
                            <th>email </th>
                            <th>ip_address</th>
                            <th>user_location</th>
                            <th>msg</th>
                            <th>login_time</th>
                            <th>login_status</th>
                        </tr>
                    </thead>

                    <tbody>

                        @if (count($log_login)>0)
                        @foreach ($log_login as $log)
                        <tr>
                            <th>{{$log->email}} </th>
                            <th>{{$log->ip_address}}</th>
                            <th>{{$log->user_location}}</th>
                            <th>{{$log->msg}}</th>
                            <th>{{$log->login_time}}</th>
                            <th>@if ($log->login_status =='success')
                                <button type="button" class="btn btn-success" disabled> {{$log->login_status}}</button>
                               
                            @else
                            <button type="button" class="btn btn-danger" disabled> {{$log->login_status}}</button>
                            
                            @endif</th>
                           
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

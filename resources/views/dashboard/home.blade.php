@extends('layouts.container')
@section('title', 'لوحة التحكم الرئيسية')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card card-custom">
                <div class="card-header">
                    <h3 class="card-title">
                        مرحباً بك في لوحة التحكم
                    </h3>
                </div>
                <div class="card-body">
                    <p>هذه هي الصفحة الرئيسية. يمكنك البدء في إضافة المحتوى الخاص بك هنا.</p>
                    <p>المستخدم الحالي: <strong>{{ Auth::user()->name }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

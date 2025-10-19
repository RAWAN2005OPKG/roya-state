@extends('dashboard.layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">{{ $project->project_title }}</h2>

     <form action="{{ route('dashboard.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">

    <hr>

 <h3>المستثمرون للمشروع</h3>
<table>
    <thead>
        <tr>
            <th>اسم المستثمر</th>
            <th>المبلغ المستثمر</th>
        </tr>
    </thead>
    <tbody>
        @foreach($project->investments as $investment)
            <tr>
                <td>{{ $investment->investor_name }}</td>
                <td>{{ number_format($investment->amount, 2) }} {{ $project->currency }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td>الإجمالي</td>
            <td>{{ number_format($project->totalInvested(), 2) }} {{ $project->currency }}</td>
        </tr>
    </tfoot>
</table>

    @else
        <p class="text-muted mt-3">لا يوجد استثمارات في هذا المشروع بعد.</p>
    @endif
</div>
@endsection

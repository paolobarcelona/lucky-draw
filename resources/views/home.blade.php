@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Winners</div>

                <div class="card-body">
                    <table id="user-table" class="table table-dark">
                        <thead>
                            <tr>
                                <th class="text-center">Name</th>
                                <th class="text-center">Prize</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($winners as $winners)
                                <tr>
                                    <td class="text-center">{{ $winners->user->name ?? '' }}</td>
                                    <td class="text-center">{{ $user->prize ?? '' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Users</div>

                <div class="card-body">
                    <table id="user-table" class="table table-dark">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th># of Winning Numbers</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $user)
                                <tr>
                                    <td><a href="/users/{{$user->id}}/winning-numbers">{{ $user->name ?? '' }}</a></td>
                                    <td>{{ $user->email ?? '' }}</td>
                                    <td class="text-center">{{ $user->winningNumbers->count() }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

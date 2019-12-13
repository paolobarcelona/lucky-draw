@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    Winning Numbers for {{ $user->name ?? '' }}
                </div>
                <div class="card-body">
                    <table id="user-table" class="table table-dark">
                        <thead>
                            <tr>
                                <th class="text-center">Number</th>
                                <th class="text-center">Date Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->winningNumbers ?? [] as $winningNumber)
                                <tr>
                                    <td class="text-center">{{ $winningNumber->winning_number ?? '' }}</td>
                                    <td class="text-center">{{ Carbon\Carbon::parse($winningNumber->created_at)->format('m/d/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-right">
                    <a href="/" class="btn btn-success">Back to dashboard</a>   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')

@if (session()->has('message'))
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if (session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('message') }}
                    </div>
                @else
                    <div class="alert alert-success">
                        {{ session()->get('message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form method="POST" action="/draw/create">
                {{ csrf_field() }}
                <div class="card">
                    <div class="card-header">
                        Create Draw
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="prize">Prize <span class="text-danger">*</span></label>
                            <select class="custom-select d-block w-100" id="prize" name="prize" required="">
                                <option value="">Please Select</option>
                                @foreach ($prizes as $prize => $prizeText)
                                    <option value="{{ $prize }}">{{$prizeText}}</option>
                                @endforeach
                            </select>
                        </div>
                        <hr/>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox"
                                v-model="draw.create.checked"
                                class="custom-control-input"
                                id="is_generated_randomly"
                                name="is_generated_randomly"
                                value="1">
                            <label class="custom-control-label" for="is_generated_randomly">Generate Randomly</label>
                        </div>
                        <hr/>
                        <div class="mb-3">
                            <label for="address">Winning Number <span v-show="!draw.create.checked" class="text-danger">*</span></label>
                            <input type="number" :disabled="draw.create.checked" :required="!draw.create.checked" class="form-control" id="winning_number" name="winning_number" placeholder="1234">
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button class="btn btn-success btn-block" type="submit">Draw</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

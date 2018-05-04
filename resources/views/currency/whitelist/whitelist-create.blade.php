@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Add currency pair</div>

        <div class="card-body">
            {{ Form::open(
                array(
                    'class'=>'form-horizontal',
                    'action' => 'CurrencyWhitelistController@store',
                )
            )}}
            {{ csrf_field() }}

            <div class="form-group row">
                <label for="currency_index" class="col-md-4 col-form-label text-md-right">Currency pair</label>

                <div class="col-md-6">
                    <input id="currency_index" type="text" class="form-control{{ $errors->has('currency_index') ? ' is-invalid' : '' }}" name="currency_index" value="{{ old('currency_index') }}">

                    @if ($errors->has('currency_index'))
                        <span class="invalid-feedback">
                                <strong>{{ $errors->first('currency_index') }}</strong>
                            </span>
                    @endif
                </div>
            </div>

            <div class="form-group row">
                <label for="listed" class="col-md-4 col-form-label text-md-right">Is listed</label>

                <div class="col-md-6">
                    <select id="listed" class="form-control" name="listed">
                        <option value="1">Yes</option>
                        <option value="0">No</option>
                    </select>

                    @if ($errors->has('listed'))
                        <span class="invalid-feedback">
                                <strong>{{ $errors->first('listed') }}</strong>
                            </span>
                    @endif
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Add currency
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection

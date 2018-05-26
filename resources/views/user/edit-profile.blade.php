@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">Edit my profile</div>

        <div class="card-body row">
            <div class="col-lg-4">
                <img class="img-responsive img-thumbnail" src="{{ $user->getImage() }}" />
            </div>
            <div class="col-lg-8">
                {{ Form::open(array('class'=>'form-horizontal',
                                            'action' => 'UserController@update',
                                            'url' => 'user/'.$user->id,
                                            'method' => 'PUT',
                                            'files' => true)) }}
                {{ csrf_field() }}

                    <h4>Personal information</h4>

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}">

                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="surname" class="col-md-4 col-form-label text-md-right">Surname</label>

                        <div class="col-md-6">
                            <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ $user->surname }}">

                            @if ($errors->has('surname'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('surname') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="username" class="col-md-4 col-form-label text-md-right">Username</label>

                        <div class="col-md-6">
                            <input id="username" type="text" class="form-control{{ $errors->has('username') ? ' is-invalid' : '' }}" name="username" value="{{ $user->username }}">

                            @if ($errors->has('username'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('username') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-4 col-form-label text-md-right">E-mail</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $user->email }}">

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="gender" class="col-md-4 col-form-label text-md-right">Gender</label>

                        <div class="col-md-6">
                            <select id="gender" type="gender" class="form-control" name="gender">
                                <option value="m">Male</option>
                                <option value="f">Female</option>
                                <option value="o">Other</option>
                            </select>

                            @if ($errors->has('gender'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('gender') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="birthdate" class="col-md-4 col-form-label text-md-right">Birthday</label>

                        <div class="col-md-6">
                            <input id="birthdate" type="birthdate" class="form-control datepicker {{ $errors->has('birthdate') ? ' is-invalid' : '' }}" name="birthdate" value="{{ $user->birthdate }}">

                            @if ($errors->has('birthdate'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('birthdate') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                        </div>
                    </div>
                    <hr />

                    <h4>Poloniex information</h4>

                    <div class="form-group row">
                        <label for="poloniex_key" class="col-md-4 col-form-label text-md-right">Poloniex key</label>

                        <div class="col-md-6">
                            <input id="poloniex_key" type="text" value="{{ $user->poloniex_key }}" class="form-control{{ $errors->has('poloniex_key') ? ' is-invalid' : '' }}" name="poloniex_key">

                            @if ($errors->has('poloniex_key'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('poloniex_key') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <label for="poloniex_secret" class="col-md-4 col-form-label text-md-right">Poloniex secret</label>

                        <div class="col-md-6">
                            <input id="poloniex_secret" type="poloniex_secret" value="{{ $user->poloniex_secret }}" class="form-control{{ $errors->has('poloniex_secret') ? ' is-invalid' : '' }}" name="poloniex_secret">

                            @if ($errors->has('poloniex_secret'))
                                <span class="invalid-feedback">
                                <strong>{{ $errors->first('poloniex_secret') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="selected_pair" class="col-md-4 col-form-label text-md-right">Selected trading pair</label>

                        <div class="col-md-6">
                            <select id="selected_pair" type="select" class="form-control" name="selected_pair">
                                <option value="">Nothing selected</option>
                                @foreach($pairs as $pair)
                                    <option value="{{ $pair }}" {{ $pair == $user->selected_pair ? 'selected' : ''}}>{{ $pair }}</option>
                                @endforeach
                            </select>

                            @if ($errors->has('selected_pair'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('selected_pair') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr />

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Save
                            </button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endsection

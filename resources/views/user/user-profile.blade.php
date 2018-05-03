@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">{{ $user->username }}</div>

        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="btn-toolbar pull-right">
                <a href="{{ URL::to('/user/'.$user->id.'/edit')  }}" class="btn btn-default">Edit profile</a>
                <a href="{{ URL::to('/')  }}" class="btn btn-default">My Balance</a>
            </div>

            <hr />

            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                    <img class="img-responsive img-thumbnail" src="{{ $user->getImage() }}" />
                </div>
                <div id="user-info" class="col-lg-8 col-md-7 col-sm-12 col-xs-12">
                    <h4>Personal information</h4>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4 text-right">Name</div>
                        <div class="col-lg-8 text-left">{{ $user->name }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Surname</div>
                        <div class="col-lg-8 text-left">{{ $user->surname }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Username</div>
                        <div class="col-lg-8 text-left">{{ $user->username }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Gender</div>
                        <div class="col-lg-8 text-left">
                            @if ($user->gender == 'm')
                                Male
                            @elseif($user->gender == 'f')
                                Female
                            @else
                                Other
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Birthday</div>
                        <div class="col-lg-8 text-left">{{ $user->birthdate }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Country</div>
                        <div class="col-lg-8 text-left">{{ $user->country }}</div>
                    </div>

                    <h4>Poloniex information</h4>
                    <hr />
                    <div class="row">
                        <div class="col-lg-4 text-right">Key</div>
                        <div class="col-lg-8 text-left">{{ $user->poloniex_key }}</div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 text-right">Secret</div>
                        <div class="col-lg-8 text-left">{{ $user->poloniex_secret }}</div>
                    </div>
                </div>
            </div>

            {{ Form::open(array(
                'url' => 'user/'.$user->id,
                'action' => 'UserController@destroy',
                'method' => 'DELETE')
            )}}
                {{ csrf_field() }}
                <button onclick="return confirm('Do you really want to delete your profile?');" type="submit" class="btn btn-default">Delete my profile</button>
            {{ Form::close() }}
    </div>
@endsection

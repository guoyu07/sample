@extends('layouts.default')
@section('title','更新密码')

@section('content')
<div class="container">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-default">
          更新密码
        </div>
        <div class="panel-body">
          @if(session('status'))
            <div class="alert alert-success">
              {{ session('status') }}
            </div>
          @endif

          <form class="form-horizontal" method="post" action="{{ route('password.update') }}">
            {{ csrf_field() }}

            <input type="hidden" name="token" value="{{ $token }}" />

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
              <label for="email" class="col-md-4 control-label">邮箱地址：</label>

              <div class="col-md-6">
                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email')}}" required autofocus />

              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>

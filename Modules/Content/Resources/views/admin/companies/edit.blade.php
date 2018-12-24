@extends('layouts.master')

@section('content-header')
<h1>
    Edit Coampany <small>{{ $company->name }}</small>
</h1>
<ol class="breadcrumb">
    <li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
    <li class=""><a href="{{ URL::route('admin.user.company.index') }}">{{ trans('user::companies.breadcrumb.companies') }}</a></li>
    <li class="active">{{ trans('user::companies.breadcrumb.edit') }}</li>
</ol>
@stop

@section('content')
{!! Form::open(['route' => ['admin.user.company.update', $company->id], 'method' => 'put']) !!}
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1-1">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    {!! Form::label('name', 'Company Name') !!}
                                    {!! Form::text('name', old('name', $company->name), ['class' => 'form-control', 'data-slug' => 'source', 'placeholder' => 'Company Name']) !!}
                                    {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group{{ $errors->has('user_limit') ? ' has-error' : '' }}">
                                    {!! Form::label('user_limit', 'User Limit') !!}
                                    {!! Form::text('user_limit', old('user_limit', $company->user_limit), ['class' => 'form-control slug', 'data-slug' => 'target', 'placeholder' => 'User Limit']) !!}
                                    {!! $errors->first('user_limit', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>
                            <div class ="row">
								<div class="col-sm-10">
									<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
										{!! Form::label('status', 'Status') !!}
										&nbsp;&nbsp;
										&nbsp;&nbsp;
										{!! Form::label('status', trans('Enable')) !!}
										{!! Form::radio('status', 1,'1', ['class' => '']) !!}
										{!! $errors->first('status', '<span class="help-block">:message</span>') !!}
										&nbsp;&nbsp;
										{!! Form::label('status', trans('Disable')) !!}
										{!! Form::radio('status', 0,'0', ['class' => '']) !!}
										{!! $errors->first('status', '<span class="help-block">:message</span>') !!}
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{ trans('user::button.update') }}</button>
                    <a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.user.company.index')}}"><i class="fa fa-times"></i> {{ trans('user::button.cancel') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
{!! Form::close() !!}
@stop
@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('user::companies.navigation.back to index') }}</dd>
    </dl>
@stop
@section('scripts')
<script>
$( document ).ready(function() {
    $(document).keypressAction({
        actions: [
            { key: 'b', route: "<?= route('admin.user.company.index') ?>" }
        ]
    });
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
});
</script>
@stop

@extends('layouts.master')

@section('content-header')
	<h1>New Company</h1>
	<ol class="breadcrumb">
		<li><a href="{{ URL::route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
		<li class=""><a href="{{ URL::route('admin.content.company.index') }}">Companies</a></li>
		<li class="active">New</li>
	</ol>
@stop

@section('content')
{!! Form::open(['route' => 'admin.content.company.store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
<div class="row">
	<div class="col-md-12">
		<div class="nav-tabs-custom">
			<div class="tab-content">
				<div class="tab-pane active" id="tab_1-1">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-4">
								<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
									{!! Form::label('name', 'Company name') !!}
									{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
									{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
								</div>

								<div class="form-group{{ $errors->has('user_limit') ? ' has-error' : '' }}">
									{!! Form::label('user_limit', 'User Limit') !!}
									{!! Form::text('user_limit', old('user_limit'), ['class' => 'form-control', 'placeholder' => 'User Limit']) !!}
									{!! $errors->first('user_limit', '<span class="help-block">:message</span>') !!}
								</div>

								<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
									{!! Form::label('status', trans('Status')) !!}
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
							<div class="col-sm-1">
								{{ Form::hidden('created_by', \Auth::user()->id) }}
							</div>
							<div class="col-sm-6" style="border-left: 1px solid #cccccc;">
								<div class="col-sm-6 custom_img">
									<div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
										{!! Form::label('logo', 'Upload Logo') !!}
										<input name="logo" type="file" onchange="previewFile()" id="img_changes">
										{!! $errors->first('logo', '<span class ="help-block">:message</span>') !!}
									</div>
									<div>
										<input type="button" class="btn btn-primary btn-flat reset_preview_image" value="Reset Image" onclick="restImageView()" >
									</div>
								</div>
								<div class="col-sm-6">
									<img class="select_img" src="" onchange="previewFile()" width="120">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button type="submit" class="btn btn-primary btn-flat">{{ trans('user::button.create') }}</button>
					<a class="btn btn-danger pull-right btn-flat" href="{{ URL::route('admin.content.company.index')}}">
						<i class="fa fa-times"></i> {{ trans('user::button.cancel') }}
					</a>
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
		<dd>Company index</dd>
	</dl>
@stop
@section('scripts')
<script>
	$( document ).ready(function() {
		$('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
			checkboxClass: 'icheckbox_flat-blue',
			radioClass: 'iradio_flat-blue'
		});
		$(document).keypressAction({
			actions: [
				{ key: 'b', route: "<?= route('admin.content.company.index') ?>" }
			]
		});
	});

	function restImageView()
	{
		$(".select_img").attr("src", "");
		$("#img_changes").val("");
	}

	function previewFile()
	{
		$('input[name="logo"]').prop('checked', false);
		var preview = document.querySelector('img.select_img'); //selects the query named img
		var file    = document.querySelector('input[type=file]').files[0]; //sames as here
		var reader  = new FileReader();

		reader.onloadend = function () {
			preview.src = reader.result;
		}

		if (file) {
			reader.readAsDataURL(file); //reads the data as a URL
		} else {
			preview.src = "";
		}
	}

</script>
@stop

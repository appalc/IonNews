<div class="box-body">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', trans('Layout name')) !!}
				{!! Form::text('name', $layout->name, ['class' => 'form-control', 'placeholder' => trans('name')]) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group{{ $errors->has('key') ? ' has-error' : '' }}">
				{!! Form::label('key', trans('Key')) !!}
				{!! Form::text('key',$layout->key, ['class' => 'form-control', 'placeholder' => trans('key')]) !!}
				{!! $errors->first('key', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group{{ $errors->has('options') ? ' has-error' : '' }}">
				{!! Form::label('options', trans('Options')) !!}
				{!! Form::textarea('options',$layout->options, ['class' => 'form-control', 'placeholder' => trans('options')]) !!}
				{!! $errors->first('options', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
	</div>
</div>
<div class="box-body">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'User Group name') !!}
				{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('user_limit') ? ' has-error' : '' }}">
				{!! Form::label('user_limit', 'User Limit') !!}
				{!! Form::text('user_limit', old('user_limit'), ['class' => 'form-control', 'placeholder' => 'User Limit']) !!}
				{!! $errors->first('user_limit', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
				{!! Form::label('company_id', 'Company') !!}
				{!! Form::text('company_id', old('company_id'), ['class' => 'form-control', 'placeholder' => 'Company']) !!}
				{!! $errors->first('company_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('skin_id') ? ' has-error' : '' }}">
				{!! Form::label('skin_id', 'Skin') !!}
				{!! Form::text('skin_id', old('skin_id'), ['class' => 'form-control', 'placeholder' => 'Skin']) !!}
				{!! $errors->first('skin_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
	</div>
</div>

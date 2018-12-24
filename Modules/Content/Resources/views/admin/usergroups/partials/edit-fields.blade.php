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
			<div class="form-group{{ $errors->has('company_group_id') ? ' has-error' : '' }}">
				{!! Form::label('company_group_id', 'Company Group') !!}
				{!! Form::text('company_group_id', old('company_group_id'), ['class' => 'form-control', 'placeholder' => 'Company Group']) !!}
				{!! $errors->first('company_group_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }}">
				{!! Form::label('category_id', 'Category') !!}
				{!! Form::text('category_id', old('category_id'), ['class' => 'form-control', 'placeholder' => 'Category']) !!}
				{!! $errors->first('category_id', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
	</div>
</div>

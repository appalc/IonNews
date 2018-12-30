<div class="box-body">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'User Group name') !!}
				{!! Form::text('name', old('name', $companygroup->name), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('user_limit') ? ' has-error' : '' }}">
				{!! Form::label('user_limit', 'User Limit') !!}
				{!! Form::text('user_limit', old('user_limit', $companygroup->user_limit), ['class' => 'form-control', 'placeholder' => 'User Limit']) !!}
				{!! $errors->first('user_limit', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<label>Company</label>
			<select class="form-control" name="company_id">
				<?php foreach ($companies as $companyId => $companyName): ?>
				<option value="{{ $companyId }}" <?php echo ($companyId == $companygroup->company_id) ? "selected" : '';?>>
					{{ $companyName }}
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-4">
			<label>Skin</label>
			<select class="form-control" name="skin_id">
				<?php foreach ($skins as $skinId => $skinName): ?>
				<option value="{{ $skinId }}" <?php echo ($skinId == $companygroup->skin_id) ? "selected" : '';?>>
					{{ $skinName }}
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-2">{{ Form::hidden('updated_by', $currentUser->id) }}</div>
	</div>
</div>

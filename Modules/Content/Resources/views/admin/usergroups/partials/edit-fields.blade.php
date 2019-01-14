<div class="box-body">
	<div class="row">
		<div class="col-sm-4">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'User Group name') !!}
				{!! Form::text('name', old('name', $usergroup->name), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-4">
			<label>Company Group</label>
			<select class="form-control" name="company_group_id">
				<?php foreach ($companygroups as $companygroup): ?>
				<option value="{{ $companygroup->id }}" <?php echo ($companygroup->id == $usergroup->company_group_id) ? "selected" : '';?>>
					{{ $companygroup->name }}
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-4">
			<label>Category</label>
			<select multiple="" class="form-control" name="category_id[]">
				<?php
				foreach ($categories as $category) :
					$parsedCategories = !empty($usergroup->category_id) ? json_decode($usergroup->category_id, true) : [];
				?>
				<option value="{{ $category->id }}" <?php echo in_array($category->id, $parsedCategories) ? "selected" : '';?>>
					{{ $category->name }}
				</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-sm-2">{{ Form::hidden('updated_by', $currentUser->id) }}</div>
	</div>
</div>

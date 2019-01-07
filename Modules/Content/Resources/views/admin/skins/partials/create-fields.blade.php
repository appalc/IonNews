<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-12 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'Name') !!}
				{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('color') ? ' has-error' : '' }}">
				{!! Form::label('color', 'Color') !!}
				{!! Form::text('color', old('color'), ['class' => 'form-control', 'placeholder' => 'Color']) !!}
				{!! $errors->first('color', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('highlight_color') ? ' has-error' : '' }}">
				{!! Form::label('highlight_color', 'Highlight Color') !!}
				{!! Form::text('highlight_color', old('highlight_color'), ['class' => 'form-control', 'placeholder' => 'Highlight Color']) !!}
				{!! $errors->first('highlight_color', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('font') ? ' has-error' : '' }}">
				{!! Form::label('font', 'Font') !!}
				{!! Form::text('font', old('font'), ['class' => 'form-control', 'placeholder' => 'Font']) !!}
				{!! $errors->first('font', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group">
				<label>Font Size</label>
				<select class="form-control" name="font_size">
					<?php foreach (range(10, 20) as $size): ?>
					<option value="{{ $size }}">{{ $size }}</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="col-sm-1">
			{{ Form::hidden('created_by', \Auth::user()->id) }}
		</div>
	</div>
</div>

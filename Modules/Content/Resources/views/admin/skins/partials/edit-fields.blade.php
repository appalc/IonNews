<div class="box-body">
	<div class="row">
		<div class="col-sm-12">
			<div class="col-sm-12 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', 'Name') !!}
				{!! Form::text('name', old('name', $skin->name), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('color') ? ' has-error' : '' }}">
				{!! Form::label('color', 'Color') !!}
				{!! Form::text('color', old('color', $skin->color), ['class' => 'form-control', 'placeholder' => 'Color']) !!}
				{!! $errors->first('color', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('color_code') ? ' has-error' : '' }}">
				{!! Form::label('color_code', 'Color Code') !!}
				{!! Form::text('color_code', old('color_code', $skin->color_code), ['class' => 'form-control', 'placeholder' => 'Color Code']) !!}
				{!! $errors->first('color_code', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('highlight_color') ? ' has-error' : '' }}">
				{!! Form::label('highlight_color', 'Highlight Color') !!}
				{!! Form::text('highlight_color', old('highlight_color', $skin->highlight_color), ['class' => 'form-control', 'placeholder' => 'Highlight Color']) !!}
				{!! $errors->first('highlight_color', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('hi_color_code') ? ' has-error' : '' }}">
				{!! Form::label('hi_color_code', 'Highlight Color Code') !!}
				{!! Form::text('hi_color_code', old('hi_color_code', $skin->hi_color_code), ['class' => 'form-control', 'placeholder' => 'Highlight Color Code']) !!}
				{!! $errors->first('hi_color_code', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('bottom_shade_color_1') ? ' has-error' : '' }}">
				{!! Form::label('bottom_shade_color_1', 'Bottom Bar Shade1 Color Code') !!}
				{!! Form::text('bottom_shade_color_1', old('bottom_shade_color_1', $skin->bottom_shade_color_1), ['class' => 'form-control', 'placeholder' => 'Bottom Bar Shade1 Color Code']) !!}
				{!! $errors->first('bottom_shade_color_1', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('bottom_shade_color_2') ? ' has-error' : '' }}">
				{!! Form::label('bottom_shade_color_2', 'Bottom Bar Shade2 Color Code') !!}
				{!! Form::text('bottom_shade_color_2', old('bottom_shade_color_2', $skin->bottom_shade_color_2), ['class' => 'form-control', 'placeholder' => 'Bottom Bar Shade2 Color Code']) !!}
				{!! $errors->first('bottom_shade_color_2', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group{{ $errors->has('button_color_code') ? ' has-error' : '' }}">
				{!! Form::label('button_color_code', 'Button Color Code') !!}
				{!! Form::text('button_color_code', old('button_color_code', $skin->button_color_code), ['class' => 'form-control', 'placeholder' => 'Button Color Code']) !!}
				{!! $errors->first('button_color_code', '<span class="help-block">:message</span>') !!}
			</div>

			<div style="clear: both;"></div>

			<div class="col-sm-6 form-group{{ $errors->has('font') ? ' has-error' : '' }}">
				{!! Form::label('font', 'Font') !!}
				{!! Form::text('font', old('font', $skin->font), ['class' => 'form-control', 'placeholder' => 'Font']) !!}
				{!! $errors->first('font', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="col-sm-6 form-group">
				<label>Font Size</label>
				<select class="form-control" name="font_size">
					<?php foreach (range(10, 20) as $size): ?>
					<option value="{{ $size }}" <?php echo ($skin->font_size == $size) ? 'selected' : ''; ?>>{{ $size }}</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="col-sm-1">
			{{ Form::hidden('created_by', $currentUser->id) }}
		</div>
	</div>
</div>

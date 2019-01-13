<div class="box-body">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', trans('Category name')) !!}
				{!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('name')]) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group{{ $errors->has('slug_name') ? ' has-error' : '' }}">
				{!! Form::label('slug_name', trans('Slug_Name')) !!}
				{!! Form::text('slug_name', old('slug_name'), ['class' => 'form-control', 'placeholder' => trans('slug_name')]) !!}
				{!! $errors->first('slug_name', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
				{!! Form::label('priority', trans('priority')) !!}
				{!! Form::text('priority', old('priority',$categories_size), ['class' => 'form-control', 'placeholder' => trans('priority')]) !!}
				{!! $errors->first('priority', '<span class="help-block">:message</span>') !!}
			</div>

			<div class="form-group{{ $errors->has('Address') ? ' has-error' : '' }}">
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

		<div class="col-sm-6" style="border-left: 1px solid #cccccc;">
			<div class="col-sm-6 custom_img">
				<div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
					{!! Form::label('icon', 'Upload Icon') !!}
					<input name="icon" type="file" onchange="previewFile()" id="img_changes">
					{!! $errors->first('icon', '<span class ="help-block">:message</span>') !!}
				</div>
				<div>
					<input type="button" class="btn btn-primary btn-flat reset_preview_image" value="Reset Image" onclick="restImageView()" >
				</div>
			</div>
			<div class="col-sm-6">
				<?php $iconUrl = !empty ($category->icon) ? env('IMG_BASE_URL') . $category->icon : ''; ?>
				<img class="select_img" src="{{ $iconUrl }}" onchange="previewFile()" width="120">
			</div>
		</div>
	</div>
</div>

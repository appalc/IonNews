<div class="box-body">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group{{ $errors->has('crawl_url') ? ' has-error' : '' }}">
				{!! Form::label('name', trans('URL Address')) !!}
				{!! Form::text('crawl_url', old('crawl_url'), ['class' => 'form-control','data-slug' => 'source', 'placeholder' => trans('Custom content URL')]) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<!--
		<div class ="col-sm-3">
			<label></label><br><br>
			<input type="button" class="btn btn-primary btn-flat" value="Crawl Content" onclick="crawl()" />
		</div>
		-->
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				{!! Form::label('title', trans('Title')) !!}
				{!! Form::text('title', old('title'), ['class' => 'form-control','id' =>'title',  'placeholder' => trans('Story title')]) !!}
				{!! $errors->first('title', '<span class ="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('sub_title') ? ' has-error' : '' }}">
				{!! Form::label('sub_titzle', trans('Subtitle')) !!}
				{!! Form::text('sub_title', old('sub_title'), ['class' => 'form-control','id' =>'sub_title', 'placeholder' => trans('subtitle')]) !!}
				{!! $errors->first('sub_title', '<span class ="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
				{!! Form::label('tags', trans('Tags')) !!}
				{!! Form::text('tags', old('tags'), ['class' => 'form-control','id' =>'tags', 'placeholder' => trans('tags')]) !!}
				{!! $errors->first('tags', '<span class ="help-block">:message</span>') !!}
			</div>
		</div>

		<div class="tab-pane" id="tab_2-2">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							{!! Form::label('category_id', trans('Category')) !!}
							<div class="form-control" style="width: 20em;height: 10em;border: 1px solid rgb(192, 192, 192);overflow: auto;">
								<?php foreach ($categories as $categoryId => $categoryName): ?>
									<label style="display: block;">
										<input type="checkbox" style="vertical-align:top; margin-right: 10px;" onclick ="selectCategory(this.value)" name="category_id[]" value="{{ $categoryId }}" />
										{{ $categoryName }}
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
				{!! Form::label('content', trans('Story content')) !!}
				{!! Form::textarea('content', old('content'), ['class' => 'form-control','id' =>'content','placeholder' => trans('Story content')]) !!}
				{!! $errors->first('content', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
	</div>

	<div class ="row">
		<div class ="col-sm-12 custom_img">
			<div class ="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
				{!! Form::label('image', trans('Image')) !!}
				<input name ="img" type="file" onchange="previewFile()">
				{!! $errors->first('image', '<span class ="help-block">:message</span>') !!}
				<img  class ="select_img" src="" onchange="previewFile()" width="120">
			</div>
		</div>
	</div>
</div>

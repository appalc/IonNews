<div class="box-body">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
				{!! Form::label('name', trans('URL Address')) !!}
				{!! Form::text('crawl_url', old('crawl_url'), ['class' => 'form-control','data-slug' => 'source', 'placeholder' => trans('URL to fetch content')]) !!}
				{!! $errors->first('name', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class ="col-sm-3">
			<label></label><br><br>
			<input type ="button" class="btn btn-primary btn-flat" value="Crawl Content" onclick="crawl()" />
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
				{!! Form::label('title', trans('Title')) !!}
				{!! Form::text('title', old('title'), ['class' => 'form-control','id' =>'title',  'placeholder' => trans('Story title')]) !!}
				{!! $errors->first('title', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('sub_title') ? ' has-error' : '' }}">
				{!! Form::label('sub_titzle', trans('Subtitle')) !!}
				{!! Form::text('sub_title', old('sub_title'), ['class' => 'form-control','id' =>'sub_title', 'placeholder' => trans('subtitle')]) !!}
				{!! $errors->first('sub_title', '<span class="help-block">:message</span>') !!}
			</div>
		</div>
		<div class="col-sm-12">
			<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
				{!! Form::label('tags', trans('Tags')) !!}
				{!! Form::text('tags', old('tags'), ['class' => 'form-control','id' =>'tags', 'placeholder' => trans('tags')]) !!}
				{!! $errors->first('tags', '<span class="help-block">:message</span>') !!}
			</div>
		</div>

		<div class="tab-pane" id="tab_2-2">
			<div class="box-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group ">
							{!! Form::label('category_id', trans('Category')) !!}

							<div class="form-control category_select multiselect"> 
								<?php foreach ($categories as $categoryId => $categoryName) : ?>
									<label>
										<input type="checkbox" style="vertical-align:top; margin-right: 10px;" onclick ="selectCategory(this.value)" name="category_id[]"  value="{{ $categoryId }}" /> {{ $categoryName }}
									</label>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class ="col-sm-12">
			<label class ="pull-left">Expiry Date</label>
			<div id="returnrange" class="pull-left" style="margin-left:20px;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
				<input type="hidden" name="expiry_date" id="expiry_date"  value="" >
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
				<span></span><b class="caret"></b>
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
		<div class ="form-group box-body img-info">
			<table id ="dataTable" width="350px" border="1" class="imgtable" style="width: 100%;border: 4px solid #ecf0f5;">
				<col width ="30">
				<thead>
					<tr>
						<th>select</th> <th>Preview</th> {{--<th>Image Description</th>--}}
					</tr>
				</thead>
				<tbody id ="syndata">
				</tbody>
					{{--<tr>--}}
						{{--<td><input  type="checkbox" name="chk"/></td>--}}
						{{--<td> 1 </td>--}}
						{{--<td class="filechoose"><input type='file' name="filebox['imgae'][]" onchange="readURL(this);" value=""></td>--}}
						{{--<td><img id="blah" src="#" alt="Image preview" width="60" /></td>--}}
						{{--<td><textarea name="filebox['description'][]"></textarea></td>--}}
					{{--</tr>--}}
			</table>
		</div>

		<div class ="col-sm-12 custom_img">
			<div class ="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
				{!! Form::label('image', trans('Image')) !!}
				<input name ="img" type="file" onchange="previewFile()" id="img_changes">
				{!! $errors->first('image', '<span class ="help-block">:message</span>') !!}
				<img class ="select_img" src="" onchange="previewFile()" width="120">
			</div>
			<div>
				<input type ="button" class="btn btn-primary btn-flat reset_preview_image" value="Reset Image" onclick="restImageView()" >
			</div>
		</div>

		<?php if (env('STORY_PUSH_ENABLE')) { ?>
			<div class="col-sm-12">
				<div class="form-group">
					{!! Form::label('pushToProd', trans('Push To Production Instance')) !!}
					&nbsp;&nbsp;&nbsp;&nbsp;
					{!! Form::checkbox('pushToProd', 1, false, ['id' => 'pushToProd']) !!}
				<div>
			</div>
		<?php } ?>
	</div>

</div>

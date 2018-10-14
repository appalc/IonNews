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
							<!-- <div class="form-group multiselect" style="height: auto;"> -->
							<div class="form-control category_select multiselect"> 
								<!-- <select multiple="" class="form-control category_select" onchange="selectCategory(this.value)" name="category_id[]"> -->
								<?php foreach ($categories as $category):
									if($category->slug_name!='archive') {
								?>

									<!-- <option value="{{ $category->id }}">{{ $category->name }}</option> -->
									<label>
										<input type="checkbox" style="vertical-align:top; margin-right: 10px;" onclick ="selectCategory(this.value)" name="category_id[]"  value="{{ $category->id }}" /> {{ $category->name }}
									</label>
								<?php
									}
								endforeach;
								?>
								<!-- </select> -->
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

		<div class ="tab-pane user-types" id="tab_2-2">
			<div class ="box-body">
				<div class ="row">
					<div class ="col-md-6">
						<div class ="form-group ">
							{!! Form::label('user_group', trans('User Group')) !!}
							<select multiple ="multiple" class="user_group form-control" name="user_roles[]">
								<?php foreach ($user_roles as $user_role): ?>
									<option value="{{ $user_role['id'] }}" <?php echo ($user_role['id'] ==-1) ? 'selected' : ''; ?>> {{ $user_role['type'] }} </option>
								<?php endforeach; ?>
							</select>
						</div>
					</div>
				</div>
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

		<!--
			<div class ="form-group user-types form_grp_relative" style="">
				<table class=" data-table table table-bordered table-hover dataTable" id="User_data" role="grid" aria-describedby="DataTables_Table_0_info" >          
					<button type="button" class="btn btn-primary" id="select_all_page" style="position: relative; top: 71px; margin-left: 5px;height: 32px;width: 83px;">
					<span style="position: relative;top: -2px;">SelectAll</span></button>
					<thead>
						<tr>
							<th data-sortable ="false" style="width: 70px;">
							<input type ="checkbox"  id="select_all">Select</th>
							<th>Name</th>
							<th>Company Name </th>
							<th>Designation</th>
						</tr>
					</thead>
					<tbody id = "user_info">
					</tbody>
				</table>
			</div>
		-->
	</div>

</div>

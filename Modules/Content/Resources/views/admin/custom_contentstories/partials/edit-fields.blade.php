<div class="box-body">
	<div class ="box-body">
		<div class ="row">

			<div class ="col-sm-12">
				<div class="form-group{{ $errors->has('custom_url') ? ' has-error' : '' }}">
					{!! Form::label('URL Address', trans('URL ')) !!}
					{!! Form::text('custom_url', $custom_contentstory->custom_url, ['class' => 'form-control', 'placeholder' => trans('custom url')]) !!}
					{!! $errors->first('custom_url', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class ="col-sm-12">
				<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
					{!! Form::label('title', trans('Title')) !!}
					{!! Form::text('title', $custom_contentstory->title, ['class' => 'form-control', 'placeholder' => trans('Story title')]) !!}
					{!! $errors->first('title', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group{{ $errors->has('sub_title') ? ' has-error' : '' }}">
					{!! Form::label('sub_title', trans('Subtitle')) !!}
					{!! Form::text('sub_title', $custom_contentstory->sub_title, ['class' => 'form-control', 'placeholder' => trans('sub_title')]) !!}
					{!! $errors->first('sub_title', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
					{!! Form::label('tags', trans('Tags')) !!}
					{!! Form::text('tags', $custom_contentstory->tags, ['class' => 'form-control', 'placeholder' => trans('tags')]) !!}
					{!! $errors->first('tags', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="tab-pane" id="tab_2-2">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('category_id', trans('Service Provider Name')) !!}
					<!--			<select multiple="" class="form-control" name="category_id[]">	-->
									<?php
									$allContentCategory = array();
									$allcategory        = $custom_contentstory->all_category;
									$allContentCategory = ($allcategory) ? json_decode($allcategory, true) : [$custom_contentstory->category_id];

									foreach ($categories as $category) :
									?>
					<!--				<option value="{{ $category->id }}" <?php // echo (in_array($category->id, $allContentCategory)) ? "selected" : '';?> >
										{{ $category->name }}
									</option>
					-->
										<label style="display: block;">
											<input type="checkbox" style="vertical-align:top; margin-right: 10px;" onclick ="selectCategory(this.value)" name="category_id[]" value="{{ $category->id }}" <?php echo (in_array($category->id, $allContentCategory)) ? "checked" : '';?> />
											{{ $category->name }}
										</label>

									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', trans('Story content')) !!}
					{!! Form::textarea('content', $custom_contentstory->content, ['class' => 'form-control', 'placeholder' => trans('user::users.form.email')]) !!}
					{!! $errors->first('email', '<span class="help-block">:message</span>') !!}
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-4">
				<div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
					{!! Form::label('image', trans('Image')) !!}
					<input name="img" type="file" onchange="previewFile()">
					{!! $errors->first('image', '<span class="help-block">:message</span>') !!}
					<img class="select_img img_preview" src="{{ $custom_contentstory->image }}" onchange="previewFile()" width="120">
				</div>
			</div>
		</div>
	</div> 
</div>

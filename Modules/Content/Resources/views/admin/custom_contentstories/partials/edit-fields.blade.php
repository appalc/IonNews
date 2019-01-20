<div class="box-body">
	<div class ="box-body">
		<div class ="row">

			<div class ="col-sm-12">
				<div class="form-group{{ $errors->has('crawl_url') ? ' has-error' : '' }}">
					{!! Form::label('URL Address', trans('URL ')) !!}
					{!! Form::text('crawl_url', $content->crawl_url, ['class' => 'form-control', 'placeholder' => trans('custom url')]) !!}
					{!! $errors->first('crawl_url', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class ="col-sm-12">
				<div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
					{!! Form::label('title', trans('Title')) !!}
					{!! Form::text('title', $content->title, ['class' => 'form-control', 'placeholder' => trans('Story title')]) !!}
					{!! $errors->first('title', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group{{ $errors->has('sub_title') ? ' has-error' : '' }}">
					{!! Form::label('sub_title', trans('Subtitle')) !!}
					{!! Form::text('sub_title', $content->sub_title, ['class' => 'form-control', 'placeholder' => trans('sub_title')]) !!}
					{!! $errors->first('sub_title', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="col-sm-12">
				<div class="form-group{{ $errors->has('tags') ? ' has-error' : '' }}">
					{!! Form::label('tags', trans('Tags')) !!}
					{!! Form::text('tags', $content->tags, ['class' => 'form-control', 'placeholder' => trans('tags')]) !!}
					{!! $errors->first('tags', '<span class="help-block">:message</span>') !!}
				</div>
			</div>

			<div class="tab-pane" id="tab_2-2">
				<div class="box-body">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								{!! Form::label('category_id', trans('Service Provider Name')) !!}
								<div class="form-control" style="width: 20em;height: 10em;border: 1px solid rgb(192, 192, 192);overflow: auto;">
									<?php
									$allContentCategory = array();
									$allcategory        = $content->all_category;
									$allContentCategory = ($allcategory) ? json_decode($allcategory, true) : [$content->category_id];

									foreach ($categories as $categoryId => $categoryName) :
									?>
										<label style="display: block;">
											<input type="checkbox" style="vertical-align:top; margin-right: 10px;" onclick ="selectCategory(this.value)" name="category_id[]" value="{{ $categoryId }}" <?php echo (in_array($categoryId, $allContentCategory)) ? "checked" : '';?> />
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
				<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
					{!! Form::label('email', trans('Story content')) !!}
					{!! Form::textarea('content', $content->content, ['class' => 'form-control', 'placeholder' => trans('user::users.form.email')]) !!}
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
					<img class="select_img img_preview" src="{{ $content->image }}" onchange="previewFile()" width="120">
				</div>
			</div>
		</div>
	</div> 
</div>

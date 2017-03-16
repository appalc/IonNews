<div class="box-body">
    <div class="box-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                {!! Form::label('first_name', trans('Title')) !!}
                {!! Form::text('first_name', $content->title, ['class' => 'form-control', 'placeholder' => trans('user::users.form.first-name')]) !!}
                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="form-group{{ $errors->has('sub_title') ? ' has-error' : '' }}">
                {!! Form::label('sub_title', trans('Subtitle')) !!}
                {!! Form::text('sub_title', $content->sub_title, ['class' => 'form-control', 'placeholder' => trans('sub_title')]) !!}
                {!! $errors->first('sub_title', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="col-sm-6">
              <div class="form-group{{ $errors->has('company_id') ? ' has-error' : '' }}">
                  {!! Form::label('category_id', trans('Service Provider Name')) !!}
                  <select class="form-control" name="category_id">
                      <?php foreach ($categories as $category): ?>
                          <option value="{{ $category->id }}">{{ $category->name }}</option>
                      <?php endforeach; ?>
                  </select>
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
     	<div class="form-group box-body">
	        <input name="" type="button" class="btn btn-primary btn-flat" value="Add Image" onclick="addRow('dataTable')" />
	        <input type="button" class="btn btn-danger btn-flat" value="Delete Image" onclick="deleteRow('dataTable')" />
	        <table id="dataTable" width="350px" border="1" class="imgtable" style="width: 100%;border: 4px solid #ecf0f5;">
	            <tr>
	                <th></th>
	                <th>SL.no</th>
	                <th>Upload</th>
	                <th>Preview</th>
	                <th>Image Description</th>
	            </tr>
	            <tr>
	                <td><input  type="checkbox" name="chk"/></td>
	                <td> 1 </td>
	                <td class="filechoose"><input type='file' name="filebox[]" onchange="readURL(this);"/ value=""></td>
	                <td><img id="blah" src="#" alt="Image preview" width="60" /></td>
	                <td><textarea name=""></textarea></td>
	            </tr>
	        </table>
	    </div>
       </div>
    </div>
</div>
<div class="box-body">
    <div class="box-body">
      <div class="row">
         <div class="col-sm-12">
            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                {!! Form::label('title', trans('Title')) !!}
                {!! Form::text('title', $content->title, ['class' => 'form-control', 'placeholder' => trans('user::users.form.first-name')]) !!}
                {!! $errors->first('first_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
       <!--  <div class="col-sm-12">
            <div class="form-group{{ $errors->has('crawl_url') ? ' has-error' : '' }}">
                {!! Form::label('Crawl url', trans('crawl url')) !!}
                {!! Form::text('crawl_url', $content->crawl_url, ['class' => 'form-control', 'placeholder' => trans('crawl url'),'readonly' => 'true']) !!}
                {!! $errors->first('crawl_url', '<span class="help-block">:message</span>') !!}
            </div>
        </div> -->
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
                                    <select multiple="" class="form-control" name="category_id[]">

                                        <?php   $allContentCategory=array();
                                            $allcategory=$content->all_category;
                                             if($allcategory)
                                             { 
                                              $allContentCategory=json_decode($allcategory,true);
                                             } else $allContentCategory[0]=$content->category_id;

                                        foreach ($categories as $category): ?>
                                        <option value="{{ $category->id }}" <?php
                                        if (in_array($category->id, $allContentCategory)) 
                                        echo "selected"; else echo '';?>  >{{ $category->name }}</option>
                      <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

         <div class="col-sm-12">
        <label class="pull-left">Expiry Date</label>
        <div id="returnrange" class="pull-left" style="margin-left:20px;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
        <input type="hidden" name="expiry_date" id="expiry_date"  value="{{$content->expiry_date}}">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
        <span></span><b class="caret"></b>
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
                    <img src="{{ $content->image }}"  class="img_preview select_img" onchange="previewFile()" width="120">
                </div>
            </div>
      </div>

        <div class="tab-pane user-types" id="tab_2-2">
           <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                      {!! Form::label('user_group', trans('User Group')) !!}
                      <select multiple="multiple" class=" user_group form-control" name="user_roles[]">
                      <?php foreach ($user_roles as $user_role): ?>                     
                      <option value="{{ $user_role['id'] }}" <?php
                                        if ($user_role['checked']==1) 
                                        echo "selected"; else echo '';?>>{{ $user_role['type'] }}</option>
                      <?php endforeach; ?>
                      </select>
                  </div>
                </div>
              </div>
            </div>
          </div>         
 
	    </div> 
      

    </div>


<div class="box-body">
    <div class="row">
         <div class="col-sm-4">
            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                {!! Form::label('name', trans('Category name')) !!}
                {!! Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => trans('name')]) !!}
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group{{ $errors->has('slug_name') ? ' has-error' : '' }}">
                {!! Form::label('slug_name', trans('Slug_Name')) !!}
                {!! Form::text('slug_name', old('slug_name'), ['class' => 'form-control', 'placeholder' => trans('slug_name')]) !!}
                {!! $errors->first('slug_name', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
         <div class="col-sm-4">
            <div class="form-group{{ $errors->has('priority') ? ' has-error' : '' }}">
                {!! Form::label('priority', trans('priority')) !!}
                {!! Form::text('priority', old('priority',$categories_size), ['class' => 'form-control', 'placeholder' => trans('priority')]) !!}
                {!! $errors->first('priority', '<span class="help-block">:message</span>') !!}
            </div>
        </div>
     </div>
      <div class="row">
         <div class="col-sm-10">
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
    </div>
</div>

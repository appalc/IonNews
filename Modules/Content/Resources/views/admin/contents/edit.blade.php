@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('content::contents.title.edit content') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li><a href="{{ route('admin.content.content.index') }}">{{ trans('content::contents.title.contents') }}</a></li>
        <li class="active">{{ trans('content::contents.title.edit content') }}</li>
    </ol>
@stop

@section('styles')
    {!! Theme::script('js/vendor/ckeditor/ckeditor.js') !!}
@stop

@section('content')
    {!! Form::open(['route' => ['admin.content.content.update', $content->id], 'method' => 'put','enctype' => 'multipart/form-data' ,'onsubmit'=>'return formValidator()' ,'id' => 'userListing']) !!}
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                @include('partials.form-tab-headers')
                <div class="tab-content">
                    <?php $i = 0; ?>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $language)
                        <?php $i++; ?>
                        <div class="tab-pane {{ locale() == $locale ? 'active' : '' }}" id="tab_{{ $i }}">
                            @include('content::admin.contents.partials.edit-fields', ['lang' => $locale])
                        </div>
                    @endforeach

                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary btn-flat">{{ trans('core::core.button.update') }}</button>
                        <a class="btn btn-danger pull-right btn-flat" href="{{ route('admin.content.content.index')}}"><i class="fa fa-times"></i> {{ trans('core::core.button.cancel') }}</a>
                    </div>
                </div>
            </div> {{-- end nav-tabs-custom --}}
        </div>
    </div>
    {!! Form::close() !!}
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>b</code></dt>
        <dd>{{ trans('core::core.back to index') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">     
       
        $( document ).ready(function() {             
            $(document).keypressAction({
                actions: [
                    { key: 'b', route: "<?= route('admin.content.content.index') ?>" }
                ]
            });
        });
    </script>
    <script>
        $( document ).ready(function() {
           
            $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
                checkboxClass: 'icheckbox_flat-blue',
                radioClass: 'iradio_flat-blue'
            });
        });
    </script>
        <script type="text/javascript">
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
      <script type="text/javascript">
       var checked_result;
       var uncheck_result;
        var a;
        var b;
        var optionSet1 = {
            startDate: moment().subtract(29, 'days'),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: moment(),
            dateLimit: { days: 300},
            showDropdowns: true,
            showWeekNumbers: true,
            autoApply: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            ranges: {
                'Today': [moment(), moment()],
            },
            opens: 'right',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            locale: {
                applyLabel: 'Submit',
                cancelLabel: 'Clear',
                fromLabel: 'From',
                toLabel: 'To',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                firstDay: 1
            }
        };
  var datetime="<?php echo ($content->expiry_date != "0000-00-00")?$content->expiry_date:date('Y-m-d'); ?>";


          var optionSet2 = {
          singleDatePicker:true,
           minDate: moment(),
          startDate:moment.utc(Date.parse(datetime)),
          endDate: moment.utc(Date.parse(datetime))
        }
        var cb = function(start, end, label) {
            $('#returnrange span').html(start.format('MMMM D, YYYY'));
            a=start.format('YYYY-MM-DD');
            b=end.format('YYYY-MM-DD');            
            $('#expiry_date').val(b);
        };
        cb(moment.utc(Date.parse(datetime)), moment.utc(Date.parse(datetime)), "Last Month");
        $('#returnrange').daterangepicker(optionSet2,cb);        
        $('#returnrange').val(daterangepicker);
        $('#expiry_date').val(b);
  </script>
  <script>
   function previewFile(){
    
       var preview = document.querySelector('img'); //selects the query named img
       var file    = document.querySelector('input[type=file]').files[0]; //sames as here
       console.log(file);

       var reader  = new FileReader();


       reader.onloadend = function () {
        console.log(reader.result);
           preview.src = reader.result;
       }

       if (file) {
           reader.readAsDataURL(file); //reads the data as a URL
       } else {
           preview.src = "";
       }
  }

  </script>
    <script language="javascript">    
    var form_checker=0;

     function formValidator() {
       
       
         return true;
    }
          checkedArray = [];
   


  
function changed(event){
  if(event.checked){
    checkedArray.push(event.value);
    console.log(checkedArray);

  }else{
    var a = checkedArray.indexOf(event.value);
    // console.log(a);
    checkedArray.splice(a, 1);
  }
  // console.log(checkedArray);
}


       
   </script>


@stop

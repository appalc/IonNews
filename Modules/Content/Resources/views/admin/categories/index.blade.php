@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('content::categories.title.categories') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('content::categories.title.categories') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.content.category.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('content::categories.button.create category') }}
                    </a>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Slug Name') }}</th>
                                <th>{{trans('priority')}}</th>
                                <th>{{ trans('Status') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($categories)): ?>
                            <?php  $priorityArray=array();
                                   foreach ($categories as $value) {
                                   $priorityArray[$value->id]=$value->priority;
                            }

                            ?>
                            <?php foreach ($categories as $category): ?>
                            <tr>

                                <td>
                                        {{ $category->id }}
                                </td>
                                <td>
                                        {{ $category->name }}
                                </td>
                                 <td>
                                        {{ $category->slug_name }}
                                </td>
                                 <td>
                                         <span name="priority_status" id="set_prioority_{{$category->id}}" onclick="changeDetails(this);"> {{ $category->priority }}</span>
                                         <input onblur="rollBackDiv(this);" onfocus="readData(this);" onchange="changeDetailsDiv(this);" orderId="{{$category->id}}"   type="hidden" class="set_prioority_{{$category->id}}" value="{{ $category->priority }}">
                                </td>
                                <td>
                                         {{ $category->status }}
                                       
                                </td>
                    
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.content.category.edit', [$category->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                        <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.content.category.destroy', [$category->id]) }}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Slug Name') }}</th>
                                <th>{{ trans('Priority') }} &nbsp <button type="button" class="btn btn-primary btn-flat" id="updatepriority" hidden="hidden"> update</button></th>
                                <th>{{ trans('Status') }} </th>
                                <th>{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </tfoot>
                        </table>
                        <!-- /.box-body -->
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
    @include('core::partials.delete-modal')
@stop

@section('footer')
    <a data-toggle="modal" data-target="#keyboardShortcutsModal"><i class="fa fa-keyboard-o"></i></a> &nbsp;
@stop
@section('shortcuts')
    <dl class="dl-horizontal">
        <dt><code>c</code></dt>
        <dd>{{ trans('content::categories.title.create category') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.content.category.create') ?>" }
                ]
            });
        });
    </script>
    <?php $locale = locale(); ?>
    <script type="text/javascript">
        $(function () {
            $('.data-table').dataTable({
                "paginate": true,
                "lengthChange": true,
                "filter": true,
                "sort": true,
                "info": true,
                "autoWidth": true,
                "order": [[ 3, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>
    <script type="text/javascript">
   
           var priorityArray = <?php if(count($priorityArray)) echo json_encode($priorityArray); else "" ;?>;  
           console.log(priorityArray);
            var count = Object.keys(priorityArray).length;
             var max=count;
             var min=1;
              console.log(count);
           // var arr = $.map(priorityArray, function(el) { return el; })
           // var max = Math.max(...arr);            
           // var min = Math.min(...arr); 
        
            // console.log(priorityArray.length);
            // var jo = $.parseJSON(priorityArray);
            // priorityArray['4']=5;
            // console.log(priorityArray['4']);

            // console.log(priorityArray);
            // $.each(priorityArray, function(key,value){
            //       console.log(value);
            //        console.log(key);
            //     });

            // $('body').on('click', 'span[id^=set_prioority]', function () {  
            // $("#updatepriority").show();           
            // var id = '';
            // var column_name = '';
            // id = $(this).attr('id').replace(/[^\d.]/g, '');
            // column_name = $(this).attr('name');
            // var input = $('<input />', {
            //     'id': 'set_prioority_' +id,
            //     'type': 'number',
            //     'name': column_name,
            //     'orderId':id,
            //     'value': $(this).text().trim()

            // });

             
            // $(this).parent().append(input);          
            // $(this).remove();
            // input.focus();
            // textbox = true;         
            
        // });
        actualData = 0;
        function readData(event){
            console.log("actualData"+actualData);
            actualData = event.value;
            console.log("actualData1"+actualData);

        }
      function changeDetailsDiv(event) { 

          event_class= $(event).attr("class");
          new_value = $("."+event_class).val();          
          var categories_id=$("."+event_class).attr('orderId');
            
           if(new_value>max || new_value<min)
           {
             alert("Please set the priority in given range ["+min+ " to "+max+" ]");
             $("."+event_class).val(priorityArray[categories_id]);
              return 0;
           }
         
          var original_value= priorityArray[categories_id];       
          $.each(priorityArray, function(key,value){
            if(original_value>new_value)
            {
             
                   if(parseInt(new_value)<= parseInt(value) && parseInt(original_value)> parseInt(value))
                   {
                      priorityArray[key]=parseInt(value)+1;
                   }
            }
            else {
                   if(parseInt(new_value)>=parseInt(value) && parseInt(original_value)<parseInt(value))
                     {
                        priorityArray[key]=parseInt(value)-1;
                     }

             }
           });

          priorityArray[categories_id]=new_value;
          console.log(priorityArray);
          $.each(priorityArray,function(k,v){
            $("#set_prioority_"+k).html(v);
            $(".set_prioority_"+k).val(v);
          })
      } 

      $("#updatepriority").click(function()
      { 
        
         $.ajax({
                type: 'POST',
                data: {url: priorityArray},
                url: '{{ env('APP_URL') }}/Category/updatePriority',
                success: function(result) {
                    
                  $("#updatepriority").hide(); 
                }
     });

      });
         
         function changeDetails(event){
            
            var className = event.id;
            $("#"+className).css({display:"none"});
            $("."+className).attr("type","number");
         }
         $("#updatepriority").hide();
         function rollBackDiv(event){
            $("#updatepriority").show(); 
            var className = $(event).attr("class");
            $("#"+className).css({display:"block"});
            $("#"+className).html($("."+className).val());
            $("."+className).attr("type","hidden");
         }

    </script>
@stop

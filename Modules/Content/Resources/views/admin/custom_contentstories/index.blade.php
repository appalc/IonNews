@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('content::custom_contentstories.title.custom_contentstories') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('content::custom_contentstories.title.custom_contentstories') }}</li>
    </ol>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
        <div>
        <level> select story Possition </level><br>
        <select class="btn btn-primary btn-flat selectpicker" id="slectPossition">

        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>6</option>
        <option>7</option>
        <option>8</option>
        <option>9</option>
        <option>10</option>
        </select>
         
       
        </div>

            <div class="row">
                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.content.custom_contentstory.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('content::custom_contentstories.button.create custom_contentstory') }}
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
                            <!-- <th data-sortable="false"><input type="checkbox"  id="select_all"/></th> -->
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Title') }}</th>
                               <th>{{ trans('core::core.table.created at') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>


                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($custom_contentstories)): ?>
                            <?php foreach ($custom_contentstories as $custom_contentstory): ?>
                            <tr>

                                <!-- <td>
                                        <input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="{{ $custom_contentstory->id }}"> 
                                        
                                       
                                </td> -->
                                <td>
                                        {{ $custom_contentstory->id }}
                                        
                                       
                                </td>
                                <td>
                                        {{ $custom_contentstory->title }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.content.custom_contentstory.edit', [$custom_contentstory->id]) }}">
                                        {{ $custom_contentstory->created_at }}
                                    </a>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.content.custom_contentstory.edit', [$custom_contentstory->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
                                        <button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.content.custom_contentstory.destroy', [$custom_contentstory->id]) }}"><i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                               <!-- <th data-sortable="false"></th> -->
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Title') }}</th>
                                
                                <th>{{ trans('core::core.table.created at') }}</th>
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
        <dd>{{ trans('content::custom_contentstories.title.create custom_contentstory') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.content.custom_contentstory.create') ?>" }
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
                "order": [[ 0, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
        var flag=0;
        $position=<?php echo $position ?>;
        
        for(var i=2;i<11;i++)
        {
            if(i==$position)
            {
                 $('select>option:eq('+(i-2)+')').prop('selected', true);
                 break;
            }
         
        }
       
        $( "select" ).change( changeorder );
        

        function  changeorder() {          
            var position=$(this).val();
            $.ajax({
            type: 'POST',
            data: {position: position},
            url: '{{ env('APP_URL') }}/set_positions',
            success: function(result){              
                

            }
        })      

        }
    </script>
@stop

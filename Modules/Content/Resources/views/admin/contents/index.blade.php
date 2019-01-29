@extends('layouts.master')

@section('content-header')
    <h1>
        {{ trans('content::contents.title.contents') }}
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a></li>
        <li class="active">{{ trans('content::contents.title.contents') }}</li>

    </ol>

@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
        <button type="button" class="btn btn-primary btn-flat" id="deleteStory" hidden="hide" style="display: none;float: left;"> DELETE</button>
        <?php if (env('STORY_PUSH_ENABLE')) { ?>
	        <button type="button" class="btn btn-primary btn-flat" id="pushToProduction" hidden="hide" style="margin-left:10px; display: none;float: left;">
	        	Push To Production Instance
	        </button>
	    <?php } ?>
		<div id="expiryDate" style="margin-left:20px; display:none; float:left; border: 1px #000 solid; padding: 2px;">
			<label class="pull-left" style="margin:5px;">Expiry Date</label>
			<div id="returnrange" class="pull-left" style="margin-left:20px;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
				<input type="hidden" name="expiry_date" id="expiry_date"  value="" >
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
				<span></span><b class="caret"></b>
			</div>

			<button type="button" class="btn btn-primary btn-flat" id="expiryDateBtn" hidden="hide" style="margin-left: 10px;">
				Extend Expiry Date
			</button>
		</div>
            <div class="row">

                <!--  <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.crawl.crawlcontent.index') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('Crawl content') }}
                    </a>
                </div> -->


                <div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
                    <a href="{{ route('admin.content.content.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
                        <i class="fa fa-pencil"></i> {{ trans('content::contents.button.create content') }}
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
                                <th data-sortable="false"><input type="checkbox"  id="select_all"/></th>
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Title') }}</th>
                                <th>{{ trans('Category') }}</th>
                                <th>{{ trans('Created At') }}</th>
                                <th>{{ trans('Expired At') }}</th>
                                <th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if (isset($contents)): ?>

							<?php
								foreach ($contents as $content):
									$all_category = "";
									if ($content->all_category) {
										$all_categories =json_decode($content->all_category);

										foreach ($all_categories as $value) {
											foreach ($categories as $category) {
												if($category->id == $value)
													$all_category = $all_category . "" . $category->name . ", ";
											}
										}
									} else {
										foreach ($categories as $category) {
											if($category->id ==$content->category_id)
												$all_category =$all_category."".$category->name.", ";
										}
									}

									$all_category =rtrim($all_category,', ');
							?>
							<tr>
								<td>
									<input class ="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="{{ $content->id }}">
								</td>
								<td>
									{{ $content->id }}
								</td>
								<td>
									{{ $content->title }}
								</td>
								<td>
									{{ $all_category }}
								</td>
								<td>
									<a href ="{{ route('admin.content.content.edit', [$content->id]) }}">
										{{ $content->created_at }}
									</a>
								</td>
								<td>
									<a href ="{{ route('admin.content.content.edit', [$content->id]) }}">
										{{ $content->expiry_date }}
									</a>
								</td>
								<td>
									<div class ="btn-group">
										<a href ="{{ route('admin.content.content.edit', [$content->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
										<button class ="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.content.content.destroy', [$content->id]) }}"><i class="fa fa-trash"></i></button>
									</div>
								</td>
							</tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th><input type="checkbox"  id="select_all_footer"/></th>
                                <th>{{ trans('ID') }}</th>
                                <th>{{ trans('Title') }}</th>
                                <th>{{ trans('Category') }}</th>
                                <th>{{ trans('Created At') }}</th>
                                <th>{{ trans('Expired At') }}</th>
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
        <dd>{{ trans('content::contents.title.create content') }}</dd>
    </dl>
@stop

@section('scripts')
    <script type="text/javascript">
        $( document ).ready(function() {
			$('input:checkbox').removeAttr('checked');

            $(document).keypressAction({
                actions: [
                    { key: 'c', route: "<?= route('admin.content.content.create') ?>" }
                ]
            });

			dateRangePickerFunctions();
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
                "order": [[ 1, "desc" ]],
                "language": {
                    "url": '<?php echo Module::asset("core:js/vendor/datatables/{$locale}.json") ?>'
                }
            });
        });
    </script>

	<script type ="text/javascript">
		checkedArray = [];

		$("#select_all,#select_all_footer").change(function() {
			var status = this.checked; 
			$("#deleteStory, #pushToProduction, #expiryDate").show();
			if (status) {
				$('.checkbox').each(function() {
					this.checked = status;
					checkedArray.push(this.value);
					console.log(checkedArray);
				});
			} else {
				$('.checkbox').each(function() {
					this.checked = status;
					var a = checkedArray.indexOf(this.value);
					checkedArray.splice(a, 1);
					// console.log(checkedArray);
				});
			}

			if(!checkedArray.length)
				$("#deleteStory, #pushToProduction, #expiryDate").hide();
		});


		function changed(event)
		{
			$("#deleteStory, #pushToProduction, #expiryDate").show();
			if (event.checked) {
				checkedArray.push(event.value);
			} else {
				var a = checkedArray.indexOf(event.value);
				checkedArray.splice(a, 1);
			}

			if(!checkedArray.length)
				$("#deleteStory, #pushToProduction, #expiryDate").hide();
				console.log(checkedArray.length);
				console.log(checkedArray);
		}

	$('#deleteStory').click(function() {
		$.ajax({
			type: 'POST',
			data: {data: checkedArray},
			url: '{{ env('APP_URL') }}/contents/delete_story',
			success: function(result) {
				$("#deleteStory, #expiryDate").hide();
				location.reload();
			}
		});
	});

	$('#pushToProduction').click(function() {
		if (!confirm("Are you sure, You want to move the selected stories to Production Instance?")) {
			return false;
		}

		$.ajax({
			type: 'POST',
			data: {data: checkedArray},
			url: '{{ env('APP_URL') }}/contents/push_story_to_prod',
			success: function(result) {
				$("#pushToProduction, #expiryDate").hide();
				alert('Selected Stories are moved to Production Instance');
				location.reload();
			}
		});
	});

	$('#expiryDateBtn').click(function() {
		if (!confirm("Are you sure, You want to change the Expiry Date of selected stories?")) {
			return false;
		}

		$.ajax({
			type: 'POST',
			data: {id: checkedArray, date: $('#expiry_date').val()},
			url: '{{ env('APP_URL') }}/contents/update_expiry_date',
			success: function(result) {
				alert(result);
				location.reload();
			},
			error: function (error) {
				alert('Somethiing went Wrong, Please Try Again');
			}
		});
	});
</script>
@stop

<script type="text/javascript" src="{{ Module::asset('content:js/datepicker.js') }}?rv={{ env('RV') }}"></script>

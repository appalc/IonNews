<?php 
use \Illuminate\Support\Arr;
?>

@extends('layouts.master')

@section('content-header')
	<h1>
		{{ trans('content::contents.title.contents') }}
	</h1>
	<ol class="breadcrumb">
		<li>
			<a href="{{ route('dashboard.index') }}"><i class="fa fa-dashboard"></i> {{ trans('core::core.breadcrumb.home') }}</a>
		</li>
		<li class="active">{{ trans('content::contents.title.contents') }}</li>
	</ol>
@stop

@section('content')
	<div class="row">
		<div class="col-xs-12">
			<div class="row">
				<div class="col-xs-6">
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
				</div>

				<div class="col-xs-6 pull-right">
					<div class="btn-group pull-right" style="margin: 0 15px 15px 0;">
						<a href="{{ route('admin.content.content.create') }}" class="btn btn-primary btn-flat" style="padding: 4px 10px;">
							<i class="fa fa-pencil"></i> {{ trans('content::contents.button.create content') }}
						</a>
					</div>
				</div>
			</div>

			<div class="box box-primary">
				<div class="box-header" style="padding:0;">
					<div class="col-md-6 text-left" style="padding: 1.5%;">
						<label> Show 
							<select class="input-sm">
								<?php
								foreach ([10, 25, 50, 100] as $count) {
									$redirctLoc = route('admin.content.content.index', ['count' => $count]);
									$selected   = ($recordCount == $count) ? "selected" : '';
								?>
								<option onclick="location.href='{{ $redirctLoc }}'" value="{{ $count }}" {{ $selected }} >
									{{ $count }}
								</option>
								<?php } ?>
							</select> records
						</label>
					</div>

					<div class="col-md-6 text-right">
						{{ $stories->render() }}
					</div>
				</div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="data-table table table-bordered table-hover">
                            <thead>
								<tr>
									<th data-sortable="false"><input type="checkbox" id="select_all"/></th>
									<th>{{ trans('ID') }}</th>
									<th>{{ trans('Title') }}</th>
									<th>{{ trans('Category') }}</th>
									<th>{{ trans('Created At') }}</th>
									<th>{{ 'Expire At' }}</th>
									<th data-sortable="false">{{ trans('core::core.table.actions') }}</th>
								</tr>
                            </thead>
                            <tbody>
                            <?php if (isset($stories)): ?>

							<?php
								foreach ($stories as $content):
									$selectedCategory = '';
									if ($content->all_category) {
										foreach (json_decode($content->all_category) as $value) {
											$selectedCategory = $selectedCategory . '' . Arr::get($categories, $value) . ', ';
										}
									}
							?>
							<tr>
								<td>
									<input class="checkbox" type="checkbox" onchange="changed(this);" name="check[]" value="{{ $content->id }}">
								</td>
								<td> {{ $content->id }} </td>
								<td> {{ $content->title }} </td>
								<td> {{ rtrim($selectedCategory, ', ') }} </td>
								<td>
									<a href="{{ route('admin.content.content.edit', [$content->id]) }}"> {{ $content->created_at }} </a>
								</td>
								<td> {{ $content->expiry_date }} </td>
								<td>
									<div class="btn-group">
										<a href="{{ route('admin.content.content.edit', [$content->id]) }}" class="btn btn-default btn-flat"><i class="fa fa-pencil"></i></a>
										<button class="btn btn-danger btn-flat" data-toggle="modal" data-target="#modal-delete-confirmation" data-action-target="{{ route('admin.content.content.destroy', [$content->id]) }}"><i class="fa fa-trash"></i></button>
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
                                <th>{{ 'Expire At' }}</th>
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
				$("#deleteStory").hide(); 
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
				$("#pushToProduction").hide();
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
			}
		});
	});
</script>
@stop

<script type="text/javascript" src="{{ Module::asset('content:js/datepicker.js') }}?rv={{ env('RV') }}"></script>

@extends('layouts.app')

@section('template_title')
  Create New Style
@endsection

@section('template_fastload_css')
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            Create New Style

            <a href="/styles" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Styles</span>
            </a>

          </div>
          <div class="panel-body">

            {!! Form::open(array('action' => 'StylesManagementController@store', 'method' => 'POST', 'role' => 'form')) !!}

              {!! csrf_field() !!}

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Style Name', array('class' => 'col-md-3 control-label')) !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'style name')) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-gear }}" aria-hidden="true"></i></label>
                  </div>
                  @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                  @endif
                </div>
              </div>

              {!! Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' . trans('Create New Style'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )) !!}

            {!! Form::close() !!}

          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('footer_scripts')
@endsection
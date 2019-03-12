@extends('layouts.app')

@section('template_title')
  Editing Style
@endsection

@section('template_linked_css')
  <style type="text/css">
    .btn-save,
    .pw-change-container {
      display: none;
    }
  </style>
@endsection

@section('content')

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            <strong>Editing Style:</strong> {{ $style->name }}

            <a href="/styles" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Styles</span>
            </a>

            <!--<a href="/users/{{$style->id}}" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
             Back  <span class="hidden-xs">to User</span>
            </a>

            <a href="/users" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to </span>Users
            </a>-->

          </div>

          {!! Form::model($style, array('action' => array('StylesManagementController@update', $style->id), 'method' => 'PUT')) !!}

            {!! csrf_field() !!}

            <div class="panel-body">

              <div class="form-group has-feedback row {{ $errors->has('name') ? ' has-error ' : '' }}">
                {!! Form::label('name', 'Style Name' , array('class' => 'col-md-3 control-label')) !!}
                <div class="col-md-9">
                  <div class="input-group">
                    {!! Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'style name')) !!}
                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-gear }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>



            </div>
            <div class="panel-footer">

              <div class="row">

                {{--<div class="col-xs-6">--}}
                  {{--<a href="#" class="btn btn-default btn-block margin-bottom-1 btn-change-pw" title="Change Password">--}}
                    {{--<i class="fa fa-fw fa-lock" aria-hidden="true"></i>--}}
                    {{--<span></span> Change Password--}}
                  {{--</a>--}}
                {{--</div>--}}

                <div class="col-xs-12">
                  {!! Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))) !!}
                </div>
              </div>
            </div>

          {!! Form::close() !!}

        </div>
      </div>
    </div>
  </div>

  @include('modals.modal-save')
  @include('modals.modal-delete')

@endsection

@section('footer_scripts')

  @include('scripts.delete-modal-script')
  @include('scripts.save-modal-script')
  @include('scripts.check-changed')

@endsection
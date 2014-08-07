@extends('admin._layouts.default')
 
@section('main')
{{ Form::open(array('route' => 'admin.articles.store', 'files' => true)) }}
                <div class="control-group">
                        {{ Form::label('title', 'Title') }}
                        <div class="controls">
                                {{ Form::text('title') }}
                        </div>
                </div>
 
                <div class="control-group">
                        {{ Form::label('body', 'Content') }}
                        <div class="controls">
                                {{ Form::textarea('body') }}
                        </div>
                </div>


                <div class="control-group">
				    {{ Form::label('image', 'Image') }}
				 
				    <div class="fileupload fileupload-new" data-provides="fileupload">
				        <div class="fileupload-preview thumbnail" style="width: 200px; height: 150px;">
				            <img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image">
				        </div>
				        <div>
				            <span class="btn btn-file"><span class="fileupload-new">Select image</span><span class="fileupload-exists">Change</span>{{ Form::file('image') }}</span>
				        </div>
				    </div>
				</div>
 
                <div class="form-actions">
                        {{ Form::submit('Save', array('class' => 'btn btn-success btn-save btn-large')) }}
                        <a href="{{ URL::route('admin.pages.index') }}" class="btn btn-large">Cancel</a>
                </div>
 
        {{ Form::close() }}

@stop
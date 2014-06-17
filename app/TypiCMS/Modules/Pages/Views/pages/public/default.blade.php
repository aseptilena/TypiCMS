@extends('pages.public.master')

@section('page')

    <div class="row">

        @if($children)
        <div class="col-sm-4">
            <ul class="list-main nested sortable">
            @foreach ($children as $model)
                @include('pages.public._listItem', array('model' => $model))
            @endforeach
            </ul>
        </div>
        @endif


        <div class="col-sm-8">
            {{ $model->body }}
        </div>

    </div>

@stop
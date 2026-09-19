@extends('pages::layouts.app')

@section('content')
    <div class="result-container panel-side shadow">
        <div class="res-h res-h-new">
            <h1>{{ $data['category']['title'] }}</h1>
        </div>
        <div class="content-news">
            {!! !empty($configData) ? \App\Helpers\Helpers::renderCode($configData, 'policy') : '' !!}
        </div>
    </div>
@endsection

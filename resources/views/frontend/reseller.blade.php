@extends('frontend.layouts.template',['menu'=>"reseller"])
@section('content')
    <style>

    </style>
    @foreach($faqs as $faq)
        <div class="news-section-container">
            <?= $faq->contents ?>
        </div>
    @endforeach
@endsection

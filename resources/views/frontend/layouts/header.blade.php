<?php
$menu=isset($menu) ? $menu : "";
?>
<div class="header">
    <div class="header-items-container">
        <div class="header-item-wrapper {{$menu=='news' ? 'active' : ''}}">
            <a href="{{url('/news')}}">News</a>
        </div>
        <div class="header-item-wrapper {{$menu=='faq' ? 'active' : ''}}">
            <a href="{{url('/faq')}}">Support</a>
        </div>
        <div class="header-item-wrapper {{$menu=='instruction' ? 'active' : ''}}">
            <a href="{{url('/instruction')}}">Instruction</a>
        </div>
        <div class="header-item-wrapper {{$menu=='mylist' ? 'active' : ''}}">
            <a href="{{url('/device/playlists')}}">Manage PlayLists</a>
        </div>
        <div class="header-item-wrapper {{$menu=='reseller' ? 'active' : ''}}">
            <a href="{{url('/become-a-reseller')}}">Become a Reseller</a>
        </div>

    </div>
</div>

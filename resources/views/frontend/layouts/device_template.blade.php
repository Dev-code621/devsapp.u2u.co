<!DOCTYPE html>
<html>
    @include('frontend.layouts.head')
    <?php
        $playlist_id=session('playlist_id');
        $playlist=App\Model\PlayList::find($playlist_id);
        $playlist->urls=$playlist->PlayListUrls;
    ?>
    <body>
        <div class="container">
            <div id="device-page-container">
                <div id="menus-container">
                    <div class="device-page-menu {{$menu=='playlists' ? 'active' : ''}}">
                        <a href="{{url('device/playlists')}}">
                            Manage Playlists
                        </a>
                    </div>
                    <div class="device-page-menu {{$menu=='activation' ? 'active' : ''}}">
                        <a href="{{url('device/activation')}}">
                            Activate Device
                        </a>
                    </div>
                    <div id="device-page-logout-menu">
                        <a href="{{url('device/logout')}}">
                            <i class="fa fa-sign-out">Log out</i>
                        </a>
                    </div>
                </div>
                <div class="content-container" id="device-page-content-container">
                    <div class="device-info-container">
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Mac Address:</div>
                            <div class="device-info-value">{{$playlist->mac_address}}</div>
                        </div>
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Status:</div>
                            <div class="device-info-value">Active</div>
                        </div>
                        <div class="device-info-item-wrapper">
                            <div class="device-info-label">Expiration:</div>
                            <div class="device-info-value">{{$playlist->expire_date}}</div>
                        </div>
                    </div>
                    @yield('content')
                </div>
            </div>
            @include('frontend.layouts.footer')
            @yield('script')
        </div>
        <script src="{{asset('/admin/js/notify.js')}}"></script>
        <script>
            function showSuccessNotify(message) {
                $.notify(message,
                    {
                        className:'success',
                        position:'right 100px',
                    }
                );
            }
            function showErrorNotify(message) {
                $.notify(message,
                    {
                        className:'error',
                        position:'right 100px',
                    }
                );
            }
            $(document).ready(function () {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                'use strict';
            })
        </script>
        <script src="{{asset('frontend/playlist.js')}}"></script>


    </body>
</html>

<?php $__env->startSection('content'); ?>
    <div id="playlist-table">
        <div class="playlist-manage-btns-container mb-10">
            <button class="btn btn-outline-dark playlist-manage-btn add_playlist_btn">
                <i class="fa fa-plus playlist-manage-btn-icon"></i>
                <span class="playlist-manage-btn-text">Add Playlist</span>
            </button>
        </div>
        <table class="table">
            <thead class="table-dark">
                <tr>
                    <td class="text-center">Playlist Name</td>
                    <td class="text-center">Url</td>
                    <td class="text-center">Action</td>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $playlist->urls; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-center"><?php echo e($url->name); ?></td>
                    <td class="text-center"><?php echo e($url->is_protected==1 ? 'This playlist is protected' : $url->url); ?></td>
                    <td class="text-center">
                        <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-edit" data-current_id="<?php echo e($url->id); ?>" data-protected="<?php echo e($url->is_protected); ?>">
                            <i class="fa fa-edit playlist-icon"></i>
                            <span class="playlist-action-btn-txt">Edit</span>
                        </button>
                        <button class="playlist-action-btn-wrapper btn btn-outline-dark playlist-url-delete" data-current_id="<?php echo e($url->id); ?>" data-protected="<?php echo e($url->is_protected); ?>">
                            <i class="fa fa-trash playlist-icon"></i>
                            <span class="playlist-action-btn-txt">Delete</span>
                        </button>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <div class="modal fade playlist-modal" id="general-list-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-title" style="margin:0 auto">

                            <button class="btn btn-outline-dark playlist-manage-btn showXtream">                                
                                <span class="playlist-manage-btn-text">Xtream-codes infos</span>                                
                            </button>
                                    
                            <button class="btn btn-outline-dark playlist-manage-btn showPlaylist">                                
                                <span class="playlist-manage-btn-text">Playlist link</span>                                
                            </button>

                        </div>
                    </div>
                    <div class="modal-body" id="xtreamcode">                      
                        
                        <form class="form-horizontal">
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="schema">Schema *</label>
                                <div class="col-sm-10">
                                <select name="init" class="form-control" id="schema" style="width: inherit;">                                    
                                    <option value="https" selected="true">https</option>
                                    <option value="http">http</option>
                                </select>
                                </div>
                            </div>
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="hostip">Host/IP *</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="hostip" placeholder="" style="width: inherit;">
                                </div>
                            </div>
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="output">port n*</label>
                                <div class="col-sm-10">
                                <input type="output" class="form-control" id="port" placeholder="" style="width: inherit;">
                                </div>
                            </div>
                            
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="username">username*</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" placeholder="" style="width: inherit;">
                                </div>
                            </div>
                            
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="pwd">password*</label>
                                <div class="col-sm-10">
                                <input type="password" class="form-control" id="pwd" placeholder="" style="width: inherit;">
                                </div>
                            </div>
                            <div class="form-inline">
                                <label class="control-label col-sm-2" for="output">output*</label>
                                <div class="col-sm-10">
                                <select name="init" class="form-control" id="output" style="width: inherit;">                                    
                                    <option value="mpegts" selected="true">mpegts</option>
                                    <option value="hls">hls</option>
                                </select>
                                </div>
                            </div>
                            
                            <div class="container-fluid form-group">
                                <label class="playlist-label">Playlist name</label>
                                <input class="form-control" id="general-playlist-name1">
                                <div class="invalid-feedback">
                                    Playlist name is required
                                </div>
                            </div>
                            <div class="container-fluid form-group">
                                <input type="checkbox" id="playlist-protect1" onchange="changeGeneralPlaylistProtect1()">
                                <label for="playlist-protect1">Protect this playlist</label><br>
                                <div class="protect-note">
                                    Protected playlists will not be viewed or modified without entering PIN
                                </div>
                            </div>
                            <div class="container-fluid row">
                                <div class="col-6">
                                    <label>PIN</label>
                                    <input class="form-control" type="password" id="pin1" autocomplete="new-password" disabled>
                                    <div class="invalid-feedback">
                                        Playlist name is required
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Confirm PIN</label>
                                    <input class="form-control" type="password" id="pin1-confirm" disabled>
                                </div>
                            </div>                            
                        </form>
                    </div>


                    <div class="modal-body" id="playlistbtn">                      
                        
                        <div class="p-10">
                            <div class="form-group">
                                <label class="playlist-label">Playlist name</label>
                                <input class="form-control" id="general-playlist-name2">
                                <div class="invalid-feedback">
                                    Playlist name is required
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="playlist-label">Playlist Url(.M3U or .M3u8)</label>
                                <textarea class="form-control" id="general-playlist-url"></textarea>
                                <div class="invalid-feedback">
                                    Playlist url is required
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="checkbox" id="playlist-protect2" onchange="changeGeneralPlaylistProtect2()">
                                <label for="playlist-protect2">Protect this playlist</label><br>
                                <div class="protect-note">
                                    Protected playlists will not be viewed or modified without entering PIN
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label>PIN</label>
                                    <input class="form-control" type="password" id="pin2" autocomplete="new-password" disabled>
                                    <div class="invalid-feedback">
                                        Playlist name is required
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label>Confirm PIN</label>
                                    <input class="form-control" type="password" id="pin2-confirm" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success playlist-modal-btn" onclick="savePlaylist()">Save</button>
                        <button class="btn btn-danger playlist-modal-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade playlist-modal" id="pin-confirm-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="playlist-modal-title">Protected Playlist</div>
                        <div class="p-10">
                            <div class="form-group">
                                <label class="playlist-label">Pin code</label>
                                <input class="form-control" type="password" id="pin-confirm-input">
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success playlist-modal-btn" onclick="checkPincode()">OK</button>
                        <button class="btn btn-danger playlist-modal-btn" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade playlist-modal" id="delete-confirm-modal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="playlist-modal-title">Delete Playlist</div>
                        <div id="delete-confirm-title"></div>
                        <div class="p-10" id="delete-pin-code-wrapper">
                            <div class="form-group">
                                <label class="playlist-label">Pin code</label>
                                <input class="form-control" type="password" id="delete-pin-confirm-input">
                                <div class="invalid-feedback">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success playlist-modal-btn" id="delete_playlist">OK</button>
                        <button class="btn btn-danger playlist-modal-btn" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="device-page-loading-overlay">
            <img src="<?php echo e(asset('images/WaitLoaderBlue.gif')); ?>">
        </div>
    </div>
    <div>
        <script>
            var site_url="<?= url('/')?>";
        </script>

        <script>
            $(document).ready(function () {
                // $('#xtreamcode').css('display', 'none');
                // $('#playlistbtn').css('display', 'none');
            });
        </script>

    </div>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('frontend.layouts.device_template',['menu'=>"playlists","title"=>$title,"keyword"=>$keyword,"description"=>$description], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\New folder\devsapp.u2u.co.2021-09-11_03_00_21\devsapp.u2u.co\resources\views/frontend/device_playlist.blade.php ENDPATH**/ ?>
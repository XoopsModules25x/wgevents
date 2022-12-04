<{include file='db:wgevents_header.tpl'}>

<div id='imghandler' class='col-xs-12 col-sm-12'>
    <ul class='nav nav-tabs'>
        <li class='active'><a id='navtab_main' href='#1' data-toggle='tab'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CURRENT}></a></li>
        <li><a id='navtab_exist' href='#2' data-toggle='tab'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_USE_EXISTING}></a></li>
        <li><a id='navtab_grid' href='#3' data-toggle='tab'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID}></a></li>
        <li><a id='navtab_crop' href='#4' data-toggle='tab'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP}></a></li>
        <li><a id='navtab_upload' href='#5' data-toggle='tab'><{$smarty.const._MA_WGEVENTS_FORM_UPLOAD_IMG}></a></li>
    </ul>

    <div class='tab-content '>
        <!-- *************** Basic Tab ***************-->
        <div class='tab-pane active center' id='1'>
            <img id='currentImg' class='img-responsive imageeditor-img center' src='<{$imgCurrent.src|default:''}>' alt='<{$imgCurrent.img_name|default:''}>'>
            <p><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CURRENT2}>: <{$image_path|default:''}><br>
                <{$smarty.const._MA_WGEVENTS_IMG_EDITOR_RESXY}>: <{$albimage_width|default:''}> / <{$albimage_height|default:''}></p>
            <input type='button' class='btn <{$btn_style|default:''}>' value='<{$smarty.const._CANCEL}>' onclick='history.go(-1);return true;'>
        </div>

        <!-- *************** Tab for select image of albums ***************-->
        <div class='tab-pane' id='2'>
            <div class='col-xs-12 col-sm-6'>
                <{foreach item=image from=$images name=fe_image}>
                <{if $image.group|default:false}><h4 class='modal-title'><{$image.group}></h4><{/if}>
                <div class='imageeditor-selimages col-xs-12 col-sm-4'>
                    <input id='<{$image.name}>_image' class='imgSelect1 img-responsive imageeditor-img <{if $image.selected|default:false}>imageeditor-modal-selected<{/if}>' type='image' src='<{$image.src}>' alt='<{$image.title}>' style='padding:3px;' value='<{$image.name}>'>
                </div>
                <{if $smarty.foreach.fe_image.iteration % 3 == 0}>
                <div class='clear'></div>
                <{/if}>
                <{/foreach}>

            </div>
            <div class='col-xs-12 col-sm-6'>
                <h5>&nbsp;</h5>
                <img id='ImageSelected' class='img-responsive imageeditor-img' src='<{$imgCurrent.src|default:''}>' alt='<{$imgCurrent.img_name|default:''}>'>
            </div>
            <div class='col-xs-12 col-sm-12 center'>
                <form class='form-horizontal' name='form' id='form_selectimage' action='image_editor.php' method='post' enctype='multipart/form-data'>
                    <input type='hidden' name='<{$imageOrigin|default:''}>' id='<{$imageOrigin|default:''}>' value='<{$imageId|default:0}>'>
                    <input type='hidden' name='image_id' id='image_id' value='<{$imgCurrent.img_name|default:''}>'>
                    <input type='hidden' name='op' id='op' value='saveImageSelected'>
                    <input type="submit" class="btn <{$btn_style|default:''}> disabled" name="btnApplySelected" id="btnApplySelected" value="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_APPLY}>">
                    <input type='button' class='btn <{$btn_style|default:''}>' value='<{$smarty.const._CANCEL}>' onclick='history.go(-1);return true;'>
                </form>
            </div>
        </div>

        <!-- *************** Tab for image grid ***************-->
        <div class='tab-pane' id='3'>
            <div class='col-xs-12 col-sm-12'>
                <label class='radio-inline'><input type='radio' name='gridtype' id='alb_imgcat1' title='<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID4}>' value='1' checked onchange='changeGridSetting(this)'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID4}>&nbsp;</label>
                <label class='radio-inline'><input type='radio' name='gridtype' id='alb_imgcat2' title='<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID6}>' value='2' onchange='changeGridSetting(this)'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID6}>&nbsp;</label>
            </div>
            <div class='col-xs-12 col-sm-4'>
                <button id='btnGridAdd1' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker1'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC1}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid1' id='imageGrid1' alt='imageGrid1' style='margin:5px;max-width:75px'>
                <br>
                <button id='btnGridAdd2' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker2'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC2}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid2' id='imageGrid2' alt='imageGrid2' style='margin:5px;max-width:75px'>
                <br>
                <button id='btnGridAdd3' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker3'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC3}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid3' id='imageGrid3' alt='imageGrid3' style='margin:5px;max-width:75px'>
                <br>
                <button id='btnGridAdd4' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker4'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC4}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid4' id='imageGrid4' alt='imageGrid4' style='margin:5px;max-width:75px'>
                <br>
                <button id='btnGridAdd5' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker5' disabled='disabled'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC5}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid5' id='imageGrid5' alt='imageGrid5' style='margin:5px;max-width:75px'>
                <br>
                <button id='btnGridAdd6' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px' data-toggle='modal' data-target='#myModalImagePicker6' disabled='disabled'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID_SRC6}></button>
                <img src='<{$wgevents_upload_image_url}>blank.gif' name='imageGrid6' id='imageGrid6' alt='imageGrid6' style='margin:5px;max-width:75px'>
            </div>
            <div class='col-xs-12 col-sm-8'>
                <img id='gridImg' class='img-responsive' src='<{$wgevents_upload_image_url}>blank.gif' alt='<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID}>'>
            </div>
            <div class='col-xs-12 col-sm-12 center'>
                <button id='btnCreateGrid4' type='button' class='btn <{$btn_style|default:''}>' style='display:inline;margin:5px'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CREATE}></button>
                <button id='btnCreateGrid6' type='button' class='btn <{$btn_style|default:''}> hidden' style='display:inline;margin:5px'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CREATE}></button>
                <form class='form-horizontal' name='form' id='form_imagegrid' action='image_editor.php' method='post' enctype='multipart/form-data'>
                    <input type='hidden' name='op' value='saveGrid'>
                    <input type='hidden' name='gridImgFinal' id='gridImgFinal' value=''>
                    <input type='hidden' name='<{$imageOrigin|default:''}>' value='<{$imageId|default:0}>'>
                    <input type="submit" class="btn <{$btn_style|default:''}> disabled" name="btnApplyGrid" id="btnApplyGrid" value="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_APPLY}>">
                    <input type='button' class='btn <{$btn_style|default:''}>' value='<{$smarty.const._CANCEL}>' onclick='history.go(-1);return true;'>
                </form>
            </div>
        </div>

        <!-- ***************Tab for crop image ***************-->
        <div class='tab-pane center' id='4' >
            <input type='hidden' id='imageIdCrop' name='imageIdCrop' value='<{$imageId|default:0}>'>
            <input type='hidden' id='imageOriginCrop' name='$imageOriginCrop' value='<{$imageOrigin|default:''}>'>
            <!-- Content -->
            <div class="container-crop">
                <div class="row">
                    <div class="img-container">
                        <img id='cropImg' class="img-responsive" src="<{$imgCurrent.src|default:''}>" alt="<{$imgCurrent.img_name|default:''}>'">
                    </div>
                </div>
            </div>
            <div class="row" id="actions">
                <div class="col-md-12 docs-toggles">
                    <!-- <h3>Toggles:</h3> -->
                    <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
                        <label class="btn imageeditor-btn-crop <{$btn_style|default:''}>">
                            <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO}>: 16 / 9">16:9</span>
                        </label>
                        <label class="btn imageeditor-btn-crop <{$btn_style|default:''}> active">
                            <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO}>: 4 / 3">4:3</span>
                        </label>
                        <label class="btn imageeditor-btn-crop <{$btn_style|default:''}>">
                            <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO}>: 1 / 1">1:1</span>
                        </label>
                        <label class="btn imageeditor-btn-crop <{$btn_style|default:''}>">
                            <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO}>: 2 / 3">2:3</span>
                        </label>
                        <label class="btn imageeditor-btn-crop <{$btn_style|default:''}>">
                            <input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO}>: NaN"><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ASPECTRATIO_FREE}></span>
                        </label>
                    </div>
                </div><!-- /.docs-toggles -->
                <div class="col-md-12 docs-buttons">
                    <!-- <h3>Toolbar:</h3> -->
                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="setDragMode" data-option="move" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE}>"><span class="fa fa-arrows"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="setDragMode" data-option="crop" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP}>">
                                <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP}>"><span class="fa fa-crop"></span>
                                </span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="zoom" data-option="0.1" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMIN}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMIN}>"><span class="fa fa-search-plus"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="zoom" data-option="-0.1" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMOUT}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ZOOMOUT}>"><span class="fa fa-search-minus"></span></span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="move" data-option="-10" data-second-option="0" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_LEFT}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_LEFT}>"><span class="fa fa-arrow-left"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="move" data-option="10" data-second-option="0" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_RIGHT}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_RIGHT}>"><span class="fa fa-arrow-right"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="move" data-option="0" data-second-option="-10" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_UP}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_UP}>"><span class="fa fa-arrow-up"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="move" data-option="0" data-second-option="10" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_DOWN}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_MOVE_DOWN}>"><span class="fa fa-arrow-down"></span></span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="rotate" data-option="-45" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_LEFT}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_LEFT}>"><span class="fa fa-rotate-left"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="rotate" data-option="45" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_RIGHT}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_ROTATE_RIGHT}>"><span class="fa fa-rotate-right"></span></span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="scaleX" data-option="-1" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_HORIZONTAL}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_HORIZONTAL}>"><span class="fa fa-arrows-h"></span></span>
                        </button>
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="scaleY" data-option="-1" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_VERTICAL}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CROP_FLIP_VERTICAL}>"><span class="fa fa-arrows-v"></span></span>
                        </button>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="reset" title="<{$smarty.const._RESET}>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._RESET}>"><span class="fa fa-refresh"></span></span>
                        </button>
                    </div>
                    <div class="btn-group">
                        <label class="btn <{$btn_style|default:''}> btn-upload" for="inputImage" title="<{$smarty.const._UPLOAD}>">
                            <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._UPLOAD}>"><span class="fa fa-upload"></span></span>
                        </label>
                    </div>

                    <div class="btn-group-horizontal btn-group-crop col-xs-12 col-sm-12">
                        <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096, &quot;save&quot;: 0 }">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._PREVIEW}>"><{$smarty.const._PREVIEW}></span>
                        </button>
                        <button id="btnCropCreate" type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096, &quot;save&quot;: 1 }">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CREATE}>"><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_CREATE}></span>
                        </button>
                        <a class="btn <{$btn_style|default:''}> disabled" id="btnCropApply" href="<{$wgevents_image_editor}>/image_editor.php?op=saveCrop&<{$imageOrigin|default:''}>=<{$imageId|default:0}>&target=<{$croptarget|default:''}>&start=<{$start|default:0}>&limit=<{$limit|default:0}>"> <{$smarty.const._MA_WGEVENTS_IMG_EDITOR_APPLY}></a>
                        <button type="button" class="btn btn-crop <{$btn_style|default:''}>"onclick='history.go(-1);return true;'><{$smarty.const._CANCEL}></button>
                    </div>

                    <!-- Show the cropped image in modal -->
                    <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="getCroppedCanvasTitle"><{$smarty.const._PREVIEW}></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="<{$smarty.const._CLOSE}>">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body"></div>
                                <div class="modal-footer">
                                    <button type="button" class="btn imageeditor-btn-crop <{$btn_style|default:''}>" data-dismiss="modal"><{$smarty.const._CLOSE}></button>
                                    <a class="btn <{$btn_style|default:''}> hidden" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
                                </div>
                            </div>
                        </div>
                    </div><!-- /.modal -->
                </div><!-- /.docs-buttons -->
            </div>
        </div>

        <!-- ***************Tab for upload image ***************-->
        <div class='tab-pane' id='5'>
            <{$form_uploadimage}>
        </div>
    </div>
</div>

<div class='clear'>&nbsp;</div>

<!-- Create Modals -->
<{foreach item=m from=$nbModals}>
    <div class='modal fade' id='myModalImagePicker<{$m}>' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
        <div class='modal-dialog wgg-modal-dialog' role='document'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal'
                            aria-label='Close'><span aria-hidden='true'>&times;</span>
                    </button>
                    <h4 class='modal-title' id='myModalLabel'><{$smarty.const._MA_WGEVENTS_IMG_EDITOR_GRID}></h4>
                </div>
                <div class='modal-body'>
                    <{foreach item=image from=$images}>
                    <{if $image.group|default:false}><h4 class='modal-title'><{$image.group}></h4><{/if}>
                <input class='imgGrid<{$m}>' type='image' src='<{$image.src}>' alt='<{$image.name}>'
                       style='padding:3px;max-height:150px;max-width:200px' value='<{$image.name}>' onclick='selectGridImage(this, <{$m}>)'>
                    <{/foreach}>
                </div>
            </div>
        </div>
    </div>
    <{/foreach }>
<!-- end of modals -->

<script type='application/javascript'>
    function changeGridSetting(element) {
        $('#btnGridAdd5').attr('disabled', 'disabled');
        $('#btnGridAdd6').attr('disabled', 'disabled');
        $('#btnCreateGrid4').addClass('hidden');
        $('#btnCreateGrid6').addClass('hidden');

        if (element.value == 1) {
            $('#btnCreateGrid4').removeClass('hidden');
        } else {
            $('#btnGridAdd5').removeAttr('disabled');
            $('#btnGridAdd6').removeAttr('disabled');
            $('#btnCreateGrid6').removeClass('hidden');
        }
    }

    $('#btnCropCreate').click(function () {
        $('#btnCropApply').removeClass('disabled');
    })

    $('#btnCreateGrid4').click(function () {
        document.getElementById('gridImg').src='<{$wgevents_url}>/assets/images/loading.gif';
        $.ajax({
            data: 'op=creategrid&type=4&<{$imageOrigin|default:''}>=<{$imageId|default:0}>&src1=' + $('#imageGrid1').attr('src') + '&src2=' + $('#imageGrid2').attr('src') + '&src3=' + $('#imageGrid3').attr('src') + '&src4=' + $('#imageGrid4').attr('src') + '&target=<{$gridtarget}>',
            url: 'image_editor.php',
            method: 'POST',
            success: function() {
                document.getElementById('gridImg').src='<{$wgevents_upload_url}>/temp/<{$gridtarget}>?' + new Date().getTime();
                $('#gridImgFinal').val('<{$wgevents_upload_path}>/temp/<{$gridtarget}>');
                $('#gridImg').addClass('imageeditor-img');
                $('#btnApplyGrid').removeClass('disabled');
            }
        });
    })
    $('#btnCreateGrid6').click(function () {
        document.getElementById('gridImg').src='<{$wgevents_url}>/assets/images/loading.gif';
        $.ajax({
            data: 'op=creategrid&type=6&<{$imageOrigin|default:''}>=<{$imageId|default:0}>&src1=' + $('#imageGrid1').attr('src') + '&src2=' + $('#imageGrid2').attr('src') + '&src3=' + $('#imageGrid3').attr('src') + '&src4=' + $('#imageGrid4').attr('src') + '&src5=' + $('#imageGrid5').attr('src') + '&src6=' + $('#imageGrid6').attr('src') + '&target=<{$gridtarget}>',
            url: 'image_editor.php',
            method: 'POST',
            success: function() {
                document.getElementById('gridImg').src='<{$wgevents_upload_url}>/temp/<{$gridtarget}>?' + new Date().getTime();
                $('#gridImg').addClass('imageeditor-img');
                $('#btnApplyGrid').removeClass('disabled');
            }
        });
    })

    function selectGridImage(element, id) {
        document.getElementById('imageGrid' + id).src=element.src;
        var elements = document.getElementsByClassName('imageeditor-modal-selected');
        while(elements.length > 0){
            elements[0].classList.remove('imageeditor-modal-selected');
        }
        $('#imageGrid' + id).addClass('imageeditor-img');
        element.classList.add('imageeditor-modal-selected');
        $('#myModalImagePicker' + id).modal('hide');
        return false;
    }

    $('.imgSelect1').click(function () {
        $('#image_id').val($(this).attr('id'));
        document.getElementById('ImageSelected').src=$(this).attr('src');
        var elements = document.getElementsByClassName('imageeditor-modal-selected');
        while(elements.length > 0){
            elements[0].classList.remove('imageeditor-modal-selected');
        }
        $(this).addClass('imageeditor-modal-selected');
        $('#btnApplySelected').removeClass('disabled');
        return false;
    })

</script>

<{include file='db:wgevents_footer.tpl'}>

/**
 * 
 */
window.wangEditor.fullscreen = {
    // editor create之后调用
    init: function(editorSelector){
        $(editorSelector + " .w-e-toolbar").append('<div class="w-e-menu"><a class="_wangEditor_btn_fullscreen" href="javascript:;" onclick="window.wangEditor.fullscreen.toggleFullscreen(\'' + editorSelector + '\');return false;">全屏</a></div>');
    },
    toggleFullscreen: function(editorSelector){
        $(editorSelector).toggleClass('fullscreen-editor');
        if($(editorSelector + ' ._wangEditor_btn_fullscreen').text() == '全屏'){
            $(editorSelector + ' ._wangEditor_btn_fullscreen').text('退出全屏');
        }else{
            $(editorSelector + ' ._wangEditor_btn_fullscreen').text('全屏');
        }
    }
};
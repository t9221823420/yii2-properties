<?php

use yii\helpers\Url;
use yozh\properties\models\PropertyModel;

?>

<?if(0){?><script type='text/javascript'><?}?>

<? switch($section) : case 'onload' : ?>

_widgets = {<?= $widgets;?>};

$(function() {
    
    $('select[name="NewModel[inputType]"]').on('change', function(){
        
        if( $(this).val() ){
            $('select[name="NewModel[widget]"]').html( _widgets[$(this).val()] );
            //$('#widget').removeClass('hide');
            $('#addButton').removeClass('disabled');
        }
        else{
            $('select[name="NewModel[widget]"]').html('<option value=""><?= Yii::t( 'properties', 'Select input' ) ?></option>');
            //$('#widget').addClass('hide');
            $('#addButton').addClass('disabled');
        }
        
    });

    $('#addButton').on('click', function () {

        var _data = {};
        
        $('*[name^="NewModel"]').each(function( _index, _input ) {
            _data[ $(_input).attr('name') ] = $(_input).val() ;
        });
        
        $.ajax({
            url: '<?= Url::to(['/properties/get-input']); ?>',
            type: 'POST',
            data: _data,
        })
        .done( function( _response ) {

            $('#inputs').append( _response.html );

            switch ( _response.widget ) {
                case '<?= PropertyModel::WIDGET_TYPE_TEXTEDITOR ?>':

                    var _editorId = 'editor-' + _response.id;

                    tinyMCE.EditorManager.execCommand('mceAddEditor', true, _editorId );
                    initTinyEvents( tinyMCE.EditorManager.get( _editorId ) );
                    
                    break;

                default:
            }
            
        })
        ;
    })
    
    $(document).on('click', '.btn.edit-label', function () {
        
        _$host = $(this).parents('label');
        _$label = _$host.find('span.text');
        _$input = _$host.find('input[name="label"]');

        _$input.val( _$label.text() ).one('change', function () {
            

            _data = {
                id: _$input.data('id'),
                name: _$input.val(),
            };

            $.ajax({
                url: '<?= Url::to( [ '/properties/name-update', ] ); ?>',
                //type: 'POST',
                data: _data,
            })
            .done( function( result ) {
                _$label.text( _$input.val() );
            })
            ;
            
        });
    })


    $(document).on('change', 'input[name^="PropertyModel"], textarea[name^="PropertyModel"]', function () {

        var _$host = $(this);
        var _id = _$host.data('id');
        var _data = {
            value: _$host.val()
        };
        
        inptuChage( _$host, _id, _data );
        
    })

    $.each( tinyMCE.editors, function( _index, _editor ){
        initTinyEvents(_editor);
    });

});


function initTinyEvents(_editor){

    _editor.on('change', function( ) {

        var _$host = $(_editor.getElement());
        var _id = _$host.data('id');
        var _data = {
            value: _editor.getContent()
        };

        inptuChage( _$host, _id, _data );
    });


}

function inptuChage( _$host, _id, _data ){

    $.ajax({
        url: '<?= Url::to(['/properties/update', 'id' => '' ]); ?>' + _id,
        type: 'POST',
        data: _data,
    })
    .done( function( result ) {
    })
    ;


}

<? break; case 'crud' : ?>

<? break; endswitch; ?>

<?if(0){?></script><?}?>
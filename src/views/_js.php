<?php

use yii\helpers\Url;

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
        .done( function( result ) {
            $('#inputs').append(result);
        })
        ;
    })
    
    $(document).on('click', '.btn.edit-label', function () {
        
        _$host = $(this).parents('label');
        _$label = _$host.find('span.text');
        _$input = _$host.find('input[name="label"]');

        _$input.val(_$label.text()).one('change', function () {
            _$label.text( $(this).val() );

            _data = {
                label: $(this).val(),
                value: $(this).val(),
            };

            $.ajax({
                url: '<?= Url::to(['/properties/update']); ?>',
                //type: 'POST',
                data: _data,
            })
            .done( function( result ) {
            })
            ;
            
        });
    })
    
});

<? break; case 'crud' : ?>

<? break; endswitch; ?>

<?if(0){?></script><?}?>
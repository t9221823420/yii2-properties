<?php

use yii\helpers\Url;

?>

<?if(0){?><script type='text/javascript'><?}?>

<? switch($section) : case 'onload' : ?>

_widgets = {<?= $widgets;?>};

$(function() {
    
    $('select[name="inputType"]').on('change', function(){
        
        if( $(this).val() ){
            $('select[name="widgetType"]').html( _widgets[$(this).val()] );
            //$('#widgetType').removeClass('hide');
            $('#addButton').removeClass('disabled');
        }
        else{
            $('select[name="widgetType"]').html('<option value=""><?= Yii::t( 'properties', 'Select input' ) ?></option>');
            //$('#widgetType').addClass('hide');
            $('#addButton').addClass('disabled');
        }
        
    });

    $('#addButton').on('click', function () {
        
        _data = {
            inputType: $('select[name="inputType"]').val(),
            widgetType: $('select[name="widgetType"]').val()
        };
        
        $.ajax({
            url: '<?= Url::to(['/properties/get-input']); ?>',
            //type: 'POST',
            data: _data,
        })
        .done( function( result ) {
            $('#inputs').append(result);
        })
        ;
    })
    
});

<? break; case 'crud' : ?>

<? break; endswitch; ?>

<?if(0){?></script><?}?>
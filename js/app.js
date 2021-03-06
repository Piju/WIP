jQuery(document).ready(function(){
  jQuery('#title-prompt-text + input').on('focusin', function(){
    jQuery(this).parent().find('#title-prompt-text').addClass('screen-reader-text');
  });

  jQuery('[role="iconpicker"]').each(function(){
    jQuery(this).on('change', function(e) {
      jQuery(this).closest('.input-group').find('input[type="text"]').val(jQuery('[role="iconpicker"]').data('prefix')+' '+e.icon);
    });
  });

  var offsetMap = jQuery('.map').offset();

  var uploadImg = function(selector){
    var custom_uploader_2;
    jQuery(selector).click(function(e){
      var id = jQuery(this).data('id');
      var selector1 = jQuery('#thumbnail-'+id);
      var selector2 = jQuery('#row-'+id+' .thumbnail');
      e.preventDefault();

      //If the uploader object has already been created, reopen the dialog
      if (custom_uploader_2) {
        custom_uploader_2.open();
        return;
      }

      //Extend the wp.media object
      custom_uploader_2 = wp.media.frames.file_frame = wp.media({
        multiple: false
      });

      //When a file is selected, grab the URL and set it as the text field's value
      custom_uploader_2.on('select', function() {
        attachment = custom_uploader_2.state().get('selection').first().toJSON();
        selector1.val(attachment.id);
        selector2.html('<img src="'+attachment.url+'" width="'+attachment.width+'" />');
      });

      //Open the uploader dialog
      custom_uploader_2.open();
    });
  }

  var drag = function(event, ui) {
    var offsetXPos = ui.position.left;
    var offsetYPos = ui.position.top-45;
    var jQueryelement = jQuery(event.target).clone();

    jQueryelement.css({
      position: 'absolute',
      left: parseInt(offsetXPos)/jQuery('.map').width()*100 + '%',
      top: parseInt(offsetYPos)/jQuery('.map').height()*100 + '%'
    }).removeClass('original').addClass('drag');

    if(jQuery(event.target).hasClass('original')){
      jQuery(jQueryelement).appendTo('.drop');
    }

    // Rebind du nouvel element
    jQuery(".drag").draggable({
      containment: jQuery('.map'),
      stop: function(event, ui) {
        drag(event, ui);
        jQuery.ajax({
          url: ajax_object.ajaxurl,
          type: 'POST',
          data:{
            action: 'update_point_plan',
            id: jQuery(this).data('id'),
            posX: parseInt(ui.position.left)/jQuery('.map').width()*100,
            posY: parseInt(ui.position.top)/jQuery('.map').height()*100
          },
          success: function( data ){
          }
        });
      }
    });
  }

  //Initialise le drag et créée l'objet à ajouter sur le plan
  jQuery(".original").draggable({
    helper:"clone",
    cursorAt: { top: 5, left: 5 },
    containment: jQuery('.map'),
    stop: function(event, ui) {
      var offsetXPos = ui.position.left;
      //var offsetYPos = ui.position.top-45;
      var offsetYPos = ui.position.top;
      drag(event, ui);
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'add_point_plan',
          posX: parseInt(offsetXPos)/jQuery('.map').width()*100,
          posY: parseInt(offsetYPos)/jQuery('.map').height()*100,
          id: jQuery('#map').data('id')
        },
        success: function( data ){
          jQuery('.info').hide();
        }
      });
    }
  });

  jQuery('.drag').draggable({
    containment: jQuery('.map'),
    stop: function(event, ui) {
      drag(event, ui);
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'update_point_plan',
          id: jQuery(this).data('id'),
          posX: parseInt(ui.position.left)/jQuery('.map').width()*100,
          posY: parseInt(ui.position.top)/jQuery('.map').height()*100
        },
        success: function( data ){
        }
      });
    }
  });

  jQuery(".drop").droppable({
    drop: function( event, ui ) {
      if(ui.draggable.hasClass('original')){
        jQuery.ajax({
          url: ajax_object.ajaxurl,
          type: 'POST',
          dataType: "json",
          data:{
            action: 'add_form_plan',
            id: jQuery('#map').data('id')
          },
          success: function( data ){
            jQuery(data.content).insertBefore( '.edit .submit' );
            jQuery('.edit .submit + .info').hide();
            jQuery('.colorpicker').wpColorPicker();
            uploadImg('.add_image_button');

            jQuery('[role="iconpicker"]').each(function(){
              jQuery(this).iconpicker();
              jQuery(this).on('change', function(e) {
                jQuery(this).closest('.input-group').find('input[type="text"]').val(jQuery('[role="iconpicker"]').data('prefix')+' '+e.icon);
              });
            });

            tinymce.execCommand( 'mceAddEditor', false, 'description-'+data['id'] );
          }
        });
      }
    }
  });

  var custom_uploader;
  jQuery('#upload_image_button').click(function(e) {
    e.preventDefault();

    //If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    //Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
        title: "Insérer l'image",
        button: {
          text: "Insérer l'image"
        },
        multiple: false
    });

    //When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
      attachment = custom_uploader.state().get('selection').first().toJSON();
      jQuery( ".map" ).html('<span class="loader"><i class="glyphicon glyphicon-refresh"></i></span> Veuillez patientez');
      jQuery.ajax({
        url: ajax_object.ajaxurl,
        type: 'POST',
        data:{
          action: 'addImg',
          id: attachment.id
        },
        success: function( data ){
          jQuery( ".map" ).html('<img src="'+attachment.url+'" width="'+attachment.width+'" height="'+attachment.height+'"/>');
          jQuery('[name="thumbnail"]').val(attachment.id);
        }
      });
    });

    //Open the uploader dialog
    custom_uploader.open();
  });

  jQuery('.colorpicker').wpColorPicker();
  uploadImg('.add_image_button');

  jQuery(window).load(function(){
    jQuery('.colorpicker').wpColorPicker();
  });
});
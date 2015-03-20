(function($){



  $(document).ready(function(){

    $('#title-prompt-text').on('focusin', function(){
      $(this).toggleClass('screen-reader-text');
    });
    $('#title-prompt-text').on('focusout', function(){
      $(this).toggleClass('screen-reader-text');
    });

    //Fonction permettant d'ouvrir/fermer les onglets

    var postbox = function(){

      $('.if-js-closed').removeClass('if-js-closed').addClass('closed');

      postboxes.add_postbox_toggles();

    }



    var offsetMap = $('.map').offset();



    var focus = function(selector){

      $(selector).on( "focusin", function(){

        $(this).addClass('active');

        $('#marker-'+$(this).data('id')).addClass('active');

      });

      $(selector).on( "focusout", function(){

        $(this).removeClass('active');

        $('#marker-'+$(this).data('id')).removeClass('active');

      });

    }



    var uploadImg = function(selector){

      var custom_uploader_2;



      $(selector).click(function(e){



        var id = $(this).data('id');

        var selector1 = $('#thumbnail-'+id);

        var selector2 = $('#row-'+id+' .thumbnail');



        e.preventDefault();



        //If the uploader object has already been created, reopen the dialog

        /*if (custom_uploader_2) {

          custom_uploader_2.open();

          return;

        }*/



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

      var $element = $(event.target).clone();



      $element.css({

        position: 'absolute',

        left: parseInt(offsetXPos)/$('.map').width()*100 + '%',

        top: parseInt(offsetYPos)/$('.map').height()*100 + '%'

      }).removeClass('original')

      .addClass('drag');



      if($(event.target).hasClass('original')){

        $($element).appendTo('.drop');

      }



      //Fonction de suppression du marqueur sur le plan

      $('.destroy').on("click", function(){

        $.ajax({

          url: ajax_object.ajaxurl,

          type: 'POST',

          data:{

            action: 'remove_point_plan',

            id: $(this).data('id')

          },

          success: function( data ){

          }

        });

        $('#postbox-container-'+$(this).data('id')).remove();

        $(this).parent('.drag').remove();

        return false;

      });



      // Rebind du nouvel element

      $(".drag").draggable({

        containment: $('.map'),

        stop: function(event, ui) {

          drag(event, ui);

          $.ajax({

            url: ajax_object.ajaxurl,

            type: 'POST',

            data:{

              action: 'update_point_plan',

              id: $(this).data('id'),

              posX: parseInt(ui.position.left)/$('.map').width()*100,

              posY: parseInt(ui.position.top)/$('.map').height()*100

            },

            success: function( data ){

            }

          });

        }

      });

    }



    //Initialise le drag et créée l'objet à ajouter sur le plan

    $(".original").draggable({

      helper:"clone",

      cursorAt: { top: 5, left: 5 },

      containment: $('.map'),

      stop: function(event, ui) {

        var offsetXPos = ui.position.left;

        var offsetYPos = ui.position.top-45;



        drag(event, ui);

        $.ajax({

          url: ajax_object.ajaxurl,

          type: 'POST',

          data:{

            action: 'add_point_plan',

            posX: parseInt(offsetXPos)/$('.map').width()*100,

            posY: parseInt(offsetYPos)/$('.map').height()*100,

            id: $('#map').data('id')

          },

          success: function( data ){

          }

        });

      }

    });



    $('.drag').draggable({

      containment: $('.map'),

      stop: function(event, ui) {

        drag(event, ui);

        $.ajax({

          url: ajax_object.ajaxurl,

          type: 'POST',

          data:{

            action: 'update_point_plan',

            id: $(this).data('id'),

            posX: parseInt(ui.position.left)/$('.map').width()*100,

            posY: parseInt(ui.position.top)/$('.map').height()*100

          },

          success: function( data ){

          }

        });

      }

    });



    $(".drop").droppable({

      drop: function( event, ui ) {

        if(ui.draggable.hasClass('original')){

          $.ajax({

            url: ajax_object.ajaxurl,

            type: 'POST',

            dataType: "json",

            data:{

              action: 'add_form_plan'

            },

            success: function( data ){

              $(data.content).insertBefore( '.edit .submit' );

              $('.edit .submit + .info').hide();

              focus('.edit .postbox');

              $('.colorpicker').wpColorPicker();

              postbox();

              uploadImg('.add_image_button');

            }

          });

        }

      }

    });



    var custom_uploader;



    $('#upload_image_button').click(function(e) {



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



        $.ajax({

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





    $('.colorpicker').wpColorPicker();

    postbox();

    focus('.edit .postbox');

    uploadImg('.add_image_button');



  });



  $(window).load(function(){

    //Fonction permettant d'ouvrir/fermer les onglets

    var postbox = function(){

      $('.if-js-closed').removeClass('if-js-closed').addClass('closed');

      postboxes.add_postbox_toggles();

    }



    $('.colorpicker').wpColorPicker();

    postbox();



    $('.destroy').on("click", function(){

      $.ajax({

        url: ajax_object.ajaxurl,

        type: 'POST',

        data:{

          action: 'remove_point_plan',

          id: $(this).data('id')

        },

        success: function( data ){

        }

      });

      $('#postbox-container-'+$(this).data('id')).remove();

      $(this).parent('.drag').remove();

      return false;

    });

    postbox();



  });



})(jQuery);
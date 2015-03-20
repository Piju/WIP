/* Copyright (C) YOOtheme GmbH, YOOtheme Proprietary Use License (http://www.yootheme.com/license) */

(function($){
	tinymce.PluginManager.requireLangPack("widgetkit");
	tinymce.create("tinymce.plugins.WidgetkitPlugin",{
		init:function(ed,url){
				ed.addCommand("mceWidgetkit", function(){
				$.topbox.show("Loading...",{title:"Insert Widgetkit widget",buttons:{
					Insert: function(){
						var widgetkitid=$("#widgetkit_select_box").val();
						if(widgetkitid!=""){
							var code="[widgetkit id="+widgetkitid+"]";
							if(typeof tinyMCE!="undefined"&&!ed.isHidden()){ed.focus();
							if(tinymce.isIE){ed.selection.moveToBookmark(tinymce.EditorManager.activeEditor.windowManager.bookmark)}ed.execCommand("mceInsertContent",false,code)}this.close()
						}
					},
					Cancel: function(){
						this.close()
					}
				}
			});

			$.topbox.box.find(".topbox-button:first").hide();
			$.post(widgetkitajax+"&task=editor",{},function(data){$.topbox.setContent(data);
			$.topbox.box.find(".topbox-button:first").show()
			})


			});
			ed.addButton("widgetkit",{title:"Add Widgetkit widget",cmd:"mceWidgetkit",image:url.replace("/js","/images/widgetkit_32.png")

			});
			ed.onNodeChange.add(function(ed,cm,n){cm.setActive("example",n.nodeName=="IMG")
			})
		},
		createControl:function(n,cm){return null},
		getInfo: function(){
			return{longname:"Widgetkit Worpdress TinyMCE Button",author:"yootheme",authorurl:"http://www.yootheme.com",infourl:"http://www.yootheme.com",version:"1.0"}
		}
	});

	tinymce.PluginManager.add("widgetkit",tinymce.plugins.WidgetkitPlugin)
})(jQuery);
(function($){
	var $this=null;
	$.topbox=$this={
		box:null,options:{},persist:false,show:function(content,options){
			if(this.box){
				this.clear()
			}
	this.options=$.extend({
		title:false,closeOnEsc:true,cls:"",width:400,height:"auto",speed:500,easing:"swing",buttons:false,
		beforeShow: function(){},onShow: function(){},beforeClose: function(){},
		onClose: function(){}},options);
			var tplDlg='<div class="topbox-window '+$this.options.cls+'">';
			tplDlg+='<div class="topbox-closebutton"></div>';
			tplDlg+='<div class="topbox-title" style="display:none;
			"></div>';
			tplDlg+='<div class="topbox-content"><div class="topbox-innercontent"></div></div>';
			tplDlg+='<div class="topbox-buttonsbar"><div class="topbox-buttons"></div></div>';
			tplDlg+="</div>";
			this.box=$(tplDlg);
			this.box.find(".topbox-closebutton").bind("click", function(){
			$this.close()
		});

	if(this.options.buttons){var btns=this.box.find(".topbox-buttons");
		$.each(this.options.buttons,function(caption,fn){
			$('<span class="topbox-button">'+caption+"</span>").bind("click", function(){
			fn.apply($this)
		}).appendTo(btns)
	})
	}else{
		this.box.find(".topbox-buttonsbar").hide()}

	if($this.options.height!="auto"){
		this.box.find(".topbox-innercontent").css({
			height:$this.options.height,"overflow-y":"auto"
		})
	}

	this.setContent(content).setTitle(this.options.title);
	this.box.css({opacity:0,visibility:"hidden",left:$(window).width()/2-$this.options.width/2,width:$this.options.width
	}).appendTo("body").css({top:-1.5*$this.box.height()
	}).css({visibility:"visible"
	}).animate({top:0,opacity:1},this.options.speed,this.options.easing, function(){
		$this.options.onShow.apply(this)
	});

	$(window).bind("resize.topbox", function(){
		$this.box.css({left:$(window).width()/2-$this.options.width/2,width:$this.options.width
	})
	});
	if(this.options.closeOnEsc){$(document).bind("keydown.topbox",function(e){if(e.keyCode===27){e.preventDefault();
		$this.close()}
	})
	}this.showOverlay();
	return this},

	close: function(){
		if(!this.box){return}
		if(this.options.beforeClose.apply(this)===false){return this}this.overlay.fadeOut();
		this.box.animate({top:-1.5*$this.box.height(),opacity:0},this.options.speed,this.options.easing, function(){
			$this.clear()
		});

		this.options.onClose.apply(this);
		return this
	},

	confirm:function(content,fn,options){var options=$.extend({title:"Please confirm",buttons:{Ok:
					function(){
	fn.apply($this)},Cancel:
				function(){
	this.close()}}},options);
	this.show(content,options)},alert:function(content,options){var options=$.extend({title:"Alert",buttons:{Ok:
					function(){
	this.close()}}},options);
	this.show(content,options)},clear:
				function(){
	if(!this.box){return}
	if(this.persist){this.persist.appendTo(this.persist.data("tb-persist-parent"));
	this.persist=false}this.box.remove();
	this.box=null;

	if(this.overlay){this.overlay.hide()}$(window).unbind("resize.topbox");
		$(document).unbind("keydown.topbox");
		return this
	},

	setTitle:function(title){if(!this.box){return}if(title){this.box.find(".topbox-title").html(title).show()}else{this.box.find(".topbox-title").html(title).hide()}return this},setContent:function(content){if(!this.box){return}if(typeof content==="object"){content=content instanceof jQuery?content:$(content);
	if(content.parent().length){this.persist=content;
	this.persist.data("tb-persist-parent",content.parent())}}else if(typeof content==="string"||typeof content==="number"){content=$("<div></div>").html(content)}else{content=$("<div></div>").html("SimpleModal Error: Unsupported data type: "+typeof content)}content.appendTo(this.box.find(".topbox-innercontent").html(""));
	return this},

	showOverlay: function(){
		if(!this.box){return}if(!this.overlay){if(!$("#topbox-overlay").length){$("<div>").attr("id","topbox-overlay").css({top:0,left:0,position:"absolute"
			}).prependTo("body")}this.overlay=$("#topbox-overlay")}this.overlay.css({width:$(document).width(),height:$(document).height()
			}).show()
		}
	};

	$.fn.topbox= function(){
		var args=arguments;
		var options=args[0]?args[0]:{};
		return this.each(function(){
			$.topbox.show(this,options)
		})
	}

})(jQuery);
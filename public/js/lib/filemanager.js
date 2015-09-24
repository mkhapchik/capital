(function($){
	var options = {
		action_url : '/filemanager',
		nav : ['back', 'upload', 'update', 'select_all', 'unselect_all', 'delete']
	};

	jQuery.fn.filemanager = function(method, params){
		var that = this;
				
		var initHTML = function(html){
			var div = $('<div/>', {html:html, 'class':'filemanager'});
			
			that.currentDir = div.find('#current_dir').val();
			
			
			div.find('.dir a.link').click(function(){
				var path = $(this).attr('href');
				methods.list.call(that, path);
				return false;						
			});
			
			div.find('.item .select input').click(function(event){
				if($(this).is(':checked')) $(this).closest('.item').addClass('selected');
				else $(this).closest('.item').removeClass('selected');
			});
			
			div.find('.bread_crumbs	a').click(function(){
				methods.list.call(that, $(this).attr('href'));
				return false;
			});
			
			var div_nav = $('<div/>',{'class':'nav'});
			var nav_l = options.nav.length;
			for(var i=0; i<nav_l; i++)
			{
				var f = nav[options.nav[i]];
				var el = f();
				div_nav.append(el);
			}
			div.prepend(div_nav);
			
			
			$(this).html(div)
		}
		
		
		var nav = {
			back: function(){
				var b = $('<button/>',{html:'back'});
				b.addClass('back');
				
				if(that.currentDir.indexOf('/')>0)
				{
					b.click(function(){
						methods.back.call(that);
					});
				}
				else 
				{
					b.prop('disabled', true);
					b.addClass('disabled');
				}
				
				return b;
			},
			upload: function(){
				var input = $('<input/>',{name:'uploadfile[]', type:'file', multiple:'true'})
				var b = $('<button/>',{html:'upload'});		
				b.click(function(){input.click()});	
				input.change(function(){
					if(this.files.length>0)
					{
						methods.upload.call(that, this.files);
					}
				});
				return b;
			},
			update:function(){
				var b = $('<button/>',{html:'update'});		
				b.click(function(){
					methods.list.call(that, that.currentDir);
				});
				return b;
			},
			select_all:function(){
				var b = $('<button/>',{html:'select_all'});		
				b.click(function(){
					$(that).find('.select input').prop('checked', true);
				});
				return b;
			},
			unselect_all:function(){
				var b = $('<button/>',{html:'unselect_all'});		
				b.click(function(){
					$(that).find('.select input').prop('checked', false);
				});
				return b;
			},
			'delete' : function(){
				var b = $('<button/>',{html:'delete'});		
				b.click(function(){
					files = [];
					$(that).find('.select input:checked').each(function(){
						files.push($(this).val());
					});
					methods['delete'].call(that, files);
				});
				return b;
			}
		}

		var methods = {
			init:function(params) {
				options = $.extend({}, options, params);
				methods.list.call(that, false);
			},
			list:function(path){
				path = path || '';
				$.ajax({
					url : options.action_url + '/list',
					method : 'post',
					data : {path:path},
					dataType : 'html',
					success : function(html){
						initHTML.call( that, html);
					}
				});
			},
			back:function(){
				var path = that.currentDir+'/../';
				methods.list.call(that, path);
			},
			upload:function(files){
				var data = new FormData();
				data.append( 'path', that.currentDir );
				$.each( files, function( key, value ){
					data.append( key, value );
				});

				$.ajax({
					url: options.action_url + '/upload',
					type: 'POST',
					data: data,
					//dataType: 'json',
					processData: false, // Не обрабатываем файлы (Don't process the files)
					contentType: false, // Так jQuery скажет серверу что это строковой запрос
					success: function( respond, textStatus, jqXHR ){
						methods.list.call(that, that.currentDir);
					},
					error: function( jqXHR, textStatus, errorThrown ){
						console.log('ОШИБКИ AJAX запроса: ' + textStatus );
					}
				});
				
			},
			'delete':function(files){
				var data = new FormData();
				
				$.each( files, function( key, value ){
					data.append( 'files[]', value );
				});
				$.ajax({
					url: options.action_url + '/delete',
					type: 'POST',
					data: data,
					//dataType: 'json',
					processData: false, // Не обрабатываем файлы (Don't process the files)
					contentType: false, // Так jQuery скажет серверу что это строковой запрос
					success: function( respond, textStatus, jqXHR ){
						methods.list.call(that, that.currentDir);
					},
					error: function( jqXHR, textStatus, errorThrown ){
						console.log('ОШИБКИ AJAX запроса: ' + textStatus );
					}
				});
			}
			
		};
		
		if ( methods[method] ) 
		{
			return methods[ method ].call( this, params);
		} 
		else if ( typeof method === 'object' || ! method ) 
		{
			return methods.init.call( this, method, params);
		}
		//return this.each(methods.init.apply( this, params )); 
	};
})(jQuery);
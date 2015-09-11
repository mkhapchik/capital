(function($){
		var options = {
			template_block : ".template",
			template_placeholder: "__INDEX__",
			target_block : ".dynamic_group",
			nav_block : ".nav",
			buttons: [
			{
			  text: "Add",
			  click: function() {
				$(this).dynamicElement('add');
				return false;
			  }
			}
			]
		};

		jQuery.fn.dynamicElement = function(method){
			var methods = {
				init:function(params) {
					options = $.extend({}, options, params);
		 
					var l = options.buttons.length;
					for(var i=0; i<l; i++)
					{
						var button_opt = options.buttons[i];
						$(options.nav_block).prepend($("<button/>", button_opt));
					}
					
				},
				getIndexByName:function(name)
				{
					var tpl_name = $(options.template_block).find('input').attr('name'),
					start_i = tpl_name.indexOf(options.template_placeholder, 0),
					end_i = name.indexOf(']', start_i),
					i = name.substr(start_i, end_i-start_i);
					
					return i;
				},
				getIndex:function(){
					var el = $(this).find(options.target_block).find('input');
					var max = el.length;
					
					el.each(function(index){
						var name = $(this).attr('name');
						var i = methods.getIndexByName.call(this, name);
						if(i>max) max=i;
					});
					
					max++;
					return max;
				},
				add:function(){
					var new_el = $(options.template_block).children(options.target_block).clone(true, true),
					index = methods.getIndex.apply( this ),
					
					html = new_el.html().split(options.template_placeholder).join(index);
					new_el.html(html);
					
					var last = $(this).find(options.target_block).last();
					if(typeof(last) === 'object' && last.length>0)
					{
						last.after(new_el);
					}
					else
					{
						$(this).prepend(new_el);
					}
				}
			};
			
			if ( methods[method] ) 
			{
                return methods[ method ].apply(this);
			} 
			else if ( typeof method === 'object' || ! method ) 
			{
				return methods.init.call( this, method );
			}
			//return this.each(methods.init.apply( this, params )); 
		};
	})(jQuery);
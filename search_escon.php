<script>
	function searchFilterSearch(searchText) {
	var searchp = jQuery('#searchp').val();
	if (searchp.length >= 1) {
		jQuery('#type_all').val('any');
		jQuery('#type_pages').val('page');
		jQuery('#type_post').val('cb_products');
	} else {

	}
}
function searchFilterAll(type_all) {
	if (type_all.checked) {
		jQuery('#type_all').val('any');
		jQuery('#type_pages').val('page');
		jQuery('#type_post').val('cb_products');

		jQuery('#type_pages').prop('checked', true);
		jQuery('#type_post').prop('checked', true);

	} else {
		jQuery('#type_all').val('');
		jQuery('#type_pages').val('');
		jQuery('#type_post').val('');

		jQuery('#type_pages').prop('checked', false);
		jQuery('#type_post').prop('checked', false);
	}
}

function searchFilterPages(pagesType) {
	if (pagesType.checked) {
		jQuery('#type_pages').val('page');
	} else {
		jQuery('#type_all').val('');
		jQuery('#type_pages').val('');
		jQuery('#type_pages').removeAttr('checked');
		jQuery('#type_all').removeAttr('checked');
	}
}

function searchFilterPost(postType) {
	if (postType.checked) {
		jQuery('#type_post').val('post');
	} else {
		jQuery('#type_all').val('');
		jQuery('#type_post').val('');
		jQuery('#type_post').removeAttr('checked');
		jQuery('#type_all').removeAttr('checked');
	}
}

function searchFilter() {
	var search_text = jQuery('#searchp').val();
	var type_all = jQuery('#type_all').val();
	var type_pages = jQuery('#type_pages').val();
	var type_post = jQuery('#type_post').val();
	var value = parseInt(document.getElementById('number').value);
	jQuery.ajax({
		type: 'post',
		dataType: 'json',
		url: "<?= site_url() ?>/wp-admin/admin-ajax.php",
		data: {
			'action': 'search_action',
			'search_text': search_text,
			'type_all': type_all,
			'type_pages': type_pages,
			'type_post': type_post,
			'number': value
		},
		beforeSend: function() {
			jQuery('.loader-img').show();
		},
		complete: function() {
			jQuery('.loader-img').hide();
		},
		success: function(resp) {
			if (resp.status == 'error') {
				var output = '';
				//output += `<ul>`;
				output += `<li class="search-error-msg" >${resp.msg}</li>`;
				//output += `</ul>`;
				jQuery('#search_div').html(output);
				jQuery('#searchp').css('border', '1px solid red');
				jQuery("#load_button").css('display', 'none');
			}
			if (resp.status == 'success') {
				jQuery('#searchp').css('border', '0px');
				var datas = resp.data.length;
				var pcount = resp.data[0].post_count;
				if(pcount >= 5 ){
					jQuery("#load_button").css('display', 'block');
				}else{
					jQuery("#load_button").css('display', 'none');
				}
				if (datas >= 1) {
					var output = '';
					for (i = 0; i < datas; i++) {						
						output += `<li class="searchresule">`;
						output += `<div class="img_box"><div class="img_box_wrap"><a href="${resp.data[i].permalink}">`;
						if (resp.data[i].thumbnail.length >= 1) {
							output += `<img src="${resp.data[i].thumbnail}" />`;
						} else { 
							output += `<img src="<?= site_url() ?>/wp-content/uploads/2022/12/search_img_def.jpg" />`;
						}
						output += `</a></div></div>`;
						output += `<div class="content_box">`;
						output += `<div class="title"> <a href="${resp.data[i].permalink}">${resp.data[i].title}</a></div>`;
						output += `<div class="content"> ${resp.data[i].contents}</div>`;
						output += `<div class="publishdate"><span>${resp.data[i].publishdate} </span></div>`;
						output += `<div class="explore_wrap"> <a href="${resp.data[i].permalink}" class="Explore_btn"> Explore </a></div>`;
						output += `</div>`;
						output += `</li>`;
					}
					jQuery('#search_div').html(output);
				}
			}
		}
	})
}
// load more button
value = 5
function incrementValue()
{
    value = parseInt(document.getElementById('number').value,10);
    value = isNaN(value) ? 0 : value;
    value+= 5;
    document.getElementById('number').value = value;
    searchFilter();
}

jQuery(document).ready(function () {  
	jQuery("#searchform").bind("keypress", function (e) {  
		//console.log(e.keyCode);
		if (e.keyCode == 13) {  
			return false;  
		}  
	});  
});

// // auto complete JS
function searchFilterTitle(){
    var search_text = jQuery('#searchtitle').val();
    if(search_text.length > 0){  
    jQuery('.ast-header-search .ast-search-menu-icon.slide-search .search-form input#searchtitle').css('borderBottom','2px solid #5DBC30');                
    jQuery("#searchformhomelk").attr('action',"<?php echo esc_url( home_url( '/' ) ); ?>");
    jQuery("#searchformhomelk input").attr('name',"s");      
    jQuery.ajax({type : 'post',
            dataType : 'json',
            url: "<?= site_url() ?>/wp-admin/admin-ajax.php",
            data: {'action':'title_search_action','searchtitle': search_text,},            
            success:function(resp){         
            	var output = '';        
				if(resp.status == 'error'){        
					console.log(resp.msg);
					output += `<li class="searchresulered">`;
					output += `<div>${resp.msg} test</div>`;                     
					output += `</li>`;
					jQuery('.searchfilter_ul').html(output);

				}
              if(resp.status == 'success'){                                
                var datas = resp.data.length;                                 
                if(datas >= 1 ){
                  //var output = '';
                  var count = 0;
                  for(l=0;l<datas; l++){                    
                    if(count <= 4){
                        output += `<li class="searchresule">`;
                        output += `<div onclick="changeTitlte(jQuery(this).text())">${resp.data[l].posts_title}</div>`;                     
                        output += `</li>`;
                    }else{
                        break;
                    }
                    count++;                    
                  }
                  jQuery('.searchfilter_ul').html(output);  
                } 
              }     
          }
    })

}else{
        jQuery("#searchformhomelk").attr('action',"");
        jQuery("#searchformhomelk input").attr('name',"");
        jQuery('.ast-header-search .ast-search-menu-icon.slide-search .search-form input#searchtitle').css('borderBottom','2px solid red');
    }
}
function changeTitlte(values){   
    var searchtitles = jQuery("#searchtitle").val(values); 
    if(searchtitle != ""){                
        jQuery("#searchformhomelk").trigger( "submit" );               
    }
}

// auto complete JS
</script>
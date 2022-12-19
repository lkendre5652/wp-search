<?php
/**
 * Search Form for Astra theme.
 *
 * @package     Astra
 * @author      Brainstorm Force
 * @copyright   Copyright (c) 2020, Brainstorm Force
 * @link        https://www.brainstormforce.com
 * @since       Astra 3.3.0
 */

/**
 * Adding argument checks to avoid rendering search-form markup from other places & to easily use get_search_form() function.
 *
 * @see https://themes.trac.wordpress.org/ticket/101061
 * @since 3.6.1
 */
$astra_search_input_placeholder = isset( $args['input_placeholder'] ) ? $args['input_placeholder'] : astra_default_strings( 'string-search-input-placeholder', false );
$astra_search_show_input_submit = isset( $args['show_input_submit'] ) ? $args['show_input_submit'] : true;
$astra_search_data_attrs        = isset( $args['data_attributes'] ) ? $args['data_attributes'] : '';
$astra_search_input_value       = isset( $args['input_value'] ) ? $args['input_value'] : '';

?>

<form role="search" method="get" class="search-form" id='searchformhomelk' action="">
	<!-- <a href="#.">closed</a> -->
    <label>
        <div>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'astra' ); ?></span>
		<input onkeyup="searchFilterTitle();" id='searchtitle' type="search" class="search-field" <?php echo esc_html( $astra_search_data_attrs ); ?> placeholder="Search..." value="<?php echo esc_attr( $astra_search_input_value ); ?>" name="s" tabindex="-1">

		
		<?php if ( class_exists( 'Astra_Icons' ) && Astra_Icons::is_svg_icons() ) { ?>
			<button class="search-submit ast-search-submit" aria-label="<?php echo esc_attr__( 'Search Submit', 'astra' ); ?>">
				<span hidden><?php echo esc_html__( 'Search', 'astra' ); ?></span>
				<i><?php Astra_Icons::get_icons( 'search', true ); ?></i>
			</button>
		<?php } ?>
    </div>
		<ul class="searchfilter_ul"></ul>        
	</label>	
	<?php if ( $astra_search_show_input_submit ) { ?>
		<input type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search', 'astra' ); ?>">
	<?php } ?>
</form>

<script> 
// function searchFilterTitle(){
//     var search_text = jQuery('#searchtitle').val();
//     if(search_text.length > 0){  
//     jQuery('.ast-header-search .ast-search-menu-icon.slide-search .search-form input#searchtitle').css('borderBottom','2px solid #5DBC30');                
//     jQuery("#searchformhomelk").attr('action',"<?php echo esc_url( home_url( '/' ) ); ?>");
//     jQuery("#searchformhomelk input").attr('name',"s");      
//     jQuery.ajax({type : 'post',
//             dataType : 'json',
//             url: "<?= site_url() ?>/wp-admin/admin-ajax.php",
//             data: {'action':'title_search_action','searchtitle': search_text,},            
//             success:function(resp){         
//               if(resp.status == 'error'){        
//                 var output = '';        
//                 output += `<ul>`;
//                 output += `<li class="search-error-msg" >${resp.msg}</li>`;
//                 output += `</ul>`;
//                 jQuery('.searchfilter_ul').html(output);
                
//               }
//               if(resp.status == 'success'){                                
//                 var datas = resp.data.length;                                 
//                 if(datas >= 1 ){
//                   var output = '';
//                   var count = 0;
//                   for(l=0;l<datas; l++){                    
//                     if(count <= 4){
//                         output += `<li class="searchresule">`;
//                         output += `<div onclick="changeTitlte(jQuery(this).text())">${resp.data[l].posts_title}</div>`;                     
//                         output += `</li>`;
//                     }else{
//                         break;
//                     }
//                     count++;                    
//                   }
//                   jQuery('.searchfilter_ul').html(output);  
//                 } 
//               }     
//           }
//     })

// }else{
//         jQuery("#searchformhomelk").attr('action',"");
//         jQuery("#searchformhomelk input").attr('name',"");
//         jQuery('.ast-header-search .ast-search-menu-icon.slide-search .search-form input#searchtitle').css('borderBottom','2px solid red');
//     }
// }
// function changeTitlte(values){   
//     var searchtitles = jQuery("#searchtitle").val(values); 
//     if(searchtitle != ""){                
//         jQuery("#searchformhomelk").trigger( "submit" );               
//     }
// }

</script>
<?php include('search_escon.php'); ?>
<style>
.searchfilter_ul {margin-top: 9px;}
.searchfilter_ul li.searchresule {
    display: flex;
    justify-content: space-between;
    border-bottom: 1px solid #b3b3b32b;
    padding: 10px 0px;
    margin-left: -48px;
    color: white;
    cursor: pointer;
}
.searchfilter_ul .searchresule div:hover{color: green;}
.searchresulered div:hover{
    color: red;
}
</style>
<?php
/* 
Plugin Name: FW Mini-Termin
Version: 2.0.4
Plugin URI: http://www.wieser.at/wordpress/plugins
Description: Mini Termin Plugin Prototyp - Metabox für Termindatum bei Beiträge, Sidebarwidget, Shortcode [termine] für Terminliste im Content (Seiten, Beiträge, usw..), InfoSeite
Author: Franz Wieser
Author URI: http://www.wieser.at
*/ 
	


//custom meta boxes
$prefix = '';

$termin_meta_box = array(
    'id' => 'TerminMetaBox',
    'title' => 'Termin',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
    	array(
        	'name' => __('Datum'),
        	'id' => $prefix . 'termindatum',
        	'type' => 'date',
        	'desc' => __('Termindatum'),
        	'std' => ''
     	),
  array(
        	'name' => __('Datum bis'),
        	'id' => $prefix . 'termindatumbis',
        	'type' => 'date',
        	'desc' => __('Termindatumbis'),
        	'std' => ''
     	),
  
  array(
        	'name' => __('Zeit von'),
        	'id' => $prefix . 'zeitvon',
        	'type' => 'time',
        	'desc' => __('Zeit von'),
        	'std' => ''
     	),
    	array(        	'name' => __('Zeits bis'),
        	'id' => $prefix . 'zeitbis',
        	'type' => 'time',
        	'desc' => __('Zeit bis'),
        	'std' => ''
     	),
     	array(
        	'name' => __('Ort'),
        	'id' => $prefix . 'ort',
        	'type' => 'text',
        	'desc' => __('Terminort'),
        	'std' => ''
     	),
     	
     	

         )
);


add_filter( 'the_content', 'termin_filter', 20 );

function termin_filter( $content ) {
   $datummeta=get_post_meta(get_the_ID(), 'termindatum', true);
   $fdatum=new DateTime($datummeta);
   $fdate=$fdatum->format('d.m.Y');
   $bisdatummeta=get_post_meta(get_the_ID(), 'termindatumbis', true);
   $bisdatum=new DateTime($bisdatummeta);
   $bisdate=$bisdatum->format('d.m.Y');
        if ( $datummeta!='' && (is_front_page() || is_single()) )
        {
	            $fcontent = '<h2>Termin: '.$fdate;
	            if ($bisdatum>$fdatum)
	            {$fcontent.=' bis '.$bisdate;}
	            $fcontent.='</h2>';
	            if (get_post_meta(get_the_ID(),'zeitvon',true)!='')
	              $fcontent.='<h2>Zeit: '.get_post_meta(get_the_ID(),'zeitvon',true);
	            if (get_post_meta(get_the_ID(),'zeitbis',true)!='')
	              $fcontent.=' bis: '.get_post_meta(get_the_ID(),'zeitbis',true);
			$fcontent.='</h2>';
	              
	            if (get_post_meta(get_the_ID(),'ort',true)!='')
	              $fcontent.='<h2>Ort: '.get_post_meta(get_the_ID(),'ort',true).'</h3>';
	              
        }
	            $fcontent.=$content;
	   

    return $fcontent;
}


function TerminAddMetaBoxes() {
    global $termin_meta_box, $posted;
    	//add_submenu_page( 'edit.php?post_type=kassa', 'Kassabuch', 'Kassabuch', 'manage_options', 'kassabuch-custom-page', 'kassabuch_page_callback' ); 


//echo "Termindaten eingeben:".$posted;
	$post_types = get_post_types(array('public' => true, 'show_ui' => true), 'objects');
	//foreach ($post_types as $page)     
	add_meta_box($termin_meta_box['id'], $termin_meta_box['title'], 'terminMetaBox', 'post', $termin_meta_box['context'], $termin_meta_box['priority']);
	
	
     
}
add_action('admin_menu', 'terminAddMetaBoxes');

function terminMetabox()
{
	global $termin_meta_box, $post;
echo "<table>";
 foreach ($termin_meta_box['fields'] as $field) {
        // get current post meta data
        echo "<tr>";
        $meta = get_post_meta($post->ID, $field['id'], true);
        
        switch ($field['type']) {
        	case 'text':
        		echo '<td>'.$field['name'].':</td><td> <input type="text" name="', $field['id'], '" id="', $field['id'], '" value="'.$meta.'" />'.$field['desc'].'</td>';
        		break;
	case 'editor':
	//	the_editor($post->post_content);
        		
        		break;
			case 'time':
				if ($meta!='')
        				{$tidate = new DateTime($meta);
$dmeta= $tidate->format('H:i');
}
				echo '<td>'.$field['name'].':</td><td> <input type="text" name="', $field['id'], '" id="', $field['id'], '" value="'.$meta.'" />'.$field['desc'].'</td>';
        		break;

				
        			case 'date':
        				if ($meta!='')
        				{$tidate = new DateTime($meta);
$dmeta= $tidate->format('d.m.Y');
}


echo '<td>'.$field['name'].':</td><td> <input class="calendarpicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="'.$dmeta.'" />'.$field['desc'].'</td>';
//echo '<script type="text/javascript">jQuery(document).ready(function() {    jQuery(\'#termindatum\').datepicker1({        dateFormat : \'dd.mm.yy\'/   });});</script>';

break;
            case 'date2':
            $metas = get_post_meta($post->ID, $field['id'], false);
            
            
        				if ($meta!='')
        				{$tidate = new DateTime($meta);
$dmeta= $tidate->format('d.m.Y');
}
foreach ($metas as $d2meta) {
echo '<td>'.$field['name'].':</td><td> <input type="text" name="', $field['id'], '" id="', $field['id'], '" value="'.$d2meta.'" />'.$field['desc'].'</td>';
}
            echo '<td>'.$field['name'].':</td><td> <input class="calendarpicker" type="text" name="', $field['id'], '" id="', $field['id'], '" value="" />'.$field['desc'].'</td>';
//echo '<script type="text/javascript"> jQuery(document).ready(function() {    jQuery(\'#testdatum\').datepicker({        dateFormat : \'dd.mm.yy\'    });});</script>';

break;
	case 'editor':
	//	the_editor($post->post_content);
        		
        		break;
        		            case 'select':
            	echo "selected Box";
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                break;
        }
        echo "</tr>";

}
echo '</table>';	
}

function terminSaveData($post_id) {
global $termin_meta_box;
global $my_save_post_flag;

if ($my_save_post_flag == 0) {






$post = array(
		'post_title'	=> 'request',
		'post_content'	=> $posted,
		'post_status'	=> 'publish',
		'post_type'	=> 'suchergebnis' 
	);
//	wp_insert_post($post);  
    
    // verify nonce
   // if (!wp_verify_nonce($_POST['kassaMetaNonce'], basename(__FILE__))) {
    //    return $post_id;
    //}

    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return $post_id;
    }

    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return $post_id;
        }
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }
    
   

   
    
    /*
        else
        {
    */
    foreach ($termin_meta_box['fields'] as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        
        $new = $_POST[$field['id']];
        if ($field['id']=='termindatum' and $new!='')
        {
        $tdate= new DateTime($new);
$new =$tdate->format('Y-m-d H:i:s');
        }
        $bfsam.= $new.'-';
        
        
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
        
        } //not RK_ID
        update_post_meta($post_id, 'requestbf', $bfsam);
   
    


}
$my_save_post_flag = 1;
    
    
}

add_action('save_post', 'terminsaveData');

function termine_shortcode($atts)
{
	extract( shortcode_atts( array(
		'cat' => '',
		'category_name' => '',
	), $atts ) );
global $wpdb;
	$out='<div class="wrap"><div id="icon-tools" class="icon32"></div>';
		$out.='<h2>Termine</h2>';
		$arg1=array(
'post_type' => 'post',

'orderby' => 'meta_value termindatum', 
'meta_key' => 'termindatum',
'order' => 'ASC',
'cat'=>'',
'posts_per_page'=>'-1',
);

$out.='<ul>';

	$my_query = new WP_Query( $arg1);
   if (  $my_query->have_posts() ) { 
       while ( $my_query->have_posts() ) {
       	$my_query->the_post();
           //$out.= "<tr>".' ';
           $tdate=get_post_meta(get_the_ID(), 'termindatum',true);
           $bisdate=get_post_meta(get_the_ID(), 'termindatumbis',true);
           $vonzeit=get_post_meta(get_the_ID(), 'zeitvon',true);
		     $biszeit=get_post_meta(get_the_ID(), 'zeitbis',true);
		   $wdate = new DateTime($tdate);
           $bdate = new DateTime($bisdate);
           $heutedate=new DateTime();
           if ($bdate>=$heutedate) 
           {
           	
           $wheutedate=$heutedate->format('d.m.Y');
$terminwdatum= $wdate->format('d.m.Y');
$bisterminwdatum= $bdate->format('d.m.Y');

           $out.="<li>".$terminwdatum;
           if ($bdate>$wdate)
           $out.=' bis '.$bisterminwdatum;
           
           $out.='<br/>';
			$out.=$vonzeit." - ".$biszeit."<br/>";
              
           $out.='<a href="'.get_permalink().'">'.get_the_title().'</a></li>';   
           
           }
           
       }
       	
} // if have posts
		$out.="";
		$out.="</ul>";			$out.='</div>';
		return $out;
		
}
add_shortcode('termine', 'termine_shortcode');

function minitermin_sidebarwidget()
{
global $wpdb;
	$out='<aside id="termine" class="widget widget_termine">';
		$out.='<h3 class="widget-title">Termine</h3>';
		$arg1=array(
'post_type' => 'post',

'orderby' => 'meta_value termindatum', 
'meta_key' => 'termindatum',
'order' => 'ASC',
'posts_per_page'=>'-1',
);

$out.='<ul>';

	$my_query = new WP_Query( $arg1);
   if (  $my_query->have_posts() ) { 
       while ( $my_query->have_posts() ) {
       	$my_query->the_post();
           //$out.= "<tr>".' ';
           $tdate=get_post_meta(get_the_ID(), 'termindatum',true);
           $bisdate=get_post_meta(get_the_ID(), 'termindatumbis',true);
		   $vonzeit=get_post_meta(get_the_ID(), 'zeitvon',true);
		     $biszeit=get_post_meta(get_the_ID(), 'zeitbis',true);
           $wdate = new DateTime($tdate);
           $bdate = new DateTime($bisdate);
           $heutedate=new DateTime();
           if ($bdate>=$heutedate) 
           {
           	
           $wheutedate=$heutedate->format('d.m.Y');
$terminwdatum= $wdate->format('d.m.Y');
$bisterminwdatum= $bdate->format('d.m.Y');

           $out.="<li>".$terminwdatum;
           if ($bdate>$wdate)
           $out.=' bis '.$bisterminwdatum;
           
           $out.='<br/>';
		   $out.=$vonzeit." - ".$biszeit."<br/>";
           $out.='<a href="'.get_permalink().'">'.get_the_title().'</a></li>';   
           
           }
           
       }
       	
} // if have posts
		$out.="";
		$out.="</ul>";
			$out.='</aside>';
		echo $out;
		
		


}

function minitermin_widget_init()
{
   wp_register_sidebar_widget( 'minitermin',__('MiniTermin'),'minitermin_sidebarwidget');
}
add_action("plugins_loaded", "minitermin_widget_init");


function minitermin_info_seite()
{
global $current_user;
   echo '<div class="wrap">';
   echo '<H3>Mini Termin</H3>';
   echo 'diese MiniTermin Plugin ermöglicht auf einfachste Weise aus jedem Beitrag eine Termin zu machen:</p>';
   echo 'Jeder Beitrag hat eine MiniTermin Metabox für Datum. Datum bis, Zeit und Ort Eintrag, sobald im Feld Datum etwas eingetragen ist, wird beim Beitrag im Frontend am Anfang des Content zB "Termin: 24.12.2013" angezeit, wird Datum bis eingetragen, erscheint die anzeige: Termin: 1.12.2013 bis 24.12.2013, wird das Feld Zeit und Ort befüllt erscheinen diese Feldinhalte ebenfalls.<p/>';
   echo 'Ein Sidebarwidget kann ebenfalls eingebunden werden.<p/>';
   echo 'mit dem Shortcode [termine] kann die Liste der Terminbeiträge im Content (Seite, Beitrag) angezeigt werden. [termine cat=2] zeigt nur Terminbeiträge mit Kategorie 2 an.<p/>';
   echo 'unabhängig vom Plugin können Kategorien genutzt werden, um verschiedene Arten von Termin mit der Standard Kategorie-Ansicht zb als Extra Menüpunkt anzuzeigen.<p/>';
   echo 'Diese Plugin sollte noch nicht für Realeinsatz verwendet werden, da ich einige kleine Dinge noch nicht eingebaut habe:<br/>';
   echo 'es fehlt zum Beispiel: sortierung nach Datum und Zeit, Datumskalender eingabefeld in der Metabox<p/>';
   echo 'wer Ideen hat und sogar selbst Code, darf natürlich erweitern und mir diese Ideen schicken: an franz@wieser.at<p/>';
   echo '</div>';
	
}

function minitermin_plugin_menu()
{
add_menu_page('Mini Termin', 'Mini Termin', 'read', 'minitermin', 'minitermin_info_seite');
}


add_action('admin_menu', 'minitermin_plugin_menu');
?>

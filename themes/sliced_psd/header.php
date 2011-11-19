<?php
/**
 * parses html, cleans html output for email marketing templates
 * to be used inside the loop, var $html is get_the_content() inside the loop   
 */
function print_the_content( $html = "" ){
	$html	= trim($html);
	print "<div class='contentpost'>{$html}</div>";
}
?>
<title><?php the_title(); ?> : <?php bloginfo('name'); ?></title>
<style type="text/css" media="screen">
    body .content_post p {
	    line-height: 1.5em;
	    margin: 0px 0px 7px 0px;
	    padding-top:1.0em;
	    padding-bottom:1.0em;
	    border:solid 1px red;
    }
    body table td p { margin:0px; padding:0px; border:none }
    body table td br.space_ {display:none }
    body table td br {display:none;}
    hr { border:none }
    #footer p{
    	text-align:left;
    	font-family:verdana;
    	color:gray;
    	font-size:10px;
    	padding:0px;
    	margin:0px;
    }
	table { 
		border-collapse:collapse;
		padding:0px;
		margin:0px;
		border:none;
	}
	table td {
		vertical-align:top;
		padding:0px;
		margin:0px; 
		border-collapse:collapse; 
	}
	table td img {
		padding:0px;
		margin:0px;
	}
	tbody {
		padding:0px;
		margin:0px;
	}
	body div table {
	padding:0px;
	margin:0px;
	}
	a{
		padding:0px;
		margin:0px;
		border:none;
		overflow:hidden;
	}
	a img {
		margin:0px;
		padding:0px;
		border:none;
	}
	div {
		padding:0px;	
		margin:0px;
	}
	body {
		font-family:verdana;
		font-size:12px;
		color:#313131;
	}
	body { 
		text-align:center;
	}
	body p, body div {
		text-align:left;
		}
	.alignright {
		float:right;
		}
	.aligncenter {
		clear:both;
		display:block;
		margin:0px auto;
		}
</style>

<div>    

<?php
/**
 * Groups configuration for default Minify implementation
 * @package Minify
 */

/** 
 * You may wish to use the Minify URI Builder app to suggest
 * changes. http://yourdomain/min/builder/
 *
 * See http://code.google.com/p/minify/wiki/CustomSource for other ideas
 **/

return array(
    // 'js' => array('//js/file1.js', '//js/file2.js'),
    // 'css' => array('//css/file1.css', '//css/file2.css'),
	
	'global' => array(
		'//assets/js/jquery-1.7.min.js',
		'//assets/js/jquery.timeago.js',
		'//assets/js/jquery.qtip.min.js'
	),
	
	'report' => array(
		'//assets/js/flot/jquery.flot.js',
		'//assets/js/flot/jquery.flot.pie.js',
		'//assets/js/module/report/report.js'
	)
);
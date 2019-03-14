<?php 

spl_autoload_register( function($classname){

	#构建文件路径
	$classname = $classname.'.php';

	// echo "$classname";
	if( file_exists($classname) ){
// echo "$classname";
		include $classname;
	}

} );

CodeIgniter-Dwoo
================

Version: 2.0

Dwoo is a PHP based templating engine aimed as a replacement for Smarty 2.x using similar and alternative syntax.
CodeIgniter-Dwoo is a simple implementation for using Dwoo in your CodeIgniter view files.


Requirements
------------

1. PHP 5.2+
2. CodeIgniter 1.6.x - 2.0-dev
3. Dwoo 1.2-dev (grab a copy from [GitHub](http://github.com/Seldaek/Dwoo/archives/master))

Requirements for Dwoo alone are:

* SPL and PCRE extensions (for php versions prior to 5.3.0)
* mbstring extension for some string manipulation plugins (especially if you intend to use UTF-8)


Installation
------------

1. [Download Dwoo](http://github.com/Seldaek/Dwoo/archives/master) and copy the main /dwoo/ folder into your application/libraries/ folder.

2. Copy libraries/MY_Parser.php to your application/libraries/ folder.

3. Copy config/parser.php to your application/config/ folder.

4. Create the folders:

	application/cache
	application/cache/dwoo
	application/cache/dwoo/compiled

5. Set the new folders file permissions to be write-able:

	chmod -R 777 application/cache


Usage
-----

	$this->load->library('parser');
	
	$this->parser->parse('dwoo_test', array('message' => 'OH HAI!'));
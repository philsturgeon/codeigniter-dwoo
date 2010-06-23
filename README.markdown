CodeIgniter-Dwoo
================

Dwoo is a PHP based templating engine aimed as a replacement for Smarty 2.x using similar and alternative syntax.
CodeIgniter-Dwoo is a simple implementation for using Dwoo in your CodeIgniter view files.


Requirements
------------

1. PHP 5.2+
2. CodeIgniter 1.6.x - 2.0-dev
3. Dwoo 1.1+

Requirements for Dwoo alone are:

* SPL and PCRE extensions (for php versions prior to 5.3.0)
* mbstring extension for some string manipulation plugins (especially if you intend to use UTF-8)


Installation
------------

1. [Download Dwoo](http://dwoo.org/) and copy the main /dwoo/ folder into your system/application/libraries/ folder.

2. Copy libraries/MY_Parser.php to your system/application/libraries/ folder.

3. Copy config/parser.php to your system/application/config/ folder.

4. Create the folders:
	system/application/cache
	system/application/cache/dwoo
	system/application/cache/dwoo/compiled

5. Set the new folders file permissions to be writeable:

	chmod -R 777 system/application/cache


Usage
-----

	$this->load->library('parser');
	
	$this->parser->parse('dwoo_test', array('message' => 'OH HAI!'));
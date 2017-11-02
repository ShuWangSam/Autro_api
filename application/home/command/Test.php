<?php
namespace app\home\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use app\index\controller\Index;

class Test extends Command{
	
	protected  function  configure(){
		
		$this->setName('test')->setDescription('Here is remark');
		
	}
	
	protected function execute(Input $input , Output $output){

	    $index = new Index();
        $t = $index->isTest();
        $output->writeln($t);


	}
	
	
}

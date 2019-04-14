<?php

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;

class First extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('first');
        // 设置参数
        
    }

    protected function execute(Input $input, Output $output)
    {
    	// 指令输出
    	$output->writeln('first');
    }
}

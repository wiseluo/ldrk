<?php
declare (strict_types = 1);

namespace app\command;

use app\dao\user\UserDao;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use app\jobs\TestJob;
use crmeb\utils\Queue;
use app\dao\subscribe\SubscribeRuleDao;
use Curl\Curl;

class Hello extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('hello')
            ->setDescription('the hello command');
    }

    protected function execute(Input $input, Output $output)
    {

        $this->test_sendsms(1);
        // $arr = [1,2,3,4,5];

        // foreach($arr as $key => $value){
        //     $this->test_sendsms($value);

        // }
        

        $output->writeln('test hello');
    }


    protected function test_sendsms($value){
        // foreach($arr as $key => $value){
        // }
           //  $value = 1;
        // go(function() use ($value){
            $curl = new Curl();
            $curl->get('http://0.0.0.0:20299/sendsms',[
                'phone'=> '13625896500',
                'content' => 'heihei:'.$value
            ]);
            $data = $curl->response;
            var_dump($data);
        // });
    }
}

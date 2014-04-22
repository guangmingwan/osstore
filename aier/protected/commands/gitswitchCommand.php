    <?php  
    class gitswitchCommand extends CConsoleCommand {  
          
        public function getHelp() {  
            return 'test command help';  
        }  
          
        public function run($args) {  
        	echo date('y-n-j').": start loop run...";
            $i = 0;
        	while(true)
        	{
        		$i ++;
        		if($i > 60)
        		{
        			echo date('y-n-j').": i still runing...\r\n";
        			$i = 0;
        		}
        		//echo "loop\r\n";
	            $predictions = Jobs::model()->findAll();
	            if(!empty($predictions))
	            {
	            	$job = $predictions[0];
	            	$branch = $job['branch'];
	            	$cmd = "time /var/www/vhosts/ns5001601.ip-192-95-33.net/www.os-store.com/shell/reindexall.sh >out.log"; 
	            	echo 'doing job: '. $branch . "\r\n";
	            	echo $cmd . "\r\n";
	            	exec($cmd,$out,$return);
	            	echo implode("\r\n",$out) . "\r\n";
	            	if(!$return)
	            	{
	            		echo "ok\r\n";
	            		echo "delete job:" . $job->id . "\r\n";
	            		Jobs::model()->deleteByPk($job->id);
	            		//$cmd = "git pull";
	            		//exec($cmd,$out,$return);
	            	}
	            	else
	            	{
	            		echo "fail\r\n";
	            		sleep(10);
	            	}
	            }
	            else
	            {
	            	//echo "no job!\r\n";
	            	sleep(1);
	            }
	            flush();
        	}
        }  
    }  
    ?>  
